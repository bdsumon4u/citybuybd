<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use \App\Traits\CacheClearing, HasFactory;

    protected $fillable = [
        'base_id',
        'base_multiplier',
        'is_stock',
        'sku',
        'thumb',
        'image',
        'gallery_images',
        'video',
        'name',
        'slug',
        'model',
        'stock',
        'description',
        'category_id',
        'brand_id',
        'color',
        'size',
        'regular_price',
        'offer_price',
        'bulk_prices',
        'status',
        'assign',
        'serial',
        'shipping',
        'inside',
        'outside',
    ];

    protected $casts = [
        'is_stock' => 'boolean',
        'bulk_prices' => 'array',
        'base_multiplier' => 'integer',
    ];

    public static function getProduct()
    {
        $records = DB::table('products')->select('id', 'base_id', 'base_multiplier', 'is_stock', 'sku', 'thumb', 'image', 'gallery_images', 'name', 'slug', 'stock', 'description', 'category_id', 'brand_id', 'color', 'size', 'regular_price', 'offer_price', 'status', 'created_at')->get()->toArray();

        return $records;
    }

    /**
     * Get the base product for a combo pack product
     */
    public function baseProduct()
    {
        return $this->belongsTo(Product::class, 'base_id');
    }

    /**
     * Get all combo pack products that use this product as their base
     */
    public function combos()
    {
        return $this->hasMany(Product::class, 'base_id');
    }

    /**
     * Get all stock purchases recorded for this base product
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id');
    }

    /**
     * Get all stock ledger logs for this product
     */
    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'product_id');
    }

    /**
     * Check if this product is a base product (not a combo)
     */
    public function isBaseProduct(): bool
    {
        return empty($this->base_id);
    }

    /**
     * Check if stock tracking is actively enabled for this product
     */
    public function isStockEnabled(): bool
    {
        if ($this->isCombo()) {
            return (bool) optional($this->baseProduct)->is_stock;
        }

        return (bool) $this->is_stock;
    }

    /**
     * Scope to products that have stock management enabled directly
     */
    public function scopeStockEnabled($query)
    {
        return $query->where('is_stock', 1)->whereNull('base_id');
    }

    /**
     * Check if this product is a combo pack product
     */
    public function isCombo(): bool
    {
        return ! empty($this->base_id);
    }

    /**
     * Resolve the effective base product (returns self if base product, or referenced base product if combo)
     */
    public function getEffectiveBaseProduct(): ?Product
    {
        if ($this->isCombo()) {
            return $this->baseProduct ?? Product::find($this->base_id);
        }

        return $this;
    }

    /**
     * Get effective unit multiplier of base product for this product
     */
    public function getEffectiveMultiplier(): int
    {
        if ($this->isCombo()) {
            $mult = (int) ($this->base_multiplier ?? 1);
            if ($mult > 1) {
                return $mult;
            }
        }

        if (!empty($this->base_multiplier) && (int) $this->base_multiplier > 1) {
            return (int) $this->base_multiplier;
        }

        // Check if product name indicates a combo pack (e.g. "(2 ps)", "(2 pcs)", "2 pcs", "2 ps", "(২ পিস)")
        if (!empty($this->name)) {
            $normalizedName = strtr((string) $this->name, [
                '০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4',
                '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9',
            ]);
            if (preg_match('/(?:^|\(|\s)(\d+)\s*(?:-|_|x|\*)?\s*(?:pcs|pc|pis|ps|pack|piece|pieces|পিস|পিছ|টি)(?:\)|\s|$)/iu', $normalizedName, $matches)) {
                $num = (int) $matches[1];
                if ($num > 1) {
                    return $num;
                }
            }
        }

        return 1;
    }

    /**
     * Get the post that owns the comment.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assign_emp()
    {
        return $this->belongsTo(User::class, 'assign');
    }

    /**
     * Get all assigned employees for this product (many-to-many)
     */
    public function assignedEmployees()
    {
        return $this->belongsToMany(User::class, 'product_user', 'product_id', 'user_id')
            ->wherePivot('user_id', '!=', null)
            ->withTimestamps();
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'product_id');
    }

    public function all_carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
}

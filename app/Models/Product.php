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
        'bulk_prices' => 'array',
        'base_multiplier' => 'integer',
    ];

    public static function getProduct()
    {
        $records = DB::table('products')->select('id', 'base_id', 'base_multiplier', 'sku', 'thumb', 'image', 'gallery_images', 'name', 'slug', 'stock', 'description', 'category_id', 'brand_id', 'color', 'size', 'regular_price', 'offer_price', 'status', 'created_at')->get()->toArray();

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
     * Check if this product is a base product (maintains stock)
     */
    public function isBaseProduct(): bool
    {
        return empty($this->base_id);
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
            return max(1, (int) ($this->base_multiplier ?? 1));
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

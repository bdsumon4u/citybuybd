<?php

namespace App\Models;

use App\Traits\CacheClearing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HomeSection extends Model
{
    use CacheClearing, HasFactory;

    protected $table = 'home_sections';

    protected $fillable = [
        'title',
        'subtitle',
        'section_type',
        'category_id',
        'product_ids',
        'product_sort',
        'product_limit',
        'display_style',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'is_active' => 'boolean',
        'category_id' => 'integer',
        'order_index' => 'integer',
        'product_limit' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Resolve and return products for this section according to rules.
     */
    public function getProducts()
    {
        $limit = max(1, (int) ($this->product_limit ?: 12));
        $sort = $this->product_sort ?: 'latest';

        switch ($this->section_type) {
            case 'hot_deals':
                $query = Product::query()
                    ->where('status', 1)
                    ->where(function ($q) {
                        $q->whereNotNull('offer_price')->where('offer_price', '>', 0);
                    });
                $this->applyProductSorting($query, $sort);
                return $query->take($limit)->get();

            case 'best_selling':
                $bestSellingIds = DB::table('carts')
                    ->select('product_id', DB::raw('SUM(quantity) as total_sales'))
                    ->groupBy('product_id')
                    ->orderByDesc('total_sales')
                    ->limit($limit)
                    ->pluck('product_id')
                    ->toArray();

                if (!empty($bestSellingIds)) {
                    $products = Product::where('status', 1)->whereIn('id', $bestSellingIds)->get();
                    return $products->sortBy(fn ($model) => array_search($model->id, $bestSellingIds))->values();
                }

                // Fallback to latest products if no order history
                return Product::where('status', 1)->latest()->take($limit)->get();

            case 'latest_products':
                $query = Product::query()->where('status', 1);
                $this->applyProductSorting($query, $sort);
                return $query->take($limit)->get();

            case 'category_products':
                if ($this->category_id) {
                    $query = Product::query()->where('status', 1)->where('category_id', $this->category_id);
                    $this->applyProductSorting($query, $sort);
                    return $query->take($limit)->get();
                }
                return collect();

            case 'custom_products':
                $ids = is_array($this->product_ids) ? $this->product_ids : [];
                if (!empty($ids)) {
                    $products = Product::where('status', 1)->whereIn('id', $ids)->get();
                    return $products->sortBy(fn ($model) => array_search($model->id, $ids))->values();
                }
                return collect();

            case 'all_products':
                $query = Product::query()->where('status', 1);
                $this->applyProductSorting($query, $sort);
                return $query->paginate($limit);

            default:
                return collect();
        }
    }

    /**
     * Apply product sorting strategy.
     */
    protected function applyProductSorting($query, string $sort): void
    {
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low_high':
                $query->orderBy(DB::raw('COALESCE(NULLIF(offer_price, 0), regular_price)'), 'asc');
                break;
            case 'price_high_low':
                $query->orderBy(DB::raw('COALESCE(NULLIF(offer_price, 0), regular_price)'), 'desc');
                break;
            case 'discount_high_low':
                $query->whereNotNull('offer_price')
                    ->where('offer_price', '>', 0)
                    ->orderByRaw('(regular_price - offer_price) DESC');
                break;
            case 'random':
                $query->inRandomOrder();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
    }
}

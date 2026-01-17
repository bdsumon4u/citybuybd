<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use \App\Traits\CacheClearing, HasFactory;

    public $fillable = [
        'product_id',
        'quantity',
        'order_id',
        'price',
        'ip_address',
        'color',
        'size',
        'model',

    ];

    protected $casts = [
        'attribute' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // total cart
    public static function totalCarts()
    {

        $carts = Cart::where('ip_address', request()->ip())->where('order_id', null)->get();

        return $carts;
    }

    // Total items added in the carts
    public static function totalItems()
    {

        $carts = Cart::where('ip_address', request()->ip())->where('order_id', null)->get();

        $total_items = 0;
        foreach ($carts as $cart) {
            $total_items += $cart->quantity;
        }

        return $total_items;
    }

    public static function totalPrice()
    {

        $carts = Cart::where('ip_address', request()->ip())->where('order_id', null)->get();

        $total_price = 0;
        foreach ($carts as $cart) {
            if (! is_null($cart->product)) {
                if (! is_null($cart->product->offer_price)) {
                    $total_price += $cart->product->offer_price * $cart->quantity;
                } else {
                    $total_price += $cart->product->regular_price * $cart->quantity;
                }
            }

        }

        return $total_price;

    }

    protected function atrItem(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function () {
            $value = $this->attribute;
            if (is_string($value)) {
                $ids = explode(',', $value);
            } elseif (is_array($value)) {
                $ids = $value;
            } else {
                $ids = [];
            }

            return AtrItem::whereIn('id', $ids)->get();
        });
    }

    public function atr_items()
    {
        return $this->hasMany(AtrItem::class, 'id', 'attribute');
        // If attribute is array, we can't directly use hasMany. We'll handle it differently below.
    }
}

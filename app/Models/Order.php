<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    public const TYPE_ONLINE = 'online';
    public const TYPE_MANUAL = 'manual';
    public const TYPE_CONVERTED = 'converted';
    public const TYPES = [
        self::TYPE_ONLINE,
        self::TYPE_MANUAL,
        self::TYPE_CONVERTED,
    ];

    protected $attributes = [
        'order_type' => self::TYPE_ONLINE,
    ];

    public $fillable = [
        'ip_address',
        'name',
        'phone',
        'email',
        'address',
        'courier',
        'city',
        'zone',
        'payment_method',
        'shipping_method',
        'shipping_cost',
        'discount',
        'sub_total',
        'total',
        'pay',
        'order_assign',
        'status',
        'order_note',
        'product_id',
        'product_slug',
        'order_type',
    ];

    protected $casts = [
        'product_slug' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shipping()
    {
        return $this->hasMany(Shipping::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'order_id');
    }

    public function many_cart()
    {
        return $this->hasMany(Cart::class, 'order_id');
    }

    public function cart_custom()
    {
        return $this->hasMany(Cart::class, 'order_id');
    }

    public function couriers()
    {
        return $this->belongsTo(Courier::class, 'courier');
    }

    public function order_city()
    {
        return $this->belongsTo(City::class, 'city');
    }

    public function order_zone()
    {
        return $this->belongsTo(Zone::class, 'zone');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'order_assign');
    }

    public static function getCustomers()
    {
        $records = DB::table('orders')->select('name', 'address', 'phone')->get()->toArray();
        return $records;
    }

    public function getMyCourierAttribute()
    {
        if ($this->courier == 1 && $this->consignment_id) {//1 = RedX
            return '<a target="_blank" class="text-primary" href="https://redx.com.bd/track-global-parcel/?trackingId=' . $this->consignment_id . '">' . $this->couriers->name . '</a>';
        } elseif ($this->courier == 3 && $this->consignment_id) {//3 = pathao
            return '<a target="_blank" class="text-primary" href="https://merchant.pathao.com/tracking?consignment_id=' . $this->consignment_id . '&phone=' . $this->phone . '">' . $this->couriers->name . '</a>';
        } elseif ($this->courier == 4 && $this->consignment_id) {//4 = steadfast
            return '<a target="_blank" class="text-primary" href="https://steadfast.com.bd/t/' . $this->consignment_id . '">' . $this->couriers->name . '</a>';
        }

        return "NOT SELECTED";
    }

    public function products()
    {
        return $this->hasMany(Cart::class, 'order_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(Cart::class, 'order_id', 'id');
    }
}

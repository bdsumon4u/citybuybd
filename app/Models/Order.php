<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\CacheClearing;
use App\Traits\SendsNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use CacheClearing;
    use HasFactory;
    use Notifiable;
    use SendsNotification;

    public const TYPE_ONLINE = 'online';

    public const TYPE_MANUAL = 'manual';

    public const TYPE_INCOMPLETE = 'incomplete';

    public const TYPES = [
        self::TYPE_ONLINE,
        self::TYPE_MANUAL,
        self::TYPE_INCOMPLETE,
    ];

    public const STATUS_PROCESSING = 1;

    public const STATUS_PENDING_DELIVERY = 2;

    public const STATUS_ON_HOLD = 3;

    public const STATUS_CANCEL = 4;

    public const STATUS_COMPLETED = 5;

    public const STATUS_PENDING_PAYMENT = 6;

    public const STATUS_ON_DELIVERY = 7;

    public const STATUS_NO_RESPONSE1 = 8;

    public const STATUS_NO_RESPONSE2 = 9;

    public const STATUS_COURIER_HOLD = 11;

    public const STATUS_ORDER_RETURN = 12;

    public const STATUS_PARTIAL_DELIVERY = 13;

    public const STATUS_PAID_RETURN = 14;

    public const STATUS_STOCK_OUT = 15;

    public const STATUS_TOTAL_DELIVERY = 16; // Display name: Total Courier

    public const STATUS_PRINTED_INVOICE = 17;

    public const STATUS_PENDING_RETURN = 18;

    public const STATUS_MAP = [
        self::STATUS_PROCESSING => 'processing',
        self::STATUS_PENDING_DELIVERY => 'pending_delivery',
        self::STATUS_ON_HOLD => 'on_hold',
        self::STATUS_CANCEL => 'cancel',
        self::STATUS_COMPLETED => 'completed',
        self::STATUS_PENDING_PAYMENT => 'pending_payment',
        self::STATUS_ON_DELIVERY => 'on_delivery',
        self::STATUS_NO_RESPONSE1 => 'no_response1',
        self::STATUS_NO_RESPONSE2 => 'no_response2',
        self::STATUS_COURIER_HOLD => 'courier_hold',
        self::STATUS_ORDER_RETURN => 'order_return',
        self::STATUS_PARTIAL_DELIVERY => 'partial_delivery',
        self::STATUS_PAID_RETURN => 'paid_return',
        self::STATUS_STOCK_OUT => 'stock_out',
        self::STATUS_TOTAL_DELIVERY => 'total_delivery',
        self::STATUS_PRINTED_INVOICE => 'printed_invoice',
        self::STATUS_PENDING_RETURN => 'pending_return',
    ];

    public function getStatusName(): ?string
    {
        return self::STATUS_MAP[$this->status] ?? null;
    }

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

    protected function myCourier(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function () {
            if ($this->courier == 1 && $this->consignment_id) {// 1 = RedX
                return '<a target="_blank" class="text-primary" href="https://redx.com.bd/track-global-parcel/?trackingId='.$this->consignment_id.'">'.$this->couriers->name.'</a>';
            } elseif ($this->courier == 3 && $this->consignment_id) {// 3 = pathao
                return '<a target="_blank" class="text-primary" href="https://merchant.pathao.com/tracking?consignment_id='.$this->consignment_id.'&phone='.$this->phone.'">'.$this->couriers->name.'</a>';
            } elseif ($this->courier == 4 && $this->consignment_id) {// 4 = steadfast
                return '<a target="_blank" class="text-primary" href="https://steadfast.com.bd/t/'.$this->consignment_id.'">'.$this->couriers->name.'</a>';
            }

            return 'NOT SELECTED';
        });
    }

    public function products()
    {
        return $this->hasMany(Cart::class, 'order_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(Cart::class, 'order_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'product_slug' => 'array',
            'status' => 'integer',
        ];
    }
}

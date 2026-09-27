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

    public const STATUS_DOUBLE = 19;

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
        self::STATUS_DOUBLE => 'double',
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
        'utm_source',
        'utm_medium',
        'utm_campaign',
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
        'ordered_quantity',
        'delivered_quantity',
        'delivered_at',
        'order_assign',
        'created_by',
        'status',
        'order_note',
        'product_id',
        'product_slug',
        'ordered_product_ids',
        'order_type',
        'master_id',
        'slave_id',
        'slave_domain',
        'forwarding_status',
        'forwarding_error',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function changeHistories()
    {
        return $this->hasMany(OrderChangeHistory::class, 'order_id', 'id')->orderByDesc('changed_at');
    }

    public function isDeliveredOrReturnedLocked(): bool
    {
        return in_array((int) $this->status, [
            self::STATUS_CANCEL,
            self::STATUS_COMPLETED,
            self::STATUS_PARTIAL_DELIVERY,
            self::STATUS_ORDER_RETURN,
            self::STATUS_PAID_RETURN,
            self::STATUS_PENDING_RETURN,
        ], true);
    }

    protected function casts(): array
    {
        return [
            'product_slug' => 'array',
            'ordered_product_ids' => 'array',
            'status' => 'integer',
            'delivered_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include orders that qualify for staff bonuses
     * based on active PayrollSetting rules (Manual Order delivery bonus and/or xSell bonus)
     * within the last 3 months (including current month).
     */
    public function scopeBonusOrders($query)
    {
        $paySettings = PayrollSetting::current();
        $isManualBonusActive = ((float) $paySettings->manual_order_bonus_rate) > 0;
        $isXsellBonusActive = ((float) $paySettings->xsell_bonus_rate) > 0;
        $bonusOnQuantityIncrease = $isXsellBonusActive && (bool) $paySettings->xsell_bonus_on_quantity_increase;
        $bonusOnProductReplace = $isXsellBonusActive && (bool) $paySettings->xsell_bonus_on_product_replace;

        if (! $isManualBonusActive && ! $bonusOnQuantityIncrease && ! $bonusOnProductReplace) {
            return $query->whereRaw('1 = 0');
        }

        $threeMonthsAgo = now()->subMonths(2)->startOfMonth()->startOfDay();

        return $query->where('orders.status', self::STATUS_COMPLETED)
            ->where(function ($dateQuery) use ($threeMonthsAgo): void {
                $dateQuery->where('orders.created_at', '>=', $threeMonthsAgo)
                    ->orWhere('orders.delivered_at', '>=', $threeMonthsAgo);
            })
            ->where(function ($q) use ($isManualBonusActive, $bonusOnQuantityIncrease, $bonusOnProductReplace): void {
                $hasCondition = false;

                // 1. Manual order delivery bonus: created_by is not null, coming = '0', slave_id is null
                if ($isManualBonusActive) {
                    $q->where(function ($manual): void {
                        $manual->where('orders.coming', '0')
                            ->whereNotNull('orders.created_by')
                            ->whereNull('orders.slave_id');
                    });
                    $hasCondition = true;
                }

                // 2. Quantity increase xSell bonus: assigned, delivered_quantity > ordered_quantity (or carts sum > ordered_quantity)
                if ($bonusOnQuantityIncrease) {
                    $callback = function ($qty): void {
                        $qty->whereNotNull('orders.order_assign')
                            ->where('orders.ordered_quantity', '>', 0)
                            ->where(function ($sub): void {
                                $sub->whereColumn('orders.delivered_quantity', '>', 'orders.ordered_quantity')
                                    ->orWhereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM carts WHERE carts.order_id = orders.id) > orders.ordered_quantity');
                            });
                    };

                    if ($hasCondition) {
                        $q->orWhere($callback);
                    } else {
                        $q->where($callback);
                        $hasCondition = true;
                    }
                }

                // 3. Product change / replacement xSell bonus: assigned, and carts have products not in ordered_product_ids
                if ($bonusOnProductReplace) {
                    $callback = function ($prod): void {
                        $prod->whereNotNull('orders.order_assign')
                            ->whereNotNull('orders.ordered_product_ids')
                            ->whereRaw('JSON_VALID(orders.ordered_product_ids) = 1')
                            ->whereRaw('JSON_LENGTH(orders.ordered_product_ids) > 0')
                            ->where(function ($diff): void {
                                $diff->whereExists(function ($sub): void {
                                    $sub->select(DB::raw(1))
                                        ->from('carts')
                                        ->whereColumn('carts.order_id', 'orders.id')
                                        ->whereNotNull('carts.product_id')
                                        ->whereRaw('NOT JSON_CONTAINS(orders.ordered_product_ids, CAST(carts.product_id AS CHAR))');
                                })->orWhereRaw('(SELECT COUNT(DISTINCT carts.product_id) FROM carts WHERE carts.order_id = orders.id) != JSON_LENGTH(orders.ordered_product_ids)');
                            });
                    };

                    if ($hasCondition) {
                        $q->orWhere($callback);
                    } else {
                        $q->where($callback);
                    }
                }
            });
    }

    /**
     * Reverse map status name to code (using existing STATUS_MAP).
     */
    public static function statusCodeFromName(?string $name): ?int
    {
        if ($name === null) {
            return null;
        }

        $lower = strtolower($name);

        foreach (self::STATUS_MAP as $code => $label) {
            if ($label === $lower) {
                return (int) $code;
            }
        }

        return null;
    }
}

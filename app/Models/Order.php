<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Order extends Model
{
     use HasFactory;
    public $fillable =[
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
        'product_slug'

    ];

    protected $casts = [
        'product_slug' => 'array',
        
    ];

    public function product(){
        return $this->belongsTo(Product::Class);
    }
    public function shipping(){
        return $this->hasMany(Shipping::Class);
    }

    public function cart(){
        return $this->hasOne(Cart::Class,'order_id');
    }

    public function many_cart(){
        return $this->hasMany(Cart::Class,'order_id');
    }

    public function cart_custom(){
        return $this->hasMany(Cart::Class,'order_id');
    }

    public function couriers(){
        return $this->belongsTo(Courier::Class,'courier');
    }
    public function order_city(){
        return $this->belongsTo(City::Class,'city');
    }
    public function order_zone(){
        return $this->belongsTo(Zone::Class,'zone');
    }

     public function user(){
        return $this->hasOne(User::Class,'id','order_assign');
    }

    public static function getCustomers(){
        $records = DB::table('orders')->select('name','address','phone')->get()->toArray();
        return $records;
    }
public function getMyCourierAttribute(){
        if($this->courier == 1 && $this->consignment_id)://1 = RedX
            return '<a target="_blank" class="text-primary" href="https://redx.com.bd/track-global-parcel/?trackingId='.$this->consignment_id.'">'.$this->couriers->name.'</a>';
        elseif($this->courier == 3 && $this->consignment_id)://3 = pathao
            return '<a target="_blank" class="text-primary" href="https://merchant.pathao.com/tracking?consignment_id='.$this->consignment_id.'&phone='.$this->phone.'">'.$this->couriers->name.'</a>';
        elseif($this->courier == 4 && $this->consignment_id)://4 = steadfast
            return '<a target="_blank" class="text-primary" href="https://steadfast.com.bd/t/'.$this->consignment_id.'">'.$this->couriers->name.'</a>';
        else:
            return "NOT SELECTED";
        endif;
    }
    public function products(){
        return $this->hasMany(Cart::class,'order_id','id');
    }

    public function items()
    {
        return $this->hasMany(Cart::class, 'order_id', 'id');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
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
        'status',
        'assign',
        'serial',
        'shipping',
        'inside',
        'outside'


    ];
    public static function getProduct(){
        $records = DB::table('products')->select('id','sku','thumb','image','gallery_images','name','slug','stock','description','category_id','brand_id','color','size','regular_price','offer_price','status','created_at')->get()->toArray();
        return $records;
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
        return $this->belongsTo(User::class,'assign');
    }
     public function order()
    {
        return $this->hasMany(Order::class);
    }
     public function cart()
    {
        return $this->hasOne(Cart::class,'product_id');
    } public function all_carts()
    {
        return $this->hasMany(Cart::class,'product_id');
    }







}

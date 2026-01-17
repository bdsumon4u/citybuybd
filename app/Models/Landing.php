<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;

    public $fillable = [
        'product_id',
        'heading',
        'slug',
        'subheading',
        'video',
        'heading_middle',
        'slider_title',
        'slider',
        'bullet',
        'bullet_logo',
        'old_price',
        'new_price',
        'phone',
        'home_delivery',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

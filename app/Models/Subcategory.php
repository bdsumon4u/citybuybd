<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory, \App\Traits\CacheClearing;
    protected $fillable = [
        'title',
        'category_id',
        'status',
    ];
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function childcategories(){
        return $this->hasMany(Childcategory::class,'subcategory_id');
    }
}

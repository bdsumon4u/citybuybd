<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childcategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'category_id',
        'subcategory_id',
        'status',
    ];
    public function products(){
        return $this->hasMany(Product::class);
    }
}
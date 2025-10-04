<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    public $fillable =[
        'name',
        'status',
    ];
    public function get_atr_item(){
        return $this->hasMany(Atr_item::Class,'atr_id');
    }
}

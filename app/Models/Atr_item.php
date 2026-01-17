<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atr_item extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'atr_id',
    ];

    public function get_atr()
    {
        return $this->belongsTo(ProductAttribute::class, 'atr_id');
    }
}

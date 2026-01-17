<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtrItem extends Model
{
    use HasFactory;

    public function productAttr()
    {
        return $this->belongsTo(ProductAttribute::class, 'atr_id', 'id');
    }
}

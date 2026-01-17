<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use \App\Traits\CacheClearing, HasFactory;

    protected $fillable = [
        'type',
        'text',
        'amount',
        'status',

    ];
}

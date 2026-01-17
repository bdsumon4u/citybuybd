<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use \App\Traits\CacheClearing, HasFactory;

    protected $fillable = [
        'name',
        'status',
        'amount',
        'status',

    ];
}

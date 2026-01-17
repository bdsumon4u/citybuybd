<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    public $fillable = [
        'courier',
        'city',
        'zone',
        'status',

    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'couriar', 'id');
    }

    public function cit()
    {
        return $this->belongsTo(City::class, 'city', 'id');
    }
}

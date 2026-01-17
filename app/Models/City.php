<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'courier_id',
        'city',
        'status',

    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function zone()
    {
        return $this->hasMany(Zone::class);
    }
}

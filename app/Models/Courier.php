<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city_av',
        'zone_av',
        'charge',
        'status',

    ];

    public function city()
    {
        return $this->hasMany(City::class);
    }

    public function zone()
    {
        return $this->hasMany(Zone::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ManualOrderType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected function scopeActive($query)
    {
        return $query->where('status', true);
    }

    protected function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}

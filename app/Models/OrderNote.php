<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class OrderNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'status',
        'sort_order',
    ];

    protected function scopeActive($query)
    {
        return $query->where('status', true);
    }

    protected function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('note');
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}

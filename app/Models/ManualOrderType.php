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

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function active($query)
    {
        return $query->where('status', true);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function ordered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}

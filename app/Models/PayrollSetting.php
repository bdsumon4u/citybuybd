<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'overtime_rate',
        'overtime_unit_minutes',
        'forgot_checkout_penalty',
    ];

    protected function casts(): array
    {
        return [
            'overtime_rate' => 'decimal:2',
            'forgot_checkout_penalty' => 'decimal:2',
        ];
    }

    public static function current(): self
    {
        return self::first() ?? new self([
            'overtime_rate' => 50,
            'overtime_unit_minutes' => 60,
            'forgot_checkout_penalty' => 100,
        ]);
    }
}

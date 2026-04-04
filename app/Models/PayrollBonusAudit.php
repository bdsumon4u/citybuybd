<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollBonusAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_payroll_id',
        'edited_by',
        'editor_ip',
        'event_type',
        'old_values',
        'new_values',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(MonthlyPayroll::class, 'monthly_payroll_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}

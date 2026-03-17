<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InactiveWindow extends Model
{
    protected $fillable = [
        'user_id',
        'inactive_from',
        'inactive_until',
        'duration_minutes',
    ];

    protected $casts = [
        'inactive_from' => 'datetime',
        'inactive_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'amount',
        'year',
        'month',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get bonuses for a specific user and month/year.
     */
    public static function forUserAndMonth(int $userId, int $month, int $year): array
    {
        return self::where('user_id', $userId)
            ->where('year', $year)
            ->where('month', str_pad($month, 2, '0', STR_PAD_LEFT))
            ->get()
            ->toArray();
    }
}

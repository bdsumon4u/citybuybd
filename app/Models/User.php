<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasPushSubscriptions;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'start_time',
        'end_time',
        'daily_salary',
        'off_days',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'daily_salary' => 'decimal:2',
        ];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function salaryAdvances(): HasMany
    {
        return $this->hasMany(SalaryAdvance::class);
    }

    public function monthlyPayrolls(): HasMany
    {
        return $this->hasMany(MonthlyPayroll::class);
    }

    public function todayAttendance(): ?Attendance
    {
        return $this->attendances()->where('date', today())->first();
    }

    public function isCheckedInToday(): bool
    {
        $attendance = $this->todayAttendance();

        return $attendance && $attendance->check_in && ! $attendance->check_out;
    }

    public function getOffDaysArray(): array
    {
        if (empty($this->off_days)) {
            return [];
        }

        return array_map('trim', explode(',', $this->off_days));
    }

    public function isOffDay(?string $date = null): bool
    {
        $dayName = $date ? date('l', strtotime($date)) : date('l');

        return in_array($dayName, $this->getOffDaysArray());
    }
}

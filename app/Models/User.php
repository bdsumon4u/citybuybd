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
        'panel_start',
        'panel_end',
        'order_start',
        'order_end',
        'monthly_salary',
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
            'monthly_salary' => 'decimal:2',
        ];
    }

    public function getStartTimeAttribute($value)
    {
        return $value ? date('H:i:s', strtotime($value)) : config('attendance.default_start_time');
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? date('H:i:s', strtotime($value)) : config('attendance.default_end_time');
    }

    public function getPanelStartAttribute($value)
    {
        return $value ? date('H:i:s', strtotime($value)) : '00:00:00';
    }

    public function getPanelEndAttribute($value)
    {
        return $value ? date('H:i:s', strtotime($value)) : '23:59:59';
    }

    public function getOrderStartAttribute($value)
    {
        return $value ? date('H:i:s', strtotime($value)) : config('attendance.default_start_time');
    }

    public function getOrderEndAttribute($value)
    {
        return $value ? date('H:i:s', strtotime($value)) : config('attendance.default_end_time');
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

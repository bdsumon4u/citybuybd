<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\PayrollSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCheckoutEmployees extends Command
{
    protected $signature = 'attendance:auto-checkout';

    protected $description = 'Auto checkout employees who forgot to check out at their end time, with penalty';

    public function handle(): void
    {
        $paySettings = PayrollSetting::current();
        $today = today();

        // Find all attendances for today that have check_in but no check_out
        $openAttendances = Attendance::whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->where('status', 'present')
            ->get();

        $count = 0;

        foreach ($openAttendances as $attendance) {
            $user = $attendance->user;
            if (! $user) {
                continue;
            }

            $endTime = Carbon::parse($today->toDateString().' '.($user->end_time ?? config('attendance.default_end_time')));

            // Only auto-checkout if current time is past the user's end_time
            if (now()->gte($endTime)) {
                // Set checkout to end_time (no overtime counted)
                $attendance->check_out = $endTime;
                $attendance->auto_checkout = true;
                $attendance->penalty_amount = $paySettings->forgot_checkout_penalty;
                $attendance->overtime_minutes = 0; // No overtime for auto-checkout
                $attendance->late_minutes = 0;

                // Still calculate early arrival overtime and late arrival
                $startTime = Carbon::parse($today->toDateString().' '.($user->start_time ?? config('attendance.default_start_time')));
                $checkInTime = Carbon::parse($attendance->check_in);
                if ($checkInTime->lt($startTime)) {
                    $attendance->overtime_minutes = abs($startTime->diffInMinutes($checkInTime));
                }
                if ($checkInTime->gt($startTime)) {
                    $attendance->late_minutes = abs($checkInTime->diffInMinutes($startTime));
                }

                $attendance->save();
                $count++;

                $this->info("Auto checked out: {$user->name} with penalty ৳{$paySettings->forgot_checkout_penalty}");
            }
        }

        $this->info("Auto checkout completed. {$count} employees processed.");
    }
}

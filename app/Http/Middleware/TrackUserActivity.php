<?php

namespace App\Http\Middleware;

use App\Models\InactiveWindow;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class TrackUserActivity
{
    /**
     * Threshold in minutes. A gap larger than this is recorded as an inactive window.
     */
    private const INACTIVITY_THRESHOLD_MINUTES = 5;

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $now = now();

            if ($user->last_active_at !== null) {
                $this->recordDutyTimeInactivityWindows($user->id, $user->start_time, $user->end_time, $user->last_active_at, $now);
            }

            // Update last_active_at without touching updated_at
            $user->timestamps = false;
            $user->last_active_at = $now;
            $user->save();
            $user->timestamps = true;
        }

        return $next($request);
    }

    /**
     * Record inactivity only for overlap between [lastActiveAt, now] and duty windows.
     */
    private function recordDutyTimeInactivityWindows(
        int $userId,
        string $dutyStartTime,
        string $dutyEndTime,
        Carbon $lastActiveAt,
        Carbon $now
    ): void {
        if ($now->lte($lastActiveAt)) {
            return;
        }

        $cursorDay = $lastActiveAt->copy()->startOfDay();
        $lastDay = $now->copy()->startOfDay();

        while ($cursorDay->lte($lastDay)) {
            [$dutyStart, $dutyEnd] = $this->buildDutyWindow($cursorDay, $dutyStartTime, $dutyEndTime);

            $inactiveFrom = $dutyStart->greaterThan($lastActiveAt) ? $dutyStart : $lastActiveAt;
            $inactiveUntil = $dutyEnd->lessThan($now) ? $dutyEnd : $now;

            if ($inactiveUntil->gt($inactiveFrom)) {
                $durationMinutes = (int) $inactiveFrom->diffInMinutes($inactiveUntil);

                if ($durationMinutes > self::INACTIVITY_THRESHOLD_MINUTES) {
                    InactiveWindow::create([
                        'user_id' => $userId,
                        'inactive_from' => $inactiveFrom,
                        'inactive_until' => $inactiveUntil,
                        'duration_minutes' => $durationMinutes,
                    ]);
                }
            }

            $cursorDay->addDay();
        }
    }

    /**
     * Build duty window for a specific date. Supports overnight shifts.
     */
    private function buildDutyWindow(Carbon $date, string $startTime, string $endTime): array
    {
        $dutyStart = Carbon::parse($date->format('Y-m-d').' '.$startTime);
        $dutyEnd = Carbon::parse($date->format('Y-m-d').' '.$endTime);

        if ($dutyEnd->lte($dutyStart)) {
            $dutyEnd->addDay();
        }

        return [$dutyStart, $dutyEnd];
    }
}

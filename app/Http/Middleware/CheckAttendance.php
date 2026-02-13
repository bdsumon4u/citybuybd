<?php

namespace App\Http\Middleware;

use App\Models\Attendance;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAttendance
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Auto check-in for employees, managers, and admins (role 1, 2, 3)
        if ($user && in_array($user->role, [1, 2, 3])) {
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', today())
                ->first();

            $isCheckedIn = $attendance && $attendance->check_in && ! $attendance->check_out;

            // Auto check-in if not already checked in today
            if (! $isCheckedIn && ! $attendance) {
                $isOffDay = method_exists($user, 'isOffDay') ? $user->isOffDay() : false;
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => today(),
                    'check_in' => now(),
                    'status' => 'present',
                    'is_off_day' => $isOffDay,
                ]);
            }
        }

        return $next($request);
    }
}

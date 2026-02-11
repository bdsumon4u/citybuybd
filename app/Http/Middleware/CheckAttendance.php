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

        // Only enforce for employees and managers (role 2 and 3)
        if ($user && in_array($user->role, [2, 3])) {
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', today())
                ->first();

            $isCheckedIn = $attendance && $attendance->check_in && ! $attendance->check_out;

            if (! $isCheckedIn) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'error' => 'attendance_required',
                        'message' => 'You must check in your attendance before managing orders.',
                    ], 403);
                }

                $dashboardRoute = $user->role == 2 ? 'manager.dashboard' : 'employee.dashboard';

                return redirect()->route($dashboardRoute)
                    ->with('attendance_warning', 'You must check in your attendance before managing orders. Please toggle the attendance switch in the top bar.');
            }
        }

        return $next($request);
    }
}

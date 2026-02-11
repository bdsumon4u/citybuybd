<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\PayrollSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $today = today();
        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        if (! $attendance) {
            // Check in
            $isOffDay = $user->isOffDay();
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'check_in' => now(),
                'status' => 'present',
                'is_off_day' => $isOffDay,
            ]);

            return response()->json([
                'status' => 'checked_in',
                'message' => 'Attendance recorded! You are checked in.'.($isOffDay ? ' (Off-day attendance)' : ''),
                'check_in' => $attendance->check_in->format('h:i A'),
            ]);
        }

        if ($attendance->check_in && ! $attendance->check_out) {
            // Check out
            $checkOutTime = now();
            $paySettings = PayrollSetting::current();

            $endTime = Carbon::parse($today->toDateString().' '.($user->end_time ?? config('attendance.default_end_time')));
            $startTime = Carbon::parse($today->toDateString().' '.($user->start_time ?? config('attendance.default_start_time')));
            $checkInTime = Carbon::parse($attendance->check_in);

            $overtimeMinutes = 0;
            $lateMinutes = 0;

            // Early arrival overtime
            if ($checkInTime->lt($startTime)) {
                $overtimeMinutes += abs($startTime->diffInMinutes($checkInTime));
            }

            // Late arrival
            if ($checkInTime->gt($startTime)) {
                $lateMinutes += abs($checkInTime->diffInMinutes($startTime));
            }

            // Late departure overtime
            if ($checkOutTime->gt($endTime)) {
                $overtimeMinutes += abs($checkOutTime->diffInMinutes($endTime));
            }

            // Early departure
            if ($checkOutTime->lt($endTime)) {
                $lateMinutes += abs($endTime->diffInMinutes($checkOutTime));
            }

            $attendance->check_out = $checkOutTime;
            $attendance->overtime_minutes = $overtimeMinutes;
            $attendance->late_minutes = $lateMinutes;
            $attendance->save();

            return response()->json([
                'status' => 'checked_out',
                'message' => 'You are checked out!'.($overtimeMinutes > 0 ? " (Overtime: {$overtimeMinutes} min)" : '').($lateMinutes > 0 ? " (Late: {$lateMinutes} min)" : ''),
                'check_out' => $checkOutTime->format('h:i A'),
                'overtime_minutes' => $overtimeMinutes,
            ]);
        }

        return response()->json([
            'status' => 'already_done',
            'message' => 'Attendance already recorded for today.',
        ]);
    }

    public function status()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->where('date', today())->first();

        return response()->json([
            'has_attendance' => $attendance !== null,
            'is_checked_in' => $attendance && $attendance->check_in && ! $attendance->check_out,
            'is_checked_out' => $attendance && $attendance->check_out !== null,
            'check_in' => $attendance?->check_in?->format('h:i A'),
            'check_out' => $attendance?->check_out?->format('h:i A'),
        ]);
    }

    public function myAttendance(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        return view('manager.pages.attendance.index', compact('attendances', 'month', 'year'));
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\PayrollSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        $users = User::whereIn('role', [1, 2, 3])->where('status', 1)->get();
        $attendances = Attendance::where('date', $date)->with('user')->get()->keyBy('user_id');

        return view('backend.pages.attendance.index', compact('users', 'attendances', 'date'));
    }

    public function history(Request $request)
    {
        $userId = $request->get('user_id');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $users = User::whereIn('role', [1, 2, 3])->where('status', 1)->get();

        $query = Attendance::with('user')->whereMonth('date', $month)->whereYear('date', $year);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attendances = $query->orderBy('date', 'desc')->orderBy('user_id')->get();

        return view('backend.pages.attendance.history', compact('users', 'attendances', 'month', 'year', 'userId'));
    }

    public function manualCheckIn(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        $user = User::findOrFail($request->user_id);
        $date = $request->date;
        $isOffDay = $user->isOffDay($date);

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => $date],
            [
                'check_in' => $request->check_in ?? Carbon::parse($date.' '.($user->start_time ?? config('attendance.default_start_time'))),
                'status' => 'present',
                'is_off_day' => $isOffDay,
            ]
        );

        return back()->with('message', 'Attendance recorded for '.$user->name);
    }

    public function manualCheckOut(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
        ]);

        $attendance = Attendance::findOrFail($request->attendance_id);
        $user = $attendance->user;
        $paySettings = PayrollSetting::current();

        $checkOutTime = $request->check_out
            ? Carbon::parse($request->check_out)
            : now();

        $attendance->check_out = $checkOutTime;

        // Calculate overtime
        $endTime = Carbon::parse($attendance->date->toDateString().' '.($user->end_time ?? config('attendance.default_end_time')));
        $startTime = Carbon::parse($attendance->date->toDateString().' '.($user->start_time ?? config('attendance.default_start_time')));
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

        $attendance->overtime_minutes = $overtimeMinutes;
        $attendance->late_minutes = $lateMinutes;
        $attendance->save();

        return back()->with('message', 'Check-out recorded for '.$user->name);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        $dateStr = $request->date;

        // Prevent duplicate
        $existing = Attendance::where('user_id', $user->id)->where('date', $dateStr)->first();
        if ($existing) {
            return back()->with('message', 'Attendance already exists for '.$user->name.' on this date. Use edit instead.');
        }

        $isOffDay = $user->isOffDay($dateStr);
        $checkInTime = Carbon::parse($dateStr.' '.$request->check_in);
        $checkOutTime = $request->check_out ? Carbon::parse($dateStr.' '.$request->check_out) : null;

        $overtimeMinutes = 0;
        $lateMinutes = 0;

        if ($checkOutTime) {
            $startTime = Carbon::parse($dateStr.' '.($user->start_time ?? config('attendance.default_start_time')));
            $endTime = Carbon::parse($dateStr.' '.($user->end_time ?? config('attendance.default_end_time')));

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
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $dateStr,
            'check_in' => $checkInTime,
            'check_out' => $checkOutTime,
            'is_off_day' => $isOffDay,
            'overtime_minutes' => $overtimeMinutes,
            'late_minutes' => $lateMinutes,
            'status' => 'present',
            'note' => $request->note,
        ]);

        return back()->with('message', 'Attendance added for '.$user->name.'. Overtime: '.$overtimeMinutes.' min, Late: '.$lateMinutes.' min.');
    }

    public function markAbsent(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        Attendance::updateOrCreate(
            ['user_id' => $request->user_id, 'date' => $request->date],
            ['status' => 'absent', 'check_in' => null, 'check_out' => null]
        );

        return back()->with('message', 'Marked as absent.');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return back()->with('message', 'Attendance record deleted.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
        ]);

        $attendance = Attendance::findOrFail($request->attendance_id);
        $user = $attendance->user;
        $dateStr = $attendance->date->toDateString();

        // Update check-in
        if ($request->check_in) {
            $attendance->check_in = Carbon::parse($dateStr.' '.$request->check_in);
        }

        // Update check-out (allow clearing by submitting empty)
        if ($request->check_out) {
            $attendance->check_out = Carbon::parse($dateStr.' '.$request->check_out);
        } else {
            $attendance->check_out = null;
        }

        // Recalculate overtime and late minutes
        $overtimeMinutes = 0;
        $lateMinutes = 0;

        if ($attendance->check_in && $attendance->check_out) {
            $startTime = Carbon::parse($dateStr.' '.($user->start_time ?? config('attendance.default_start_time')));
            $endTime = Carbon::parse($dateStr.' '.($user->end_time ?? config('attendance.default_end_time')));
            $checkInTime = Carbon::parse($attendance->check_in);
            $checkOutTime = Carbon::parse($attendance->check_out);

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
        }

        $attendance->overtime_minutes = $overtimeMinutes;
        $attendance->late_minutes = $lateMinutes;
        $attendance->save();

        return back()->with('message', 'Attendance updated for '.$user->name.'. Overtime: '.$overtimeMinutes.' min, Late: '.$lateMinutes.' min.');
    }

    // ---- Self-service methods for admin's own attendance ----

    public function selfToggle(Request $request)
    {
        $user = Auth::user();
        $today = today();
        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        if (! $attendance) {
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
            $checkOutTime = now();
            $paySettings = PayrollSetting::current();

            $endTime = Carbon::parse($today->toDateString().' '.($user->end_time ?? config('attendance.default_end_time')));
            $startTime = Carbon::parse($today->toDateString().' '.($user->start_time ?? config('attendance.default_start_time')));
            $checkInTime = Carbon::parse($attendance->check_in);

            $overtimeMinutes = 0;
            $lateMinutes = 0;

            if ($checkInTime->lt($startTime)) {
                $overtimeMinutes += abs($startTime->diffInMinutes($checkInTime));
            }

            if ($checkInTime->gt($startTime)) {
                $lateMinutes += abs($checkInTime->diffInMinutes($startTime));
            }

            if ($checkOutTime->gt($endTime)) {
                $overtimeMinutes += abs($checkOutTime->diffInMinutes($endTime));
            }

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

    public function selfStatus()
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

        return view('backend.pages.attendance.my', compact('attendances', 'month', 'year'));
    }
}

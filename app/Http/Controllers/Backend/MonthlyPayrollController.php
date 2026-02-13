<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\MonthlyPayroll;
use App\Models\PayrollSetting;
use App\Models\SalaryAdvance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyPayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $payrolls = MonthlyPayroll::with('user')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('user_id')
            ->get();

        $users = User::whereIn('role', [1, 2, 3])->where('status', 1)->get();

        return view('backend.pages.payroll.monthly', compact('payrolls', 'month', 'year', 'users'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $month = $request->month;
        $year = $request->year;
        $paySettings = PayrollSetting::current();

        $users = User::whereIn('role', [1, 2, 3])->where('status', 1)->get();

        foreach ($users as $user) {
            $this->generateForUser($user, $month, $year, $paySettings);
        }

        return back()->with('message', 'Payroll generated for '.date('F', mktime(0, 0, 0, $month, 1)).' '.$year);
    }

    public function generateSingle(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $user = User::findOrFail($request->user_id);
        $paySettings = PayrollSetting::current();

        $this->generateForUser($user, $request->month, $request->year, $paySettings);

        return back()->with('message', 'Payroll generated for '.$user->name);
    }

    private function generateForUser(User $user, int $month, int $year, PayrollSetting $paySettings): void
    {
        $totalDays = Carbon::create($year, $month)->daysInMonth;
        $offDays = $user->getOffDaysArray();

        // Count working days (total days minus off days)
        $workingDays = 0;
        $offDayCount = 0;
        for ($day = 1; $day <= $totalDays; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dayName = $date->format('l');
            if (in_array($dayName, $offDays)) {
                $offDayCount++;
            } else {
                $workingDays++;
            }
        }

        // Get attendance records
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        // Fix any missed auto-checkouts: if check_in exists but no check_out, apply penalty and set check_out to end_time
        $startTimeDefault = $user->start_time ?? config('attendance.default_start_time');
        $endTimeDefault = $user->end_time ?? config('attendance.default_end_time');

        foreach ($attendances as $att) {
            if ($att->status === 'present' && $att->check_in && ! $att->check_out) {
                $dateStr = $att->date->toDateString();
                $endTime = Carbon::parse($dateStr.' '.$endTimeDefault);
                $startTime = Carbon::parse($dateStr.' '.$startTimeDefault);
                $checkInTime = Carbon::parse($att->check_in);

                $att->check_out = $endTime;
                $att->auto_checkout = true;
                $att->penalty_amount = $paySettings->forgot_checkout_penalty;
                $att->overtime_minutes = 0;
                $att->late_minutes = 0;

                // Early arrival overtime
                if ($checkInTime->lt($startTime)) {
                    $att->overtime_minutes = abs($startTime->diffInMinutes($checkInTime));
                }
                // Late arrival
                if ($checkInTime->gt($startTime)) {
                    $att->late_minutes = abs($checkInTime->diffInMinutes($startTime));
                }

                $att->save();
            }
        }

        $presentDays = $attendances->where('status', 'present')->count();
        $offDayPresents = $attendances->where('status', 'present')->where('is_off_day', true)->count();
        $absentDays = $workingDays - ($presentDays - $offDayPresents); // present on working days
        if ($absentDays < 0) {
            $absentDays = 0;
        }

        // Base salary: daily_salary * present days (on regular working days)
        $regularPresent = $presentDays - $offDayPresents;
        $baseSalary = $user->daily_salary * $regularPresent;

        // Off day bonus: 1.5x on off days (base already counted, add 0.5x as bonus)
        // Full off day pay = 1.5 * daily_salary. So off_day_bonus = 1.5 * daily_salary * off_day_presents
        $offDayBonus = $user->daily_salary * 1.5 * $offDayPresents;

        // Daily-based overtime and late fee calculation
        $totalOvertimeAmount = 0;
        $totalLateDeduction = 0;
        $unitMinutes = max($paySettings->overtime_unit_minutes, 1);
        $rate = $paySettings->overtime_rate;
        $dailySalary = $user->daily_salary;

        // Calculate scheduled minutes for half-time check
        $schedStart = Carbon::parse($user->start_time ?? config('attendance.default_start_time'));
        $schedEnd = Carbon::parse($user->end_time ?? config('attendance.default_end_time'));
        $scheduledMinutes = abs($schedEnd->diffInMinutes($schedStart));

        foreach ($attendances->where('status', 'present') as $att) {
            // Daily overtime bonus
            $dailyOvertimeUnits = floor(($att->overtime_minutes ?? 0) / $unitMinutes);
            $dailyOvertimeAmount = $dailyOvertimeUnits * $rate;
            $totalOvertimeAmount += $dailyOvertimeAmount;

            // Daily late fee
            $dailyLateUnits = floor(($att->late_minutes ?? 0) / $unitMinutes);
            $dailyLateFee = $dailyLateUnits * $rate;

            // Determine the cap: default = daily salary
            $lateCap = $dailySalary;

            // Special rule: if present at least half the scheduled time, cap at half daily salary
            if ($att->check_in && $att->check_out && $scheduledMinutes > 0) {
                $workedMinutes = abs(Carbon::parse($att->check_out)->diffInMinutes(Carbon::parse($att->check_in)));
                if ($workedMinutes >= ($scheduledMinutes / 2)) {
                    $lateCap = $dailySalary / 2;
                }
            }

            $dailyLateFee = min($dailyLateFee, $lateCap);
            $totalLateDeduction += $dailyLateFee;
        }

        $overtimeAmount = $totalOvertimeAmount;
        $lateDeduction = $totalLateDeduction;

        // Penalty: sum all penalties from attendance records
        $penaltyAmount = $attendances->sum('penalty_amount');

        // Advance deduction
        $advanceDeduction = SalaryAdvance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        // Net salary
        $netSalary = $baseSalary + $offDayBonus + $overtimeAmount - $lateDeduction - $penaltyAmount - $advanceDeduction;

        MonthlyPayroll::updateOrCreate(
            ['user_id' => $user->id, 'month' => $month, 'year' => $year],
            [
                'total_days' => $totalDays,
                'working_days' => $workingDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'off_day_presents' => $offDayPresents,
                'daily_salary' => $user->daily_salary,
                'base_salary' => $baseSalary,
                'off_day_bonus' => $offDayBonus,
                'overtime_amount' => $overtimeAmount,
                'late_deduction' => $lateDeduction,
                'penalty_amount' => $penaltyAmount,
                'advance_deduction' => $advanceDeduction,
                'net_salary' => $netSalary,
                'generated_by' => Auth::id(),
                'status' => 'draft',
            ]
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,approved,paid',
        ]);

        $payroll = MonthlyPayroll::findOrFail($id);
        $payroll->status = $request->status;
        $payroll->save();

        return back()->with('message', 'Payroll status updated.');
    }

    public function show($id)
    {
        $payroll = MonthlyPayroll::with('user')->findOrFail($id);
        $attendances = Attendance::where('user_id', $payroll->user_id)
            ->whereMonth('date', $payroll->month)
            ->whereYear('date', $payroll->year)
            ->orderBy('date')
            ->get();

        $advances = SalaryAdvance::where('user_id', $payroll->user_id)
            ->whereMonth('date', $payroll->month)
            ->whereYear('date', $payroll->year)
            ->orderBy('date')
            ->get();

        $paySettings = PayrollSetting::current();

        return view('backend.pages.payroll.show', compact('payroll', 'attendances', 'advances', 'paySettings'));
    }

    // ---- Self-service methods for admin's own payroll ----

    public function myPayrolls(Request $request)
    {
        $user = Auth::user();

        $payrolls = MonthlyPayroll::where('user_id', $user->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('backend.pages.payroll.my-payrolls', compact('payrolls'));
    }

    public function myPayrollShow($id)
    {
        $user = Auth::user();
        $payroll = MonthlyPayroll::where('user_id', $user->id)->findOrFail($id);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $payroll->month)
            ->whereYear('date', $payroll->year)
            ->orderBy('date')
            ->get();

        $advances = SalaryAdvance::where('user_id', $user->id)
            ->whereMonth('date', $payroll->month)
            ->whereYear('date', $payroll->year)
            ->orderBy('date')
            ->get();

        $paySettings = PayrollSetting::current();

        return view('backend.pages.payroll.my-payroll-show', compact('payroll', 'attendances', 'advances', 'paySettings'));
    }

    public function myAdvances(Request $request)
    {
        $user = Auth::user();

        $advances = SalaryAdvance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('backend.pages.payroll.my-advances', compact('advances'));
    }
}

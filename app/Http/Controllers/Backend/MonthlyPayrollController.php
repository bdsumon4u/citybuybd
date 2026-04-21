<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Cart;
use App\Models\Holiday;
use App\Models\MonthlyPayroll;
use App\Models\Order;
use App\Models\PayrollBonusAudit;
use App\Models\PayrollSetting;
use App\Models\SalaryAdvance;
use App\Models\User;
use App\Models\UserBonus;
use App\Services\QuantityMonitorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MonthlyPayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $users = User::whereIn('role', [1, 2, 3])->get();

        // Auto-generate payroll only for the current month
        $isCurrentMonth = ($month == now()->month && $year == now()->year);
        if ($isCurrentMonth) {
            $paySettings = PayrollSetting::current();
            foreach ($users as $user) {
                $this->generateForUser($user, $month, $year, $paySettings);
            }
        }

        $payrolls = MonthlyPayroll::with('user')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('user_id')
            ->get();

        $auditGroups = PayrollBonusAudit::with('editor')
            ->whereIn('monthly_payroll_id', $payrolls->pluck('id')->all())
            ->orderByDesc('id')
            ->get()
            ->groupBy('monthly_payroll_id');

        return view('backend.pages.payroll.monthly', compact('payrolls', 'auditGroups', 'month', 'year', 'users', 'isCurrentMonth'));
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

        $users = User::whereIn('role', [1, 2, 3])->get();

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

        // Collect active holidays that overlap with the requested month
        $monthStart = Carbon::create($year, $month, 1)->startOfDay();
        $monthEnd = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        $holidays = Holiday::active()
            ->whereDate('from_date', '<=', $monthEnd->toDateString())
            ->whereDate('to_date', '>=', $monthStart->toDateString())
            ->get();

        $holidayDates = [];
        foreach ($holidays as $holiday) {
            $holidayStart = $holiday->from_date->copy()->max($monthStart);
            $holidayEnd = $holiday->to_date->copy()->min($monthEnd);

            for ($cursor = $holidayStart->copy(); $cursor->lte($holidayEnd); $cursor->addDay()) {
                $holidayDates[$cursor->toDateString()] = true;
            }
        }

        // Count working days (total days minus weekly off days and holidays)
        $workingDays = 0;
        $offDayCount = 0;
        for ($day = 1; $day <= $totalDays; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dayName = $date->format('l');
            $dateKey = $date->toDateString();

            if (in_array($dayName, $offDays) || isset($holidayDates[$dateKey])) {
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
        $startTimeDefault = $user->start_time;
        $endTimeDefault = $user->end_time;

        foreach ($attendances as $att) {
            if ($att->status === 'present' && $att->check_in && ! $att->check_out) {
                $dateStr = $att->date->toDateString();
                $endTime = Carbon::parse($dateStr.' '.$endTimeDefault);
                $startTime = Carbon::parse($dateStr.' '.$startTimeDefault);
                $checkInTime = Carbon::parse($att->check_in);

                // Check if this is today and the employee is still working (end_time not exceeded)
                $isToday = Carbon::parse($att->date)->isToday();
                $stillWorking = $isToday && now()->lt($endTime);

                if ($stillWorking) {
                    // Employee is still working today — don't auto-checkout.
                    // Set temporary values for payroll calculation only (NOT saved to DB).
                    // Assume normal checkout at end_time: worked = end - checkIn, scheduled = end - start
                    $att->check_out = $endTime;
                    $att->penalty_amount = 0;

                    $workedMinutes = abs($endTime->diffInMinutes($checkInTime));
                    $scheduledMinutes = abs($endTime->diffInMinutes($startTime));
                    $offset = $workedMinutes - $scheduledMinutes;

                    $att->overtime_minutes = $offset > 0 ? $offset : 0;
                    $att->late_minutes = 0; // No late deduction while still working
                } else {
                    // Past date or end_time already exceeded — apply auto-checkout with penalty
                    $att->check_out = $endTime;
                    $att->auto_checkout = true;
                    $att->penalty_amount = $paySettings->forgot_checkout_penalty;

                    // offset = worked - scheduled (check_out is endTime)
                    $workedMinutes = abs($endTime->diffInMinutes($checkInTime));
                    $scheduledMinutes = abs($endTime->diffInMinutes($startTime));
                    $offset = $workedMinutes - $scheduledMinutes;

                    $att->overtime_minutes = $offset > 0 ? $offset : 0;
                    $att->late_minutes = $offset < 0 ? abs($offset) : 0;

                    $att->save();
                }
            }
        }

        $presentAttendances = $attendances->where('status', 'present');
        $presentDays = $presentAttendances->count();

        // Holiday presence is treated exactly like off-day presence for salary purposes.
        $offLikePresentDates = [];
        $holidayPresentDates = [];
        foreach ($presentAttendances as $attendance) {
            $dateKey = $attendance->date->toDateString();
            if (isset($holidayDates[$dateKey])) {
                $holidayPresentDates[$dateKey] = true;
            }

            if ($attendance->is_off_day || isset($holidayDates[$dateKey])) {
                $offLikePresentDates[$dateKey] = true;
            }
        }

        $offDayPresents = count($offLikePresentDates);
        $holidayDaysCount = count($holidayDates);
        $holidayPresentCount = count($holidayPresentDates);

        // If absent on a holiday, treat as paid present day (1x daily salary).
        $holidayAbsentPaidDays = max($holidayDaysCount - $holidayPresentCount, 0);

        $absentDays = $workingDays - ($presentDays - $offDayPresents); // present on working days
        if ($absentDays < 0) {
            $absentDays = 0;
        }

        // Daily salary = monthly salary / total days in the month
        $dailySalary = $totalDays > 0 ? $user->monthly_salary / $totalDays : 0;

        // Base salary:
        // - 1x for regular present days (non-off-day and non-holiday)
        // - 1x for holiday days even if absent
        $regularPresent = $presentDays - $offDayPresents;
        $baseSalary = $dailySalary * ($regularPresent + $holidayAbsentPaidDays);

        // Off day bonus: 1.5x on off days
        // Full off day pay = 1.5 * daily_salary * off_day_presents
        $offDayBonus = $dailySalary * 1.5 * $offDayPresents;

        // Daily-based overtime and late fee calculation
        $totalOvertimeAmount = 0;
        $totalLateDeduction = 0;
        $unitMinutes = max($paySettings->overtime_unit_minutes, 1);
        $rate = $paySettings->overtime_rate;
        $lateUnitMinutes = max($paySettings->latetime_unit_minutes ?? $unitMinutes, 1);
        $lateRate = $paySettings->latetime_rate ?? $rate;

        // Calculate scheduled minutes for half-time check
        $schedStart = Carbon::parse($user->start_time);
        $schedEnd = Carbon::parse($user->end_time);
        $scheduledMinutes = abs($schedEnd->diffInMinutes($schedStart));

        foreach ($attendances->where('status', 'present') as $att) {
            // Daily overtime bonus (includes extra overtime)
            $totalOT = ($att->overtime_minutes ?? 0) + ($att->extra_overtime_minutes ?? 0);
            $dailyOvertimeUnits = floor($totalOT / $unitMinutes);
            $dailyOvertimeAmount = $dailyOvertimeUnits * $rate;
            $totalOvertimeAmount += $dailyOvertimeAmount;

            // Daily late fee
            $dailyLateUnits = floor(($att->late_minutes ?? 0) / $lateUnitMinutes);
            $dailyLateFee = $dailyLateUnits * $lateRate;

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

        // === HAZIRA BONUS ===
        // Eligible if: no absences AND no late minutes in the month
        $haziraBonusAmount = 0;
        $attendanceByDate = $attendances->keyBy(fn (Attendance $attendance) => $attendance->date->toDateString());
        $absentCount = 0;

        for ($day = 1; $day <= $totalDays; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dateKey = $date->toDateString();
            $dayName = $date->format('l');

            if (in_array($dayName, $offDays) || isset($holidayDates[$dateKey])) {
                continue;
            }

            $attendance = $attendanceByDate[$dateKey] ?? null;

            if (! $attendance || $attendance->status !== 'present' || ! $attendance->check_in || ! $attendance->check_out) {
                $absentCount++;
            }
        }

        $totalLateMinutes = $attendances->sum('late_minutes');
        if ($absentCount === 0 /* && $totalLateMinutes === 0 */) {
            $haziraBonusAmount = $paySettings->hazira_bonus;
        }

        // === USER-SPECIFIC BONUSES ===
        $userBonusAmount = 0;
        $userBonuses = UserBonus::where('user_id', $user->id)
            ->where('year', $year)
            ->where('month', str_pad($month, 2, '0', STR_PAD_LEFT))
            ->get();
        foreach ($userBonuses as $uBonus) {
            $userBonusAmount += $uBonus->amount;
        }

        // === xSELL BONUS ===
        $xsellQualifiedCount = $this->countXsellQualifiedOrders($user, $month, $year, $paySettings);
        $xsellBonusAmount = $xsellQualifiedCount * $paySettings->xsell_bonus_rate;

        // Advance deduction
        $advanceDeduction = SalaryAdvance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        // Net salary
        $netSalary = $baseSalary + $offDayBonus + $overtimeAmount - $lateDeduction - $penaltyAmount + $haziraBonusAmount + $userBonusAmount + $xsellBonusAmount - $advanceDeduction;

        $existingPayroll = MonthlyPayroll::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        $oldValues = null;
        if ($existingPayroll) {
            $oldValues = [
                'base_salary' => (float) ($existingPayroll->base_salary ?? 0),
                'off_day_bonus' => (float) ($existingPayroll->off_day_bonus ?? 0),
                'overtime_amount' => (float) ($existingPayroll->overtime_amount ?? 0),
                'late_deduction' => (float) ($existingPayroll->late_deduction ?? 0),
                'penalty_amount' => (float) ($existingPayroll->penalty_amount ?? 0),
                'hazira_bonus_amount' => (float) ($existingPayroll->hazira_bonus_amount ?? 0),
                'occasional_bonus_amount' => (float) ($existingPayroll->occasional_bonus_amount ?? 0),
                'xsell_bonus_amount' => (float) ($existingPayroll->xsell_bonus_amount ?? 0),
                'advance_deduction' => (float) ($existingPayroll->advance_deduction ?? 0),
                'net_salary' => (float) ($existingPayroll->net_salary ?? 0),
            ];
        }

        $payroll = MonthlyPayroll::updateOrCreate(
            ['user_id' => $user->id, 'month' => $month, 'year' => $year],
            [
                'total_days' => $totalDays,
                'working_days' => $workingDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'off_day_presents' => $offDayPresents,
                'daily_salary' => round($dailySalary, 2),
                'base_salary' => $baseSalary,
                'off_day_bonus' => $offDayBonus,
                'overtime_amount' => $overtimeAmount,
                'late_deduction' => $lateDeduction,
                'penalty_amount' => $penaltyAmount,
                'hazira_bonus_amount' => $haziraBonusAmount,
                'occasional_bonus_amount' => $userBonusAmount,
                'xsell_bonus_amount' => $xsellBonusAmount,
                'advance_deduction' => $advanceDeduction,
                'net_salary' => $netSalary,
                'generated_by' => Auth::id(),
                'status' => 'draft',
            ]
        );

        if ($oldValues !== null) {
            $newValues = [
                'base_salary' => (float) ($payroll->base_salary ?? 0),
                'off_day_bonus' => (float) ($payroll->off_day_bonus ?? 0),
                'overtime_amount' => (float) ($payroll->overtime_amount ?? 0),
                'late_deduction' => (float) ($payroll->late_deduction ?? 0),
                'penalty_amount' => (float) ($payroll->penalty_amount ?? 0),
                'hazira_bonus_amount' => (float) ($payroll->hazira_bonus_amount ?? 0),
                'occasional_bonus_amount' => (float) ($payroll->occasional_bonus_amount ?? 0),
                'xsell_bonus_amount' => (float) ($payroll->xsell_bonus_amount ?? 0),
                'advance_deduction' => (float) ($payroll->advance_deduction ?? 0),
                'net_salary' => (float) ($payroll->net_salary ?? 0),
            ];

            // Skip logging when regeneration doesn't actually change tracked values.
            $trackedKeys = [
                'base_salary',
                'off_day_bonus',
                'overtime_amount',
                'late_deduction',
                'penalty_amount',
                'hazira_bonus_amount',
                'occasional_bonus_amount',
                'xsell_bonus_amount',
                'advance_deduction',
                'net_salary',
            ];

            $hasChanges = false;
            foreach ($trackedKeys as $key) {
                if (round((float) ($oldValues[$key] ?? 0), 2) !== round((float) ($newValues[$key] ?? 0), 2)) {
                    $hasChanges = true;
                    break;
                }
            }

            if ($hasChanges) {
                PayrollBonusAudit::create([
                    'monthly_payroll_id' => $payroll->id,
                    'edited_by' => Auth::id(),
                    'editor_ip' => request()?->ip(),
                    'event_type' => 'regenerated',
                    'old_values' => $oldValues,
                    'new_values' => $newValues,
                ]);
            }
        }
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

    public function updateBonuses(Request $request, $id)
    {
        $request->validate([
            'hazira_bonus_amount' => 'required|numeric|min:0',
            'occasional_bonus_amount' => 'required|numeric|min:0',
            'xsell_bonus_amount' => 'required|numeric|min:0',
        ]);

        $payroll = MonthlyPayroll::findOrFail($id);

        $oldValues = [
            'base_salary' => (float) ($payroll->base_salary ?? 0),
            'off_day_bonus' => (float) ($payroll->off_day_bonus ?? 0),
            'overtime_amount' => (float) ($payroll->overtime_amount ?? 0),
            'late_deduction' => (float) ($payroll->late_deduction ?? 0),
            'penalty_amount' => (float) ($payroll->penalty_amount ?? 0),
            'hazira_bonus_amount' => (float) ($payroll->hazira_bonus_amount ?? 0),
            'occasional_bonus_amount' => (float) ($payroll->occasional_bonus_amount ?? 0),
            'xsell_bonus_amount' => (float) ($payroll->xsell_bonus_amount ?? 0),
            'advance_deduction' => (float) ($payroll->advance_deduction ?? 0),
            'net_salary' => (float) ($payroll->net_salary ?? 0),
        ];

        $payroll->hazira_bonus_amount = (float) $request->hazira_bonus_amount;
        $payroll->occasional_bonus_amount = (float) $request->occasional_bonus_amount;
        $payroll->xsell_bonus_amount = (float) $request->xsell_bonus_amount;

        $payroll->net_salary =
            (float) $payroll->base_salary +
            (float) $payroll->off_day_bonus +
            (float) $payroll->overtime_amount -
            (float) $payroll->late_deduction -
            (float) $payroll->penalty_amount +
            (float) $payroll->hazira_bonus_amount +
            (float) $payroll->occasional_bonus_amount +
            (float) $payroll->xsell_bonus_amount -
            (float) $payroll->advance_deduction;

        $payroll->save();

        PayrollBonusAudit::create([
            'monthly_payroll_id' => $payroll->id,
            'edited_by' => Auth::id(),
            'editor_ip' => $request->ip(),
            'event_type' => 'manual_edit',
            'old_values' => $oldValues,
            'new_values' => [
                'base_salary' => (float) $payroll->base_salary,
                'off_day_bonus' => (float) $payroll->off_day_bonus,
                'overtime_amount' => (float) $payroll->overtime_amount,
                'late_deduction' => (float) $payroll->late_deduction,
                'penalty_amount' => (float) $payroll->penalty_amount,
                'hazira_bonus_amount' => (float) $payroll->hazira_bonus_amount,
                'occasional_bonus_amount' => (float) $payroll->occasional_bonus_amount,
                'xsell_bonus_amount' => (float) $payroll->xsell_bonus_amount,
                'advance_deduction' => (float) $payroll->advance_deduction,
                'net_salary' => (float) $payroll->net_salary,
            ],
        ]);

        Log::channel(config('logging.default'))->info('Payroll bonus edited by admin', [
            'payroll_id' => $payroll->id,
            'user_id' => $payroll->user_id,
            'month' => $payroll->month,
            'year' => $payroll->year,
            'edited_by' => Auth::id(),
            'editor_ip' => $request->ip(),
            'old' => $oldValues,
            'new' => [
                'hazira_bonus_amount' => (float) $payroll->hazira_bonus_amount,
                'occasional_bonus_amount' => (float) $payroll->occasional_bonus_amount,
                'xsell_bonus_amount' => (float) $payroll->xsell_bonus_amount,
                'net_salary' => (float) $payroll->net_salary,
            ],
        ]);

        return back()->with('message', 'Payroll bonuses updated successfully.');
    }

    public function show($id)
    {
        $payroll = MonthlyPayroll::with('user')->findOrFail($id);
        $monthStart = Carbon::create($payroll->year, $payroll->month, 1)->startOfDay();
        $monthEnd = Carbon::create($payroll->year, $payroll->month, 1)->endOfMonth()->endOfDay();

        $holidays = Holiday::active()
            ->whereDate('from_date', '<=', $monthEnd->toDateString())
            ->whereDate('to_date', '>=', $monthStart->toDateString())
            ->orderBy('from_date')
            ->get();

        $holidayRanges = collect();
        $holidayDateMap = [];

        foreach ($holidays as $holiday) {
            $rangeStart = $holiday->from_date->copy()->max($monthStart);
            $rangeEnd = $holiday->to_date->copy()->min($monthEnd);
            $days = $rangeStart->diffInDays($rangeEnd) + 1;

            $holidayRanges->push([
                'name' => $holiday->name,
                'start' => $rangeStart->copy(),
                'end' => $rangeEnd->copy(),
                'days' => $days,
            ]);

            for ($cursor = $rangeStart->copy(); $cursor->lte($rangeEnd); $cursor->addDay()) {
                $holidayDateMap[$cursor->toDateString()] = true;
            }
        }

        $holidayDaysInMonth = count($holidayDateMap);

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
        $bonusAudits = $payroll->bonusAudits()
            ->with('editor')
            ->latest('id')
            ->limit(30)
            ->get();

        return view('backend.pages.payroll.show', compact('payroll', 'bonusAudits', 'holidayRanges', 'holidayDaysInMonth', 'attendances', 'advances', 'paySettings'));
    }

    public function xsellOrders($id)
    {
        $payroll = MonthlyPayroll::with('user')->findOrFail($id);
        $xsellOrders = $this->getXsellOrderDetails($payroll->user, $payroll->month, $payroll->year);

        return view('backend.pages.payroll.xsell-orders', compact('payroll', 'xsellOrders'));
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
        $monthStart = Carbon::create($payroll->year, $payroll->month, 1)->startOfDay();
        $monthEnd = Carbon::create($payroll->year, $payroll->month, 1)->endOfMonth()->endOfDay();

        $holidays = Holiday::active()
            ->whereDate('from_date', '<=', $monthEnd->toDateString())
            ->whereDate('to_date', '>=', $monthStart->toDateString())
            ->orderBy('from_date')
            ->get();

        $holidayRanges = collect();
        $holidayDateMap = [];

        foreach ($holidays as $holiday) {
            $rangeStart = $holiday->from_date->copy()->max($monthStart);
            $rangeEnd = $holiday->to_date->copy()->min($monthEnd);

            $holidayRanges->push([
                'name' => $holiday->name,
                'start' => $rangeStart->copy(),
                'end' => $rangeEnd->copy(),
            ]);

            for ($cursor = $rangeStart->copy(); $cursor->lte($rangeEnd); $cursor->addDay()) {
                $holidayDateMap[$cursor->toDateString()] = true;
            }
        }

        $holidayDaysInMonth = count($holidayDateMap);

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

        return view('backend.pages.payroll.my-payroll-show', compact('payroll', 'holidayRanges', 'holidayDaysInMonth', 'attendances', 'advances', 'paySettings'));
    }

    private function getXsellOrderDetails(User $user, int $month, int $year): Collection
    {
        $paySettings = PayrollSetting::current();
        $quantityMonitorService = app(QuantityMonitorService::class);
        $qualifiedOrders = collect();

        $this->getMonthlyDeliveredOrdersQuery($user, $month, $year)
            ->orderBy('id')
            ->chunkById(200, function ($orders) use (&$qualifiedOrders, $quantityMonitorService, $paySettings): void {
                [$orderQuantityByOrderId, $deliveredProductIdsByOrderId] = $this->buildCartCacheForOrders($orders);

                foreach ($orders as $order) {
                    $evaluated = $this->evaluateXsellOrder(
                        $order,
                        $quantityMonitorService,
                        $paySettings,
                        $orderQuantityByOrderId,
                        $deliveredProductIdsByOrderId
                    );

                    if ($evaluated !== null) {
                        $qualifiedOrders->push($evaluated);
                    }
                }
            });

        return $qualifiedOrders;
    }

    private function countXsellQualifiedOrders(User $user, int $month, int $year, PayrollSetting $paySettings): int
    {
        $quantityMonitorService = app(QuantityMonitorService::class);
        $qualifiedCount = 0;

        $this->getMonthlyDeliveredOrdersQuery($user, $month, $year)
            ->orderBy('id')
            ->chunkById(800, function ($orders) use (&$qualifiedCount, $quantityMonitorService, $paySettings): void {
                [$orderQuantityByOrderId, $deliveredProductIdsByOrderId] = $this->buildCartCacheForOrders($orders);

                foreach ($orders as $order) {
                    if ($this->evaluateXsellOrder(
                        $order,
                        $quantityMonitorService,
                        $paySettings,
                        $orderQuantityByOrderId,
                        $deliveredProductIdsByOrderId
                    ) !== null) {
                        $qualifiedCount++;
                    }
                }
            });

        return $qualifiedCount;
    }

    private function getMonthlyDeliveredOrdersQuery(User $user, int $month, int $year)
    {
        return Order::query()
            ->select([
                'id',
                'name',
                'phone',
                'order_type',
                'ordered_quantity',
                'delivered_quantity',
                'delivered_at',
                'product_id',
                'product_slug',
                'ordered_product_ids',
            ])
            ->where('order_assign', $user->id)
            ->where('status', OrderStatus::Completed)
            ->whereNotNull('delivered_at')
            ->whereYear('delivered_at', $year)
            ->whereMonth('delivered_at', $month);
    }

    private function buildCartCacheForOrders(Collection $orders): array
    {
        $orderIds = $orders->pluck('id')
            ->filter(fn ($id) => ! empty($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($orderIds->isEmpty()) {
            return [[], []];
        }

        $orderQuantityByOrderId = Cart::query()
            ->selectRaw('order_id, SUM(quantity) as qty')
            ->whereIn('order_id', $orderIds->all())
            ->groupBy('order_id')
            ->pluck('qty', 'order_id')
            ->mapWithKeys(fn ($qty, $orderId) => [(int) $orderId => (int) $qty])
            ->all();

        $deliveredProductIdsByOrderId = [];

        $cartProductRows = Cart::query()
            ->select(['order_id', 'product_id'])
            ->whereIn('order_id', $orderIds->all())
            ->whereNotNull('product_id')
            ->orderBy('order_id')
            ->get();

        foreach ($cartProductRows as $row) {
            $orderId = (int) $row->order_id;
            $productId = (int) $row->product_id;

            if ($productId <= 0) {
                continue;
            }

            $deliveredProductIdsByOrderId[$orderId][] = $productId;
        }

        foreach ($deliveredProductIdsByOrderId as $orderId => $productIds) {
            $deliveredProductIdsByOrderId[$orderId] = array_values(array_unique(array_map('intval', $productIds)));
        }

        return [$orderQuantityByOrderId, $deliveredProductIdsByOrderId];
    }

    private function evaluateXsellOrder(
        Order $order,
        QuantityMonitorService $quantityMonitorService,
        PayrollSetting $paySettings,
        array $orderQuantityByOrderId = [],
        array $deliveredProductIdsByOrderId = []
    ): ?array {
        $bonusOnQuantityIncrease = (bool) $paySettings->xsell_bonus_on_quantity_increase;
        $bonusOnProductReplace = (bool) $paySettings->xsell_bonus_on_product_replace;

        if (! $bonusOnQuantityIncrease && ! $bonusOnProductReplace) {
            return null;
        }

        $cachedQty = (int) ($orderQuantityByOrderId[(int) $order->id] ?? 0);
        $orderedQty = $order->ordered_quantity ?: $cachedQty;
        $deliveredQty = $order->delivered_quantity ?: $cachedQty;

        if ($orderedQty <= 0 && $deliveredQty <= 0) {
            // Legacy safe fallback for rare rows where chunk cache is empty.
            $fallbackQty = $quantityMonitorService->getOrderedQuantity($order);
            $orderedQty = $orderedQty ?: $fallbackQty;
            $deliveredQty = $deliveredQty ?: $fallbackQty;
        }

        $isQuantityIncreaseQualified = $bonusOnQuantityIncrease && $deliveredQty > $orderedQty;

        $isProductReplaceQualified = false;
        if ($bonusOnProductReplace) {
            $orderedProductIds = $quantityMonitorService->getOrderedProductIdsSnapshot($order);

            $deliveredProductIds = $deliveredProductIdsByOrderId[(int) $order->id] ?? [];
            if (empty($deliveredProductIds)) {
                // Fallback keeps behavior identical for any edge row.
                $deliveredProductIds = $quantityMonitorService->getDeliveredProductIds($order);
            }

            if (! empty($orderedProductIds) && ! empty($deliveredProductIds)) {
                $addedProducts = array_diff($deliveredProductIds, $orderedProductIds);
                $removedProducts = array_diff($orderedProductIds, $deliveredProductIds);
                $isProductReplaceQualified = ! empty($addedProducts) || ! empty($removedProducts);
            }
        }

        if (! $isQuantityIncreaseQualified && ! $isProductReplaceQualified) {
            return null;
        }

        $reasons = [];
        if ($isQuantityIncreaseQualified) {
            $reasons[] = 'Delivered quantity exceeded ordered quantity';
        }
        if ($isProductReplaceQualified) {
            $reasons[] = 'Delivered product set differs from ordered product set';
        }

        return [
            'order' => $order,
            'ordered_quantity' => $orderedQty,
            'delivered_quantity' => $deliveredQty,
            'bonus_reason' => implode(' + ', $reasons),
        ];
    }

    public function myAdvances(Request $request)
    {
        $user = Auth::user();

        $advances = SalaryAdvance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('backend.pages.payroll.my-advances', compact('advances'));
    }

    public function printSalary($id)
    {
        $payroll = MonthlyPayroll::with('user')->findOrFail($id);
        $monthStart = Carbon::create($payroll->year, $payroll->month, 1)->startOfDay();
        $monthEnd = Carbon::create($payroll->year, $payroll->month, 1)->endOfMonth()->endOfDay();

        $holidays = Holiday::active()
            ->whereDate('from_date', '<=', $monthEnd->toDateString())
            ->whereDate('to_date', '>=', $monthStart->toDateString())
            ->orderBy('from_date')
            ->get();

        $holidayRanges = collect();
        $holidayDateMap = [];

        foreach ($holidays as $holiday) {
            $rangeStart = $holiday->from_date->copy()->max($monthStart);
            $rangeEnd = $holiday->to_date->copy()->min($monthEnd);

            $holidayRanges->push([
                'name' => $holiday->name,
                'start' => $rangeStart->copy(),
                'end' => $rangeEnd->copy(),
            ]);

            for ($cursor = $rangeStart->copy(); $cursor->lte($rangeEnd); $cursor->addDay()) {
                $holidayDateMap[$cursor->toDateString()] = true;
            }
        }

        $holidayDaysInMonth = count($holidayDateMap);

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

        return view('backend.pages.payroll.print-salary', compact('payroll', 'holidayRanges', 'holidayDaysInMonth', 'attendances', 'advances', 'paySettings'));
    }
}

<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\MonthlyPayroll;
use App\Models\PayrollSetting;
use App\Models\SalaryAdvance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $payrolls = MonthlyPayroll::where('user_id', $user->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('employee.pages.payroll.index', compact('payrolls'));
    }

    public function show($id)
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

        return view('employee.pages.payroll.show', compact('payroll', 'holidayRanges', 'holidayDaysInMonth', 'attendances', 'advances', 'paySettings'));
    }

    public function advances(Request $request)
    {
        $user = Auth::user();

        $advances = SalaryAdvance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('employee.pages.payroll.advances', compact('advances'));
    }
}

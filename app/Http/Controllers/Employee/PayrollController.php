<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\MonthlyPayroll;
use App\Models\PayrollSetting;
use App\Models\SalaryAdvance;
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

        return view('employee.pages.payroll.show', compact('payroll', 'attendances', 'advances', 'paySettings'));
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

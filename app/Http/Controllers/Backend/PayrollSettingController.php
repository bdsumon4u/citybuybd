<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PayrollSetting;
use Illuminate\Http\Request;

class PayrollSettingController extends Controller
{
    public function index()
    {
        $paySettings = PayrollSetting::current();

        return view('backend.pages.payroll.settings', compact('paySettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'overtime_rate' => 'required|numeric|min:0',
            'overtime_unit_minutes' => 'required|integer|min:1',
            'latetime_rate' => 'required|numeric|min:0',
            'latetime_unit_minutes' => 'required|integer|min:1',
            'forgot_checkout_penalty' => 'required|numeric|min:0',
            'hazira_bonus' => 'required|numeric|min:0',
            'allow_self_checkout' => 'nullable|boolean',
        ]);

        $paySettings = PayrollSetting::first();

        if (! $paySettings) {
            $paySettings = new PayrollSetting;
        }

        $paySettings->overtime_rate = $request->overtime_rate;
        $paySettings->overtime_unit_minutes = $request->overtime_unit_minutes;
        $paySettings->latetime_rate = $request->latetime_rate;
        $paySettings->latetime_unit_minutes = $request->latetime_unit_minutes;
        $paySettings->forgot_checkout_penalty = $request->forgot_checkout_penalty;
        $paySettings->hazira_bonus = $request->hazira_bonus;
        $paySettings->allow_self_checkout = $request->boolean('allow_self_checkout');
        $paySettings->save();

        return back()->with('message', 'Payroll settings updated successfully!');
    }
}

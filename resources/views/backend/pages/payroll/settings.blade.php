@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Payroll Settings</h4>
            <p class="mg-b-0">Configure overtime rates and penalties</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible">{{ session('message') }}</div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <form method="POST" action="{{ route('admin.payroll.settings.update') }}">
                        @csrf

                        <div class="form-group">
                            <label class="font-weight-bold">Overtime Rate (৳ per unit)</label>
                            <input type="number" step="0.01" name="overtime_rate"
                                value="{{ $paySettings->overtime_rate }}" class="form-control" required>
                            <small class="text-muted">Amount paid per overtime unit</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Overtime Unit (minutes)</label>
                            <input type="number" name="overtime_unit_minutes"
                                value="{{ $paySettings->overtime_unit_minutes }}" class="form-control" required
                                min="1">
                            <small class="text-muted">How many minutes = 1 unit of overtime. E.g., 60 means overtime is
                                calculated per hour.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Forgot Checkout Penalty (৳)</label>
                            <input type="number" step="0.01" name="forgot_checkout_penalty"
                                value="{{ $paySettings->forgot_checkout_penalty }}" class="form-control" required>
                            <small class="text-muted">Penalty deducted if employee doesn't toggle off the attendance switch
                                before leaving</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="text-white card-header bg-info"><strong>How Payroll Works</strong></div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li><strong>Base Salary:</strong> Daily salary × present days (regular working days)</li>
                                <li><strong>Off-day Bonus:</strong> If an employee works on their off day, they get
                                    <strong>1.5×</strong> their daily salary for that day</li>
                                <li><strong>Overtime (Daily):</strong> Calculated per day. If check-in is before start_time
                                    or check-out is after end_time, daily OT = floor(minutes ÷ unit) × rate</li>
                                <li><strong>Late Fee (Daily):</strong> Calculated per day. If check-in is after start_time
                                    or check-out is before end_time, daily late = floor(minutes ÷ unit) × rate</li>
                                <li class="text-danger"><strong>Late Fee Cap:</strong> Daily late fee cannot exceed the
                                    employee's daily salary. <em>Special rule:</em> if the employee was present for at least
                                    <strong>half</strong> of their scheduled hours, late fee is capped at
                                    <strong>half</strong> the daily salary.</li>
                                <li><strong>Penalty:</strong> If an employee doesn't check out, system auto-checks them out
                                    at their end_time and applies the penalty</li>
                                <li><strong>Advance Deduction:</strong> Any salary advances taken during the month are
                                    deducted</li>
                                <li><strong>Net Salary = Base + Off-day Bonus + Overtime - Late Fee - Penalty -
                                        Advances</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

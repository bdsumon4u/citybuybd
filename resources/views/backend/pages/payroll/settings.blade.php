@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Payroll Settings</h4>
            <p class="mg-b-0">Configure Overtime rates and penalties</p>
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
                            <input type="text" name="overtime_rate" value="{{ $paySettings->overtime_rate }}"
                                class="form-control" required>
                            <small class="text-muted">Amount paid per Overtime unit</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Overtime Unit (minutes)</label>
                            <input type="text" name="overtime_unit_minutes"
                                value="{{ $paySettings->overtime_unit_minutes }}" class="form-control" required
                                min="1">
                            <small class="text-muted">How many minutes = 1 unit of Overtime. E.g., 60 means Overtime is
                                calculated per hour.</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="font-weight-bold">Late Fee Rate (৳ per unit)</label>
                            <input type="text" name="latetime_rate" value="{{ $paySettings->latetime_rate }}"
                                class="form-control" required>
                            <small class="text-muted">Amount deducted per late unit. Set to <strong>0</strong> to disable
                                late fee deduction entirely.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Late Fee Unit (minutes)</label>
                            <input type="text" name="latetime_unit_minutes"
                                value="{{ $paySettings->latetime_unit_minutes }}" class="form-control" required
                                min="1">
                            <small class="text-muted">How many minutes = 1 unit of late fee. E.g., 60 means late fee is
                                calculated per hour.</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="font-weight-bold">Forgot Checkout Penalty (৳)</label>
                            <input type="text" name="forgot_checkout_penalty"
                                value="{{ $paySettings->forgot_checkout_penalty }}" class="form-control" required>
                            <small class="text-muted">Penalty deducted if employee doesn't toggle off the attendance switch
                                before leaving</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="font-weight-bold">Allow Self Check-out</label>
                            <div class="d-flex align-items-center">
                                <input type="hidden" name="allow_self_checkout" value="0">
                                <label class="toggle-switch mr-2 mb-0" style="position:relative;display:inline-block;width:50px;height:26px;">
                                    <input type="checkbox" name="allow_self_checkout" value="1"
                                        {{ $paySettings->allow_self_checkout ? 'checked' : '' }}
                                        style="opacity:0;width:0;height:0;"
                                        onchange="document.getElementById('toggleLabel').textContent = this.checked ? 'Enabled' : 'Disabled'">
                                    <span style="position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;border-radius:26px;transition:.3s;"></span>
                                </label>
                                <span id="toggleLabel" class="font-weight-bold {{ $paySettings->allow_self_checkout ? 'text-success' : 'text-danger' }}">
                                    {{ $paySettings->allow_self_checkout ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <style>
                                .toggle-switch input:checked + span { background-color: #23BF08 !important; }
                                .toggle-switch span:before {
                                    content:""; position:absolute; height:20px; width:20px; left:3px; bottom:3px;
                                    background-color:white; border-radius:50%; transition:.3s;
                                }
                                .toggle-switch input:checked + span:before { transform: translateX(24px); }
                            </style>
                            <small class="text-muted">When <strong>OFF</strong>, employees can check-in but cannot
                                check-out by themselves. Admin must check them out or the system will auto-checkout at
                                end time.</small>
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
                                    <strong>1.5×</strong> their daily salary for that day
                                </li>
                                <li><strong>Overtime (Daily):</strong> Calculated per day. If check-in is before start_time
                                    or check-out is after end_time, daily Overtime = floor(minutes ÷ Overtime unit) ×
                                    Overtime rate</li>
                                <li><strong>Late Fee (Daily):</strong> Calculated per day using <em>separate</em> late fee
                                    rate & unit. If check-in is after start_time or check-out is before end_time,
                                    daily late = floor(minutes ÷ Late unit) × Late rate. <strong>Set late rate to 0 to
                                        disable late fee entirely.</strong></li>
                                <li class="text-danger"><strong>Late Fee Cap:</strong> Daily late fee cannot exceed the
                                    employee's daily salary. <em>Special rule:</em> if the employee was present for at least
                                    <strong>half</strong> of their scheduled hours, late fee is capped at
                                    <strong>half</strong> the daily salary.
                                </li>
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

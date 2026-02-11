@extends('employee.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Payroll Details: {{ $payroll->month_name }} {{ $payroll->year }}</h4>
            <p class="mg-b-0">Detailed salary breakdown</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <a href="{{ route('employee.payroll.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Payroll
            </a>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white"><strong>Summary</strong></div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th>Month:</th>
                                    <td>{{ $payroll->month_name }} {{ $payroll->year }}</td>
                                </tr>
                                <tr>
                                    <th>Daily Salary:</th>
                                    <td>৳{{ number_format($payroll->daily_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total Days:</th>
                                    <td>{{ $payroll->total_days }}</td>
                                </tr>
                                <tr>
                                    <th>Working Days:</th>
                                    <td>{{ $payroll->working_days }}</td>
                                </tr>
                                <tr>
                                    <th>Present Days:</th>
                                    <td>{{ $payroll->present_days }}</td>
                                </tr>
                                <tr>
                                    <th>Absent Days:</th>
                                    <td>{{ $payroll->absent_days }}</td>
                                </tr>
                                <tr>
                                    <th>Off-day Work:</th>
                                    <td>{{ $payroll->off_day_presents }} days</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white"><strong>Salary Calculation</strong></div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th>Base Salary:</th>
                                    <td>৳{{ number_format($payroll->base_salary, 2) }}</td>
                                </tr>
                                <tr class="text-success">
                                    <th>+ Off-day Bonus (1.5x):</th>
                                    <td>৳{{ number_format($payroll->off_day_bonus, 2) }}</td>
                                </tr>
                                <tr class="text-success">
                                    <th>+ Overtime:</th>
                                    <td>৳{{ number_format($payroll->overtime_amount, 2) }}</td>
                                </tr>
                                <tr class="text-danger">
                                    <th>- Late Fee:</th>
                                    <td>৳{{ number_format($payroll->late_deduction, 2) }}</td>
                                </tr>
                                <tr class="text-danger">
                                    <th>- Penalties:</th>
                                    <td>৳{{ number_format($payroll->penalty_amount, 2) }}</td>
                                </tr>
                                <tr class="text-danger">
                                    <th>- Advances:</th>
                                    <td>৳{{ number_format($payroll->advance_deduction, 2) }}</td>
                                </tr>
                                <tr class="table-info font-weight-bold">
                                    <th>Net Salary:</th>
                                    <td>৳{{ number_format($payroll->net_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if ($payroll->status == 'draft')
                                            <span class="badge badge-secondary">Draft</span>
                                        @elseif($payroll->status == 'approved')
                                            <span class="badge badge-primary">Approved</span>
                                        @elseif($payroll->status == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3">Attendance Records</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>OT (min)</th>
                            <th>OT ৳</th>
                            <th>Late (min)</th>
                            <th>Late ৳</th>
                            <th>Penalty</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $unitMin = max($paySettings->overtime_unit_minutes, 1);
                            $otRate = $paySettings->overtime_rate;
                            $dSalary = $payroll->daily_salary;
                            $sStart = \Carbon\Carbon::parse($payroll->user->start_time ?? config('attendance.default_start_time'));
                            $sEnd = \Carbon\Carbon::parse($payroll->user->end_time ?? config('attendance.default_end_time'));
                            $schedMin = abs($sEnd->diffInMinutes($sStart));
                        @endphp
                        @foreach ($attendances as $att)
                            @php
                                $dailyOT = floor(($att->overtime_minutes ?? 0) / $unitMin) * $otRate;
                                $dailyLate = floor(($att->late_minutes ?? 0) / $unitMin) * $otRate;
                                $cap = $dSalary;
                                if ($att->check_in && $att->check_out && $schedMin > 0) {
                                    $worked = abs(
                                        \Carbon\Carbon::parse($att->check_out)->diffInMinutes(
                                            \Carbon\Carbon::parse($att->check_in),
                                        ),
                                    );
                                    if ($worked >= $schedMin / 2) {
                                        $cap = $dSalary / 2;
                                    }
                                }
                                $dailyLate = min($dailyLate, $cap);
                            @endphp
                            <tr class="{{ $att->is_off_day ? 'table-warning' : '' }}">
                                <td>{{ $att->date->format('d M') }}</td>
                                <td>{{ $att->date->format('l') }}</td>
                                <td>
                                    <span class="badge badge-{{ $att->status == 'present' ? 'success' : 'danger' }}">{{ ucfirst($att->status) }}</span>
                                    @if ($att->is_off_day)
                                        <span class="badge badge-warning">Off-day</span>
                                    @endif
                                    @if ($att->auto_checkout)
                                        <span class="badge badge-danger">Auto-out</span>
                                    @endif
                                </td>
                                <td>{{ $att->check_in?->format('h:i A') ?? '-' }}</td>
                                <td>{{ $att->check_out?->format('h:i A') ?? '-' }}</td>
                                <td>{{ $att->overtime_minutes }}</td>
                                <td class="text-success">
                                    {{ $att->status == 'present' && $dailyOT > 0 ? '৳' . number_format($dailyOT, 2) : '-' }}
                                </td>
                                <td>{{ $att->late_minutes }}</td>
                                <td class="text-danger">
                                    {{ $att->status == 'present' && $dailyLate > 0 ? '৳' . number_format($dailyLate, 2) : '-' }}
                                </td>
                                <td>{{ $att->penalty_amount > 0 ? '৳' . number_format($att->penalty_amount, 2) : '-' }}</td>
                                <td>{{ $att->note }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($advances->count() > 0)
                <h5 class="mb-3">Salary Advances</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($advances as $adv)
                                <tr>
                                    <td>{{ $adv->date->format('d M Y') }}</td>
                                    <td>৳{{ number_format($adv->amount, 2) }}</td>
                                    <td>{{ $adv->note ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@extends('manager.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <div class="mb-3 d-flex justify-content-between">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14">
                    <i class="fas fa-file-invoice-dollar"></i> Payroll Details - {{ $payroll->month_name }}
                    {{ $payroll->year }}
                </h6>
                <a href="{{ route('manager.payroll.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="mb-4 row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="text-white card-header bg-primary">
                            <strong>Employee Information</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Name:</strong> {{ $payroll->user->name }}</p>
                            <p><strong>Email:</strong> {{ $payroll->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $payroll->user->phone }}</p>
                            <p><strong>Monthly Salary:</strong> ৳{{ number_format($payroll->monthly_salary, 2) }}</p>
                            <p><strong>Status:</strong>
                                @if ($payroll->status == 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($payroll->status == 'approved')
                                    <span class="badge badge-primary">Approved</span>
                                @else
                                    <span class="badge badge-warning">Draft</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="text-white card-header bg-success">
                            <strong>Salary Summary</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td>Present Days</td>
                                    <td class="text-right">{{ $payroll->present_days }}</td>
                                </tr>
                                <tr>
                                    <td>Off-day Work</td>
                                    <td class="text-right">{{ $payroll->off_day_presents }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td>Base Salary</td>
                                    <td class="text-right">৳{{ number_format($payroll->base_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Off-Day Bonus (1.5x)</td>
                                    <td class="text-right text-success">+ ৳{{ number_format($payroll->off_day_bonus, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>OVER Amount</td>
                                    <td class="text-right text-success">+
                                        ৳{{ number_format($payroll->overtime_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Hazira Bonus</td>
                                    <td class="text-right text-success">+
                                        ৳{{ number_format($payroll->hazira_bonus_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Special Bonus</td>
                                    <td class="text-right text-success">+
                                        ৳{{ number_format($payroll->occasional_bonus_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>xSell Bonus</td>
                                    <td class="text-right text-success">+
                                        ৳{{ number_format($payroll->xsell_bonus_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Late Fee</td>
                                    <td class="text-right text-danger">- ৳{{ number_format($payroll->late_deduction, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Penalty</td>
                                    <td class="text-right text-danger">- ৳{{ number_format($payroll->penalty_amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Advance Deduction</td>
                                    <td class="text-right text-danger">-
                                        ৳{{ number_format($payroll->advance_deduction, 2) }}</td>
                                </tr>
                                <tr class="border-top font-weight-bold">
                                    <td>Net Salary</td>
                                    <td class="text-right">৳{{ number_format($payroll->net_salary, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3">Attendance Records</h5>
            <div class="mb-4 table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>OVER (min)</th>
                            <th>OVER ৳</th>
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
                            $lateUnitMin = max($paySettings->latetime_unit_minutes ?? $unitMin, 1);
                            $lateRate = $paySettings->latetime_rate ?? $otRate;
                            $dSalary = $payroll->daily_salary;
                            $sStart = \Carbon\Carbon::parse($payroll->user->start_time);
                            $sEnd = \Carbon\Carbon::parse($payroll->user->end_time);
                            $schedMin = abs($sEnd->diffInMinutes($sStart));
                            $totalOTMin = $totalOTAmount = $totalLateMin = $totalLateAmount = $totalPenalty = 0;
                        @endphp
                        @foreach ($attendances as $att)
                            @php
                                $dailyOT = floor(($att->overtime_minutes ?? 0) / $unitMin) * $otRate;
                                $dailyLate = floor(($att->late_minutes ?? 0) / $lateUnitMin) * $lateRate;
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
                                $totalOTMin += $att->overtime_minutes ?? 0;
                                $totalOTAmount += $dailyOT;
                                $totalLateMin += $att->late_minutes ?? 0;
                                $totalLateAmount += $dailyLate;
                                $totalPenalty += $att->penalty_amount ?? 0;
                            @endphp
                            <tr class="{{ $att->is_off_day ? 'table-warning' : '' }}">
                                <td>{{ $att->date->format('d M') }}</td>
                                <td>{{ $att->date->format('l') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $att->status == 'present' ? 'success' : 'danger' }}">{{ ucfirst($att->status) }}</span>
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
                                <td>{{ $att->penalty_amount > 0 ? '৳' . number_format($att->penalty_amount, 2) : '-' }}
                                </td>
                                <td>{{ $att->note }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td colspan="6" style="text-align: right;">Total:</td>
                            <td>{{ $totalOTMin }} min</td>
                            <td class="text-success">৳{{ number_format($totalOTAmount, 2) }}</td>
                            <td>{{ $totalLateMin }} min</td>
                            <td class="text-danger">৳{{ number_format($totalLateAmount, 2) }}</td>
                            <td>৳{{ number_format($totalPenalty, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
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

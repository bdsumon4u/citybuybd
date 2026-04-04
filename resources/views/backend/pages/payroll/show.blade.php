@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Payroll Details: {{ $payroll->user->name ?? 'N/A' }}</h4>
            <p class="mg-b-0">{{ $payroll->month_name }} {{ $payroll->year }} - Detailed Breakdown</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <a href="{{ route('admin.payroll.monthly', ['month' => $payroll->month, 'year' => $payroll->year]) }}"
                class="mb-3 btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Monthly Payroll
            </a>
            <a href="{{ route('admin.payroll.print', $payroll->id) }}" class="mb-3 btn btn-outline-dark" target="_blank">
                <i class="fas fa-print"></i> Print Salary Sheet
            </a>

            <div class="mb-4 row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="text-white card-header bg-primary"><strong>Employee Info</strong></div>
                        <div class="card-body">
                            <table class="table mb-0 table-sm">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $payroll->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Role:</th>
                                    <td>{{ $payroll->user->role == 2 ? 'Manager' : 'Employee' }}</td>
                                </tr>
                                <tr>
                                    <th>Monthly Salary:</th>
                                    <td>৳{{ number_format($payroll->user->monthly_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Schedule:</th>
                                    <td>{{ $payroll->user->start_time }} - {{ $payroll->user->end_time }}</td>
                                </tr>
                                <tr>
                                    <th>Off Days:</th>
                                    <td>{{ $payroll->user->off_days ?? 'None' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3 card">
                        <div class="text-white card-header bg-warning"><strong>Holidays in This Month</strong></div>
                        <div class="card-body">
                            @if ($holidayRanges->count() > 0)
                                <ul class="pl-3 mb-0">
                                    @foreach ($holidayRanges as $holiday)
                                        <li>
                                            <strong>{{ $holiday['name'] }}</strong>
                                            ({{ $holiday['start']->format('d M Y') }} -
                                            {{ $holiday['end']->format('d M Y') }})
                                        </li>
                                    @endforeach
                                </ul>
                                <hr class="my-2">
                                <p class="mb-0 font-weight-bold">Total holiday days in this month:
                                    {{ $holidayDaysInMonth }}</p>
                            @else
                                <p class="mb-0 text-muted">No active holiday in this month.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="text-white card-header bg-success"><strong>Salary Summary</strong></div>
                        <div class="card-body">
                            <table class="table mb-0 table-sm">
                                <tr>
                                    <th>Present Days:</th>
                                    <td>{{ $payroll->present_days }} days</td>
                                </tr>
                                <tr>
                                    <th>Off-day Work:</th>
                                    <td>{{ $payroll->off_day_presents }} days</td>
                                </tr>
                                <tr class="table-light">
                                    <th>Base Salary:</th>
                                    <td>৳{{ number_format($payroll->base_salary, 2) }}</td>
                                </tr>
                                <tr class="text-success">
                                    <th>+ Off-day Bonus:</th>
                                    <td>৳{{ number_format($payroll->off_day_bonus, 2) }}</td>
                                </tr>
                                <tr class="text-success">
                                    <th>+ Overtime:</th>
                                    <td>৳{{ number_format($payroll->overtime_amount, 2) }}</td>
                                </tr>
                                <tr class="text-success">
                                    <th>+ Hazira Bonus:</th>
                                    <td>৳{{ number_format($payroll->hazira_bonus_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr class="text-success">
                                    <th>+ Special Bonus:</th>
                                    <td>৳{{ number_format($payroll->occasional_bonus_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr class="text-success">
                                    <th>+ xSell Bonus:</th>
                                    <td>৳{{ number_format($payroll->xsell_bonus_amount ?? 0, 2) }}</td>
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

            <h5 class="mb-3">Bonus Edit Audit</h5>
            <div class="mb-4 table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Info</th>
                            <th>
                                <div>Base</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>Off-day</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>Overtime</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>Late Fee</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>Penalty</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>Advance</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>Hazira</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>Special</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>xSell</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                            <th>
                                <div>Net</div><small class="text-muted">Old-&gt;New</small>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonusAudits as $audit)
                            <tr>
                                <td>
                                    <div><strong>{{ $audit->created_at?->format('d M Y') }}</strong></div>
                                    <div><strong>{{ $audit->created_at?->format('h:i A') }}</strong></div>
                                    <div>{{ $audit->editor->name ?? 'System' }}</div>
                                    <div>
                                        @if (($audit->event_type ?? 'manual_edit') === 'regenerated')
                                            <span class="badge badge-info">Regenerated</span>
                                        @else
                                            <span class="badge badge-primary">Manual Edit</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['base_salary'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['base_salary'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['off_day_bonus'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['off_day_bonus'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['overtime_amount'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['overtime_amount'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['late_deduction'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['late_deduction'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['penalty_amount'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['penalty_amount'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['advance_deduction'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['advance_deduction'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['hazira_bonus_amount'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['hazira_bonus_amount'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['occasional_bonus_amount'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['occasional_bonus_amount'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['xsell_bonus_amount'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['xsell_bonus_amount'] ?? 0, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{ number_format($audit->old_values['net_salary'] ?? 0, 2) }}</div>
                                    <div class="text-muted">-></div>
                                    <div>{{ number_format($audit->new_values['net_salary'] ?? 0, 2) }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">No bonus audit history for this payroll.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                                // Cap: default = daily salary
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
                            <td colspan="5" class="text-right">Total:</td>
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
                                <th>Approved By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($advances as $adv)
                                <tr>
                                    <td>{{ $adv->date->format('d M Y') }}</td>
                                    <td>৳{{ number_format($adv->amount, 2) }}</td>
                                    <td>{{ $adv->note ?? '-' }}</td>
                                    <td>{{ $adv->approver->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

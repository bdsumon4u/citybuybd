@extends('manager.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">
                <i class="fas fa-money-bill-wave"></i> My Payroll
            </h6>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Month</th>
                            <th>Monthly Salary</th>
                            <th>Present</th>
                            <th>Base Salary</th>
                            <th>Off-Day Bonus</th>
                            <th>OVER</th>
                            <th>Hazira Bonus</th>
                            <th>Special Bonus</th>
                            <th>xSell Bonus</th>
                            <th>Late Fee</th>
                            <th>Penalty</th>
                            <th>Advances</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $key => $payroll)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $payroll->month_name }} {{ $payroll->year }}</td>
                                <td>৳{{ number_format($payroll->user->monthly_salary ?? 0, 2) }}</td>
                                <td>{{ $payroll->present_days }}{{ $payroll->off_day_presents > 0 ? ' (OFF: ' . $payroll->off_day_presents . ')' : '' }}
                                </td>
                                <td>৳{{ number_format($payroll->base_salary, 2) }}</td>
                                <td>৳{{ number_format($payroll->off_day_bonus, 2) }}</td>
                                <td>৳{{ number_format($payroll->overtime_amount, 2) }}</td>
                                <td>৳{{ number_format($payroll->hazira_bonus_amount ?? 0, 2) }}
                                </td>
                                <td>৳{{ number_format($payroll->occasional_bonus_amount ?? 0, 2) }}</td>
                                <td>৳{{ number_format($payroll->xsell_bonus_amount ?? 0, 2) }}</td>
                                <td class="text-danger">৳{{ number_format($payroll->late_deduction, 2) }}</td>
                                <td>৳{{ number_format($payroll->penalty_amount, 2) }}</td>
                                <td>৳{{ number_format($payroll->advance_deduction, 2) }}</td>
                                <td><strong>৳{{ number_format($payroll->net_salary, 2) }}</strong></td>
                                <td>
                                    @if ($payroll->status == 'paid')
                                        <span class="badge badge-success">Paid</span>
                                    @elseif($payroll->status == 'approved')
                                        <span class="badge badge-primary">Approved</span>
                                    @else
                                        <span class="badge badge-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('manager.payroll.show', $payroll->id) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center">No payroll records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

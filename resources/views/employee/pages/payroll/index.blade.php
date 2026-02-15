@extends('employee.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>My Payroll</h4>
            <p class="mg-b-0">View your monthly salary details</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Month</th>
                            <th>Daily Salary</th>
                            <th>Present Days</th>
                            <th>Base Salary</th>
                            <th>Off-day Bonus</th>
                            <th>OVER</th>
                            <th>Late Fee</th>
                            <th>Penalty</th>
                            <th>Advance</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $i => $payroll)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $payroll->month_name }} {{ $payroll->year }}</td>
                                <td>৳{{ number_format($payroll->daily_salary, 2) }}</td>
                                <td>{{ $payroll->present_days }} / {{ $payroll->working_days }}</td>
                                <td>৳{{ number_format($payroll->base_salary, 2) }}</td>
                                <td class="text-success">+৳{{ number_format($payroll->off_day_bonus, 2) }}</td>
                                <td class="text-success">+৳{{ number_format($payroll->overtime_amount, 2) }}</td>
                                <td class="text-danger">-৳{{ number_format($payroll->late_deduction, 2) }}</td>
                                <td class="text-danger">-৳{{ number_format($payroll->penalty_amount, 2) }}</td>
                                <td class="text-danger">-৳{{ number_format($payroll->advance_deduction, 2) }}</td>
                                <td class="font-weight-bold">৳{{ number_format($payroll->net_salary, 2) }}</td>
                                <td>
                                    @if ($payroll->status == 'draft')
                                        <span class="badge badge-secondary">Draft</span>
                                    @elseif($payroll->status == 'approved')
                                        <span class="badge badge-primary">Approved</span>
                                    @elseif($payroll->status == 'paid')
                                        <span class="badge badge-success">Paid</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('employee.payroll.show', $payroll->id) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">No payroll records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('backend.layout.template')
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
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Month</th>
                            <th>Working Days</th>
                            <th>Present</th>
                            <th>Base Salary</th>
                            <th>Off-Day Bonus</th>
                            <th>Overtime</th>
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
                                <td>{{ $payroll->working_days }}</td>
                                <td>{{ $payroll->present_days }}</td>
                                <td>৳{{ number_format($payroll->base_salary, 2) }}</td>
                                <td>৳{{ number_format($payroll->off_day_bonus, 2) }}</td>
                                <td>৳{{ number_format($payroll->overtime_amount, 2) }}</td>
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
                                    <a href="{{ route('admin.payroll.myShow', $payroll->id) }}"
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

@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Monthly Payroll</h4>
            <p class="mg-b-0">Generate and manage monthly salary calculations</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible">{{ session('message') }}</div>
            @endif

            <div class="mb-3 row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('admin.payroll.monthly') }}" class="form-inline">
                        <select name="month" class="mr-2 form-control">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <input type="number" name="year" class="mr-2 form-control" value="{{ $year }}"
                            min="2020" max="2099" style="width:100px">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-sync-alt"></i> View</button>
                    </form>
                </div>
                <div class="text-right col-md-4 d-flex align-items-center justify-content-end">
                    @if ($isCurrentMonth)
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Current month payroll is auto-generated
                            on page load.</small>
                    @else
                        <form method="POST" action="{{ route('admin.payroll.generate') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-cogs"></i> Generate Payroll for {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                {{ $year }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Daily Rate</th>
                            <th>Working Days</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Off-day Work</th>
                            <th>Base Salary</th>
                            <th>Off-day Bonus</th>
                            <th>Overtime</th>
                            <th>Special Bonus</th>
                            <th>Late Fee</th>
                            <th>Penalty</th>
                            <th>Advance</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @forelse($payrolls as $i => $payroll)
                            @php $grandTotal += $payroll->net_salary; @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $payroll->user->name ?? 'N/A' }}</td>
                                <td>৳{{ number_format($payroll->daily_salary, 2) }}</td>
                                <td>{{ $payroll->working_days }}</td>
                                <td>{{ $payroll->present_days }}</td>
                                <td>{{ $payroll->absent_days }}</td>
                                <td>{{ $payroll->off_day_presents }}</td>
                                <td>৳{{ number_format($payroll->base_salary, 2) }}</td>
                                <td class="text-success">+৳{{ number_format($payroll->off_day_bonus, 2) }}</td>
                                <td class="text-success">+৳{{ number_format($payroll->overtime_amount, 2) }}</td>
                                <td class="text-success">
                                    +৳{{ number_format(($payroll->hazira_bonus_amount ?? 0) + ($payroll->occasional_bonus_amount ?? 0) + ($payroll->xsell_bonus_amount ?? 0), 2) }}
                                </td>
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
                                    <a href="{{ route('admin.payroll.show', $payroll->id) }}" class="btn btn-sm btn-info"
                                        title="Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            data-toggle="dropdown">Status</button>
                                        <div class="dropdown-menu">
                                            <form method="POST"
                                                action="{{ route('admin.payroll.updateStatus', $payroll->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="draft">
                                                <button class="dropdown-item">Draft</button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.payroll.updateStatus', $payroll->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button class="dropdown-item">Approve</button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.payroll.updateStatus', $payroll->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="paid">
                                                <button class="dropdown-item">Mark Paid</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="text-center">No payroll records found. No active employees or no
                                    attendance data for this month.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($payrolls->count() > 0)
                        <tfoot>
                            <tr class="table-info font-weight-bold">
                                <td colspan="14" class="text-right">Grand Total:</td>
                                <td>৳{{ number_format($grandTotal, 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

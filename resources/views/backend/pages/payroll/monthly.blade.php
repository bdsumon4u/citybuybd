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

            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.payroll.monthly') }}" class="form-inline">
                        <select name="month" class="form-control mr-2">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <input type="number" name="year" class="form-control mr-2" value="{{ $year }}"
                            min="2020" max="2099" style="width:100px">
                        <button class="btn btn-primary" type="submit">View</button>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <form method="POST" action="{{ route('admin.payroll.generate') }}" class="d-inline"
                        onsubmit="return confirm('Generate/re-generate payroll for all employees for this month?')">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <button class="btn btn-success">
                            <i class="fas fa-calculator"></i> Generate Payroll for
                            {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Daily Salary</th>
                            <th>Working Days</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Off-day Work</th>
                            <th>Base Salary</th>
                            <th>Off-day Bonus</th>
                            <th>Overtime</th>
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
                                <td colspan="16" class="text-center">No payroll records found. Click "Generate Payroll" to
                                    create.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($payrolls->count() > 0)
                        <tfoot>
                            <tr class="table-info font-weight-bold">
                                <td colspan="13" class="text-right">Grand Total:</td>
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

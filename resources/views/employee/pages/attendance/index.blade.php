@extends('employee.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>My Attendance</h4>
            <p class="mg-b-0">View your attendance records</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('employee.attendance.index') }}" class="form-inline">
                        <select name="month" class="form-control mr-2">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <input type="number" name="year" class="form-control mr-2" value="{{ $year }}"
                            min="2020" max="2099" style="width:100px">
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </form>
                </div>
            </div>

            @php
                $presentCount = $attendances->where('status', 'present')->count();
                $absentCount = $attendances->where('status', 'absent')->count();
                $offDayWork = $attendances->where('is_off_day', true)->where('status', 'present')->count();
                $totalOvertime = $attendances->sum('overtime_minutes');
                $totalLate = $attendances->sum('late_minutes');
                $totalPenalty = $attendances->sum('penalty_amount');
            @endphp

            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card bg-success text-white text-center p-3">
                        <h5>{{ $presentCount }}</h5>
                        <small>Present</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-danger text-white text-center p-3">
                        <h5>{{ $absentCount }}</h5>
                        <small>Absent</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning text-dark text-center p-3">
                        <h5>{{ $offDayWork }}</h5>
                        <small>Off-day Work</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white text-center p-3">
                        <h5>{{ $totalOvertime }} min</h5>
                        <small>Total Overtime</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-secondary text-white text-center p-3">
                        <h5>{{ $totalLate }} min</h5>
                        <small>Total Late</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-dark text-white text-center p-3">
                        <h5>৳{{ number_format($totalPenalty, 2) }}</h5>
                        <small>Total Penalty</small>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Overtime (min)</th>
                            <th>Late (min)</th>
                            <th>Penalty (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $i => $attendance)
                            <tr class="{{ $attendance->is_off_day ? 'table-warning' : '' }}">
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $attendance->date->format('d M Y') }}</td>
                                <td>{{ $attendance->date->format('l') }}</td>
                                <td>
                                    @if ($attendance->status == 'present')
                                        <span class="badge badge-success">Present</span>
                                    @else
                                        <span class="badge badge-danger">Absent</span>
                                    @endif
                                    @if ($attendance->is_off_day)
                                        <span class="badge badge-warning">Off-day</span>
                                    @endif
                                    @if ($attendance->auto_checkout)
                                        <span class="badge badge-danger">Auto-checkout</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->check_in?->format('h:i A') ?? '-' }}</td>
                                <td>{{ $attendance->check_out?->format('h:i A') ?? '-' }}</td>
                                <td>{{ $attendance->overtime_minutes }}</td>
                                <td>{{ $attendance->late_minutes }}</td>
                                <td>{{ number_format($attendance->penalty_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

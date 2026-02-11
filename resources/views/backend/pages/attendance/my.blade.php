@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>My Attendance</h4>
            <p class="mg-b-0">View your own attendance records</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.attendance.my') }}" class="form-inline">
                        <select name="month" class="form-control mr-2">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <select name="year" class="form-control mr-2">
                            @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                                </option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </form>
                </div>
            </div>

            @php
                $totalPresent = $attendances->where('status', 'present')->count();
                $totalAbsent = $attendances->where('status', 'absent')->count();
                $totalOffDay = $attendances->where('is_off_day', true)->where('status', 'present')->count();
                $totalOvertime = $attendances->sum('overtime_minutes');
                $totalLate = $attendances->sum('late_minutes');
                $totalPenalty = $attendances->sum('penalty_amount');
            @endphp

            <div class="row mb-3">
                <div class="col-md-2">
                    <div class="card bg-success text-white text-center p-2">
                        <h5 class="mb-0">{{ $totalPresent }}</h5>
                        <small>Present</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-danger text-white text-center p-2">
                        <h5 class="mb-0">{{ $totalAbsent }}</h5>
                        <small>Absent</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white text-center p-2">
                        <h5 class="mb-0">{{ $totalOffDay }}</h5>
                        <small>Off-Day Work</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-primary text-white text-center p-2">
                        <h5 class="mb-0">{{ $totalOvertime }} min</h5>
                        <small>Overtime</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-secondary text-white text-center p-2">
                        <h5 class="mb-0">{{ $totalLate }} min</h5>
                        <small>Late</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning text-white text-center p-2">
                        <h5 class="mb-0">৳{{ number_format($totalPenalty, 2) }}</h5>
                        <small>Penalty</small>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Overtime</th>
                            <th>Late</th>
                            <th>Penalty</th>
                            <th>Off Day</th>
                            <th>Auto Checkout</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $key => $att)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $att->date->format('d M, Y (l)') }}</td>
                                <td>
                                    @if ($att->status == 'present')
                                        <span class="badge badge-success">Present</span>
                                    @else
                                        <span class="badge badge-danger">Absent</span>
                                    @endif
                                </td>
                                <td>{{ $att->check_in ? $att->check_in->format('h:i A') : '-' }}</td>
                                <td>{{ $att->check_out ? $att->check_out->format('h:i A') : '-' }}</td>
                                <td>{{ $att->overtime_minutes > 0 ? $att->overtime_minutes . ' min' : '-' }}</td>
                                <td>{{ $att->late_minutes > 0 ? $att->late_minutes . ' min' : '-' }}</td>
                                <td>{{ $att->penalty_amount > 0 ? '৳' . number_format($att->penalty_amount, 2) : '-' }}
                                </td>
                                <td>{!! $att->is_off_day ? '<span class="badge badge-info">Yes</span>' : '-' !!}</td>
                                <td>{!! $att->auto_checkout ? '<span class="badge badge-warning">Yes</span>' : '-' !!}</td>
                                <td>{{ $att->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

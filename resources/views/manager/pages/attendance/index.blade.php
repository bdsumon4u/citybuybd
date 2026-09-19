@extends('manager.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="pd-20 br-section-wrapper">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">
                <i class="fas fa-calendar-check"></i> My Attendance & Overtime
            </h6>

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="mb-3 row align-items-center">
                <div class="col-md-7">
                    <form method="GET" action="{{ route('manager.attendance.index') }}" class="form-inline">
                        <select name="month" class="mr-2 form-control">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <select name="year" class="mr-2 form-control">
                            @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                                </option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    </form>
                </div>
                <div class="text-right col-md-5">
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#requestOvertimeModal">
                        <i class="fas fa-plus-circle"></i> Request Overtime
                    </button>
                </div>
            </div>

            @php
                $totalPresent = $attendances->where('status', 'present')->count();
                $totalAbsent = $attendances->where('status', 'absent')->count();
                $totalOffDay = $attendances->where('is_off_day', true)->where('status', 'present')->count();
                $totalOvertime = $attendances->sum('overtime_minutes') + $attendances->sum('extra_overtime_minutes');
                $totalLate = $attendances->sum('late_minutes');
                $totalPenalty = $attendances->sum('penalty_amount');
            @endphp

            <div class="mb-3 row">
                <div class="col-md-2">
                    <div class="p-2 text-center text-white card bg-success">
                        <h5 class="mb-0">{{ $totalPresent }}</h5>
                        <small>Present</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-2 text-center text-white card bg-danger">
                        <h5 class="mb-0">{{ $totalAbsent }}</h5>
                        <small>Absent</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-2 text-center text-white card bg-info">
                        <h5 class="mb-0">{{ $totalOffDay }}</h5>
                        <small>Off-Day Work</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-2 text-center text-white card bg-primary">
                        <h5 class="mb-0">{{ $totalOvertime }} min</h5>
                        <small>Total OT (inc. Extra)</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-2 text-center text-white card bg-secondary">
                        <h5 class="mb-0">{{ $totalLate }} min</h5>
                        <small>Late</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-2 text-center text-white card bg-warning">
                        <h5 class="mb-0">৳{{ number_format($totalPenalty, 2) }}</h5>
                        <small>Penalty</small>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <h6 class="mt-4 mb-2 tx-gray-800 tx-bold"><i class="fas fa-calendar-alt"></i> Daily Attendance Records</h6>
            <div class="mb-4 table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>System OT</th>
                            <th>Approved Extra OT</th>
                            <th>Late</th>
                            <th>Penalty</th>
                            <th>Off Day</th>
                            <th>Auto Checkout</th>
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
                                <td>{{ $att->overtime_minutes > 0 ? $att->overtime_minutes . ' min' : '0 min' }}</td>
                                <td>
                                    @if(($att->extra_overtime_minutes ?? 0) > 0)
                                        <span class="badge badge-success font-weight-bold">+{{ $att->extra_overtime_minutes }} min</span>
                                    @else
                                        0 min
                                    @endif
                                </td>
                                <td>{{ $att->late_minutes > 0 ? $att->late_minutes . ' min' : '-' }}</td>
                                <td>{{ $att->penalty_amount > 0 ? '৳' . number_format($att->penalty_amount, 2) : '-' }}</td>
                                <td>{!! $att->is_off_day ? '<span class="badge badge-info">Yes</span>' : '-' !!}</td>
                                <td>{!! $att->auto_checkout ? '<span class="badge badge-warning">Yes</span>' : '-' !!}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Overtime Requests History -->
            <h6 class="mt-4 mb-2 tx-gray-800 tx-bold"><i class="fas fa-clock"></i> My Overtime Requests ({{ date('F Y', mktime(0, 0, 0, (int)$month, 1, (int)$year)) }})</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Requested Duration</th>
                            <th>Approved Duration</th>
                            <th>Reason / Note</th>
                            <th>Status</th>
                            <th>Admin Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overtimeRequests ?? [] as $i => $req)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $req->date->format('d M Y') }}</td>
                                <td>{{ $req->date->format('l') }}</td>
                                <td>
                                    <span class="badge badge-info p-1">
                                        {{ $req->formatted_requested_time }}
                                    </span>
                                </td>
                                <td>
                                    @if($req->status === 'approved')
                                        <span class="badge badge-success p-1">
                                            {{ $req->formatted_approved_time }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="max-width: 250px; white-space: normal;">
                                    {{ $req->reason ?: 'N/A' }}
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge badge-warning px-2 py-1">Pending Approval</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge badge-success px-2 py-1">Approved</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="badge badge-danger px-2 py-1">Rejected</span>
                                    @endif
                                </td>
                                <td style="max-width: 200px; white-space: normal;">
                                    <small class="text-muted">{{ $req->admin_note ?: '-' }}</small>
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <form method="POST" action="{{ route('manager.attendance.overtimeRequest.cancel', $req->id) }}"
                                            class="d-inline" onsubmit="return confirm('Cancel this overtime request?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Cancel Request">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted"><i class="fas fa-lock"></i> Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-3 text-center text-muted">
                                    No overtime requests submitted for this month.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Request Overtime Modal -->
    <div class="modal fade" id="requestOvertimeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('manager.attendance.overtimeRequest.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="text-white modal-header bg-primary">
                        <h5 class="modal-title"><i class="fas fa-clock"></i> Submit Daily Overtime Request</h5>
                        <button type="button" class="text-white close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ today()->toDateString() }}" max="{{ today()->toDateString() }}" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Overtime Duration (Minutes) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="minutes" class="form-control" placeholder="e.g. 60 for 1 hour, 90 for 1.5 hours" min="1" max="1440" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">minutes</span>
                                </div>
                            </div>
                            <small class="text-muted">Common examples: 30 min, 60 min (1h), 90 min (1.5h), 120 min (2h), 180 min (3h).</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Reason / Tasks Completed (Optional)</label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Briefly describe what tasks or orders you worked on during overtime..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Today's Attendance</h4>
            <p class="mg-b-0">Manage daily employee attendance</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible">{{ session('message') }}</div>
            @endif

            <div class="mb-3 row">
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.attendance.index') }}">
                        <div class="input-group">
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.attendance.history') }}" class="btn btn-info">
                        <i class="fas fa-history"></i> View History
                    </a>
                    <button class="btn btn-success" data-toggle="modal" data-target="#addAttendanceModal">
                        <i class="fas fa-plus"></i> Add Attendance
                    </button>
                    <a href="{{ route('admin.attendance.printDaily', ['date' => $date]) }}" class="btn btn-outline-dark"
                        target="_blank">
                        <i class="fas fa-print"></i> Print Daily Sheet
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Role</th>
                            <th>Schedule</th>
                            <th>Off Days</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>OVER (min)</th>
                            <th>Late (min)</th>
                            <th>Penalty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($users as $user)
                            @php
                                $attendance = $attendances->get($user->id);
                                $isOffDay = $user->isOffDay($date);
                            @endphp
                            <tr class="{{ $isOffDay ? 'table-warning' : '' }}">
                                <td>{{ $i++ }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if ($user->role == 2)
                                        <span class="badge badge-primary">Manager</span>
                                    @else
                                        <span class="badge badge-dark">Employee</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $user->start_time ?? 'N/A' }} - {{ $user->end_time ?? 'N/A' }}</small>
                                </td>
                                <td><small>{{ $user->off_days ?? 'None' }}</small></td>
                                <td>
                                    @if ($attendance)
                                        @if ($attendance->status == 'present')
                                            <span class="badge badge-success">Present</span>
                                            @if ($attendance->is_off_day)
                                                <span class="badge badge-warning">Off-day</span>
                                            @endif
                                            @if ($attendance->auto_checkout)
                                                <span class="badge badge-danger">Auto-checkout</span>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">Absent</span>
                                        @endif
                                    @else
                                        @if ($isOffDay)
                                            <span class="badge badge-secondary">Off Day</span>
                                        @else
                                            <span class="badge badge-light">No Record</span>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $attendance?->check_in?->format('h:i A') ?? '-' }}</td>
                                <td>{{ $attendance?->check_out?->format('h:i A') ?? '-' }}</td>
                                <td>{{ $attendance?->overtime_minutes ?? 0 }}</td>
                                <td>{{ $attendance?->late_minutes ?? 0 }}</td>
                                <td>{{ $attendance?->penalty_amount ?? 0 }}</td>
                                <td>
                                    @if (!$attendance)
                                        <form method="POST" action="{{ route('admin.attendance.checkIn') }}"
                                            class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" name="date" value="{{ $date }}">
                                            <button class="btn btn-sm btn-success" title="Mark Present">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.attendance.markAbsent') }}"
                                            class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" name="date" value="{{ $date }}">
                                            <button class="btn btn-sm btn-danger" title="Mark Absent">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @elseif($attendance->check_in && !$attendance->check_out)
                                        <form method="POST" action="{{ route('admin.attendance.checkOut') }}"
                                            class="d-inline">
                                            @csrf
                                            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                                            <button class="btn btn-sm btn-warning" title="Check Out">
                                                <i class="fas fa-sign-out-alt"></i> Out
                                            </button>
                                        </form>
                                    @endif
                                    @if ($attendance)
                                        <button class="btn btn-sm btn-info" title="Edit"
                                            onclick="openEditModal({{ json_encode([
                                                'id' => $attendance->id,
                                                'check_in' => $attendance->check_in?->format('H:i'),
                                                'check_out' => $attendance->check_out?->format('H:i'),
                                                'penalty_amount' => $attendance->penalty_amount,
                                                'auto_checkout' => $attendance->auto_checkout,
                                                'note' => $attendance->note,
                                            ]) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST"
                                            action="{{ route('admin.attendance.destroy', $attendance->id) }}"
                                            class="d-inline" onsubmit="return confirm('Delete this record?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Attendance Modal -->
    <div class="modal fade" id="addAttendanceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('admin.attendance.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add Attendance Record</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Employee <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control" required>
                                <option value="">-- Select Employee --</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}
                                        ({{ $u->role == 1 ? 'Admin' : ($u->role == 2 ? 'Manager' : 'Employee') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ $date }}"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Check In Time <span
                                            class="text-danger">*</span></label>
                                    <input type="time" name="check_in" class="form-control" step="60" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Check Out Time</label>
                                    <input type="time" name="check_out" class="form-control" step="60">
                                    <small class="text-muted">Leave empty if still working</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Note</label>
                            <input type="text" name="note" class="form-control"
                                placeholder="Optional note (e.g. reason for manual entry)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save
                            Attendance</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div class="modal fade" id="editAttendanceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('admin.attendance.update') }}">
                @csrf
                <input type="hidden" name="attendance_id" id="editAttendanceId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Attendance Record</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Check In Time</label>
                                    <input type="time" name="check_in" id="editCheckIn" class="form-control"
                                        step="60">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Check Out Time</label>
                                    <input type="time" name="check_out" id="editCheckOut" class="form-control"
                                        step="60">
                                    <small class="text-muted">Leave empty to clear check-out (re-open attendance)</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Penalty Amount (৳)</label>
                                    <input type="text" name="penalty_amount" id="editPenalty" class="form-control"
                                        min="0" value="0">
                                    <small class="text-muted">Set to <strong>0</strong> to remove penalty</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="autoCheckoutGroup" style="display:none">
                                    <label class="font-weight-bold text-danger">Auto-Checkout</label>
                                    <div class="mt-2 custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="undoAutoCheckout"
                                            name="undo_auto_checkout" value="1">
                                        <label class="custom-control-label" for="undoAutoCheckout">
                                            <strong>Undo auto-checkout</strong> — remove the auto-checkout flag
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Note</label>
                            <input type="text" name="note" id="editNote" class="form-control"
                                placeholder="Optional note (e.g. reason for edit)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save &
                            Recalculate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(data) {
            document.getElementById('editAttendanceId').value = data.id;
            document.getElementById('editCheckIn').value = data.check_in || '';
            document.getElementById('editCheckOut').value = data.check_out || '';
            document.getElementById('editPenalty').value = data.penalty_amount || 0;
            document.getElementById('editNote').value = data.note || '';
            document.getElementById('undoAutoCheckout').checked = false;

            if (data.auto_checkout) {
                document.getElementById('autoCheckoutGroup').style.display = 'block';
            } else {
                document.getElementById('autoCheckoutGroup').style.display = 'none';
            }

            $('#editAttendanceModal').modal('show');
        }
    </script>
@endsection

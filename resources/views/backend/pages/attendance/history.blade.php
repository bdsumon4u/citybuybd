@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Attendance History</h4>
            <p class="mg-b-0">View attendance history by month</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible">{{ session('message') }}</div>
            @endif

            <div class="mb-3 row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('admin.attendance.history') }}" class="form-inline">
                        <select name="user_id" class="mr-2 form-control">
                            <option value="">All Employees</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                        <select name="month" class="mr-2 form-control">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <input type="number" name="year" class="mr-2 form-control" value="{{ $year }}"
                            min="2020" max="2099" style="width:100px">
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </form>
                </div>
                <div class="text-right col-md-4">
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-info">
                        <i class="fas fa-calendar-day"></i> Today's Attendance
                    </a>
                    @if ($userId)
                        <a href="{{ route('admin.attendance.printMonthly', ['user_id' => $userId, 'month' => $month, 'year' => $year]) }}"
                            class="btn btn-outline-dark" target="_blank">
                            <i class="fas fa-print"></i> Print Monthly
                        </a>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>OVER (min)</th>
                            <th>Late (min)</th>
                            <th>Penalty (৳)</th>
                            <th>Notes</th>
                            <th>Action</th>
                    <tbody>
                        @forelse($attendances as $i => $attendance)
                            <tr
                                class="{{ $attendance->is_off_day ? 'table-warning' : '' }} {{ $attendance->auto_checkout ? 'table-danger' : '' }}">
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $attendance->user->name ?? 'N/A' }}</td>
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
                                </td>
                                <td>{{ $attendance->check_in?->format('h:i A') ?? '-' }}</td>
                                <td>
                                    {{ $attendance->check_out?->format('h:i A') ?? '-' }}
                                    @if ($attendance->auto_checkout)
                                        <span class="badge badge-danger">Auto</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->overtime_minutes }}</td>
                                <td>{{ $attendance->late_minutes }}</td>
                                <td>{{ number_format($attendance->penalty_amount, 2) }}</td>
                                <td>{{ $attendance->note }}</td>
                                <td>
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
                                    <form method="POST" action="{{ route('admin.attendance.destroy', $attendance->id) }}"
                                        class="d-inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

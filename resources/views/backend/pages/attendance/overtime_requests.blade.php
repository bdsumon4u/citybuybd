@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Overtime Requests & Approvals</h4>
            <p class="mg-b-0">Review, approve, and manage daily staff overtime requests</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="pd-20 br-section-wrapper">
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

            <!-- Summary KPI Widgets -->
            <div class="mb-4 row">
                <div class="col-md-4">
                    <div class="p-3 text-white card bg-warning text-dark text-center">
                        <h4 class="mb-1 font-weight-bold">{{ $pendingCount }}</h4>
                        <small class="font-weight-bold text-uppercase">Pending Approval</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 text-white card bg-success text-center">
                        <h4 class="mb-1 font-weight-bold">
                            {{ \App\Models\OvertimeRequest::formatMinutesToDuration($monthlyApprovedMinutes) }}
                        </h4>
                        <small class="text-uppercase">Approved OT ({{ date('F Y', mktime(0, 0, 0, (int)$month, 1, (int)$year)) }})</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 text-white card bg-danger text-center">
                        <h4 class="mb-1 font-weight-bold">{{ $monthlyRejectedCount }}</h4>
                        <small class="text-uppercase">Rejected ({{ date('F Y', mktime(0, 0, 0, (int)$month, 1, (int)$year)) }})</small>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-3 row align-items-center">
                <div class="col-lg-12">
                    <form method="GET" action="{{ route('admin.attendance.overtimeRequests') }}" class="form-inline flex-wrap">
                        <select name="status" class="mr-2 mb-2 form-control">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>

                        <select name="user_id" class="mr-2 mb-2 form-control">
                            <option value="">All Employees</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="month" class="mr-2 mb-2 form-control">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>

                        <input type="number" name="year" class="mr-2 mb-2 form-control" value="{{ $year }}"
                            min="2020" max="2099" style="width:100px">

                        <button class="btn btn-primary mr-2 mb-2" type="submit">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.attendance.overtimeRequests') }}" class="btn btn-secondary mr-2 mb-2">
                            Reset
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-info mb-2">
                            <i class="fas fa-calendar-check"></i> Daily Attendance
                        </a>
                    </form>
                </div>
            </div>

            <!-- Requests Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Requested</th>
                            <th>Approved</th>
                            <th>Reason / Work Done</th>
                            <th>Status</th>
                            <th>Admin Remarks</th>
                            <th>Handled By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $i => $req)
                            <tr>
                                <td>{{ $requests->firstItem() + $i }}</td>
                                <td>
                                    <strong>{{ $req->user->name ?? 'Unknown' }}</strong>
                                    @if(optional($req->user)->role == 2)
                                        <span class="badge badge-primary ml-1">Manager</span>
                                    @elseif(optional($req->user)->role == 3)
                                        <span class="badge badge-dark ml-1">Employee</span>
                                    @endif
                                </td>
                                <td>{{ $req->date->format('d M Y') }}</td>
                                <td>{{ $req->date->format('l') }}</td>
                                <td>
                                    <span class="badge badge-info p-1" style="font-size: 12px;">
                                        {{ $req->formatted_requested_time }}
                                    </span>
                                </td>
                                <td>
                                    @if($req->status === 'approved')
                                        <span class="badge badge-success p-1" style="font-size: 12px;">
                                            {{ $req->formatted_approved_time }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="max-width: 200px; white-space: normal;">
                                    {{ $req->reason ?: 'N/A' }}
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge badge-warning px-2 py-1">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge badge-success px-2 py-1">Approved</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="badge badge-danger px-2 py-1">Rejected</span>
                                    @endif
                                </td>
                                <td style="max-width: 180px; white-space: normal;">
                                    <small class="text-muted">{{ $req->admin_note ?: '-' }}</small>
                                </td>
                                <td>
                                    @if($req->approver)
                                        <small>
                                            <strong>{{ $req->approver->name }}</strong><br>
                                            {{ optional($req->approved_at)->format('d M, h:i A') }}
                                        </small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="white-space: nowrap;">
                                    @if($req->status === 'pending')
                                        <button class="btn btn-sm btn-success" title="Approve & Set Time"
                                            onclick="openApproveModal({{ json_encode([
                                                'id' => $req->id,
                                                'user_name' => $req->user->name ?? '',
                                                'date' => $req->date->format('d M Y'),
                                                'requested_minutes' => $req->requested_minutes,
                                                'reason' => $req->reason,
                                            ]) }})">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Reject"
                                            onclick="openRejectModal({{ json_encode([
                                                'id' => $req->id,
                                                'user_name' => $req->user->name ?? '',
                                                'date' => $req->date->format('d M Y'),
                                                'requested_minutes' => $req->requested_minutes,
                                            ]) }})">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-info" title="Edit Overtime"
                                            onclick="openEditModal({{ json_encode([
                                                'id' => $req->id,
                                                'user_name' => $req->user->name ?? '',
                                                'date' => $req->date->format('d M Y'),
                                                'requested_minutes' => $req->requested_minutes,
                                                'approved_minutes' => $req->approved_minutes ?? $req->requested_minutes,
                                                'admin_note' => $req->admin_note ?? '',
                                                'status' => $req->status,
                                            ]) }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    @endif

                                    <form method="POST" action="{{ route('admin.attendance.overtimeRequests.destroy', $req->id) }}"
                                        class="d-inline" onsubmit="return confirm('Delete this overtime request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    No overtime requests found for the selected criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($requests->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" id="approveForm" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-check-circle"></i> Approve Overtime Request</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border mb-3">
                            <p class="mb-1"><strong>Employee:</strong> <span id="approveUserName"></span></p>
                            <p class="mb-1"><strong>Date:</strong> <span id="approveDate"></span></p>
                            <p class="mb-1"><strong>Requested Duration:</strong> <span id="approveRequestedTime" class="badge badge-info"></span></p>
                            <p class="mb-0"><strong>Reason:</strong> <span id="approveReason" class="text-muted"></span></p>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Approved Overtime Minutes <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="approved_minutes" id="approveMinutes" class="form-control" min="0" max="1440" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">minutes</span>
                                </div>
                            </div>
                            <small class="text-muted">You can modify/override the duration before approving. E.g., 60 = 1 hour, 90 = 1h 30m, 120 = 2 hours.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Admin Remarks / Note (Optional)</label>
                            <textarea name="admin_note" class="form-control" rows="2" placeholder="Optional approval remarks..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Approve & Sync Attendance</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" id="rejectForm" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-times-circle"></i> Reject Overtime Request</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border mb-3">
                            <p class="mb-1"><strong>Employee:</strong> <span id="rejectUserName"></span></p>
                            <p class="mb-1"><strong>Date:</strong> <span id="rejectDate"></span></p>
                            <p class="mb-0"><strong>Requested Duration:</strong> <span id="rejectRequestedTime" class="badge badge-info"></span></p>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Rejection Reason / Remarks (Optional)</label>
                            <textarea name="admin_note" class="form-control" rows="3" placeholder="Reason for rejecting this request..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-ban"></i> Reject Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" id="editForm" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Overtime Record</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border mb-3">
                            <p class="mb-1"><strong>Employee:</strong> <span id="editUserName"></span></p>
                            <p class="mb-1"><strong>Date:</strong> <span id="editDate"></span></p>
                            <p class="mb-0"><strong>Original Requested:</strong> <span id="editRequestedTime" class="badge badge-info"></span></p>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Approved Minutes <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="approved_minutes" id="editMinutes" class="form-control" min="0" max="1440" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">minutes</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Admin Remarks / Note</label>
                            <textarea name="admin_note" id="editAdminNote" class="form-control" rows="2" placeholder="Optional remarks..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(data) {
            document.getElementById('approveForm').action = "{{ url('admin/attendance/overtime-requests') }}/" + data.id + "/approve";
            document.getElementById('approveUserName').innerText = data.user_name;
            document.getElementById('approveDate').innerText = data.date;
            document.getElementById('approveRequestedTime').innerText = data.requested_minutes + " min";
            document.getElementById('approveReason').innerText = data.reason || 'N/A';
            document.getElementById('approveMinutes').value = data.requested_minutes;

            $('#approveModal').modal('show');
        }

        function openRejectModal(data) {
            document.getElementById('rejectForm').action = "{{ url('admin/attendance/overtime-requests') }}/" + data.id + "/reject";
            document.getElementById('rejectUserName').innerText = data.user_name;
            document.getElementById('rejectDate').innerText = data.date;
            document.getElementById('rejectRequestedTime').innerText = data.requested_minutes + " min";

            $('#rejectModal').modal('show');
        }

        function openEditModal(data) {
            document.getElementById('editForm').action = "{{ url('admin/attendance/overtime-requests') }}/" + data.id + "/update";
            document.getElementById('editUserName').innerText = data.user_name;
            document.getElementById('editDate').innerText = data.date;
            document.getElementById('editRequestedTime').innerText = data.requested_minutes + " min";
            document.getElementById('editMinutes').value = data.approved_minutes;
            document.getElementById('editAdminNote').value = data.admin_note || '';

            $('#editModal').modal('show');
        }
    </script>
@endsection

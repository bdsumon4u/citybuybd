@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Salary Advances</h4>
            <p class="mg-b-0">Manage employee salary advances</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible">{{ session('message') }}</div>
            @endif

            <div class="mb-3 row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('admin.salary-advance.index') }}" class="form-inline">
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
                        <button class="mr-2 btn btn-primary" type="submit">Filter</button>
                    </form>
                </div>
                <div class="text-right col-md-4">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addAdvance">
                        <i class="fas fa-plus"></i> Add Advance
                    </button>
                </div>
            </div>

            <!-- Add Advance Modal -->
            <div class="modal fade" id="addAdvance" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Salary Advance</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <form method="POST" action="{{ route('admin.salary-advance.store') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Employee</label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="">Select Employee</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->role == 2 ? 'Manager' : 'Employee' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Amount (৳)</label>
                                    <input type="text" name="amount" class="form-control" required
                                        min="0.01">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Date</label>
                                    <input type="date" name="date" class="form-control"
                                        value="{{ today()->toDateString() }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Note</label>
                                    <textarea name="note" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Amount (৳)</th>
                            <th>Date</th>
                            <th>Note</th>
                            <th>Approved By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalAdvance = 0; @endphp
                        @forelse($advances as $i => $advance)
                            @php $totalAdvance += $advance->amount; @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $advance->user->name ?? 'N/A' }}</td>
                                <td>{{ number_format($advance->amount, 2) }}</td>
                                <td>{{ $advance->date->format('d M Y') }}</td>
                                <td>{{ $advance->note ?? '-' }}</td>
                                <td>{{ $advance->approver->name ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#editAdvance{{ $advance->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.salary-advance.destroy', $advance->id) }}"
                                        class="d-inline" onsubmit="return confirm('Delete this advance?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editAdvance{{ $advance->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Advance</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('admin.salary-advance.update', $advance->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Amount (৳)</label>
                                                            <input type="text" name="amount"
                                                                class="form-control" value="{{ $advance->amount }}"
                                                                required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Date</label>
                                                            <input type="date" name="date" class="form-control"
                                                                value="{{ $advance->date->toDateString() }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Note</label>
                                                            <textarea name="note" class="form-control" rows="2">{{ $advance->note }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No salary advances found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($advances->count() > 0)
                        <tfoot>
                            <tr class="table-info font-weight-bold">
                                <td colspan="2" class="text-right">Total:</td>
                                <td>{{ number_format($totalAdvance, 2) }}</td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

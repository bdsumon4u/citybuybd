@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Holidays</h4>
            <p class="mg-b-0">Manage holiday date ranges (e.g. Eid Vacation)</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible">{{ session('message') }}</div>
            @endif

            <div class="mb-3 row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('admin.payroll.holidays.index') }}" class="form-inline">
                        <select name="month" class="mr-2 form-control">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                        <input type="number" name="year" class="mr-2 form-control" value="{{ $year }}"
                            min="2020" max="2099" style="width:100px">
                        <button class="mr-2 btn btn-primary" type="submit">Filter</button>
                    </form>
                </div>
                <div class="text-right col-md-4">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addHoliday">
                        <i class="fas fa-plus"></i> Add Holiday
                    </button>
                </div>
            </div>

            <div class="modal fade" id="addHoliday" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Holiday</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <form method="POST" action="{{ route('admin.payroll.holidays.store') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Holiday Name</label>
                                    <input type="text" name="name" class="form-control" required
                                        placeholder="Eid Vacation">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">From Date</label>
                                    <input type="date" name="from_date" class="form-control"
                                        value="{{ today()->toDateString() }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">To Date</label>
                                    <input type="date" name="to_date" class="form-control"
                                        value="{{ today()->toDateString() }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Note</label>
                                    <textarea name="note" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold d-block">Status</label>
                                    <input type="hidden" name="status" value="0">
                                    <label class="mb-0">
                                        <input type="checkbox" name="status" value="1" checked>
                                        Active
                                    </label>
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
                            <th>Name</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Total Days</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($holidays as $i => $holiday)
                            @php
                                $days = $holiday->from_date->diffInDays($holiday->to_date) + 1;
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $holiday->name }}</td>
                                <td>{{ $holiday->from_date->format('d M Y') }}</td>
                                <td>{{ $holiday->to_date->format('d M Y') }}</td>
                                <td>{{ $days }}</td>
                                <td>
                                    @if ($holiday->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $holiday->note ?: '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#editHoliday{{ $holiday->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST"
                                        action="{{ route('admin.payroll.holidays.destroy', $holiday->id) }}"
                                        class="d-inline" onsubmit="return confirm('Delete this holiday?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>

                                    <div class="modal fade" id="editHoliday{{ $holiday->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Holiday</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('admin.payroll.holidays.update', $holiday->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Holiday Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ $holiday->name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">From Date</label>
                                                            <input type="date" name="from_date" class="form-control"
                                                                value="{{ $holiday->from_date->toDateString() }}"
                                                                required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">To Date</label>
                                                            <input type="date" name="to_date" class="form-control"
                                                                value="{{ $holiday->to_date->toDateString() }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Note</label>
                                                            <textarea name="note" class="form-control" rows="2">{{ $holiday->note }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold d-block">Status</label>
                                                            <input type="hidden" name="status" value="0">
                                                            <label class="mb-0">
                                                                <input type="checkbox" name="status" value="1"
                                                                    {{ $holiday->status ? 'checked' : '' }}>
                                                                Active
                                                            </label>
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
                                <td colspan="8" class="text-center">No holidays found in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

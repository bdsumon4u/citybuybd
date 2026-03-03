@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Employee Bonuses</h4>
            <p class="mg-b-0">Manage individual bonuses for employees (similar to salary advances)</p>
        </div>
        <div class="br-pagetitle-right">
            <a href="{{ route('admin.payroll.user-bonus.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Bonus
            </a>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible">{{ session('message') }}</div>
            @endif

            <div class="mb-3 row">
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.payroll.user-bonus.index') }}" class="form-inline">
                        <select name="user_id" class="form-control mr-2" onchange="this.form.submit()">
                            <option value="">All Employees</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.payroll.user-bonus.index') }}" class="form-inline">
                        <select name="year" class="form-control mr-2" onchange="this.form.submit()">
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                                </option>
                            @endfor
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Bonus Name</th>
                            <th>Description</th>
                            <th>Amount (৳)</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonuses as $bonus)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $bonus->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $bonus->user->email ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $bonus->name }}</td>
                                <td>
                                    <small>{{ $bonus->description ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <strong>৳{{ number_format($bonus->amount, 2) }}</strong>
                                </td>
                                <td>{{ str_pad($bonus->month, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $bonus->year }}</td>
                                <td>
                                    <a href="{{ route('admin.payroll.user-bonus.edit', $bonus) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.payroll.user-bonus.destroy', $bonus) }}"
                                        class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <em>No bonuses created yet. Click "Add Bonus" to create one!</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $bonuses->links() }}
        </div>
    </div>
@endsection

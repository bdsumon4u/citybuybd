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

            <div class="mb-3 row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('admin.payroll.monthly') }}" class="form-inline">
                        <select name="month" class="mr-2 form-control">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <input type="number" name="year" class="mr-2 form-control" value="{{ $year }}"
                            min="2020" max="2099" style="width:100px">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-sync-alt"></i> View</button>
                    </form>
                </div>
                <div class="text-right col-md-4 d-flex align-items-center justify-content-end">
                    @if ($isCurrentMonth)
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Current month payroll is auto-generated
                            on page load.</small>
                    @else
                        <form method="POST" action="{{ route('admin.payroll.generate') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-cogs"></i> Generate Payroll for {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                {{ $year }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Monthly Salary</th>
                            <th>Present</th>
                            <th>Base Salary</th>
                            <th>Off-day Bonus</th>
                            <th>Overtime</th>
                            <th>Hazira Bonus</th>
                            <th>Special Bonus</th>
                            <th>xSell Bonus</th>
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
                                <td>৳{{ number_format($payroll->user->monthly_salary ?? 0, 2) }}</td>
                                <td>{{ $payroll->present_days - $payroll->off_day_presents }} <span
                                        class="badge badge-success">+
                                        {{ $payroll->off_day_presents }}</span>
                                </td>
                                <td>৳{{ number_format($payroll->base_salary, 2) }}</td>
                                <td class="text-success">+৳{{ number_format($payroll->off_day_bonus, 2) }}</td>
                                <td class="text-success">+৳{{ number_format($payroll->overtime_amount, 2) }}</td>
                                <td class="text-success">+৳{{ number_format($payroll->hazira_bonus_amount ?? 0, 2) }}</td>
                                <td class="text-success">+৳{{ number_format($payroll->occasional_bonus_amount ?? 0, 2) }}
                                </td>
                                <td class="text-success">
                                    <a href="{{ route('admin.payroll.xsellOrders', $payroll->id) }}"
                                        class="text-success font-weight-bold" title="View xSell orders">
                                        +৳{{ number_format($payroll->xsell_bonus_amount ?? 0, 2) }}
                                    </a>
                                </td>
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
                                    <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#editBonuses{{ $payroll->id }}" title="Edit Bonuses">
                                        <i class="fas fa-edit"></i>
                                    </button>
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

                                    <div class="modal fade" id="editBonuses{{ $payroll->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog" style="max-width:1140px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Bonuses -
                                                        {{ $payroll->user->name ?? 'N/A' }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span
                                                            aria-hidden="true">&times;</span></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('admin.payroll.updateBonuses', $payroll->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Hazira Bonus (৳)</label>
                                                            <input type="number" step="0.01" min="0"
                                                                name="hazira_bonus_amount" class="form-control"
                                                                value="{{ $payroll->hazira_bonus_amount ?? 0 }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Special Bonus (৳)</label>
                                                            <input type="number" step="0.01" min="0"
                                                                name="occasional_bonus_amount" class="form-control"
                                                                value="{{ $payroll->occasional_bonus_amount ?? 0 }}"
                                                                required>
                                                        </div>
                                                        <div class="mb-0 form-group">
                                                            <label class="font-weight-bold">xSell Bonus (৳)</label>
                                                            <input type="number" step="0.01" min="0"
                                                                name="xsell_bonus_amount" class="form-control"
                                                                value="{{ $payroll->xsell_bonus_amount ?? 0 }}" required>
                                                        </div>

                                                        @php
                                                            $auditItems = (
                                                                $auditGroups[$payroll->id] ?? collect()
                                                            )->take(5);
                                                        @endphp
                                                        <hr>
                                                        <h6 class="mb-2">Recent Bonus Edits</h6>
                                                        @if ($auditItems->count() > 0)
                                                            <div class="table-responsive">
                                                                <table class="table mb-0 table-sm table-bordered">
                                                                    <thead class="thead-light">
                                                                        <tr>
                                                                            <th>Info</th>
                                                                            <th>OT (Old -> New)</th>
                                                                            <th>Late (Old -> New)</th>
                                                                            <th>Penalty (Old -> New)</th>
                                                                            <th>Hazira (Old -> New)</th>
                                                                            <th>Special (Old -> New)</th>
                                                                            <th>xSell (Old -> New)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($auditItems as $audit)
                                                                            <tr>
                                                                                <td>
                                                                                    <div><strong>{{ $audit->created_at?->format('d M Y h:i A') }}</strong>
                                                                                    </div>
                                                                                    <div>{{ $audit->editor->name ?? 'System' }}</div>
                                                                                    <div>
                                                                                        @if (($audit->event_type ?? 'manual_edit') === 'regenerated')
                                                                                            <span
                                                                                                class="badge badge-info">Regenerated</span>
                                                                                        @else
                                                                                            <span
                                                                                                class="badge badge-primary">Manual
                                                                                                Edit</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div>{{ number_format($audit->old_values['overtime_amount'] ?? 0, 2) }}</div>
                                                                                    <div class="text-muted">-></div>
                                                                                    <div>{{ number_format($audit->new_values['overtime_amount'] ?? 0, 2) }}</div>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div>{{ number_format($audit->old_values['late_deduction'] ?? 0, 2) }}</div>
                                                                                    <div class="text-muted">-></div>
                                                                                    <div>{{ number_format($audit->new_values['late_deduction'] ?? 0, 2) }}</div>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div>{{ number_format($audit->old_values['penalty_amount'] ?? 0, 2) }}</div>
                                                                                    <div class="text-muted">-></div>
                                                                                    <div>{{ number_format($audit->new_values['penalty_amount'] ?? 0, 2) }}</div>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div>{{ number_format($audit->old_values['hazira_bonus_amount'] ?? 0, 2) }}</div>
                                                                                    <div class="text-muted">-></div>
                                                                                    <div>{{ number_format($audit->new_values['hazira_bonus_amount'] ?? 0, 2) }}</div>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div>{{ number_format($audit->old_values['occasional_bonus_amount'] ?? 0, 2) }}</div>
                                                                                    <div class="text-muted">-></div>
                                                                                    <div>{{ number_format($audit->new_values['occasional_bonus_amount'] ?? 0, 2) }}</div>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div>{{ number_format($audit->old_values['xsell_bonus_amount'] ?? 0, 2) }}</div>
                                                                                    <div class="text-muted">-></div>
                                                                                    <div>{{ number_format($audit->new_values['xsell_bonus_amount'] ?? 0, 2) }}</div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <p class="mb-0 text-muted">No bonus audit yet.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center">No payroll records found. No active employees or no
                                    attendance data for this month.</td>
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

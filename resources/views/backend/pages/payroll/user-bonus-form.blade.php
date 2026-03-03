@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>{{ isset($bonus) ? 'Edit' : 'Create' }} Employee Bonus</h4>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <div class="row">
                <div class="col-md-8">
                    <form method="POST"
                        action="{{ isset($bonus) ? route('admin.payroll.user-bonus.update', $bonus) : route('admin.payroll.user-bonus.store') }}">
                        @csrf
                        @if (isset($bonus))
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label class="font-weight-bold">Employee <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control" required
                                @if (isset($bonus)) disabled @endif>
                                <option value="">Select Employee...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ (isset($bonus) && $bonus->user_id == $user->id) || old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @if (isset($bonus))
                                <input type="hidden" name="user_id" value="{{ $bonus->user_id }}">
                            @endif
                            @error('user_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Bonus Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ $bonus->name ?? old('name') }}"
                                class="form-control" required placeholder="e.g., Eid Bonus, Performance Bonus">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Optional description">{{ $bonus->description ?? old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Bonus Amount (৳) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" value="{{ $bonus->amount ?? old('amount') }}"
                                class="form-control" required step="0.01" min="0" placeholder="Enter amount">
                            @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Year <span class="text-danger">*</span></label>
                                    <select name="year" class="form-control" required>
                                        @for ($y = date('Y'); $y >= 2020; $y--)
                                            <option value="{{ $y }}"
                                                {{ (isset($bonus) && $bonus->year == $y) || old('year') == $y || (!isset($bonus) && $y == date('Y')) ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('year')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Month <span class="text-danger">*</span></label>
                                    <select name="month" class="form-control" required>
                                        <option value="">Select Month...</option>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}"
                                                {{ (isset($bonus) && $bonus->month == str_pad($m, 2, '0', STR_PAD_LEFT)) || old('month') == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('month')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes (optional)">{{ $bonus->notes ?? old('notes') }}</textarea>
                            @error('notes')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <a href="{{ route('admin.payroll.user-bonus.index') }}" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Save Bonus</button>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="text-white card-header bg-info"><strong>About Employee Bonuses</strong></div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li><strong>Individual:</strong> Each bonus is tied to a specific employee</li>
                                <li><strong>Month & Year:</strong> Bonus applies only to the selected month/year during
                                    payroll generation</li>
                                <li><strong>Amount:</strong> The bonus amount in taka, added to the employee's salary for
                                    that month</li>
                                <li><strong>Usage:</strong> Perfect for:
                                    <ul>
                                        <li>Individual performance bonuses</li>
                                        <li>Eid/festival bonuses (different amounts per employee)</li>
                                        <li>Incentives or awards</li>
                                        <li>Adjustments to salary</li>
                                    </ul>
                                </li>
                                <li><strong>Display:</strong> Shows as part of the employee's monthly payroll calculation
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

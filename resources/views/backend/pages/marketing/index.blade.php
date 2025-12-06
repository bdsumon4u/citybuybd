@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-15">
                        <h4 class="mb-4">Bulk SMS Marketing</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($errors->has('sms_errors'))
                            <div class="alert alert-warning">
                                <strong>Some SMS failed to send:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->get('sms_errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-6">
                                <form action="{{ route('marketing.filter') }}" method="GET" id="filterForm" novalidate>
                                    <div class="form-group">
                                        <label class="form-control-label">Date Range Type</label>
                                        <select name="date_range_type" id="date_range_type" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="last_days"
                                                {{ request('date_range_type') == 'last_days' ? 'selected' : '' }}>Last N
                                                Days</option>
                                            <option value="date_range"
                                                {{ request('date_range_type') == 'date_range' ? 'selected' : '' }}>Date
                                                Range</option>
                                        </select>
                                    </div>

                                    <div class="form-group" id="last_days_group"
                                        style="display: {{ request('date_range_type') == 'last_days' ? 'block' : 'none' }};">
                                        <label class="form-control-label">Last N Days</label>
                                        <input type="number" name="last_days" class="form-control" min="1"
                                            value="{{ request('last_days') }}" placeholder="e.g., 7, 30">
                                    </div>

                                    <div class="form-group" id="date_range_group"
                                        style="display: {{ request('date_range_type') == 'date_range' ? 'block' : 'none' }};">
                                        <label class="form-control-label">From Date</label>
                                        <input type="date" name="from_date" class="form-control"
                                            value="{{ request('from_date') }}">
                                    </div>

                                    <div class="form-group" id="to_date_group"
                                        style="display: {{ request('date_range_type') == 'date_range' ? 'block' : 'none' }};">
                                        <label class="form-control-label">To Date</label>
                                        <input type="date" name="to_date" class="form-control"
                                            value="{{ request('to_date') }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">Products</label>
                                        <select name="product_ids[]" id="product_ids" class="form-control select2" multiple>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ in_array($product->id, (array) request('product_ids')) ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Leave empty to include all products</small>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">Order Status</label>
                                        <select name="statuses[]" id="statuses" class="form-control select2" multiple>
                                            @foreach ($statusOptions as $statusId => $statusName)
                                                <option value="{{ $statusId }}"
                                                    {{ in_array($statusId, (array) request('statuses')) ? 'selected' : '' }}>
                                                    {{ $statusName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Leave empty to include all statuses</small>
                                    </div>

                                    <button type="submit" class="btn btn-teal">
                                        <i class="fa fa-filter"></i> Filter Orders
                                    </button>
                                </form>
                            </div>

                            <div class="col-lg-6">
                                @if (request()->routeIs('marketing.filter'))
                                    <div class="alert alert-info">
                                        <strong>Found Orders:</strong> {{ $orderCount }}
                                    </div>

                                    @if ($orderCount > 0)
                                        <form action="{{ route('marketing.send') }}" method="POST" id="sendSmsForm">
                                            @csrf
                                            <input type="hidden" name="date_range_type"
                                                value="{{ request('date_range_type') }}">
                                            <input type="hidden" name="last_days" value="{{ request('last_days') }}">
                                            <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                                            <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                                            @if (request('product_ids'))
                                                @foreach (request('product_ids') as $productId)
                                                    <input type="hidden" name="product_ids[]" value="{{ $productId }}">
                                                @endforeach
                                            @endif
                                            @if (request('statuses'))
                                                @foreach (request('statuses') as $status)
                                                    <input type="hidden" name="statuses[]" value="{{ $status }}">
                                                @endforeach
                                            @endif
                                            <div class="form-group">
                                                <label class="form-control-label">SMS Message <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="message" id="sms_message" class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}"
                                                    rows="5" placeholder="Hello Customer, your order is ready for delivery!" required>{{ old('message') }}</textarea>
                                                @if ($errors->has('message'))
                                                    <div class="invalid-feedback d-block">
                                                        {{ $errors->first('message') }}
                                                    </div>
                                                @endif
                                                <small class="text-muted">Maximum 1000 characters.</small>
                                            </div>
                                            <button type="submit" class="btn btn-primary"
                                                onclick="return confirm('Are you sure you want to send SMS to {{ $orderCount }} customers?')">
                                                <i class="fa fa-paper-plane"></i> Send Bulk SMS
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('date_range_type').addEventListener('change', function() {
            const type = this.value;
            document.getElementById('last_days_group').style.display = type === 'last_days' ? 'block' : 'none';
            document.getElementById('date_range_group').style.display = type === 'date_range' ? 'block' : 'none';
            document.getElementById('to_date_group').style.display = type === 'date_range' ? 'block' : 'none';
        });

        $(document).ready(function() {
            $('.select2').select2({
                dropdownCssClass: 'hover-success',
            });
        });
    </script>
@endsection

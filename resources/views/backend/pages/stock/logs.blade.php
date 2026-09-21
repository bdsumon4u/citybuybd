@extends('backend.layout.template')
@section('body-content')
<div class="br-pagetitle">
    <i class="icon ion-ios-paper-outline tx-70 lh-0"></i>
    <div>
        <h4 class="text-dark font-weight-bold">Stock Audit Ledger & Movement Logs</h4>
        <p class="mg-b-0 text-muted">Complete audit trail of all automated delivery deductions, courier return restorations, and purchases.</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('product.stock') }}" class="btn btn-outline-teal font-weight-bold">
            <i class="fa fa-boxes mr-1"></i> Stock Overview
        </a>
        <a href="{{ route('stock.purchases') }}" class="btn btn-outline-info font-weight-bold ml-1">
            <i class="fa fa-shopping-cart mr-1"></i> Purchases History
        </a>
    </div>
</div>

<div class="br-pagebody">
    <!-- Filters Card -->
    <div class="card bd-0 shadow-sm mb-4">
        <div class="card-body bg-light p-3">
            <form method="GET" action="{{ route('stock.logs') }}" class="row align-items-end">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="tx-12 font-weight-bold text-secondary mb-1">Base Product</label>
                    <select name="product_id" id="filter_product_id" class="form-control select2" style="width: 100%;">
                        <option value="">— All Base Products —</option>
                        @foreach($baseProducts as $bp)
                            <option value="{{ $bp->id }}" {{ request('product_id') == $bp->id ? 'selected' : '' }}>
                                {{ $bp->name }} (SKU: {{ $bp->sku ?: 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="tx-12 font-weight-bold text-secondary mb-1">Movement Type</label>
                    <select name="type" class="form-control form-control-sm">
                        <option value="">— All Types —</option>
                        <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>Purchase Intake (+)</option>
                        <option value="order_delivery" {{ request('type') === 'order_delivery' ? 'selected' : '' }}>On-Delivery Deduction (-)</option>
                        <option value="order_return" {{ request('type') === 'order_return' ? 'selected' : '' }}>Courier Return (+)</option>
                        <option value="manual_adjustment" {{ request('type') === 'manual_adjustment' ? 'selected' : '' }}>Manual Adjustment</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="tx-12 font-weight-bold text-secondary mb-1">Order #</label>
                    <input type="number" name="order_id" class="form-control form-control-sm" placeholder="e.g. 1024" value="{{ request('order_id') }}">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="tx-12 font-weight-bold text-secondary mb-1">Date Range</label>
                    <div class="d-flex gap-1">
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-teal btn-sm btn-block font-weight-bold">
                        <i class="fa fa-filter mr-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['product_id', 'type', 'order_id', 'from_date', 'to_date']))
                        <a href="{{ route('stock.logs') }}" class="btn btn-sm btn-outline-secondary ml-1" title="Clear Filters">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Stock Ledger Logs Table Card -->
    <div class="card bd-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fa fa-history text-primary mr-1"></i> Stock Movement Ledger
            </h6>
            <span class="text-muted tx-12">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} Log Entries</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">#ID</th>
                            <th>Timestamp</th>
                            <th>Base Product</th>
                            <th>Transaction Type</th>
                            <th>Linked Reference</th>
                            <th style="text-align: center;">Change (Units)</th>
                            <th style="text-align: center;">Balance (Before → After)</th>
                            <th>User / Trigger</th>
                            <th>Details & Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td><span class="badge badge-light border text-dark">#{{ $log->id }}</span></td>
                                <td>
                                    <span class="font-weight-semibold">{{ $log->created_at->format('d M, Y') }}</span>
                                    <small class="d-block text-muted">{{ $log->created_at->format('h:i:s A') }}</small>
                                </td>
                                <td>
                                    @if($log->product)
                                        <a href="{{ route('details', $log->product->slug) }}" target="_blank" class="font-weight-bold text-dark">
                                            {{ $log->product->name }}
                                        </a>
                                        <small class="d-block text-muted">SKU: {{ $log->product->sku ?: 'N/A' }}</small>
                                    @else
                                        <span class="text-danger"><em>Product Deleted</em></span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->type === 'purchase')
                                        <span class="badge badge-success px-2 py-1 tx-11">
                                            <i class="fa fa-cart-plus mr-1"></i> Purchase Intake
                                        </span>
                                    @elseif($log->type === 'order_delivery')
                                        <span class="badge badge-warning text-dark px-2 py-1 tx-11">
                                            <i class="fa fa-truck mr-1"></i> On-Delivery Deduction
                                        </span>
                                    @elseif($log->type === 'order_return')
                                        <span class="badge badge-teal text-white px-2 py-1 tx-11">
                                            <i class="fa fa-undo mr-1"></i> Courier Return Restore
                                        </span>
                                    @elseif($log->type === 'manual_adjustment')
                                        <span class="badge badge-primary px-2 py-1 tx-11">
                                            <i class="fa fa-sliders-h mr-1"></i> Manual Adjustment
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1 tx-11">{{ $log->type }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->order_id)
                                        <a href="{{ route('orders.show', $log->order_id) }}" target="_blank" class="badge badge-outline-primary font-weight-bold">
                                            <i class="fa fa-external-link-alt mr-1"></i> Order #{{ $log->order_id }}
                                        </a>
                                    @elseif($log->purchase_id)
                                        <span class="badge badge-outline-success font-weight-bold">
                                            Purchase #{{ $log->purchase_id }}
                                        </span>
                                    @else
                                        <span class="text-muted tx-12">—</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($log->quantity > 0)
                                        <span class="badge badge-success px-2 py-1 tx-13 font-weight-bold">
                                            +{{ $log->quantity }}
                                        </span>
                                    @elseif($log->quantity < 0)
                                        <span class="badge badge-danger px-2 py-1 tx-13 font-weight-bold">
                                            {{ $log->quantity }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1 tx-13 font-weight-bold">0</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="tx-12 text-muted">{{ $log->stock_before }}</span>
                                    <i class="fa fa-arrow-right tx-10 text-secondary mx-1"></i>
                                    <span class="tx-13 font-weight-bold {{ $log->stock_after <= 0 ? 'text-danger' : ($log->stock_after <= 5 ? 'text-warning' : 'text-success') }}">
                                        {{ $log->stock_after }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $log->creator->name ?? 'System / Observer' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $log->notes ?: '—' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fa fa-file-alt tx-24 mb-2 d-block text-secondary"></i>
                                    No stock transaction logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="p-3 d-flex justify-content-center">
                    {{ $logs->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function initSelect2() {
            if (window.jQuery && $.fn.select2) {
                $('#filter_product_id').select2({
                    placeholder: '— All Base Products —',
                    allowClear: true,
                    width: '100%'
                });
            }
        }

        initSelect2();
        window.addEventListener('load', initSelect2);
    });
</script>
@endsection

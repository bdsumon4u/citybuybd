@extends('backend.layout.template')
@section('body-content')
<div class="br-pagetitle">
    <i class="icon ion-ios-cart-outline tx-70 lh-0"></i>
    <div>
        <h4 class="text-dark font-weight-bold">Stock Purchases & Intake History</h4>
        <p class="mg-b-0 text-muted">View and manage all historical stock purchase logs and intake entries.</p>
    </div>
    <div class="ml-auto">
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#addPurchaseModal">
            <i class="fa fa-plus-circle mr-1"></i> New Stock Purchase
        </button>
        <a href="{{ route('product.stock') }}" class="btn btn-outline-teal font-weight-bold ml-1">
            <i class="fa fa-boxes mr-1"></i> Stock Overview
        </a>
        <a href="{{ route('stock.logs') }}" class="btn btn-outline-secondary font-weight-bold ml-1">
            <i class="fa fa-history mr-1"></i> Stock Ledger
        </a>
    </div>
</div>

<div class="br-pagebody">
    <!-- Stats Row -->
    <div class="row row-sm mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="bg-teal rounded overflow-hidden pd-20-force text-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fa fa-box-open tx-40 lh--9 op-6"></i>
                    <div class="mg-l-20">
                        <p class="tx-11 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-5">Total Purchased Units</p>
                        <h4 class="tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($totalPurchasedUnits) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4 mg-t-15 mg-sm-t-0">
            <div class="bg-primary rounded overflow-hidden pd-20-force text-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fa fa-file-invoice-dollar tx-40 lh--9 op-6"></i>
                    <div class="mg-l-20">
                        <p class="tx-11 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-5">Total Purchase Cost</p>
                        <h4 class="tx-white tx-lato tx-bold mg-b-0 lh-1">{{ $settings->currency ?? '৳' }} {{ number_format($totalPurchasedCost, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4 mg-t-15 mg-xl-t-0">
            <div class="bg-info rounded overflow-hidden pd-20-force text-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fa fa-receipt tx-40 lh--9 op-6"></i>
                    <div class="mg-l-20">
                        <p class="tx-11 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-5">Total Purchase Entries</p>
                        <h4 class="tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($purchases->total()) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card bd-0 shadow-sm mb-4">
        <div class="card-body bg-light p-3">
            <form method="GET" action="{{ route('stock.purchases') }}" class="row align-items-end">
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
                    <label class="tx-12 font-weight-bold text-secondary mb-1">Supplier</label>
                    <input type="text" name="supplier" class="form-control form-control-sm" placeholder="Supplier name" value="{{ request('supplier') }}">
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="tx-12 font-weight-bold text-secondary mb-1">Invoice / Memo</label>
                    <input type="text" name="invoice_no" class="form-control form-control-sm" placeholder="Invoice no" value="{{ request('invoice_no') }}">
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
                    @if(request()->hasAny(['product_id', 'supplier', 'invoice_no', 'from_date', 'to_date']))
                        <a href="{{ route('stock.purchases') }}" class="btn btn-sm btn-outline-secondary ml-1" title="Clear Filters">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Purchases Table Card -->
    <div class="card bd-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fa fa-list-alt text-primary mr-1"></i> Stock Purchase Records
            </h6>
            <span class="text-muted tx-12">Showing {{ $purchases->firstItem() ?? 0 }} to {{ $purchases->lastItem() ?? 0 }} of {{ $purchases->total() }} Records</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">#ID</th>
                            <th>Purchase Date</th>
                            <th>Base Product</th>
                            <th>Quantity Added</th>
                            <th>Unit Price</th>
                            <th>Total Cost</th>
                            <th>Supplier</th>
                            <th>Invoice / Memo</th>
                            <th>Recorded By</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td><span class="badge badge-light border text-dark">#{{ $purchase->id }}</span></td>
                                <td>
                                    <span class="font-weight-semibold">{{ $purchase->purchase_date ? $purchase->purchase_date->format('d M, Y') : '—' }}</span>
                                    <small class="d-block text-muted">{{ $purchase->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if($purchase->product)
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('backend/img/products/' . ($purchase->product->image ?: 'default.png')) }}" width="32" height="32" class="rounded border mr-2" alt="">
                                            <div>
                                                <a href="{{ route('details', $purchase->product->slug) }}" target="_blank" class="font-weight-bold text-dark d-block">
                                                    {{ $purchase->product->name }}
                                                </a>
                                                <small class="text-muted">SKU: {{ $purchase->product->sku ?: 'N/A' }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-danger"><em>Product Deleted</em></span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success px-2 py-1 tx-13 font-weight-bold">
                                        +{{ number_format($purchase->quantity) }} units
                                    </span>
                                </td>
                                <td>
                                    {{ $purchase->unit_price !== null ? ($settings->currency ?? '৳') . ' ' . number_format($purchase->unit_price, 2) : '—' }}
                                </td>
                                <td>
                                    <strong>{{ $purchase->total_price !== null ? ($settings->currency ?? '৳') . ' ' . number_format($purchase->total_price, 2) : '—' }}</strong>
                                </td>
                                <td>{{ $purchase->supplier ?: '—' }}</td>
                                <td><code>{{ $purchase->invoice_no ?: '—' }}</code></td>
                                <td>
                                    {{ $purchase->creator->name ?? 'System / Admin' }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ $purchase->notes ?: '—' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="fa fa-shopping-cart tx-24 mb-2 d-block text-secondary"></i>
                                    No purchase entries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($purchases->hasPages())
                <div class="p-3 d-flex justify-content-center">
                    {{ $purchases->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Add Purchase / Stock Intake -->
<div class="modal fade" id="addPurchaseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('stock.purchases.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-cart-plus mr-1"></i> Record Stock Purchase / Intake
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 tx-12">
                        <i class="fa fa-info-circle"></i> Stock is maintained and purchased directly for <strong>Base Products</strong>. Linked combo packages will automatically share this stock.
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold">Base Product <span class="text-danger">*</span></label>
                            <select name="product_id" id="modal_product_id" class="form-control select2" required style="width: 100%;">
                                <option value="">— Select Base Product —</option>
                                @foreach($baseProducts as $bp)
                                    <option value="{{ $bp->id }}">
                                        {{ $bp->name }} (SKU: {{ $bp->sku ?: 'N/A' }} | Current Stock: {{ $bp->stock ?? 0 }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Quantity (Units) <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="modal_quantity" class="form-control" min="1" required placeholder="e.g. 50">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Unit Purchase Price (৳)</label>
                            <input type="number" step="any" name="unit_price" id="modal_unit_price" class="form-control" placeholder="e.g. 250">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Total Cost (৳)</label>
                            <input type="number" step="any" name="total_price" id="modal_total_price" class="form-control" placeholder="Calculated or enter total">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Purchase Date <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Supplier / Source Name</label>
                            <input type="text" name="supplier" class="form-control" placeholder="e.g. China Importer / Local Factory">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Invoice / Memo No</label>
                            <input type="text" name="invoice_no" class="form-control" placeholder="e.g. INV-2026-001">
                        </div>
                        <div class="col-md-12 form-group mb-0">
                            <label class="font-weight-bold">Notes / Remarks</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Save Purchase & Add Stock
                    </button>
                </div>
            </form>
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

                $('#modal_product_id').select2({
                    dropdownParent: $('#addPurchaseModal'),
                    placeholder: '— Select Base Product —',
                    allowClear: true,
                    width: '100%'
                });
            }
        }

        initSelect2();
        window.addEventListener('load', initSelect2);

        $('#modal_quantity, #modal_unit_price').on('input', function() {
            var qty = parseFloat($('#modal_quantity').val()) || 0;
            var unitPrice = parseFloat($('#modal_unit_price').val()) || 0;
            if (qty > 0 && unitPrice > 0) {
                $('#modal_total_price').val((qty * unitPrice).toFixed(2));
            }
        });
    });
</script>
@endsection

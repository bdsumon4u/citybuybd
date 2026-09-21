@extends('backend.layout.template')
@section('body-content')
<div class="br-pagetitle">
    <i class="icon ion-ios-filing-outline tx-70 lh-0"></i>
    <div>
        <h4 class="text-dark font-weight-bold">Stock & Inventory Management</h4>
        <p class="mg-b-0 text-muted">Monitor base product stock levels, linked bundle packages, and record purchases.</p>
    </div>
    <div class="ml-auto">
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#addPurchaseModal">
            <i class="fa fa-plus-circle mr-1"></i> Add Stock / Purchase
        </button>
        <a href="{{ route('stock.purchases') }}" class="btn btn-outline-info font-weight-bold ml-1">
            <i class="fa fa-shopping-cart mr-1"></i> Purchases List
        </a>
        <a href="{{ route('stock.logs') }}" class="btn btn-outline-secondary font-weight-bold ml-1">
            <i class="fa fa-history mr-1"></i> Stock Ledger
        </a>
    </div>
</div>

<div class="br-pagebody">
    <!-- Statistics KPI Row -->
    <div class="row row-sm mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-teal rounded overflow-hidden pd-20-force text-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fa fa-cubes tx-40 lh--9 op-6"></i>
                    <div class="mg-l-20">
                        <p class="tx-11 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-5">Total In-Stock Units</p>
                        <h4 class="tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($totalStockUnits) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mg-t-15 mg-sm-t-0">
            <div class="bg-primary rounded overflow-hidden pd-20-force text-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fa fa-cube tx-40 lh--9 op-6"></i>
                    <div class="mg-l-20">
                        <p class="tx-11 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-5">Base Products</p>
                        <h4 class="tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($totalBaseProducts) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mg-t-15 mg-xl-t-0">
            <div class="bg-warning rounded overflow-hidden pd-20-force text-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fa fa-exclamation-triangle tx-40 lh--9 op-6"></i>
                    <div class="mg-l-20">
                        <p class="tx-11 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-5">Low Stock (≤ 5)</p>
                        <h4 class="tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($lowStockCount) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mg-t-15 mg-xl-t-0">
            <div class="bg-danger rounded overflow-hidden pd-20-force text-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fa fa-times-circle tx-40 lh--9 op-6"></i>
                    <div class="mg-l-20">
                        <p class="tx-11 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-5">Out of Stock</p>
                        <h4 class="tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($outOfStockCount) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Accordion / Search Card -->
    <div class="card bd-0 shadow-sm mb-4">
        <div class="card-body bg-light p-3">
            <form method="GET" action="{{ route('product.stock') }}" class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control" placeholder="Search by Base Product Name, SKU..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="category_id" class="form-control">
                        <option value="">— All Categories —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="stock_status" class="form-control">
                        <option value="">— All Stock Levels —</option>
                        <option value="in" {{ request('stock_status') === 'in' ? 'selected' : '' }}>In Stock (> 5)</option>
                        <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock (1 - 5)</option>
                        <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock (0)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-teal btn-block font-weight-bold">
                        <i class="fa fa-filter mr-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'category_id', 'stock_status']))
                        <a href="{{ route('product.stock') }}" class="btn btn-outline-secondary ml-1" title="Clear Filters">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Base Products Table Card -->
    <div class="card bd-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fa fa-boxes text-primary mr-1"></i> Base Products Stock Overview
            </h6>
            <span class="text-muted tx-12">Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} Base Products</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">#Sl</th>
                            <th style="width: 70px;">Image</th>
                            <th>Base Product Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th style="text-align: center;">Stock Status</th>
                            <th>Current Stock</th>
                            <th>Linked Bundle / Combos</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $stockVal = (int) ($product->stock ?? 0);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td>
                                    <img src="{{ asset('backend/img/products/' . ($product->image ?: 'default.png')) }}" width="40" height="40" class="rounded object-cover border" alt="{{ $product->name }}">
                                </td>
                                <td>
                                    <a href="{{ route('details', $product->slug) }}" target="_blank" class="font-weight-bold text-dark d-block">
                                        {{ $product->name }}
                                    </a>
                                    <span class="badge badge-light border text-primary tx-11"><i class="fa fa-cube"></i> Base Product</span>
                                </td>
                                <td><code>{{ $product->sku ?: '—' }}</code></td>
                                <td>{{ $product->category->title ?? '—' }}</td>
                                <td style="text-align: center;">
                                    @if($stockVal <= 0)
                                        <span class="badge badge-danger px-2 py-1 tx-11"><i class="fa fa-times-circle"></i> Out of Stock</span>
                                    @elseif($stockVal <= 5)
                                        <span class="badge badge-warning text-dark px-2 py-1 tx-11"><i class="fa fa-exclamation-triangle"></i> Low Stock</span>
                                    @else
                                        <span class="badge badge-success px-2 py-1 tx-11"><i class="fa fa-check-circle"></i> In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="tx-16 font-weight-bold {{ $stockVal <= 0 ? 'text-danger' : ($stockVal <= 5 ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($stockVal) }}
                                    </span>
                                    <span class="tx-11 text-muted">units</span>
                                </td>
                                <td>
                                    @if($product->combos->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($product->combos as $combo)
                                                <span class="badge badge-info tx-11 mr-1 mb-1" title="{{ $combo->name }} (Multiplier: {{ $combo->base_multiplier }}x)">
                                                    <i class="fa fa-link"></i> {{ $combo->base_multiplier }}x: {{ Str::limit($combo->name, 22) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted tx-12"><em>No linked combos</em></span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn btn-sm btn-primary quick-purchase-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}" title="Add Stock / Purchase">
                                        <i class="fa fa-plus mr-1"></i> Add Stock
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning quick-adjust-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-stock="{{ $stockVal }}" title="Manual Adjust">
                                        <i class="fa fa-sliders-h"></i>
                                    </button>
                                    <a href="{{ route('stock.logs', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-info" title="View Stock Ledger">
                                        <i class="fa fa-history"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fa fa-info-circle tx-24 mb-2 d-block text-secondary"></i>
                                    No base products found matching your search.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
                <div class="p-3 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-4') }}
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
                                @foreach($baseProductsList as $bp)
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

<!-- Modal: Manual Adjust Stock -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('stock.adjust') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="adjust_product_id">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-sliders-h mr-1"></i> Manual Stock Adjustment
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-3 text-dark font-weight-semibold" id="adjust_product_title"></p>
                    <div class="form-group">
                        <label class="font-weight-bold">Current Stock: <span id="adjust_current_stock" class="badge badge-info"></span></label>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">New Physical Stock Count <span class="text-danger">*</span></label>
                        <input type="number" name="new_stock" id="adjust_new_stock" class="form-control" min="0" required placeholder="Enter exact current count">
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Reason for Adjustment <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control" required placeholder="e.g. Physical audit recount, Damaged units write-off">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark">
                        <i class="fa fa-check mr-1"></i> Update Stock Count
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

        // Quick purchase button handler
        $('.quick-purchase-btn').on('click', function() {
            var productId = $(this).data('id');
            $('#modal_product_id').val(productId).trigger('change');
            $('#addPurchaseModal').modal('show');
        });

        // Quick adjust button handler
        $('.quick-adjust-btn').on('click', function() {
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            var currentStock = $(this).data('stock');

            $('#adjust_product_id').val(productId);
            $('#adjust_product_title').text(productName);
            $('#adjust_current_stock').text(currentStock + ' units');
            $('#adjust_new_stock').val(currentStock);
            $('#adjustStockModal').modal('show');
        });

        // Auto calculate total cost
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

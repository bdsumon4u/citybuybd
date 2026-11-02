<div class="mt-4 container-fluid">
    <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist"
        aria-multiselectable="true">
        <div class="card">
            <div class="card-header" role="tab" id="headingOne">
                <h6 class="mg-b-0">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" aria-expanded="true"
                        aria-controls="collapseTwo" class="transition tx-purple"
                        style="background: linear-gradient(90deg, #27ae60, #1a6abe); color: #fff;">
                        Order Filter
                        <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                    </a>
                </h6>
            </div><!-- card-header -->

            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="card-block pd-2 filter-card-block">
                    <!-- Hidden input for special filter -->
                    <input type="hidden" name="special_filter" id="special_filter" value="">

                    <!-- ============================================== -->
                    <!-- LINE 1: Core Search & Order Filters (12 Cols)  -->
                    <!-- ============================================== -->
                    <div class="row align-items-center mb-2">
                        <!-- Search input field Start -->
                        <div class="px-1 mb-1 col-md-4 col-sm-6 col-12">
                            <input name="search_input" type="text" class="form-control" placeholder="Search Orders (ID, Name, Phone)..."
                                id="search_input">
                        </div>

                        @if (request('type') !== 'sales')
                        <!-- Select Status Start -->
                        <div class="px-1 mb-1 col-md-2 col-sm-6 col-12">
                            <select onchange="filterData()" name="status" id="status_ajax" class="form-control">
                                <option value="">Select Status</option>
                                <option @if (request('status') == 1) selected @endif value="1">Processing</option>
                                <option @if (request('status') == 2) selected @endif value="2">Courier Entry</option>
                                <option @if (request('status') == 17) selected @endif value="17">Printed Invoice</option>
                                <option @if (request('status') == 16) selected @endif value="16">Total Courier</option>
                                <option @if (request('status') == 3) selected @endif value="3">On Hold</option>
                                <option @if (request('status') == 4) selected @endif value="4">Cancel</option>
                                <option @if (request('status') == 5) selected @endif value="5">Delivery</option>
                                <option @if (request('status') == 6) selected @endif value="6">Pending Payment</option>
                                <option @if (request('status') == 7) selected @endif value="7">On Delivery</option>
                                <option @if (request('status') == 8) selected @endif value="8">No Response 1</option>
                                <option @if (request('status') == 9) selected @endif value="9">No Response 2</option>
                                <option @if (request('status') == 11) selected @endif value="11">Courier Hold</option>
                                <option @if (request('status') == 12) selected @endif value="12">Return</option>
                                <option @if (request('status') == 18) selected @endif value="18">Pending Return</option>
                                <option @if (request('status') == 13) selected @endif value="13">Partial Delivery</option>
                                <option @if (request('status') == 14) selected @endif value="14">Paid Return</option>
                                <option @if (request('status') == 15) selected @endif value="15">Stock Out</option>
                                <option @if (request('status') == 19) selected @endif value="19">Double</option>
                            </select>
                        </div>

                        <!-- Delivery Area Start -->
                        <div class="px-1 mb-1 col-md-3 col-sm-6 col-12">
                            <select onchange="filterData()" name="delivery_area" id="delivery_area" class="form-control">
                                <option value="">Delivery Area</option>
                                <option value="inside_dhaka" @if (request('delivery_area') === 'inside_dhaka') selected @endif>Inside Dhaka</option>
                                <option value="outside_dhaka" @if (request('delivery_area') === 'outside_dhaka') selected @endif>Outside Dhaka</option>
                            </select>
                        </div>

                        <!-- Order Type Start -->
                        <div class="px-1 mb-1 col-md-3 col-sm-6 col-12">
                            <select onchange="typeFun()" name="order_type" id="order_type" class="form-control">
                                <option value="">Order Type</option>
                                <option value="{{ \App\Models\Order::TYPE_ONLINE }}"
                                    @if (request('order_type') === \App\Models\Order::TYPE_ONLINE) selected @endif>Online</option>
                                <option value="{{ \App\Models\Order::TYPE_MANUAL }}"
                                    @if (request('order_type') === \App\Models\Order::TYPE_MANUAL) selected @endif>Manual</option>
                                <option value="{{ \App\Models\Order::TYPE_INCOMPLETE }}"
                                    @if (request('order_type') === \App\Models\Order::TYPE_INCOMPLETE) selected @endif>Incomplete</option>
                            </select>
                        </div>
                        @endif
                    </div>
                    <!-- LINE 1 End -->

                    <!-- ======================================================== -->
                    <!-- LINE 2: Detailed Filters & Pagination (12 Cols)          -->
                    <!-- ======================================================== -->
                    <div class="row align-items-center mb-2">
                        <!-- Number List Start (Paginate) -->
                        <div class="px-1 mb-1 col-md-2 col-sm-4 col-6">
                            <select onchange="filterData()" name="paginate" id="paginate" class="form-control" title="Per Page">
                                @foreach ([20, 50, 100, 300, 500, 1000, 2000, 3000, 3500, 4000, 4500, 5000] as $item)
                                    <option @if (request('paginate') == $item) selected @endif
                                        value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Filter Start -->
                        <div class="px-1 mb-1 col-md-3 col-sm-4 col-6">
                            <input type="date" id="fromDate" name="fromDate" class="form-control" title="From Date">
                        </div>
                        <div class="px-1 mb-1 col-md-3 col-sm-4 col-6">
                            <input type="date" id="toDate" name="toDate" class="form-control" title="To Date">
                        </div>
                        <!-- Date Filter End -->

                        @if (request('type') !== 'sales')
                        <!-- Delay Days -->
                        <div class="px-1 mb-1 col-md-2 col-sm-4 col-6">
                            <input type="number" min="1" name="delay_days" id="delay_days"
                                value="5" class="form-control" placeholder="Delay" title="Delay Days">
                        </div>
                        @endif

                        <!-- Apply Button Start -->
                        <div class="px-1 mb-1 col-md-2 col-sm-4 col-12">
                            <button type="button" onclick="filterData()" class="btn btn-info btn-block">
                                <i class="fa fa-filter mr-1"></i> Apply
                            </button>
                        </div>
                    </div>
                    <!-- LINE 2 End -->

                    <!-- ======================================================== -->
                    <!-- LINE 3: Action Buttons                                   -->
                    <!-- ======================================================== -->
                    <div class="pt-2 mt-1 border-top d-flex flex-wrap align-items-center justify-content-between" style="border-color: rgba(255,255,255,0.08) !important; gap: 8px;">
                        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                            <!-- Add Order Button -->
                            <a class="btn btn-success" {{ request('type') === 'sales' ? 'target="_blank"' : '' }}
                                href="{{ request('type') === 'sales' ? route('employee.order.create', ['type' => 'sales']) : route('employee.order.create') }}">
                                <i class="fa-solid fa-circle-plus mr-1"></i> {{ request('type') === 'sales' ? 'Add Sales' : 'Add Order' }}
                            </a>
                        </div>
                    </div>
                    <!-- LINE 3 End -->
                </div>
            </div>
        </div>
    </div>
</div>

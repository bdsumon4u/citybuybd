@php
$allEmployees = App\Models\User::where('role', 3)->get();
@endphp
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
                        <div class="px-1 mb-1 col-md-3 col-sm-6 col-12">
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
                        <div class="px-1 mb-1 col-md-2 col-sm-6 col-12">
                            <select onchange="filterData()" name="delivery_area" id="delivery_area" class="form-control">
                                <option value="">Delivery Area</option>
                                <option value="inside_dhaka" @if (request('delivery_area') === 'inside_dhaka') selected @endif>Inside Dhaka</option>
                                <option value="outside_dhaka" @if (request('delivery_area') === 'outside_dhaka') selected @endif>Outside Dhaka</option>
                            </select>
                        </div>

                        <!-- Select Courier Start -->
                        <div class="px-1 mb-1 col-md-2 col-sm-6 col-12">
                            <select onchange="filterData()" name="courier" id="courier" class="form-control">
                                <option value="">Select Courier</option>
                                <option value="1">RedX</option>
                                <option value="3">Pathao</option>
                                <option value="4">Steadfast</option>
                                <option value="14">Bahok</option>
                            </select>
                        </div>

                        <!-- Order Type Start -->
                        <div class="px-1 mb-1 col-md-2 col-sm-6 col-12">
                            <select onchange="typeFun()" name="order_type" id="order_type" class="form-control">
                                <option value="">Order Type</option>
                                <option value="{{ \App\Models\Order::TYPE_ONLINE }}"
                                    @if (request('order_type') === \App\Models\Order::TYPE_ONLINE) selected @endif>Online</option>
                                <option value="{{ \App\Models\Order::TYPE_MANUAL }}"
                                    @if (request('order_type') === \App\Models\Order::TYPE_MANUAL) selected @endif>Manual</option>
                                <option value="{{ \App\Models\Order::TYPE_SALES }}"
                                    @if (request('order_type') === \App\Models\Order::TYPE_SALES) selected @endif>Sales</option>
                                <option value="{{ \App\Models\Order::TYPE_INCOMPLETE }}"
                                    @if (request('order_type') === \App\Models\Order::TYPE_INCOMPLETE) selected @endif>Incomplete</option>
                                @foreach (\App\Models\ManualOrderType::active()->ordered()->get() as $type)
                                    <option value="{{ $type->name }}"
                                        @if (request('order_type') === $type->name) selected @endif>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Daywise Selection start -->
                        <div class="px-1 mb-1 {{ request('type') === 'sales' ? 'col-md-9 col-sm-6 col-12' : 'col-md-1 col-sm-6 col-12' }}">
                            <select name="fixeddate" class="form-control" onchange="filterData()" id="fixeddate">
                                <option value="">Total</option>
                                <option value="1">Today</option>
                                <option value="2">Yesterday</option>
                                <option value="7">Last 7 Days</option>
                                <option value="15">Last 15 Days</option>
                                <option value="30">Last 30 Days</option>
                            </select>
                        </div>
                    </div>
                    <!-- LINE 1 End -->

                    <!-- ======================================================== -->
                    <!-- LINE 2: Detailed Filters & Report Selectors (12 Cols)    -->
                    <!-- ======================================================== -->
                    <div class="row align-items-center mb-2">
                        <!-- Number List Start (Paginate) -->
                        <div class="px-1 mb-1 col-md-1 col-sm-4 col-6">
                            <select onchange="filterData()" name="paginate" id="paginate" class="form-control" title="Per Page">
                                @foreach ([20, 50, 100, 300, 500, 1000, 2000, 3000, 3500, 4000, 4500, 5000] as $item)
                                    <option @if (request('paginate') == $item) selected @endif
                                        value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Filter Start -->
                        <div class="px-1 mb-1 col-md-2 col-sm-4 col-6">
                            <input type="date" id="fromDate" name="fromDate" class="form-control" title="From Date">
                        </div>
                        <div class="px-1 mb-1 col-md-2 col-sm-4 col-6">
                            <input type="date" id="toDate" name="toDate" class="form-control" title="To Date">
                        </div>
                        <!-- Date Filter End -->

                        @if (request('type') !== 'sales')
                        <!-- Delay Days -->
                        <div class="px-1 mb-1 col-md-1 col-sm-4 col-6">
                            <input type="number" min="1" name="delay_days" id="delay_days"
                                value="5" class="form-control" placeholder="Delay" title="Delay Days">
                        </div>
                        @endif

                        <!-- Employee Report Start -->
                        <div class="px-1 mb-1 col-md-2 col-sm-4 col-12">
                            <select name="" id="order_assign" class="form-control">
                                <option value="">Employee Report</option>
                                @foreach ($allEmployees as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Report Start -->
                        <div class="px-1 mb-1 col-md-3 col-sm-8 col-12">
                            <select name="" id="product_id" class="form-control select2">
                                <option value="">Select Here For Product Report</option>
                                @if (isset($products))
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Apply Button Start -->
                        <div class="px-1 mb-1 col-md-1 col-sm-4 col-12">
                            <button type="button" onclick="filterData()" class="btn btn-info btn-block">
                                <i class="fa fa-filter mr-1"></i> Apply
                            </button>
                        </div>
                    </div>
                    <!-- LINE 2 End -->

                    <!-- ======================================================== -->
                    <!-- LINE 3: Action Buttons & Bulk Operations (Compact Row)   -->
                    <!-- ======================================================== -->
                    <div class="pt-2 mt-1 border-top d-flex align-items-center flex-nowrap" style="border-color: rgba(255,255,255,0.08) !important; gap: 5px; overflow-x: auto;">
                        <!-- Add Order Button -->
                        <a class="btn btn-sm btn-success text-nowrap" {{ request('type') === 'sales' ? 'target="_blank"' : '' }}
                            href="{{ request('type') === 'sales' ? route('manager.order.create', ['type' => 'sales']) : route('order.create') }}">
                            <i class="fa-solid fa-circle-plus mr-1"></i> {{ request('type') === 'sales' ? 'Add Sales' : 'Add Order' }}
                        </a>

                        @if (request('type') !== 'sales')
                        <!-- Override Key -->
                        <div style="width: 100px; min-width: 90px; flex-shrink: 0;">
                            <input type="text" id="blk_stts_ovr_cod" name="status_override_key"
                                class="form-control form-control-sm" autocomplete="new-password" readonly
                                onfocus="this.removeAttribute('readonly');" data-lpignore="true"
                                data-1p-ignore="true" autocapitalize="off" autocorrect="off" spellcheck="false"
                                placeholder="Override Key">
                        </div>

                        <!-- Assign Employee -->
                        <div style="width: 130px; min-width: 120px; flex-shrink: 0;">
                            <form action="{{ route('selected_e_assign') }}" method="post" class="all_e_assign_form m-0">
                                @csrf
                                <input type="hidden" class="all_e_assign" name="all_e_assign">
                                <input type="hidden" class="bulk_over_cod_input" name="status_over_cod">
                                <input type="hidden" name="status_override_key" class="status_override_key_input">
                                <select name="e_assign" class="form-control form-control-sm e_assign">
                                    <option value="">Employee</option>
                                    @foreach ($allEmployees as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <!-- Change Bulk Order Status -->
                        <div style="width: 135px; min-width: 125px; flex-shrink: 0;">
                            <form action="{{ route('selected_status') }}" method="post" id="all_status_form" class="m-0">
                                @csrf
                                <input type="hidden" id="all_status" name="all_status">
                                <input type="hidden" class="bulk_over_cod_input" name="status_over_cod">
                                <input type="hidden" name="status_override_key" class="status_override_key_input">
                                <select name="status" id="status" class="form-control form-control-sm">
                                    <option value="">Bulk Status</option>
                                    <option value="1">Processing</option>
                                    <option value="2">Courier Entry</option>
                                    <option value="17">Printed Invoice</option>
                                    <option value="16">Total Courier</option>
                                    <option value="4">Cancel</option>
                                    <option value="5" disabled>Delivery</option>
                                    <option value="6">Pending Payment</option>
                                    <option value="7">On Delivery</option>
                                    <option value="8">No Response 1</option>
                                    <option value="9">No Response 2</option>
                                    <option value="3">On Hold</option>
                                    <option value="11">Courier Hold</option>
                                    <option value="12">Return</option>
                                    <option value="18">Pending Return</option>
                                    <option value="13">Partial Delivery</option>
                                    <option value="14">Paid Return</option>
                                    <option value="15">Stock Out</option>
                                    <option value="19">Double</option>
                                </select>
                            </form>
                        </div>

                        <!-- Bulk Assign Courier -->
                        <div style="width: 120px; min-width: 110px; flex-shrink: 0;">
                            <form action="{{ route('manager.selected_courier') }}" method="post" id="all_courier_form" class="m-0">
                                @csrf
                                <input type="hidden" id="all_courier" name="all_courier">
                                <select name="courier_id" id="bulk_courier" class="form-control form-control-sm">
                                    <option value="">Courier</option>
                                    @foreach (\App\Models\Courier::all() as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        @endif

                        <!-- Bulk Invoice Print -->
                        <div style="flex-shrink: 0;">
                            <form action="{{ route('printChecketorders') }}" method="post" id="bulk_print_form" target="_blank" class="m-0">
                                @csrf
                                <input type="hidden" id="all_id_print" name="all_id_print">
                                <input type="hidden" name="status_override_key" class="status_override_key_input">
                                <button type="button" id="bulk_print" class="btn btn-sm btn-info text-nowrap">
                                    <i class="fa fa-print mr-1"></i> Bulk Invoice
                                </button>
                            </form>
                        </div>

                        <!-- Invoice / Label -->
                        <div style="flex-shrink: 0;">
                            <form action="{{ route('labelChecketorders') }}" method="post" id="bulk_label_form" target="_blank" class="m-0">
                                @csrf
                                <input type="hidden" id="all_id_label" name="all_id_label">
                                <input type="hidden" name="status_override_key" class="status_override_key_input">
                                <button type="button" id="bulk_label" class="btn btn-sm btn-warning text-nowrap">
                                    <i class="fa-solid fa-file-invoice mr-1"></i> Invoice
                                </button>
                            </form>
                        </div>

                        <!-- Bulk Delete -->
                        <div style="flex-shrink: 0;">
                            <form action="{{ route('deleteChecketorders') }}" method="post" id="bulk_delete_form" class="m-0">
                                @csrf
                                <input type="hidden" id="all_id" name="all_id">
                                <input type="hidden" name="status_override_key" class="status_override_key_input">
                                <button type="button" id="bulk_delete" class="btn btn-sm btn-danger text-nowrap">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Bulk Delete
                                </button>
                            </form>
                        </div>

                        <!-- Export CSV -->
                        <div class="dropdown" style="flex-shrink: 0;">
                            <button class="btn btn-sm btn-warning dropdown-toggle text-nowrap" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa-solid fa-file-excel mr-1"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-right pd-10 wd-200" aria-labelledby="dropdownMenuButton">
                                <form action="{{ route('excelChecketorders') }}" method="post" id="bulk_excel_form">
                                    @csrf
                                    <input type="hidden" id="all_id_excel" name="all_id_excel">
                                    <input type="hidden" name="courier" value="Normal">
                                    <button type="button" id="bulk_excel"
                                        class="btn btn-sm btn-dark w-100 mb-1">Normal</button>
                                </form>
                                <form action="{{ route('excelChecketorders') }}" method="post" id="bulk_excel_form_redx">
                                    @csrf
                                    <input type="hidden" id="all_id_excel_redx" name="all_id_excel">
                                    <input type="hidden" name="courier" value="Redx">
                                    <button type="button" id="bulk_excel_redx"
                                        class="btn btn-sm btn-danger w-100 mb-1">Redx</button>
                                </form>
                                <form action="{{ route('excelChecketorders') }}" method="post" id="bulk_excel_form_pathao">
                                    @csrf
                                    <input type="hidden" id="all_id_excel_pathao" name="all_id_excel">
                                    <input type="hidden" name="courier" value="Pathao">
                                    <button type="button" id="bulk_excel_pathao"
                                        class="btn btn-sm btn-success w-100 mb-1">Pathao</button>
                                </form>
                                <form action="{{ route('excelChecketorders') }}" method="post" id="bulk_excel_form_steadfast">
                                    @csrf
                                    <input type="hidden" id="all_id_excel_steadfast" name="all_id_excel">
                                    <input type="hidden" name="courier" value="Steadfast">
                                    <button type="button" id="bulk_excel_steadfast"
                                        class="btn btn-sm btn-info w-100">Steadfast</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- LINE 3 End -->
                </div>
            </div>
        </div>
    </div>
</div>

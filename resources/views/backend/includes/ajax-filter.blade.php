<div class="mt-4 container-fluid">
    <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
        <div class="card">
            <div class="card-header" role="tab" id="headingOne">
                <h6 class="mg-b-0">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                       aria-expanded="true" aria-controls="collapseTwo" class="transition tx-purple"
                       style="background: linear-gradient(90deg, #27ae60, #1a6abe); color: #fff;">
                        Order Filter
                        <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                    </a>
                </h6>
            </div>

            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="card-block pd-5" style="background-color: #e9ecef; border: 1px solid lightgrey;">
                    <!-- Filter First Row start -->
                    <div class="row bd-b">
                        <!-- Add button start -->
                        <div class="pb-1 col-md-1">
                            <a class="btn btn-success" href="{{route('order.create')}}">Add Order</a>
                        </div>
                        <!-- Search input field Start -->
                        <div class="pb-1 col-md-2">
                            <input name="search_input" type="text" class="form-control" placeholder="Search Orders" id="search_input">
                        </div>
                        <!-- Select Status Start -->
                        <div class="px-1 pb-1 col-md-1 col-12">
                            <!--<select onchange="filterData()" name="status" id="status_ajax" class="form-control">-->
                            <!--    <option value="">Select Status</option>-->
                            <!--    <option value="1">Processing</option>-->
                            <!--    <option value="2">Pending Delivery</option>-->
                            <!--    <option value="3">On Hold</option>-->
                            <!--    <option value="4">Cancel</option>-->
                            <!--    <option value="5">Completed</option>-->
                            <!--    <option value="6">Pending Payment</option>-->
                            <!--    <option value="7">On Delivery</option>-->
                            <!--    <option value="8">No Response 1</option>-->
                            <!--    <option value="9">No Response 2</option>-->
                            <!--    <option value="11">Courier Hold</option>-->
                            <!--    <option value="12">Return</option>-->
                            <!--</select>    -->
                            <select onchange="filterData()" name="status" id="status_ajax" class="form-control">
                            <option value="">Select Status</option>
                            <option @if(request('status') == 1) selected @endif value="1">Processing</option>
                            <option @if(request('status') == 2) selected @endif value="2">Pending Delivery</option>
                            <option @if(request('status') == 16) selected @endif value="16">Total Courier</option>
                            <option @if(request('status') == 3) selected @endif value="3">On Hold</option>
                            <option @if(request('status') == 4) selected @endif value="4">Cancel</option>
                            <option @if(request('status') == 5) selected @endif value="5">Completed</option>
                            <option @if(request('status') == 6) selected @endif value="6">Pending Payment</option>
                            <option @if(request('status') == 7) selected @endif value="7">On Delivery</option>
                            <option @if(request('status') == 8) selected @endif value="8">No Response 1</option>
                            <option @if(request('status') == 9) selected @endif value="9">No Response 2</option>
                            <option @if(request('status') == 11) selected @endif value="11">Courier Hold</option>
                            <option @if(request('status') == 12) selected @endif value="12">Return</option>
                            <option @if(request('status') == 13) selected @endif value="13">Partial Delivery</option>
                            <option @if(request('status') == 14) selected @endif value="14">Paid Return</option>
                            <option @if(request('status') == 15) selected @endif value="15">Stock Out</option>
                        </select>

                        </div>

                        <!-- Select Courier Start -->
                        <div class="px-1 pb-1 col-md-1 col-12">
                            <select onchange="filterData()" name="courier" id="courier" class="form-control">
                                <option value="">Select Courier</option>
                                <option value="1">RedX</option>
                                <option value="3">Pathao</option>
                                <option value="4">Steadfast</option>
                            </select>
                        </div>

                        <!-- Order Type Start -->
                        <div class="pb-1 col-md-2 col-12">
                            <select onchange="typeFun()" name="order_type" id="order_type" class="form-control">
                                <option value="">Order Type</option>
                                <option value="{{ \App\Models\Order::TYPE_ONLINE }}" @if(request('order_type') === \App\Models\Order::TYPE_ONLINE) selected @endif>Online</option>
                                <option value="{{ \App\Models\Order::TYPE_MANUAL }}" @if(request('order_type') === \App\Models\Order::TYPE_MANUAL) selected @endif>Manual</option>
                                <option value="{{ \App\Models\Order::TYPE_CONVERTED }}" @if(request('order_type') === \App\Models\Order::TYPE_CONVERTED) selected @endif>Converted</option>
                                @foreach(\App\Models\ManualOrderType::active()->ordered()->get() as $type)
                                    <option value="{{ $type->name }}" @if(request('order_type') === $type->name) selected @endif>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Daywise Selection start -->
                        <div class="pb-1 col-md-1">
                            <select name="fixeddate" class="form-control" onchange="filterData()" id="fixeddate">
                                <option value="">Total</option>
                                <option value="1">Today</option>
                                <option value="2">Yesterday</option>
                                <option value="7">Last 7 Days</option>
                                <option value="15">Last 15 Days</option>
                                <option value="30">Last 30 Days</option>
                            </select>
                        </div>

                        <!-- Assign Employee Start -->
                        <div class="pb-1 col-md-2 col-12">
                            <form action="{{route('selected_e_assign')}}" method="post" class="all_e_assign_form">
                                @csrf
                                <input type="hidden" class="all_e_assign" name="all_e_assign">
                                <select name="e_assign" class="form-control e_assign">
                                    <option value="">Assign Employee</option>
                                    @foreach(App\Models\User::where('role', 3)->get() as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <!-- Change Bulk Order Status Start -->
                        <div class="col-md-2">
                            <form action="{{route('selected_status')}}" method="post" id="all_status_form">
                                @csrf
                                <input type="hidden" id="all_status" name="all_status">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Change Bulk Order Status</option>
                                    <option value="1">Processing</option>
                                    <option value="2">Pending Delivery</option>
                                    <option value="16">Total Courier</option>
                                    <option value="3">On Hold</option>
                                    <option value="4">Cancel</option>
                                    <option value="5">Completed</option>
                                    <option value="6">Pending Payment</option>
                                    <option value="7">On Delivery</option>
                                    <option value="8">No Response 1</option>
                                    <option value="9">No Response 2</option>
                                    <option value="11">Courier Hold</option>
                                    <option value="12">Return</option>
                                    <option value="13">Partial Delivery</option>
                                    <option value="14">Paid Return</option>
                                    <option value="15">Stock Out</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <!-- Filter First Row End -->

                    <!-- Filter Second Row Start -->
                    <div class="pt-3 row">
                        <!-- <div class="pb-1 col-md-12"> -->
                            <!-- <div class="d-flex"> -->
                                <!-- Number List Start -->
                                <div class="pb-1 col-md-1 col-12">
                                    <select onchange="filterData()" name="paginate" id="paginate" class="form-control">
                                        <option @if(request('paginate') == 20) selected @endif value="20">20</option>
                                        <option @if(request('paginate') == 50) selected @endif value="50">50</option>
                                        <option @if(request('paginate') == 100) selected @endif value="100">100</option>
                                        <option @if(request('paginate') == 500) selected @endif value="500">500</option>
                                        <option @if(request('paginate') == 1000) selected @endif value="1000">1000</option>
                                    </select>
                                </div>

                                <!-- Date Filter Start -->
                                <div class="col-md-1">
                                    <input type="date" id="fromDate" name="fromDate" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <input type="date" id="toDate" name="toDate" class="form-control">
                                </div>
                                <!-- Date Filter End -->

                                <!-- Employee Report Start -->
                                <div class="col-md-1 d-flex justify-content-left">
                                    <select name="" id="order_assign" class="form-control">
                                        <option value="">Employee Report</option>
                                        @foreach(App\Models\User::where('role', 3)->get() as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Product Report Start -->
                                <div class="mb-1 col-md-3 justify-content-center position-static" >
                                    <select name="" id="product_id" class="form-control select2">
                                        <option value="">Select Here For Product Report</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Apply Button Start -->
                                <div class="mb-1 col-4 col-md-1" >
                                    <button type="button" onclick="filterData()" class="btn btn-info">Apply Here</button>
                                </div>

                                <!-- Bulk Invoice Print Start -->
                                <div class="mb-1 col-4 col-md-1 d-flex">
                                    <form action="{{route('printChecketorders')}}" method="post" id="bulk_print_form" target="_blank">
                                        @csrf
                                        <input type="hidden" id="all_id_print" name="all_id_print">
                                        <button type="button" id="bulk_print" class="btn btn-info">Bulk Invoice</button>
                                    </form>
                                </div>
                    <!-- Normal print start -->
                        <div class="pb-1 col-md-1 d-flex justify-content-center col-4">
                                        <form action="{{route('labelChecketorders')}}" method="post" id="bulk_label_form" target="_blank">
                                            @csrf

                                                <input type="hidden" id="all_id_label" name="all_id_label">
                                                <button type="button" id="bulk_label" class="btn btn-warning"><i class="fa-solid fa-file-invoice"></i>  invoice </button>

                                        </form>
                                    </div>
                         <!-- Normal print end-->
                                <!-- Bulk Delete Start -->
                                <div class="pb-1 mb-1 col-4 col-md-1">
                                    <form action="{{route('deleteChecketorders')}}" method="post" id="bulk_delete_form">
                                        @csrf
                                        <input type="hidden" id="all_id" name="all_id">
                                        <button type="button" id="bulk_delete" class="btn btn-danger">Bulk Delete</button>
                                    </form>
                                </div>

                                <!-- Export CSV Start -->
                                <div class="pb-1 col-md-1 col-4">
                                    <div class="mr-auto dropdown">
                                        <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Export
                                        </button>
                                        <div class="dropdown-menu pd-10 wd-200" aria-labelledby="dropdownMenuButton">
                                            <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form">
                                                @csrf
                                                <input type="hidden" id="all_id_excel" name="all_id_excel">
                                                <input type="hidden" name="courier" value="Normal">
                                                <button type="button" id="bulk_excel" class="btn btn-sm btn-dark w-75">Normal</button>
                                            </form>
                                            <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form">
                                                @csrf
                                                <input type="hidden" id="all_id_excel" name="all_id_excel">
                                                <input type="hidden" name="courier" value="Redx">
                                                <button type="button" id="bulk_excel" class="mt-1 btn btn-sm btn-danger w-75">Redx</button>
                                            </form>
                                            <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form">
                                                @csrf
                                                <input type="hidden" id="all_id_excel" name="all_id_excel">
                                                <input type="hidden" name="courier" value="Pathao">
                                                <button type="button" id="bulk_excel" class="mt-1 btn btn-sm btn-success w-75">Pathao</button>
                                            </form>
                                            <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form">
                                                @csrf
                                                <input type="hidden" id="all_id_excel" name="all_id_excel">
                                                <input type="hidden" name="courier" value="Steadfast">
                                                <button type="button" id="bulk_excel" class="mt-1 btn btn-sm btn-info w-75">Steadfast</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
                    <!-- Filter Second Row End -->

                </div>
            </div>
        </div>
    </div>
</div>

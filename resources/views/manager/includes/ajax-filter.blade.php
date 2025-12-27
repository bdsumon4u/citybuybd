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
                </div><!-- card-header -->

                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">

                <!-- Filter First Row start -->
                  <div class="row bd-b">



                    <!-- Add button start-->
                     <div class="pb-1 col-md-1">

                      <a class="btn btn-success" href="{{route('order.create')}}">Add Order</a>

                    </div>
                    <!-- Add Button End-->


                    <!-- Search input field Start-->
                     <div class="pb-1 col-md-2">
                                        <!-- <form action="{{route('order.search.input')}}" method="get" > -->

                                                <input name="search_input" type="text" class="form-control" placeholder="Search Orders" id="search_input">
                                                <!--<div class="col-md-3"><button onclick="filterData()" type="button" class="btn btn-warning">Search</button></div>-->

                                        <!-- </form> -->
                     </div>
                     <!-- Search input field End-->

                    <!-- Select Status Start -->
                    <div class="pb-1 col-md-2 col-12">
                                           <select onclick="filterData()" name="status" id="status_ajax" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="1">Processing</option>
                                                <option value="2">Courier Entry</option>
                                                <option value="3">On Hold</option>
                                                <option value="4">Cancel</option>
                                                <option value="5">Completed</option>
                                                <option value="6">Pending Payment</option>
                                                <option value="7">On Delivery</option>
                                                <option value="8">No Response 1</option>
                                                <option value="9">Printed Invoice</option>
                                                <option value="11">Courier Hold</option>
                                                <option value="12">Return</option>
                                                <option value="13">Partial Delivery</option>
                                                <option value="14">Paid Return</option>
                                                <option value="15">Stock Out</option>

                       </select>

                                        <!-- <form action="{{route('selected_status')}}" method="post" id="all_status_form">
                                            @csrf
                                            <input type="hidden" id="all_status" name="all_status">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="1">Processing</option>
                                                <option value="2">Courier Entry</option>
                                                <option value="3">On Hold</option>
                                                <option value="4">Cancel</option>
                                                <option value="5">Completed</option>
                                                <option value="6">Pending Payment</option>
                                                <option value="7">On Delivery</option>
                                                <option value="8">No Response 1</option>
                                                <option value="9">Printed Invoice</option>
                                                <option value="11">Courier Hold</option>
                                                <option value="12">Return</option>
                                            </select>
                                        </form> -->


                         </div>
                    <!--Select Status End-->



                    <!-- Currier Start -->
                    <div class="pb-1 col-md-2 col-12">
                                            <select onclick="filterData()" name="courier" id="courier" class="form-control">
                                                <option value="">Select Courier </option>
                                                <option value="1">RedX</option>
                                                <option value="3">Pathao</option>
                                                <option value="4">Steadfast</option>
                                            </select>
                        </div>
                    <!-- currier End-->

                    <!-- order type start -->
                    <div class="pb-1 col-md-2 col-12">
                        <select onchange="typeFun()" name="order_type" id="order_type" class="form-control">
                            <option value="">Order Type</option>
                            <option value="{{ \App\Models\Order::TYPE_ONLINE }}">Online</option>
                            <option value="{{ \App\Models\Order::TYPE_MANUAL }}">Manual</option>
                            <option value="{{ \App\Models\Order::TYPE_CONVERTED }}">Converted</option>
                        </select>
                    </div>
                    <!-- order type end -->






                                    <!--<div class="pb-1 col-md-2 col-12">-->
                                    <!--    <form action="{{route('selected_e_assign')}}" method="post" class="all_e_assign_form">-->
                                    <!--        @csrf-->
                                    <!--        <input type="hidden" class="all_e_assign" name="all_e_assign">-->
                                    <!--        <select name="e_assign"  class="form-control e_assign">-->
                                    <!--            <option value="">Select Employee</option>-->
                                    <!--            @foreach(App\Models\User::where('role',3)->get() as $user)-->
                                    <!--                <option value="{{$user->id}}">{{$user->name}}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--    </form>-->
                                    <!--</div>-->





                    <!-- select employee start -->
                        <div class="pb-1 col-md-2 col-12">
                                        <form action="{{route('selected_e_assign')}}" method="post" class="all_e_assign_form">
                                            @csrf
                                            <input type="hidden" class="all_e_assign" name="all_e_assign">
                                            <select name="e_assign"  class="form-control e_assign">
                                                <option value="">Assign Employee </option>
                                                @foreach(App\Models\User::where('role',3)->get() as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                        <!--sekect employee end-->



                        <!-- select status start -->

                        <div class="col-md-2">
                            <form action="{{route('selected_status')}}" method="post" id="all_status_form">
                                            @csrf
                                            <input type="hidden" id="all_status" name="all_status">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Change Bulk Order Status</option>
                                                <option value="1">Processing</option>
                                                <option value="2">Courier Entry</option>
                                                <option value="16">Total Courier</option>
                                                <option value="3">On Hold</option>
                                                <option value="4">Cancel</option>
                                                <option value="5">Completed</option>
                                                <option value="6">Pending Payment</option>
                                                <option value="7">On Delivery</option>
                                                <option value="8">No Response 1</option>
                                                <option value="9">Printed Invoice</option>
                                                <option value="11">Courier Hold</option>
                                                <option value="12">Return</option>
                                                <option value="13">Partial Delivery</option>
                                                <option value="14">Paid Return</option>
                                                <option value="15">Stock Out</option>
                                            </select>
                                        </form>
                        </div>

                        <!-- select status end-->



                </div>
                <!-- Filter First Row End-->



                <!-- Filter Secoind row Sart -->
                <div class="pt-3 row">


                                    <div class="pb-1 col-md-12">
                                        <!-- <form action="{{route('order.search')}}" method="get" > -->

                                            <div class="d-flex">

                                                 <!--<div class="col-md-6"> -->

                                                 <!--                 <a href="{{ url('admin/order-management/filter-data')}}" target="_blank" class="btn btn-info form-control">Click Here For Print Page</a>-->


                                                 <!--   </div>-->
                                                    <!--<div class="pb-1 col-md-3 col-12">-->
                                                    <!--    <form action="{{route('selected_e_assign')}}" method="post" class="all_e_assign_form">-->
                                                    <!--        @csrf-->
                                                    <!--        <input type="hidden" class="all_e_assign" name="all_e_assign">-->
                                                    <!--        <select name="e_assign"  class="form-control e_assign">-->
                                                    <!--            <option value="">Select Employee</option>-->
                                                    <!--            @foreach(App\Models\User::where('role',3)->get() as $user)-->
                                                    <!--                <option value="{{$user->id}}">{{$user->name}}</option>-->
                                                    <!--            @endforeach-->
                                                    <!--        </select>-->
                                                    <!--    </form>-->
                                                    <!--</div>-->



                         <!-- Nuber List stast -->
                        <div class="pb-1 col-md-1 col-12">
                                                        <select onclick="filterData()" name="paginate" id="paginate" class="form-control">
                                                            <option @if(request('paginate') == 20) selected @endif value="20">20</option>
                                                            <option @if(request('paginate') == 50) selected @endif value="50">50</option>
                                                            <option @if(request('paginate') == 100) selected @endif value="100">100</option>
                                                            <option @if(request('paginate') == 500) selected @endif value="500">500</option>
                                                            <option @if(request('paginate') == 1000) selected @endif value="1000">1000</option>
                                                        </select>
                         </div>
                        <!-- Nuber list End -->









                                 <!-- Apply Button Start-->
                                <div class="col-md-1">
                                        <button type="button" onclick="filterData()" class="btn btn-info"> Apply Here</button>
                                    </div>
                                 <!-- Apply button  End -->


                        <!-- print invoice start -->
                       <div class="pb-1 col-md-1 d-flex justify-content-center col-12">
                                        <form action="{{route('printChecketorders')}}" method="post" id="bulk_print_form" target="_blank">
                                            @csrf


                                                <input type="hidden" id="all_id_print" name="all_id_print">
                                                <button type="button" id="bulk_print" class="btn btn-info">Bulk Invoice</button>

                                        </form>
                                    </div>
                        <!-- print invoice end -->



                         <!-- Normal print start -->
                        <div class="pb-1 col-md-1 d-flex justify-content-center col-12">
                                        <form action="{{route('labelChecketorders')}}" method="post" id="bulk_label_form" target="_blank">
                                            @csrf

                                                <input type="hidden" id="all_id_label" name="all_id_label">
                                                <button type="button" id="bulk_label" class="btn btn-warning"><i class="fa-solid fa-file-invoice"></i>  invoice </button>

                                        </form>
                                    </div>
                         <!-- Normal print end-->




                        <!-- export csv start -->
                        <div class="pb-1 col-md-1 col-12">
                                                        <div class="mr-auto dropdown">
                                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Export
                                                            </button>
                                                            <div class="dropdown-menu pd-10 wd-200" aria-labelledby="dropdownMenuButton">
                                                                <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form">
                                                                    @csrf
                                                                    <div class="m-1">
                                                                        <input type="hidden" id="all_id_excel" name="all_id_excel">
                                                                        <input type="hidden" name="courier" value="Normal">
                                                                        <button type="button" id="bulk_excel" class="btn btn-sm btn-dark w-75">Normal</button>
                                                                    </div>
                                                                </form>
                                                                <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form_redx">
                                                                    @csrf
                                                                    <div class="m-1">
                                                                        <input type="hidden" id="all_id_excel_redx" name="all_id_excel">
                                                                        <input type="hidden" name="courier" value="redx">
                                                                        <button type="button" id="bulk_excel_redx" class="btn btn-sm btn-danger w-75">RedX</button>
                                                                    </div>
                                                                </form>
                                                                <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form_pathao">
                                                                    @csrf
                                                                    <div class="m-1">
                                                                        <input type="hidden" id="all_id_excel_pathao" name="all_id_excel">
                                                                        <input type="hidden" name="courier" value="pathao">
                                                                        <button type="button" id="bulk_excel_pathao" class="btn btn-sm btn-success w-75">Pathao</button>
                                                                    </div>
                                                                </form>
                                                                <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form_paperfly">
                                                                    @csrf
                                                                    <div class="m-1">
                                                                        <input type="hidden" id="all_id_excel_paperfly" name="all_id_excel">
                                                                        <input type="hidden" name="courier" value="paperfly">
                                                                        <button type="button" id="bulk_excel_paperfly" class="btn btn-sm btn-info w-75">Paperfly</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                        <!-- export csv end -->






                            </div>

                        </div>

                                    <!--<div class="pb-1 mr-3 col-md-1 col-12">-->
                                    <!--    <form action="{{route('printChecketorders')}}" method="post" id="bulk_print_form" target="_blank">-->
                                    <!--        @csrf-->

                                            <!--<div>-->
                                            <!--    <input type="hidden" id="all_id_print" name="all_id_print">-->
                                            <!--    <button type="button" id="bulk_print" class="btn btn-info">Print Invoice</button>-->
                                            <!--</div>-->
                                    <!--    </form>-->
                                    <!--</div>-->

                                    <!--<div class="pb-1 col-md-1 col-12">-->
                                    <!--    <form action="{{route('labelChecketorders')}}" method="post" id="bulk_label_form" target="_blank">-->
                                    <!--        @csrf-->

                                    <!--        <div>-->
                                    <!--            <input type="hidden" id="all_id_label" name="all_id_label">-->
                                    <!--            <button type="button" id="bulk_label" class="btn btn-warning"><i class="fa-solid fa-file-invoice"></i></button>-->
                                    <!--        </div>-->
                                    <!--    </form>-->
                                    <!--</div>-->
                                    <!--<div class="pb-1 col-md-1 col-12">-->
                                    <!--    <form action="{{route('deleteChecketorders')}}" method="post" id="bulk_delete_form">-->
                                    <!--        @csrf-->
                                    <!--        <div>-->
                                    <!--            <input type="hidden" id="all_id" name="all_id">-->
                                    <!--            <button type="button" id="bulk_delete" class="btn btn-danger">Delete</button>-->
                                    <!--        </div>-->
                                    <!--    </form>-->
                                    <!--</div>-->
                                    <!--<div class="pb-1 col-md-1 col-12">-->
                                    <!--    <div class="mr-auto dropdown">-->
                                    <!--        <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                                    <!--            Export Invoice-->
                                    <!--        </button>-->
                                    <!--        <div class="dropdown-menu pd-10 wd-200" aria-labelledby="dropdownMenuButton">-->
                                    <!--            <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form">-->
                                    <!--                @csrf-->
                                    <!--                <div class="m-1">-->
                                    <!--                    <input type="hidden" id="all_id_excel" name="all_id_excel">-->
                                    <!--                    <input type="hidden" name="courier" value="Normal">-->
                                    <!--                    <button type="button" id="bulk_excel" class="btn btn-sm btn-dark w-75">Normal</button>-->
                                    <!--                </div>-->
                                    <!--            </form>-->
                                    <!--            <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form_redx">-->
                                    <!--                @csrf-->
                                    <!--                <div class="m-1">-->
                                    <!--                    <input type="hidden" id="all_id_excel_redx" name="all_id_excel">-->
                                    <!--                    <input type="hidden" name="courier" value="redx">-->
                                    <!--                    <button type="button" id="bulk_excel_redx" class="btn btn-sm btn-danger w-75">RedX</button>-->
                                    <!--                </div>-->
                                    <!--            </form>-->
                                    <!--            <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form_pathao">-->
                                    <!--                @csrf-->
                                    <!--                <div class="m-1">-->
                                    <!--                    <input type="hidden" id="all_id_excel_pathao" name="all_id_excel">-->
                                    <!--                    <input type="hidden" name="courier" value="pathao">-->
                                    <!--                    <button type="button" id="bulk_excel_pathao" class="btn btn-sm btn-success w-75">Pathao</button>-->
                                    <!--                </div>-->
                                    <!--            </form>-->
                                    <!--            <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form_paperfly">-->
                                    <!--                @csrf-->
                                    <!--                <div class="m-1">-->
                                    <!--                    <input type="hidden" id="all_id_excel_paperfly" name="all_id_excel">-->
                                    <!--                    <input type="hidden" name="courier" value="paperfly">-->
                                    <!--                    <button type="button" id="bulk_excel_paperfly" class="btn btn-sm btn-info w-75">Paperfly</button>-->
                                    <!--                </div>-->
                                    <!--            </form>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->



                            </div>
                <!-- Filter Second Row End -->



                 <!-- Filter Third Row Start -->
                <div class="row">







        {{--                            <div class="col-md-1 col-12">--}}
        {{--                                <form action="{{route('excelChecketorders')}}" method="post" id="bulk_excel_form">--}}
        {{--                                    @csrf--}}

        {{--                                    <div>--}}
        {{--                                        <input type="hidden" id="all_id_excel" name="all_id_excel">--}}
        {{--                                        <button type="button" id="bulk_excel" class="btn btn-warning">Export Invoice</button>--}}
        {{--                                    </div>--}}
        {{--                                </form>--}}
        {{--                                <!-- <a href="{{route('order.export')}}" class="btn btn-info">Export All</a> -->--}}
        {{--                            </div>--}}



                                </div>
                 <!-- Filter Third Row End-->


             </div>
            </div><!-- card -->
            <!-- ADD MORE CARD HERE -->
        </div><!-- accordion -->

    </div>

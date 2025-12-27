<div class="container-fluid mt-4">
        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"
                           aria-expanded="true" aria-controls="collapseTwo" class="tx-purple transition">
                            Order Filter
                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                        </a>
                    </h6>
                </div><!-- card-header -->

                <div id="collapseTwo" class="collapse " role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                    
                    <form action="{{ url('admin/order-management/filter-data') }}" method="get">
                        <div class="row bd-b">
                            <div class="col-md-2 col-12 pb-1">
                                   <select  name="status" id="statusabc" class="form-control">
                                        <option  value="">Select Status</option>
                                        <option value="1" @if(request('status') == 1) selected @endif >Processing</option>
                                        <option value="2" @if(request('status') == 2) selected @endif>Courier Entry</option>
                                        <option value="3" @if(request('status') == 3) selected @endif>On Hold</option>
                                        <option value="4" @if(request('status') == 4) selected @endif>Cancel</option>
                                        <option value="5" @if(request('status') == 5) selected @endif>Completed</option>
                                        <option value="6" @if(request('status') == 6) selected @endif>Pending Payment</option>
                                        <option value="7" @if(request('status') == 7) selected @endif>On Delivery</option>
                                        <option value="8" @if(request('status') == 8) selected @endif>No Response 1</option>
                                        <option value="9" @if(request('status') == 9) selected @endif>Printed Invoice</option>
                                        <option value="11" @if(request('status') == 11) selected @endif>Courier Hold</option>
                                        <option value="12" @if(request('status') == 12) selected @endif>Return</option>
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

                            <div class="col-md-2 col-12 pb-1">
                                <select  name="courier" id="courier" class="form-control">
                                    <option value="">Select Courier </option>
                                    <option @if(request('courier') == 1) selected @endif value="1">RedX</option>
                                    <option @if(request('courier') == 3) selected @endif value="3">Pathao</option>
                                    <option @if(request('courier') == 4) selected @endif value="4">Steadfast</option>
                                </select>
                           </div>
                           <div class="col-md-2 col-12 pb-1">
                                <select  name="paginate" id="paginate" class="form-control">
                                    <option @if(request('paginate') == 20) selected @endif value="20">20</option>
                                    <option @if(request('paginate') == 50) selected @endif value="50">50</option>
                                    <option @if(request('paginate') == 100) selected @endif value="100">100</option>
                                    <option @if(request('paginate') == 500) selected @endif value="500">500</option>
                                    <option @if(request('paginate') == 1000) selected @endif value="1000">1000</option>
                                </select>
                           </div>
                            <div class="col-md-2">
                                <div>
                                    <input type="date" id="fromDate" value="{{ request('fromDate') }}" name="fromDate" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div>
                                    <input type="date" id="toDate" value="{{ request('toDate') }}" name="toDate" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div>
                                   <button type="submit" class="btn btn-success btn-sm form-control">Filter</button>
                                </div>
                            </div>
                        
                        </div>
                        </form>
                        <div class="row pt-3 ">
                            <!-- <div class="col-md-1 mr-3 pb-1" >
                                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Today
                                    </button>
                                    <div class="dropdown-menu pd-10 wd-200" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{url('/admin/order-management/search-Date/0')}}">Today</a>
                                        <a class="dropdown-item" href="{{url('/admin/order-management/search-Date/1')}}">Yesterday</a>
                                        <a class="dropdown-item" href="{{url('/admin/order-management/search-Date/7')}}">Last 7 Days</a>
                                        <a class="dropdown-item" href="{{url('/admin/order-management/search-Date/15')}}">Last 15 Days</a>
                                        <a class="dropdown-item" href="{{url('/admin/order-management/search-Date/30')}}">Last 30 Days</a>
                                    </div>
                            </div> -->

                            <div class="col-md-6 pb-1">
                                <!-- <form action="{{route('order.search')}}" method="get" > -->

                                <!-- </form> -->
                            </div>


  <div class="col-md-2 col-12 pb-1">
                                <form action="{{route('selected_e_assign')}}" method="post" class="all_e_assign_form">
                                    @csrf
                                    <input type="hidden" class="all_e_assign" name="all_e_assign">
                                    <select name="e_assign"  class="form-control e_assign">
                                        <option value="">Select Employee</option>
                                        @foreach(App\Models\User::where('role',3)->get() as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            
                            
                            <div class="col-md-1 col-12 pb-1 mr-3">
                                <form action="{{route('printChecketorders')}}" method="post" id="bulk_print_form" target="_blank">
                                    @csrf

                                    <div>
                                        <input type="hidden" id="all_id_print" name="all_id_print">
                                        <button type="button" id="bulk_print" class="btn btn-info ">Print Invoice</button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="col-md-1 col-12 pb-1">
                                <form action="{{route('labelChecketorders')}}" method="post" id="bulk_label_form" target="_blank">
                                    @csrf

                                    <div>
                                        <input type="hidden" id="all_id_label" name="all_id_label">
                                        <button type="button" id="bulk_label" class="btn btn-warning "><i class="fa-solid fa-file-invoice"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-1 col-12 pb-1">
                                <form action="{{route('deleteChecketorders')}}" method="post" id="bulk_delete_form">
                                    @csrf
                                    <div>
                                        <input type="hidden" id="all_id" name="all_id">
                                        <button type="button" id="bulk_delete" class="btn btn-danger ">Delete</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-1 col-12 pb-1">
                                <div class="dropdown mr-auto">
                                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Export Invoice
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
                        </div>
                    </div>
                </div>
            </div><!-- card -->
            <!-- ADD MORE CARD HERE -->
        </div><!-- accordion -->

    </div>
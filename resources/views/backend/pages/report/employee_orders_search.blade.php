@extends('backend.layout.template')
@section('body-content')
 @php

$employee= request('employee');
$status= request('status');

$searchDays= request('searchDays');
$fromDate= request('fromDate');
$toDate= request('toDate');

@endphp

 
   <div class="container-fluid">
        <div id="accordion" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                           aria-expanded="true" aria-controls="collapseOne" class="tx-purple transition">
                            Order Statistics
                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                            &nbsp;&nbsp;
                        </a>
                    </h6>
                </div><!-- card-header -->

                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row  py-2">
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,0,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fas fa-cart-arrow-down tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1">Total Order</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders)}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div> 
                         
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,1,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    {{--                                    <i class="fa fa-spinner tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-sync-alt tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-primary">Total Processing</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',1))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            
                     
                             <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,2,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal "py-1>
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-truck tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning">Pending Delivery</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',2))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,7,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-truck tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning">On Delivery</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',7))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            
                        </div>
                        <div class="row py-2">
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,6,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-money-bill-wave  tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-danger"> Pending Payment</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',6))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,3,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fa-regular fa-circle-pause tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning">Total Hold</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',3))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,11,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fa-regular fa-circle-pause tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning"> Courier Hold</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',11))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            
                             
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,8,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-random tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-danger"> No Response 1</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',8))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            
                            


                        </div>
                        
                        <div class="row py-2">
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,9,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fas fa-random tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning">No Response 2</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',9))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            
                            
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,4,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="ion ion-close-circled tx-20 lh-0 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-red">Total Canceled</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',4))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,12,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fa-solid fa-rotate-left tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning">Total Return</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',12))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                             <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('employee_status',[$employee,5,$searchDays,$fromDate,$toDate])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fas fa-check-square tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-8 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-success">Total Completed</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders->where('status',5))}}</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                        </div>
                        
                        
                   
                        
                        
                        
                        
                        
                        
                        
                    </div>
                </div>
            </div><!-- card -->
            <!-- ADD MORE CARD HERE -->
        </div><!-- accordion -->

    </div>


 <div class="container-fluid">
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
                     <div class="col-md-5 col-12 mb-2">
                             <form action="{{route('employee_orders_search')}}" method="GET">
                                                 
                                <div class="form-row">
                                    <div class="form-group col-lg-6">
                                         <select name="employee" class="form-control select2">
                                            <option value="">Select Employee</option>
                                            @foreach(App\Models\User::where('role',3)->get() as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <select name="searchDays" required="required" class="form-control searchDays">
                                            <option value="00">Select Time</option>
                                            <option value="0">Today</option>
                                            <option value="1">Yesterday</option>
                                            <option value="7">Last 7 Days</option>
                                            <option value="15">Last 15 Days</option>
                                            <option value="30">Last 30 Days</option>
                                            <option value="100">Total</option>
                                            <option value="200">Custom</option>
                                        </select>
                                    </div>
                                    

                                </div>
                                

                                <div class="form-row onDate"  >
                                    <div class="d-flex">
                                        <div>
                                            <input type="date" name="fromDate" class="form-control">
                                        </div>
                                        <div>
                                            <input type="date" name="toDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                  
                                 
                                <div class="form-row">

                                    <div class="mt-2">
                                        <input type="submit" value="search" class="btn btn-primary float-right" data-loading-text="Loading...">
                                    </div>
                                </div>
                            </form>
                         </div>
                     <div class="row pb-3">
                         
                         <div class="col-md-3 mr-2  mb-2">
                             <form action="{{route('order.search.input')}}" method="get" >

                                 <div class="row d-flex">
                                     <div class="col-md-9"><input name="search_input" type="text" class="form-control" placeholder="Search Orders"></div>
                                     <div class="col-md-3"><button type="submit" class="btn btn-warning ">Search</button></div>
                                 </div>
                             </form>
                         </div>

                         <div class="col-md-3 col-12 mb-2">
                             <form action="{{route('selected_status')}}" method="post" id="all_status_form">
                                 @csrf

                                 <input type="hidden" id="all_status" name="all_status">
                                 <select name="status" id="status" class="form-control">
                                     <option value="">Select Status</option>
                                     <option value="1">Processing</option>
                                     <option value="2">Pending Delivery</option>
                                     <option value="3">On Hold</option>
                                     <option value="4">Cancel</option>
                                     <option value="5">Completed</option>
                                     <option value="6">Pending Payment</option>
                                 </select>
                             </form>
                         </div>

                         <div class="col-md-3 col-12 mb-2">
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

                         <div class="col-md-2 col-12 mb-2">
                             <form action="{{route('printChecketorders')}}" method="post" id="bulk_print_form" target="_blank">
                                 @csrf

                                 <div>
                                     <input type="hidden" id="all_id_print" name="all_id_print">
                                     <button type="button" id="bulk_print" class="btn btn-warning ">Print Invoice</button>
                                 </div>
                             </form>
                         </div>
                         <div class="col-md-1 col-12 mb-2">
                             <form action="{{route('deleteChecketorders')}}" method="post" id="bulk_delete_form">
                                 @csrf

                                 <div>
                                     <input type="hidden" id="all_id" name="all_id">
                                     <button type="button" id="bulk_delete" class="btn btn-danger ">Delete</button>
                                 </div>
                             </form>
                         </div>
                         <div class="col-md-1 col-12 mb-2">
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







    <div class="br-pagebody">
        <div class="br-section-wrapper">

            <div class="row justify-content-center">
                <span class="tx-20 text-center mt-1" >All Processing Orders</span>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div style="overflow-x: auto;">

                        <table class="table mg-b-0 table-bordered table-striped" >
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" class="chkCheckAll">
                                </th>
                                <th scope="col">#Sl</th>
                                <th scope="col">Invoice</th>
                                <th scope="col">Customer Info </th>
                                <th scope="col">Products </th>
                                <th scope="col">Total </th>
                                <th scope="col">Courier </th>
                                  <th scope="col">Courier Status</th>
                                <th scope="col">Date </th>
                                <th scope="col">Status </th>
                                <th scope="col">Note </th>
                                <th scope="col">Asigned </th>
                                <th scope="col">Action </th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @php $i=1 @endphp
                            @foreach( $orders as $order )

                                    <?php
                                    $check_duplicate = count($total_orders->where('phone',$order->phone))
                                    ?>
                                    
                                 
                                <tr >
                                    <th scope="row">
                                        <input type="checkbox" class="sub_chk" data-id="{{$order->id}}">
                                    </th>
                                    <td>{{$i}}</td>
                                    <td>{{$order->id}} 
                                    @if($order->coming=='1')
                                          
                                            <span class="tx-10 font-weight-bold text-white bg-success pd-4">Landing</span>
                                        @endif
                                        </td>
                                    <td class='{{$check_duplicate>1?"bg-danger-light":""}}'>
                                        <p class="mb-0">{{ $order->name ?? "N/A" }}</p>
                                        <p class="mb-0">
                                            <a href="tel:{{ $order->phone ?? "N/A" }}"><strong>{{ $order->phone ?? "N/A" }}</strong></a>
                                        </p>
                                        <p class="mb-0">{{ $order->address ?? "N/A" }}</p>
                                    </td>
                                    <td>

                                        @foreach($carts->where('order_id',$order->id) as $cart)

                                            @if(!is_null($cart->product))

                                                                                                     <a href="{{route('details',$cart->product->slug)}}" target="_blank"><p> <span class="tx-10 font-weight-bold text-white bg-crystal-clear pd-4">{{$cart->quantity}}</span>  {{$cart->product->name }}</p></a>

                                            @else
                                                N/A
                                            @endif

                                        @endforeach

                                    </td>
                                    <td>{{ $order ->total }}</td>
<td> {!!@$order->my_courier!!} </td>
 <td> {{ $order ->courier_status }} </td>
                                    <td>
                                        {{date('d M, Y',strtotime($order->created_at))}}<br>
                                            {{date('h:i:s A',strtotime($order->created_at))}}
                                    </td>
                                     <td>
                                            <div class="btn-group">
                                                @if($order->status==1)
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Processing</button>
                                                @elseif($order->status==2)
                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pending Delivery</button>
                                                @elseif($order->status==3)
                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> On Hold</button>
                                                @elseif($order->status==4)
                                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cancel</button>
                                                @elseif($order->status==5)
                                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Completed</button>
                                                @elseif($order->status==6)
                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">pending payment</button>
                                                @elseif($order->status==7)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">on delivery</button>
                                                @elseif($order->status==7)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">on delivery</button>
                                                @elseif($order->status==7)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">on delivery</button>
                                                @elseif($order->status==8)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">No Response 1</button>
                                                @elseif($order->status==9)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">No Response 2</button>
                                                @elseif($order->status==11)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Courier Hold</button>
                                                @elseif($order->status==12)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Return</button>
                                                @endif


                                                <div class="dropdown-menu">
                                                    @if($order->status!=1)
                                                    <a class="dropdown-item" href="{{route('order.statusChange', [1,$order->id])}}">Processing</a>
                                                    @endif
                                                    @if($order->status!=2)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [2,$order->id])}}">Pending Delivery</a>
                                                    @endif
                                                    @if($order->status!=3)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [3,$order->id])}}">On Hold</a>
                                                    @endif
                                                    @if($order->status!=4)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [4,$order->id])}}">Cancel</a>
                                                    @endif
                                                    @if($order->status!=5)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [5,$order->id])}}">Completed</a>
                                                    @endif

                                                    @if($order->status!=6)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [6,$order->id])}}">Pending Payment</a>
                                                    @endif
                                                    @if($order->status!=7)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [7,$order->id])}}">On Delivery</a>
                                                    @endif
                                                    @if($order->status!=8)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [8,$order->id])}}">No Response 1</a>
                                                    @endif
                                                    @if($order->status!=9)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [9,$order->id])}}">No Response 2</a>
                                                    @endif
                                                  
                                                    @if($order->status!=11)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [11,$order->id])}}">Courier Hold</a>
                                                    @endif
                                                    @if($order->status!=12)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [12,$order->id])}}">Return</a>
                                                    @endif




                                                </div>
                                            </div>
                                        </td>
                                    <td>
                                         <div class="list-group mt-1">
                                                <div class="list-group-item align-items-center justify-content-start">
                                                    <div class="">
                                                        <p class="mg-b-0 tx-inverse tx-medium">{{$order->order_note ??"N/A"}}</p>
                                                    </div>
                                                    <div class="">
                                                        <a href="" data-toggle="modal" data-target="#noted{{$order->id}}" class="btn btn-outline-primary btn-icon">
                                                            <div class="tx-10"><i class="fa-solid fa-pen-to-square tx-14"></i></div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- assign modaal start -->
                                            <div class="modal fade" id="noted{{$order->id}}" aria-labelledby="exampleModalLabel2" aria-hidden="true" style="overflow: hidden">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header ">
                                                            <h5 class="modal-title " id="exampleModalLabel2">Note </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body ">
                                                            <div class="row justify-content-center">
                                                                <div class="col-lg-12">
                                                                    <form action="{{ route('order.noted_edit', $order->id)}}" method="POST" class="assign_f_button">
                                                                        @csrf
                                                                        <div class="col-md-12">
                                                                            <textarea name="order_noted" id="order_noted" >{{$order->order_note ??"N/A"}} </textarea>
                                                                            <input type="submit" value="Save" name="delete" class="btn btn-success btn-block mt-2 noted_e_button" >
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- assign modaal end -->
                                            
                                    </td>
                                    <td>



                                        <div class="list-group mt-1">
                                            <div class="list-group-item align-items-center justify-content-start">
                                                <div class="">
                                                    <p class="mg-b-0 tx-inverse tx-medium">{{$order->user->name ?? "N/A"}}</p>
                                                </div>
                                                <div class="">
                                                    <a href="" data-toggle="modal" data-target="#assign{{$order->id}}" class="btn btn-outline-primary btn-icon">
                                                        <div class="tx-10"><i class="fa-solid fa-pen-to-square tx-14"></i></div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- assign modaal start -->
                                        <div class="modal fade" id="assign{{$order->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header ">
                                                        <h5 class="modal-title " id="exampleModalLabel">Assign User </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                    </div>
                                                    <div class="modal-body ">
                                                        <div class="row justify-content-center">
                                                            <div class="col-lg-6">
                                                                <form action="{{ route('order.assign_edit', $order->id)}}" method="POST" class="assign_f_button">
                                                                    @csrf

                                                                    <select name="order_assign" class="form-control">
                                                                        @foreach($users as $user)

                                                                            <option value="{{$user->id}}" @if($user->id==$order->order_assign)selected @endif>{{$user->name}}</option>
                                                                        @endforeach

                                                                    </select>
                                                                    <input type="submit" value="Assign" name="delete" class="btn btn-success btn-block mt-2 assign_e_button" >
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- assign modaal end -->
                                    </td>
                                    <td class="action-button">


                                        <div class="list-group ">
                                            <div class="list-group-item d-grid">

                                                <a href="{{route('order.edit', $order->id)}}" class="btn  btn-icon">

                                                    <i class="fa-solid fa-pen-to-square tx-17"></i>

                                                </a>
                                                <a href="" data-toggle="modal" data-target="#delete{{$order->id}}" class="btn  btn-icon">

                                                    <i class="text-danger fa-solid fa-delete-left tx-17"></i>

                                                </a>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="delete{{$order->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure to want to delete this Order?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('order.destroy', $order->id)}}" method="POST">
                                                            @csrf


                                                            <input type="submit" value="Confirm" name="delete" class="btn btn-danger" >
                                                        </form>
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @php $i++ @endphp
                            @endforeach

                            </tbody>
                            @if($orders->count()==0)

                                <div class="alert alert-danger">sorry! No Orders Found.</div>
                            @endif

                        </table>
                    </div>
                    <div class="ht-80 bd d-flex align-items-center justify-content-center">
                        <ul class="pagination pagination-basic pagination-danger mg-b-0">
                            <li>{{$orders->withQueryString()->links()}}</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>



@endsection


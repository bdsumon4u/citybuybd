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
                            <span class="tx-14"> [ Last Order: @if($last) {{$last->created_at->diffForHumans()}}@endif ] </span>
                        </a>
                    </h6>
                </div><!-- card-header -->

                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row  py-2">
                            <div class="col-lg-3 col-6 pb-1">
                                
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fas fa-cart-arrow-down tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                      
                                            <div class="col-md-6 tx-center d-flex align-items-center" >
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-14  tx-semibold pb-1">Total Order </h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($total_orders)}}</h4>
                                                </div>
                                            </div>
                                            
                                          
                                            
                                            
                                        </div>
                                    </div>
                          
                            </div>
                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('manager.order.management',['processing'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fas fa-sync-alt tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                       
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-14 tx-semibold text-primary"> Processing</h6>
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
                                <a href="{{route('manager.order.management',['pending'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal "py-1>
                                                <div>
                                                    <i class="fas fa-truck tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-12  tx-semibold pb-1 text-warning">Pending Delivery</h6>
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
                                <a href="{{route('manager.order.management',['ondelivery'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-truck tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
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
                                <a href="{{route('manager.order.management',['pending_p'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-money-bill-wave  tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-12  tx-semibold pb-1 text-danger"> Pending Payment</h6>
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
                                <a href="{{route('manager.order.management',['hold'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fa-regular fa-circle-pause tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning"> Hold</h6>
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
                                <a href="{{route('manager.order.management',['courier_hold'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fa-regular fa-circle-pause tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
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
                                <a href="{{route('manager.order.management',['noresponse1'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-random tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-14  tx-semibold pb-1 text-danger"> No Response 1</h6>
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
                                <a href="{{route('manager.order.management',['noresponse2'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fas fa-random tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-14  tx-semibold pb-1 text-warning">No Response 2</h6>
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
                                <a href="{{route('manager.order.management',['cancel'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="ion ion-close-circled tx-20 lh-0 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-red"> Canceled</h6>
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
                                <a href="{{route('manager.order.management',['return'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fa-solid fa-rotate-left tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning"> Return</h6>
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
                                <a href="{{route('manager.order.management',['completed'])}}">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal py-1">
                                                <div>
                                                    <i class="fas fa-check-square tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="col-md-12 text-center pt-2">
                                                    <h6 class="tx-16  tx-semibold pb-1 text-success"> Completed</h6>
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
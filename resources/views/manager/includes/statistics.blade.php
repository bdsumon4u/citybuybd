  <div class="container-fluid">
        <div id="accordion" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                           aria-expanded="true" aria-controls="collapseOne" class="transition tx-purple">
                            Order Statistics <span class="tx-14"> [ Last Order: @if($last) {{$last->created_at->diffForHumans()}}@endif ] </span>
                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                            &nbsp;&nbsp;
                            <span class="tx-14"> </span>
                        </a>
                    </h6>
                </div><!-- card-header -->

                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="py-2 row">
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing('')">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fas fa-cart-arrow-down tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>

                                            <div class="col-md-6 tx-center d-flex align-items-center" >
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-14 tx-semibold">Total Order </h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="total_count">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(1)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fas fa-sync-alt tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>

                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="tx-14 tx-semibold text-primary"> Processing</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="processing">0</h4>
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
                             <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(2)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal"py-1>
                                                <div>
                                                    <i class="fas fa-truck tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>

                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-12 tx-semibold text-warning">Pending Delivery</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="pending">0</h4>
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
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(7)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-truck tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-warning">On Delivery</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="ondelivery">0</h4>
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
                        <div class="py-2 row">
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(6)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-money-bill-wave tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-12 tx-semibold text-danger"> Pending Payment</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="pending_p">0</h4>
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
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(3)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fa-regular fa-circle-pause tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-warning"> Hold</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="hold">0</h4>
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

                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(11)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fa-regular fa-circle-pause tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-warning"> Courier Hold</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="courier_hold">0</h4>
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


                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(8)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    {{--                                    <i class="fas fa-tasks tx-30 lh-1 tx-white op-9"></i>--}}
                                                    <i class="fas fa-random tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-14 tx-semibold text-danger"> No Response 1</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="noresponse1">0</h4>
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

                        <div class="py-2 row">
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(9)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fas fa-random tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-14 tx-semibold text-warning">No Response 2</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="noresponse2">0</h4>
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
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(4)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="ion ion-close-circled tx-20 lh-0 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-red"> Canceled</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="cancel">0</h4>
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
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(12)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fa-solid fa-rotate-left tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-warning"> Return</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="return">0</h4>
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
                             <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(5)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fas fa-check-square tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-success"> Completed</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="completed">0</h4>
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

                        <div class="py-2 row">
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(16)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fas fa-check-circle tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-success">Total Delivery</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="total_delivery">0</h4>
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
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(13)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fas fa-truck-loading tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-warning">Partial Delivery</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="partial_delivery">0</h4>
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
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(14)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fas fa-money-check-alt tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-warning">Paid Return</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="paid_return">0</h4>
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
                            <div class="pb-1 col-lg-3 col-6">
                                <a href="#" onclick="Processing(15)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="row no-gutters">
                                            <div class="py-1 col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                                                <div>
                                                    <i class="fas fa-box-open tx-20 lh-1 tx-white op-9"></i>
                                                </div>
                                            </div>
                                            <!-- col-6 -->
                                            <div class="col-md-6 tx-center d-flex align-items-center">
                                                <div class="pt-2 text-center col-md-12">
                                                    <h6 class="pb-1 tx-16 tx-semibold text-danger">Stock Out</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="stock_out">0</h4>
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

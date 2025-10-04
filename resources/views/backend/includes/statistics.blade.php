  <div class="container-fluid">
        <div id="accordion" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion" style="background: linear-gradient(90deg, #27ae60, #1a6abe); color: #fff;"
                        href="#collapseOne"
                           aria-expanded="true" aria-controls="collapseOne" class="tx-purple transition">
                            Order Statistics <span class="tx-14"> [ Last Order: @if($last) {{ $last?->created_at?->diffForHumans() ?? 'N/A' }}@endif ] </span>
                            <i class="fa-duotone fa-arrow-down-arrow-up"></i>
                            &nbsp;&nbsp;
                            <span class="tx-14"> </span>
                        </a>
                    </h6>
                </div><!-- card-header -->
                
                <style>
                   @media (max-width: 767px) {
                       .col-3 {
                        padding-left: 8px;
                        padding-right: 8px;
                    }
                    /* Fix card height */
                    .card.shadow-base {
                        height: 140px;   /* static height */
                    }

                    /* Force inner row to fill the card */
                    .card .row.no-gutters {
                        height: 100%;
                        }

                    .card .w-100 {
                       border-bottom: none !important;
                       border-left: none !important;
                       border-right: none !important;
                    }
                    }
                    
                    .badge.position-absolute {
                        position: absolute !important;
                        right: 0px;
                        font-size: 13px;
                        font-weight: 400;
                    }
                    
                </style>

                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block pd-5" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                        <div class="row  py-2">
                          
                                <div class="col-3 pb-1">
                                <a href="#" onclick="Processing('')">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #6f42c1">
                                                    <i class="fas fa-cart-arrow-down fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: #6f42c1">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1" >Total Order</h6>
                                                        <h4 class="fw-bold mb-0" id="total_count">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute total_count_percent">0%</span>

                                    </div>
                                </a>
                            </div>
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(1)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #17a2b8;">
                                                    <i class="fas fa-sync-alt fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #17a2b8, #17a2b8);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1" >Processing</h6>
                                                        <h4 class="fw-bold mb-0" id="processing">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute processing_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                             <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(2)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #11aa0c;">
                                                    <i class="fas fa-truck fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #11aa0c, #11aa0c);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1" >Pending Delivery</h6>
                                                        <h4 class="fw-bold mb-0" id="pending">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute pending_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(7)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #0d6efd;">
                                                    <i class="fas fa-truck fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #0d6efd, #0d6efd);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">On Delivery</h6>
                                                        <h4 class="fw-bold mb-0" id="ondelivery">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute ondelivery_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(6)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #dc3545;">
                                                    <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">Pending Payment</h6>
                                                        <h4 class="fw-bold mb-0" id="pending_p">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute pending_p_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(3)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #ffc107;">
                                                    <i class="fa-regular fa-circle-pause fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #ffc107, #ffc107);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">Hold</h6>
                                                        <h4 class="fw-bold mb-0" id="hold">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute hold_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(11)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background:  #20c997">
                                                    <i class="fa-regular fa-circle-pause fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: #20c997">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">Courier Hold</h6>
                                                        <h4 class="fw-bold mb-0" id="courier_hold">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute courier_hold_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            
                             
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(8)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #dc3545;">
                                                    <i class="fas fa-random fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">No Response 1</h6>
                                                        <h4 class="fw-bold mb-0" id="noresponse1">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute noresponse1_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                        </div>
                        
                        <div class="row py-2">
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(9)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #ffc107;">
                                                    <i class="fas fa-random fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #ffc107, #ffc107);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">No Response 2</h6>
                                                        <h4 class="fw-bold mb-0" id="noresponse2">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute noresponse2_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(4)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #dc3545;">
                                                    <i class="ion ion-close-circled fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">Canceled</h6>
                                                        <h4 class="fw-bold mb-0" id="cancel">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute cancel_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                            <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(12)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background:  #e83e8c;">
                                                    <i class="fa-solid fa-rotate-left fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: #e83e8c">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">Return</h6>
                                                        <h4 class="fw-bold mb-0" id="return">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute return_percent">0%</span>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->
                                </a>
                            </div>
                             <div class="col-3 pb-1">
                                <a href="#" onclick="Processing(5)">
                                    <div class="card shadow-base bd-0  rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="row no-gutters shadow rounded overflow-hidden">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #28a745;">
                                                    <i class="fas fa-check-square fa-2x text-white"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #28a745, #28a745);">
                                                    <div class="w-100 text-center py-3 text-white" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="text-uppercase mb-1">Completed</h6>
                                                        <h4 class="fw-bold mb-0" id="completed">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute completed_percent">0%</span>
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
  <div class="px-1 container-fluid">
        <div id="accordion" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mg-b-0">
                        <a data-toggle="collapse" data-parent="#accordion" style="background: linear-gradient(90deg, #27ae60, #1a6abe); color: #fff;"
                        href="#collapseOne"
                           aria-expanded="true" aria-controls="collapseOne" class="transition tx-purple">
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
                        height: 110px;   /* static height */
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
                        <div class="py-2 row stats-grid">
                            <!-- Cards: Total Order, Processing, Courier Entry, Printed Invoice, Total Courier, On Delivery, Pending Payment, Hold, Courier Hold, No Response 1, No Response 2, Canceled, Return, Pending Return, Completed, Partial Delivery, Paid Return, Stock Out -->
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing('')">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #6f42c1">
                                                    <i class="text-white fas fa-cart-arrow-down fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: #6f42c1">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase" >Total Order</h6>
                                                        <h4 class="mb-0 fw-bold" id="total_count">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute total_count_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(1)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #17a2b8;">
                                                    <i class="text-white fas fa-sync-alt fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #17a2b8, #17a2b8);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase" >Processing</h6>
                                                        <h4 class="mb-0 fw-bold" id="processing">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute processing_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(2)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #11aa0c;">
                                                    <i class="text-white fas fa-truck fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #11aa0c, #11aa0c);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase" >Courier Entry</h6>
                                                        <h4 class="mb-0 fw-bold" id="pending">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute pending_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(17)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #ffc107;">
                                                    <i class="text-white fas fa-random fa-2x"></i>
                                                </div>

                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #ffc107, #ffc107);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Printed Invoice</h6>
                                                        <h4 class="mb-0 fw-bold" id="printed_invoice">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute printed_invoice_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(16)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #28a745;">
                                                    <i class="text-white fas fa-check-circle fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #28a745, #28a745);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Total Courier</h6>
                                                        <h4 class="mb-0 fw-bold" id="total_delivery">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute total_delivery_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(7)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #0d6efd;">
                                                    <i class="text-white fas fa-truck fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #0d6efd, #0d6efd);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">On Delivery</h6>
                                                        <h4 class="mb-0 fw-bold" id="ondelivery">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute ondelivery_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(6)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #dc3545;">
                                                    <i class="text-white fas fa-money-bill-wave fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Pending Payment</h6>
                                                        <h4 class="mb-0 fw-bold" id="pending_p">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute pending_p_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(3)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #ffc107;">
                                                    <i class="text-white fa-regular fa-circle-pause fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #ffc107, #ffc107);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Hold</h6>
                                                        <h4 class="mb-0 fw-bold" id="hold">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute hold_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(11)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background:  #20c997">
                                                    <i class="text-white fa-regular fa-circle-pause fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: #20c997">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Courier Hold</h6>
                                                        <h4 class="mb-0 fw-bold" id="courier_hold">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute courier_hold_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(8)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #dc3545;">
                                                    <i class="text-white fas fa-random fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">No Response 1</h6>
                                                        <h4 class="mb-0 fw-bold" id="noresponse1">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute noresponse1_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(9)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #ffc107;">
                                                    <i class="text-white fas fa-random fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #ffc107, #ffc107);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">No Response 2</h6>
                                                        <h4 class="mb-0 fw-bold" id="noresponse2">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute noresponse2_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(4)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                        <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #dc3545;">
                                                    <i class="text-white ion ion-close-circled fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Canceled</h6>
                                                        <h4 class="mb-0 fw-bold" id="cancel">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute cancel_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(18)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #6c757d;">
                                                    <i class="text-white fas fa-hourglass-half fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #6c757d, #6c757d);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Pending Return</h6>
                                                        <h4 class="mb-0 fw-bold" id="pending_return">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute pending_return_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(14)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #6f42c1;">
                                                    <i class="text-white fas fa-money-check-alt fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #6f42c1, #6f42c1);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Paid Return</h6>
                                                        <h4 class="mb-0 fw-bold" id="paid_return">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute paid_return_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(12)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background:  #e83e8c;">
                                                    <i class="text-white fa-solid fa-rotate-left fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: #e83e8c">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Return</h6>
                                                        <h4 class="mb-0 fw-bold" id="return">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute return_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(5)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #28a745;">
                                                    <i class="text-white fas fa-check-square fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #28a745, #28a745);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Completed</h6>
                                                        <h4 class="mb-0 fw-bold" id="completed">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute completed_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(13)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #fd7e14;">
                                                    <i class="text-white fas fa-truck-loading fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #fd7e14, #fd7e14);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Partial Delivery</h6>
                                                        <h4 class="mb-0 fw-bold" id="partial_delivery">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute partial_delivery_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 col-3 col-md-2">
                                <a href="#" onclick="Processing(15)">
                                    <div class="card shadow-base bd-0 rounded-right">
                                       <div class="card shadow-base bd-0 rounded-right">
                                            <div class="overflow-hidden rounded shadow row no-gutters">
                                                <!-- Icon Section -->
                                                <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #dc3545;">
                                                    <i class="text-white fas fa-box-open fa-2x"></i>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                    <div class="py-0 text-center text-white py-md-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                        <h6 class="mb-1 text-uppercase">Stock Out</h6>
                                                        <h4 class="mb-0 fw-bold" id="stock_out">0</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge badge-primary position-absolute stock_out_percent">0%</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- card -->
            <!-- ADD MORE CARD HERE -->
        </div><!-- accordion -->

    </div>

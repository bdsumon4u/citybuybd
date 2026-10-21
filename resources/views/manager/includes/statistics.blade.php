  <div class="px-1 container-fluid">
      <div id="accordion" class="accordion accordion-head-colored accordion-primary" role="tablist"
          aria-multiselectable="true">
          <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center py-2 px-3" role="tab" id="headingOne"
                  style="background: linear-gradient(90deg, #27ae60, #1a6abe); color: #fff;">
                  <h6 class="mg-b-0 flex-grow-1">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                          aria-expanded="true" aria-controls="collapseOne" class="text-white d-inline-flex align-items-center"
                          style="text-decoration: none; font-weight: 600;">
                          <span>Order Statistics <span class="tx-14 font-weight-normal"> [ Last Order: @if ($last)
                                  {{ $last?->created_at?->diffForHumans() ?? 'N/A' }}
                              @endif ] </span></span>
                          <i class="fa-duotone fa-arrow-down-arrow-up ml-2"></i>
                      </a>
                  </h6>
                  <div class="d-flex align-items-center" onclick="event.stopPropagation();">
                      <span class="badge badge-light tx-11 mr-2 d-none d-lg-inline-block text-dark shadow-sm" style="background: rgba(255,255,255,0.92); font-weight: 600; padding: 4px 8px; border-radius: 4px;">
                          <i class="fas fa-arrows-alt text-primary mr-1"></i> Drag cards to reorder
                      </span>
                      <div class="dropdown">
                          <button class="btn btn-xs btn-outline-light dropdown-toggle text-white py-1 px-2 font-weight-bold" type="button" id="statsSortMenuBtn" onclick="toggleStatsSortMenu(event)" style="font-size: 11px; border-color: rgba(255,255,255,0.7); background: rgba(0,0,0,0.2); border-radius: 4px;">
                              <i class="fas fa-sort-amount-down mr-1"></i> Sort / Reset
                          </button>
                          <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="statsSortMenuBtn" style="font-size: 12px; min-width: 190px; z-index: 1050;">
                              <h6 class="dropdown-header text-uppercase tx-10 text-muted px-3 py-1 mb-0">Sort Options</h6>
                              <a class="dropdown-item py-2 px-3" href="javascript:void(0)" onclick="sortStatCards('count-desc'); toggleStatsSortMenu(event);">
                                  <i class="fas fa-sort-numeric-down mr-2 text-primary"></i> Highest Count First
                              </a>
                              <a class="dropdown-item py-2 px-3" href="javascript:void(0)" onclick="sortStatCards('count-asc'); toggleStatsSortMenu(event);">
                                  <i class="fas fa-sort-numeric-up mr-2 text-info"></i> Lowest Count First
                              </a>
                              <a class="dropdown-item py-2 px-3" href="javascript:void(0)" onclick="sortStatCards('name-asc'); toggleStatsSortMenu(event);">
                                  <i class="fas fa-sort-alpha-down mr-2 text-success"></i> Name (A to Z)
                              </a>
                              <div class="dropdown-divider my-1"></div>
                              <a class="dropdown-item py-2 px-3 text-danger font-weight-bold" href="javascript:void(0)" onclick="resetStatCardOrder(); toggleStatsSortMenu(event);">
                                  <i class="fas fa-undo mr-2"></i> Reset Default Order
                              </a>
                          </div>
                      </div>
                  </div>
              </div><!-- card-header -->

              <style>
                  @media (max-width: 767px) {
                      .col-3 {
                          padding-left: 8px;
                          padding-right: 8px;
                      }

                      /* Fix card height */
                      .card.shadow-base {
                          height: 110px;
                          /* static height */
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

                  /* Desktop: 7 cards per row */
                  @media (min-width: 768px) {
                      .stats-grid>div[class*="col-md-2"] {
                          flex: 0 0 calc(100% / 7);
                          max-width: calc(100% / 7);
                      }
                  }

                  .badge.position-absolute {
                      position: absolute !important;
                      right: 0px;
                      font-size: 13px;
                      font-weight: 400;
                  }

                  #headingOne,
                  #accordion,
                  #accordion > .card {
                      overflow: visible !important;
                  }

                  .dropdown-menu.show,
                  .dropdown.show > .dropdown-menu {
                      display: block !important;
                  }

                  #headingOne .dropdown {
                      position: relative;
                  }

                  #headingOne .dropdown-menu {
                      position: absolute;
                      top: 100%;
                      right: 0;
                      left: auto;
                      z-index: 99999 !important;
                      background-color: #ffffff;
                      border: 1px solid rgba(0, 0, 0, 0.15);
                      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25) !important;
                      border-radius: 4px;
                  }

                  /* Drag and Drop Card Reordering Styles */
                  .stat-card-item {
                      cursor: grab;
                      cursor: -webkit-grab;
                      user-select: none;
                      -webkit-user-select: none;
                      touch-action: manipulation;
                      position: relative;
                      transition: transform 0.15s ease, box-shadow 0.15s ease;
                  }
                  .stat-card-item:active {
                      cursor: grabbing;
                      cursor: -webkit-grabbing;
                  }
                  .stat-card-item a {
                      -webkit-user-drag: none;
                      user-drag: none;
                      display: block;
                      text-decoration: none;
                      color: inherit;
                  }
                  .stat-card-item .stat-drag-handle {
                      position: absolute;
                      top: 5px;
                      left: 8px;
                      color: rgba(255, 255, 255, 0.55);
                      font-size: 10px;
                      opacity: 0;
                      transition: opacity 0.2s ease;
                      z-index: 10;
                      pointer-events: none;
                  }
                  .stat-card-item:hover .stat-drag-handle {
                      opacity: 0.95;
                  }
                  .stat-card-ghost {
                      opacity: 0.35 !important;
                      transform: scale(0.96) !important;
                      border: 2px dashed #1a6abe !important;
                      border-radius: 4px;
                  }
                  .stat-card-chosen {
                      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35) !important;
                      transform: scale(1.02) !important;
                      z-index: 9999 !important;
                  }
                  .stat-card-drag {
                      opacity: 0.95 !important;
                  }
              </style>

              <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                  <div class="card-block pd-2" style="background-color: #e9ecef;border: 1px solid lightgrey;">
                      <div class="py-2 row stats-grid" id="statsCardGrid">
                          <!-- Cards: Total Order, Processing, Courier Entry, Printed Invoice, Total Courier, On Delivery, Pending Payment, Hold, Courier Hold, No Response 1, No Response 2, Canceled, Stock Out, Paid Return, Pending Return, Return, Partial Delivery, Delivery, Delay, Double, Bonus Orders -->
                          
                          <!-- Card: Total Order -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="total_count">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing('')" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #6f42c1">
                                                  <i class="text-white fas fa-cart-arrow-down fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: #6f42c1">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Total Order</h6>
                                                      <h4 class="mb-0 fw-bold" id="total_count">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span class="badge badge-primary position-absolute total_count_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Processing -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="processing">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(1)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #17a2b8;">
                                                  <i class="text-white fas fa-sync-alt fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #17a2b8, #17a2b8);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Processing</h6>
                                                      <h4 class="mb-0 fw-bold" id="processing">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span class="badge badge-primary position-absolute processing_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Courier Entry -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="pending">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(2)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #11aa0c;">
                                                  <i class="text-white fas fa-truck fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #11aa0c, #11aa0c);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Courier Entry</h6>
                                                      <h4 class="mb-0 fw-bold" id="pending">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span class="badge badge-primary position-absolute pending_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Printed Invoice -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="printed_invoice">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(17)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #ffc107;">
                                                  <i class="text-white fas fa-random fa-2x"></i>
                                              </div>

                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #ffc107, #ffc107);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Printed Invoice</h6>
                                                      <h4 class="mb-0 fw-bold" id="printed_invoice">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span
                                          class="badge badge-primary position-absolute printed_invoice_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Total Courier -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="total_delivery">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(16)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #28a745;">
                                                  <i class="text-white fas fa-check-circle fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #28a745, #28a745);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Total Courier</h6>
                                                      <h4 class="mb-0 fw-bold" id="total_delivery">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span
                                          class="badge badge-primary position-absolute total_delivery_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: On Delivery -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="ondelivery">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(7)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #0d6efd;">
                                                  <i class="text-white fas fa-truck fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #0d6efd, #0d6efd);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: Pending Payment -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="pending_p">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(6)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #dc3545;">
                                                  <i class="text-white fas fa-money-bill-wave fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: No Response 1 -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="noresponse1">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(8)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #0dcaf0;">
                                                  <i class="text-white fas fa-random fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #0dcaf0, #0dcaf0);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: No Response 2 -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="noresponse2">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(9)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #fd7e14;">
                                                  <i class="text-white fas fa-random fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #fd7e14, #fd7e14);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: Hold -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="hold">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(3)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #6f42c1;">
                                                  <i class="text-white fa-regular fa-circle-pause fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #6f42c1, #6f42c1);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: Courier Hold -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="courier_hold">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(11)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background:  #20c997">
                                                  <i class="text-white fa-regular fa-circle-pause fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: #20c997">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Courier Hold</h6>
                                                      <h4 class="mb-0 fw-bold" id="courier_hold">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span
                                          class="badge badge-primary position-absolute courier_hold_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Canceled -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="cancel">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(4)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #dc3545;">
                                                  <i class="text-white ion ion-close-circled fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #dc3545, #dc3545);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: Stock Out -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="stock_out">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(15)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #343a40;">
                                                  <i class="text-white fas fa-box-open fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #343a40, #343a40);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: Paid Return -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="paid_return">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(14)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #6f42c1;">
                                                  <i class="text-white fas fa-money-check-alt fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #6f42c1, #6f42c1);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: Pending Return -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="pending_return">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(18)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #6c757d;">
                                                  <i class="text-white fas fa-hourglass-half fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #6c757d, #6c757d);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Pending Return</h6>
                                                      <h4 class="mb-0 fw-bold" id="pending_return">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span
                                          class="badge badge-primary position-absolute pending_return_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Return -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="return">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(12)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background:  #e83e8c;">
                                                  <i class="text-white fa-solid fa-rotate-left fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: #e83e8c">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
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

                          <!-- Card: Partial Delivery -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="partial_delivery">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(13)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #fd7e14;">
                                                  <i class="text-white fas fa-truck-loading fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #fd7e14, #fd7e14);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Partial Delivery</h6>
                                                      <h4 class="mb-0 fw-bold" id="partial_delivery">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span
                                          class="badge badge-primary position-absolute partial_delivery_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Delivery -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="completed">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing(5)" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #28a745;">
                                                  <i class="text-white fas fa-check-square fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #28a745, #28a745);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Delivery</h6>
                                                      <h4 class="mb-0 fw-bold" id="completed">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span class="badge badge-primary position-absolute completed_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Delay -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="delay">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="specialFilter('delay')" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #e74c3c;">
                                                  <i class="text-white fas fa-hourglass-end fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #e74c3c, #e74c3c);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Delay</h6>
                                                      <h4 class="mb-0 fw-bold" id="delay">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span class="badge badge-primary position-absolute delay_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Double -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="double">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="Processing({{ \App\Models\Order::STATUS_DOUBLE }})" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #9b59b6;">
                                                  <i class="text-white fas fa-clone fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #9b59b6, #9b59b6);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Double</h6>
                                                      <h4 class="mb-0 fw-bold" id="double">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span class="badge badge-primary position-absolute double_percent">0%</span>
                                  </div>
                              </a>
                          </div>

                          <!-- Card: Bonus Orders -->
                          <div class="p-1 col-3 col-md-2 stat-card-item" data-card-id="bonus_orders">
                              <span class="stat-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                              <a href="#" onclick="specialFilter('bonus')" draggable="false">
                                  <div class="card shadow-base bd-0 rounded-right">
                                      <div class="card shadow-base bd-0 rounded-right">
                                          <div class="overflow-hidden rounded shadow row no-gutters">
                                              <!-- Icon Section -->
                                              <div class="col-md-2 d-flex align-items-center justify-content-center"
                                                  style="background: #20c997;">
                                                  <i class="text-white fas fa-gift fa-2x"></i>
                                              </div>

                                              <!-- Content Section -->
                                              <div class="col-md-10 d-flex align-items-center"
                                                  style="background: linear-gradient(90deg, #20c997, #20c997);">
                                                  <div class="py-0 text-center text-white py-md-3 w-100"
                                                      style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                                      <h6 class="mb-1 text-uppercase">Bonus Orders</h6>
                                                      <h4 class="mb-0 fw-bold" id="bonus_orders">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <span class="badge badge-primary position-absolute bonus_orders_percent">0%</span>
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

  <script src="{{ asset('backend/js/sortable.min.js') }}"></script>
  <script>
      if (typeof Sortable === 'undefined') {
          document.write('<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"><\/script>');
      }
  </script>
  <script>
      (function() {
          var storageKey = 'order_stats_cards_order_{{ auth()->id() ?? "guest" }}';
          var defaultOrder = [
              'total_count', 'processing', 'pending', 'printed_invoice',
              'total_delivery', 'ondelivery', 'pending_p', 'noresponse1',
              'noresponse2', 'hold', 'courier_hold', 'cancel',
              'stock_out', 'paid_return', 'pending_return', 'return',
              'partial_delivery', 'completed', 'delay', 'double', 'bonus_orders'
          ];

          window.getStatCardsStorageKey = function() {
              return storageKey;
          };

          window.applySavedStatCardOrder = function() {
              var grid = document.getElementById('statsCardGrid');
              if (!grid) return;
              var saved = localStorage.getItem(storageKey);
              if (!saved) return;
              try {
                  var order = JSON.parse(saved);
                  if (Array.isArray(order) && order.length > 0) {
                      var cardMap = {};
                      var cards = grid.querySelectorAll('.stat-card-item');
                      for (var i = 0; i < cards.length; i++) {
                          var id = cards[i].getAttribute('data-card-id');
                          if (id) cardMap[id] = cards[i];
                      }
                      for (var j = 0; j < order.length; j++) {
                          if (cardMap[order[j]]) {
                              grid.appendChild(cardMap[order[j]]);
                              delete cardMap[order[j]];
                          }
                      }
                      for (var remId in cardMap) {
                          if (cardMap.hasOwnProperty(remId)) {
                              grid.appendChild(cardMap[remId]);
                          }
                      }
                  }
              } catch (e) {
                  console.error('Error applying saved card order:', e);
              }
          };

          window.saveStatCardOrder = function() {
              var grid = document.getElementById('statsCardGrid');
              if (!grid) return;
              var cards = grid.querySelectorAll('.stat-card-item');
              var order = [];
              for (var i = 0; i < cards.length; i++) {
                  var id = cards[i].getAttribute('data-card-id');
                  if (id) order.push(id);
              }
              localStorage.setItem(storageKey, JSON.stringify(order));
          };

          window.sortStatCards = function(type) {
              var grid = document.getElementById('statsCardGrid');
              if (!grid) return;
              var cards = Array.prototype.slice.call(grid.querySelectorAll('.stat-card-item'));
              if (!cards.length) return;

              if (type === 'count-desc' || type === 'count-asc') {
                  cards.sort(function(a, b) {
                      var elA = a.querySelector('h4');
                      var elB = b.querySelector('h4');
                      var valA = parseInt((elA ? elA.textContent : '0').replace(/[^0-9]/g, ''), 10) || 0;
                      var valB = parseInt((elB ? elB.textContent : '0').replace(/[^0-9]/g, ''), 10) || 0;
                      return type === 'count-desc' ? valB - valA : valA - valB;
                  });
              } else if (type === 'name-asc') {
                  cards.sort(function(a, b) {
                      var elA = a.querySelector('h6');
                      var elB = b.querySelector('h6');
                      var nameA = (elA ? elA.textContent : '').trim().toLowerCase();
                      var nameB = (elB ? elB.textContent : '').trim().toLowerCase();
                      return nameA.localeCompare(nameB);
                  });
              }

              cards.forEach(function(card) {
                  grid.appendChild(card);
              });

              window.saveStatCardOrder();

              if (typeof toastr !== 'undefined') {
                  toastr.success('Cards sorted successfully!');
              }
          };

          window.resetStatCardOrder = function() {
              var grid = document.getElementById('statsCardGrid');
              if (!grid) return;
              localStorage.removeItem(storageKey);

              var cardMap = {};
              var cards = grid.querySelectorAll('.stat-card-item');
              for (var i = 0; i < cards.length; i++) {
                  var id = cards[i].getAttribute('data-card-id');
                  if (id) cardMap[id] = cards[i];
              }

              defaultOrder.forEach(function(id) {
                  if (cardMap[id]) {
                      grid.appendChild(cardMap[id]);
                  }
              });

              if (typeof toastr !== 'undefined') {
                  toastr.info('Default card order restored.');
              }
          };

          // Apply saved order immediately on DOM parse to eliminate layout flash
          window.applySavedStatCardOrder();

          var isDraggingCard = false;
          var dragEndTime = 0;

          function setupDragClickBlocker(grid) {
              if (grid._dragBlockerSetup) return;
              grid._dragBlockerSetup = true;

              // Capture phase listener to prevent clicks on cards if a drag occurred
              grid.addEventListener('click', function(e) {
                  if (isDraggingCard || (Date.now() - dragEndTime < 400)) {
                      e.preventDefault();
                      e.stopPropagation();
                      e.stopImmediatePropagation();
                      return false;
                  }
              }, true);
          }

          // Initialize SortableJS
          function initStatCardsSortable() {
              var grid = document.getElementById('statsCardGrid');
              if (!grid) return;

              window.applySavedStatCardOrder();

              if (typeof Sortable === 'undefined') {
                  if (!window._sortableLoading) {
                      window._sortableLoading = true;
                      var s = document.createElement('script');
                      s.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js';
                      s.onload = function() {
                          initStatCardsSortable();
                      };
                      document.head.appendChild(s);
                  } else {
                      setTimeout(initStatCardsSortable, 100);
                  }
                  return;
              }

              if (grid._sortableInitialized) return;
              grid._sortableInitialized = true;

              new Sortable(grid, {
                  animation: 200,
                  ghostClass: 'stat-card-ghost',
                  chosenClass: 'stat-card-chosen',
                  dragClass: 'stat-card-drag',
                  touchStartThreshold: 3,
                  fallbackTolerance: 3,
                  onStart: function() {
                      isDraggingCard = true;
                  },
                  onEnd: function() {
                      isDraggingCard = false;
                      dragEndTime = Date.now();
                      window.saveStatCardOrder();
                  }
              });

              setupDragClickBlocker(grid);
          }

          window.toggleStatsSortMenu = function(e) {
              if (e) {
                  e.preventDefault();
                  e.stopPropagation();
              }
              var menu = document.querySelector('#statsSortMenuBtn ~ .dropdown-menu');
              if (menu) {
                  menu.classList.toggle('show');
              }
          };

          document.addEventListener('click', function(e) {
              if (!e.target.closest('#statsSortMenuBtn') && !e.target.closest('.dropdown-menu')) {
                  var menu = document.querySelector('#statsSortMenuBtn ~ .dropdown-menu.show');
                  if (menu) menu.classList.remove('show');
              }
          });

          if (document.readyState === 'loading') {
              document.addEventListener('DOMContentLoaded', initStatCardsSortable);
          } else {
              initStatCardsSortable();
          }
          window.addEventListener('load', initStatCardsSortable);
      })();
  </script>

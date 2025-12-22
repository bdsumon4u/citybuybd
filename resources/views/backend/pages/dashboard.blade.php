@extends('backend.layout.template')
@section('body-content')




    <div class="br-pagebody" >
        <div class="px-2 py-2 row">


            <!-- <div class="pb-1 col-lg-3 col-6"> -->
            <div class="pb-1 col-3">
                <div class="card shadow-base bd-0 rounded-right">
                    <div class="overflow-hidden rounded shadow row no-gutters">
                        <!-- Icon Section -->
                        <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #198754;">
                            <i class="text-white fa fa-rocket fa-2x"></i>
                        </div>

                        <!-- Content Section -->
                        <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg,  #198754, #198754);">
                            <div class="py-3 text-center text-white w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                <h6 class="mb-1 text-uppercase">Total Revenue</h6>
                                <h4 class="mb-0 fw-bold">
                                    {{ $settings->currency ?? "৳" }} {{ \App\Models\Order::sum('total') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- card -->
            </div>
            <div class="pb-1 col-3">
               <div class="card shadow-base bd-0 rounded-right">
                    <div class="overflow-hidden rounded shadow row no-gutters">
                        <!-- Icon Section -->
                        <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #20c997;">
                            <i class="text-white fas fa-users fa-2x"></i>
                        </div>

                        <!-- Content Section -->
                        <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #20c997, #20c997);">
                            <div class="py-3 text-center text-white w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                <h6 class="mb-1 text-uppercase">Total Staff</h6>
                                <h4 class="mb-0 fw-bold">{{ count($users) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card -->
            </div>
            <div class="pb-1 col-3">
               <div class="card shadow-base bd-0 rounded-right">
                    <div class="overflow-hidden rounded shadow row no-gutters">
                        <!-- Icon Section -->
                        <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #0dcaf0;">
                            <i class="text-white fas fa-user-friends fa-2x"></i>
                        </div>

                        <!-- Content Section -->
                        <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #0dcaf0, #0dcaf0);">
                            <div class="py-3 text-center text-white w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                <h6 class="mb-1 text-uppercase">Total Customer</h6>
                                <h4 class="mb-0 fw-bold">{{ \App\Models\Order::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card -->
            </div>
            <div class="pb-1 col-3">
                <div class="card shadow-base bd-0 rounded-right">
                    <div class="overflow-hidden rounded shadow row no-gutters">
                        <!-- Icon Section -->
                        <div class="col-md-2 d-flex align-items-center justify-content-center" style="background: #198754;">
                            <i class="text-white fab fa-product-hunt fa-2x"></i>
                        </div>

                        <!-- Content Section -->
                        <div class="col-md-10 d-flex align-items-center" style="background: linear-gradient(90deg, #198754, #198754);">
                            <div class="py-3 text-center text-white w-100" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                                <h6 class="mb-1 text-uppercase">Total Product</h6>
                                <h4 class="mb-0 fw-bold">{{ App\Models\Product::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card -->
            </div>
        </div>


@include('backend.includes.statistics')











        <!-- copy start -->



        <div class="mt-5 mb-3 row mb-md-4">
                    <div class="col-xl-5 col-lg-6 col-md-5 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Today's Report</h5>
                            <div class="card-body">
                                <table class="table mg-b-0 table-bordered table-striped">
                                    <tbody>
                                    <tr>
                                        <th>Orders</th>
                                        <td>

                                            {{ $today_orders }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Processing</th>
                                        <td>{{ $today_processing }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pending Delivery</th>
                                        <td>{{ $today_pendingdelivery }}</td>
                                    </tr>
                                    <tr>
                                        <th>On Delivery</th>
                                        <td>{{ $today_ondelivery }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pending Payment</th>
                                        <td>{{$today_pending_pay}}</td>
                                    </tr>
                                    <tr>
                                        <th>On Hold</th>
                                        <td>{{$today_hold}}</td>
                                    </tr>

                                    <tr>
                                        <th>Courier Hold</th>
                                        <td>{{ $today_courierhold }}</td>
                                    </tr>
                                    <tr>
                                        <th>No Response 1</th>
                                        <td>{{ $today_noresponse1 }}</td>
                                    </tr>
                                    <tr>
                                        <th>No Response 2</th>
                                        <td>{{ $today_noresponse2 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Canceled</th>
                                        <td>{{$today_canceled}}</td>
                                    </tr>
                                    <tr>
                                        <th>Return</th>
                                        <td>{{ $today_return }}</td>
                                    </tr>
                                    <tr>
                                        <th>Completed</th>
                                        <td>{{ $today_completed }}</td>
                                    </tr>




                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Recent Orders</h5>
                            <div class="p-0 card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Date</th>
                                            <th>C. Name</th>
                                            <th>C. Phone</th>
                                            <th>Total</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($recent_orders as $order)


                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$order->created_at}}</td>
                                                <td>{{$order->name}}</td>
                                                <td>
                                                    <a href="tel:{{$order->phone}}">{{$order->phone}}</a>
                                                </td>
                                                <td>{{$settings->currency ?? "৳"}} {{$order->total}}</td>
                                                <td class="text-center">
                                                    @if($order->status==1)


                                                        <span class="badge badge-info">Processing</span>

                                                    @elseif($order->status==2)

                                                        <span class="badge badge-primary">Pending</span>
                                                    @elseif($order->status==3)

                                                        <span class="badge badge-warning">On Hold</span>
                                                    @elseif($order->status==4)

                                                        <span class="badge badge-danger">Canceled</span>
                                                    @elseif($order->status==5)

                                                        <span class="badge badge-success">Completed</span>
                                                    @endif


                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


        <!-- copy end -->


    </div>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>


  $(document).ready(function() {
  statistics();
 });

 function statistics(){
    var params = {
        fixeddate : 1,
      };
      var paramStrings = [];
        for (var key in params) {
        paramStrings.push(key + '=' + encodeURIComponent(params[key]));
      }
        $.ajax({
        url: "{{ url('total-order-list?') }}"+paramStrings.join('&'),
        type: "get",
        datatype: "html",
        })
        .done(function(data){
              $('#processing').text(data.processing);
              $('#pending').text(data.pending_Delivery);
              $('#ondelivery').text(data.on_Delivery);
              $('#pending_p').text(data.pending_Payment);
              $('#hold').text(data.hold);
              $('#courier_hold').text(data.courier_hold);
              $('#noresponse1').text(data.no_response1);
              $('#noresponse2').text(data.no_response2);
              $('#cancel').text(data.cancel);
              $('#return').text(data.return);
              $('#completed').text(data.completed);
              $('#partial_delivery').text(data.partial_delivery);
              $('#paid_return').text(data.paid_return);
              $('#stock_out').text(data.stock_out);
              $('#total_count').text(data.total);
          });
  }
    </script>

    <style>
        @media (max-width: 767px) {
        .card .w-100 h6,
        .card .w-100 p,
        .card .w-100 h4 {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .card .w-100 h4 {
        font-size: 12px !important; /* same as <p> in Bootstrap */
        font-weight: normal !important; /* optional: make it lighter like <p> */
        margin: 0; /* keep spacing tight */
        }
    }

    </style>


@endsection

@extends('manager.layout.template')
@section('body-content')

    <div class="br-pagebody" >

        <div class="px-2 py-2 row">
            <div class="pb-1 col-3">
                <div class="card shadow-base bd-0 rounded-right">
                    <div class="row no-gutters">
                        <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                            <div>
                                <i class="fa fa-rocket tx-20 lh-1 tx-white op-9"></i>
                            </div>
                        </div>
                        <!-- col-6 -->
                        <div class="col-md-8 tx-center d-flex align-items-center">
                            <div class="pt-2 text-center col-md-12">
                                <h6 class="pb-1 tx-16 tx-semibold">Total Revenue</h6>
                                <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{$settings->currency ?? "৳"}} {{\App\Models\Order::sum('total')}}</h4>
                            </div>
                            <!-- pd-30 -->
                        </div>
                        <!-- col-6 -->
                    </div>
                    <!-- row -->
                </div>
                <!-- card -->
            </div>
            <div class="pb-1 col-3">
                <div class="card shadow-base bd-0 rounded-right">
                    <div class="row no-gutters">
                        <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                            <div>
                                <i class="fas fa-users tx-20 lh-1 tx-white op-9"></i>
                            </div>
                        </div>
                        <!-- col-6 -->
                        <div class="col-md-8 tx-center d-flex align-items-center">
                            <div class="pt-2 text-center col-md-12">
                                <h6 class="pb-1 tx-16 tx-semibold">Total Staff</h6>
                                <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count($users)}}</h4>
                            </div>
                            <!-- pd-30 -->
                        </div>
                        <!-- col-6 -->
                    </div>
                    <!-- row -->
                </div>
                <!-- card -->
            </div>
            <div class="pb-1 col-3">
                <div class="card shadow-base bd-0 rounded-right">
                    <div class="row no-gutters">
                        <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                            <div>
                                <i class="fas fa-user-friends tx-20 lh-1 tx-white op-9"></i>
                            </div>
                        </div>
                        <!-- col-6 -->
                        <div class="col-md-8 tx-center d-flex align-items-center">
                            <div class="pt-2 text-center col-md-12">
                                <h6 class="pb-1 tx-16 tx-semibold">Total Customer</h6>
                                <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{\App\Models\Order::count()}}</h4>
                            </div>
                            <!-- pd-30 -->
                        </div>
                        <!-- col-6 -->
                    </div>
                    <!-- row -->
                </div>
                <!-- card -->
            </div>
            <div class="pb-1 col-3">
                <div class="card shadow-base bd-0 rounded-right">
                    <div class="row no-gutters">
                        <div class="col-md-2 tx-center d-flex align-items-center justify-content-center btn-teal">
                            <div>
                                <i class="fab fa-product-hunt tx-20 lh-1 tx-white op-9"></i>
                            </div>
                        </div>
                        <!-- col-6 -->
                        <div class="col-md-8 tx-center d-flex align-items-center">
                            <div class="pt-2 text-center col-md-12">
                                <h6 class="pb-1 tx-16 tx-semibold">Total Product</h6>
                                <h4 class="tx-30 tx-dark tx-semibold mg-b-8">{{count(App\Models\Product::all())}}</h4>
                            </div>
                            <!-- pd-30 -->
                        </div>
                        <!-- col-6 -->
                    </div>
                    <!-- row -->
                </div>
                <!-- card -->
            </div>
        </div>



@include('manager.includes.statistics')


        <!-- copy start -->






        <!-- copy end -->


    </div>

<div class="br-pagebody" >
            <!-- copy start -->
            <div class="container-fluid dashboard-content">
    <div class="ecommerce-widget">

        <div class="mb-3 row mb-md-4">
            <div class="col-xl-5 col-lg-6 col-md-5 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Today's Report</h5>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th>Orders</th>
                                <td>

                                    {{App\Models\Order::whereRaw('Date(created_at) = CURDATE()')->count()}}
                                </td>
                            </tr>
                            <tr>
                                <th>Processing</th>
                                <td>{{App\Models\Order::where('status',1)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
                            </tr>
                            <tr>
                                <th>Pending Payment</th>
                                <td>{{App\Models\Order::where('status',2)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
                            </tr>
                            <tr>
                                <th>On Hold</th>
                                <td>{{App\Models\Order::where('status',3)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
                            </tr>
                            <tr>
                                <th>Canceled</th>
                                <td>{{App\Models\Order::where('status',4)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
                            </tr>
                            <tr>
                                <th>Delivery</th>
                                <td>{{App\Models\Order::where('status',5)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
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
                            @foreach(App\Models\Order::orderBy('id','desc')->take(4)->get() as $order)

                                               <tr>
                                                <td>2</td>
                                                <td>{{$order->created_at}}</td>
                                                <td>{{$order->name}}</td>
                                                <td><a href="tel:{{$order->phone}}">{{$order->phone}}</a></td>
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
                                                          <span class="badge badge-success">Delivery</span>
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
    </div>
</div>
            <!-- copy end -->
    </div>

<script>
    $(document).ready(function () {
        statistics();
    });

    function statistics() {
        var params = {
            fixeddate: 1,
        };

        var paramStrings = [];
        for (var key in params) {
            paramStrings.push(key + '=' + encodeURIComponent(params[key]));
        }

        $.ajax({
            url: "{{ url('manager/order-management/manager-total-order-list?') }}" + paramStrings.join('&'),
            type: "get",
            datatype: "html",
        }).done(function (data) {
            $('#processing').text(data.processing);
            $('#pending').text(data.pending_Delivery);
            $('#printed_invoice').text(data.printed_invoice);
            $('#ondelivery').text(data.on_Delivery);
            $('#pending_p').text(data.pending_Payment);
            $('#hold').text(data.hold ?? data.on_Hold);
            $('#courier_hold').text(data.courier_hold);
            $('#noresponse1').text(data.no_response1);
            $('#noresponse2').text(data.no_response2);
            $('#cancel').text(data.cancel);
            $('#return').text(data.return);
            $('#pending_return').text(data.pending_return);
            $('#completed').text(data.completed);
            $('#partial_delivery').text(data.partial_delivery);
            $('#paid_return').text(data.paid_return);
            $('#stock_out').text(data.stock_out);
            $('#total_delivery').text(data.total_delivery);
            $('#total_count').text(data.total);
        });
    }
</script>
@endsection

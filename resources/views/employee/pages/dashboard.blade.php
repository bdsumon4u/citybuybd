
@extends('employee.layout.template')
@section('body-content')

    <div class="br-pagebody" >



        @include('employee.includes.statistics')




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

                                    {{App\Models\Order::where('order_assign', Auth::user()->id)->whereRaw('Date(created_at) = CURDATE()')->count()}}
                                </td>
                            </tr>
                            <tr>
                                <th>Processing</th>
                                <td>{{App\Models\Order::where('order_assign', Auth::user()->id)->where('status',1)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
                            </tr>
                            <tr>
                                <th>Pending Payment</th>
                                <td>{{App\Models\Order::where('order_assign', Auth::user()->id)->where('status',2)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
                            </tr>
                            <tr>
                                <th>On Hold</th>
                                <td>{{App\Models\Order::where('order_assign', Auth::user()->id)->where('status',3)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
                            </tr>
                            <tr>
                                <th>Canceled</th>
                                <td>{{App\Models\Order::where('order_assign', Auth::user()->id)->where('status',4)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
                            </tr>
                            <tr>
                                <th>Delivery</th>
                                <td>{{App\Models\Order::where('order_assign', Auth::user()->id)->where('status',5)->whereRaw('Date(created_at) = CURDATE()')->count()}}</td>
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
                            @foreach(App\Models\Order::where('order_assign', Auth::user()->id)->orderBy('id','desc')->take(4)->get() as $order)

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
        function statistics() {
            var params = {};
            var paramStrings = [];
            for (var key in params) {
                paramStrings.push(key + '=' + encodeURIComponent(params[key]));
            }

            $.ajax({
                url: "{{ url('employee/order-management/emp-total-order-list') }}" + (paramStrings.length ? '?' + paramStrings.join('&') : ''),
                type: "get",
                datatype: "html",
            })
            .done(function(data) {
                if (typeof data === 'string') {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        console.error('Failed to parse statistics response:', e);
                        return;
                    }
                }

                var total = parseInt(data.total, 10) || 0;
                var calcPercent = function(val) {
                    return total > 0 ? (((val || 0) / total) * 100).toFixed(3) + " %" : "0.000 %";
                };

                $('#processing').text(data.processing ?? 0);
                $('#pending').text(data.pending_Delivery ?? 0);
                $('#printed_invoice').text(data.printed_invoice ?? 0);
                $('#ondelivery').text(data.on_Delivery ?? 0);
                $('#pending_p').text(data.pending_Payment ?? 0);
                $('#hold').text(data.hold ?? data.on_Hold ?? 0);
                $('#courier_hold').text(data.courier_hold ?? 0);
                $('#noresponse1').text(data.no_response1 ?? 0);
                $('#noresponse2').text(data.no_response2 ?? 0);
                $('#cancel').text(data.cancel ?? 0);
                $('#return').text(data.return ?? 0);
                $('#pending_return').text(data.pending_return ?? 0);
                $('#completed').text(data.completed ?? 0);
                $('#partial_delivery').text(data.partial_delivery ?? 0);
                $('#paid_return').text(data.paid_return ?? 0);
                $('#stock_out').text(data.stock_out ?? 0);
                $('#total_delivery').text(data.total_delivery ?? 0);
                $('#total_count').text(total);
                $('#delay').text(data.delay ?? 0);
                $('#double').text(data.double ?? 0);
                $('#bonus_orders').text(data.bonus_orders ?? data.bonus ?? 0);

                $('.total_count_percent').text("100 %");
                $('.processing_percent').text(calcPercent(data.processing));
                $('.pending_percent').text(calcPercent(data.pending_Delivery));
                $('.printed_invoice_percent').text(calcPercent(data.printed_invoice));
                $('.ondelivery_percent').text(calcPercent(data.on_Delivery));
                $('.pending_p_percent').text(calcPercent(data.pending_Payment));
                $('.hold_percent').text(calcPercent(data.hold ?? data.on_Hold));
                $('.courier_hold_percent').text(calcPercent(data.courier_hold));
                $('.noresponse1_percent').text(calcPercent(data.no_response1));
                $('.noresponse2_percent').text(calcPercent(data.no_response2));
                $('.cancel_percent').text(calcPercent(data.cancel));
                $('.return_percent').text(calcPercent(data.return));
                $('.pending_return_percent').text(calcPercent(data.pending_return));
                $('.completed_percent').text(calcPercent(data.completed));
                $('.partial_delivery_percent').text(calcPercent(data.partial_delivery));
                $('.paid_return_percent').text(calcPercent(data.paid_return));
                $('.stock_out_percent').text(calcPercent(data.stock_out));
                $('.total_delivery_percent').text(calcPercent(data.total_delivery));
                $('.delay_percent').text(calcPercent(data.delay));
                $('.double_percent').text(calcPercent(data.double));
                $('.bonus_orders_percent').text(calcPercent(data.bonus_orders ?? data.bonus));
            });
        }

        function Processing(status) {
            window.location.href = "{{ route('employee.order.newmanage') }}" + (status !== '' && status !== null && status !== undefined ? '?status=' + status : '');
        }

        function specialFilter(filterType) {
            window.location.href = "{{ route('employee.order.newmanage') }}?special_filter=" + filterType;
        }

        function delay() {
            window.location.href = "{{ route('employee.order.newmanage') }}?special_filter=delay";
        }

        function double() {
            window.location.href = "{{ route('employee.order.newmanage') }}?special_filter=double";
        }

        (function initDashboardStats() {
            function run() {
                if (typeof jQuery === 'undefined') {
                    setTimeout(run, 50);
                    return;
                }
                jQuery(function($) {
                    statistics();
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', run);
            } else {
                run();
            }
        })();
    </script>
@endsection


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
                                <th>Completed</th>
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
        // fixeddate : $("#fixeddate").val(),

    //   fromDate : $("#fromDate").val(),
    //   toDate : $("#toDate").val(),
    //   courier : $("#courier").val(),
    //   order_assign : $("#order_assign").val(),
    //   product_id:$("#product_id").val(),
      };
      var paramStrings = [];
        for (var key in params) {
        paramStrings.push(key + '=' + encodeURIComponent(params[key]));
      }

    //  var url: "{{ url('http://localhost/ecommerce/total-order-list?') }}"+paramStrings.join('&');

$.ajax({
url: "{{ url('employee/order-management/emp-total-order-list?') }}"+paramStrings.join('&'),
type: "get",
datatype: "html",
})
.done(function(data){
              $('#processing').text(data.processing);
              $('#pending').text(data.pending_Delivery);
              $('#printed_invoice').text(data.printed_invoice);
              $('#ondelivery').text(data.on_Delivery);
              $('#pending_p').text(data.pending_Payment);
              $('#hold').text(data.on_Hold);
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

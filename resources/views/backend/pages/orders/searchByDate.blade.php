@extends('backend.layout.template')
@section('body-content')

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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="total_count_count">0</h4>
                                                </div>
                                                <!-- pd-30 -->
                                            </div>
                                            <!-- col-6 -->
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- card -->

                            </div>

                            <div class="col-lg-3 col-6 pb-1">
                                <a href="{{route('order.searchByPastDateStatus',[$count,1])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="processing_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,2])}}">
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
                                                    <h6 class="tx-16  tx-semibold pb-1 text-warning">Courier Entry</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="pending_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,7])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="ondelivery_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,6])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="pending_p_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,3])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="hold_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,11])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="courier_hold_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,8])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="noresponse1_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,9])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="noresponse2_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,4])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="cancel_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,12])}}">
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
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="return_count">0</h4>
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
                                <a href="{{route('order.searchByPastDateStatus',[$count,5])}}">
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
                                                    <h6 class="tx-16  tx-semibold pb-1 text-success">Total Delivery</h6>
                                                    <h4 class="tx-30 tx-dark tx-semibold mg-b-8" id="completed_count">0</h4>
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





                    </div>








                    </div>
                </div>
            </div><!-- card -->
            <!-- ADD MORE CARD HERE -->
        </div><!-- accordion -->

    </div>
@include('backend.includes.filter')



    <div class="br-pagebody">
        <div class="br-section-wrapper">

            <div class="bd bd-gray-300 rounded ">
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
                                    <th scope="col">Invoice ID</th>
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
                                        $check_duplicate = count($orders->where('phone',$order->phone))


                                        ?>
                                    <tr >
                                        <th scope="row">
                                            <input type="checkbox" class="sub_chk" data-id="{{$order->id}}">
                                        </th>
                                        <td>{{$i}}</td>
                                        <td>{{$order->id}}</td>
                                        <td class='{{$check_duplicate>1?"bg-danger-light":""}}'>
                                            <p class="mb-0">{{ $order->name ?? "N/A" }}</p>
                                            <p class="mb-0">
                                               <a href="tel:{{ $order->phone ?? "N/A" }}"><strong>{{ $order->phone ?? "N/A" }}</strong></a>
                                            </p>
                                            <p class="mb-0">{{ $order->address ?? "N/A" }}</p>
                                        </td>
                                        <td>

                                            @foreach($order->many_cart as $cart)

                                                @if(!is_null($cart->product))
                                                                                                                                                         <a href="{{route('details',$cart->product->slug)}}" target="_blank"><p> <span class="tx-10 font-weight-bold text-white bg-crystal-clear pd-4">{{$cart->quantity}}</span>  {{$cart->product->name }}</p></a>


                                                @else
                                                    N/A
                                                @endif

                                            @endforeach

                                        </td>
                                        <td>{{$settings->currency ?? "৳"}} {{ $order ->total }}</td>
 <td> {!!@$order->my_courier!!} </td>
  <td> {{ $order ->courier_status }} </td><td>
                                            {{date('d M, Y',strtotime($order->created_at))}}<br>
                                            {{date('h:i:s A',strtotime($order->created_at))}}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if($order->status==1)


                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Processing
                                                    </button>

                                                @elseif($order->status==2)

                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Courier Entry
                                                    </button>
                                                @elseif($order->status==3)

                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        On Hold
                                                    </button>
                                                @elseif($order->status==4)

                                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Cancel
                                                    </button>
                                                @elseif($order->status==5)

                                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Delivery
                                                    </button>
                                                @elseif($order->status==6)

                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        pending payment
                                                    </button>

                                                @elseif($order->status==7)

                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        on delivery
                                                    </button>


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
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [2,$order->id])}}">Courier Entry</a>
                                                    @endif
                                                    @if($order->status!=3)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [3,$order->id])}}">On Hold</a>
                                                    @endif
                                                    @if($order->status!=4)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [4,$order->id])}}">Cancel</a>
                                                    @endif
                                                    @if($order->status!=5)
                                                        <a class="dropdown-item" href="{{route('order.statusChange', [5,$order->id])}}">Delivery</a>
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
                                            {{$order->order_note ??"N/A"}}
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
                                                                <span aria-hidden="true">&times;</span></button>
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

                                                    {{--                                                        <a href="{{route('order.print', $order->id)}}" class="btn  btn-icon">--}}

                                                    {{--                                                                <i class="fa-solid fa-print tx-17"></i>--}}

                                                    {{--                                                        </a>--}}

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
    </div>


@endsection
@push('custom-scripts')
    <script type="text/javascript">

        $(document).ready(function() {

        var count = "{{$count}}";


        $.get( "/total-order-fixed-date/"+count, function( data ) {

            $('#processing_count').text(data.processing);
            $('#pending_count').text(data.pending_Delivery);
            $('#ondelivery_count').text(data.on_Delivery);
            $('#pending_p_count').text(data.pending_Payment);
            $('#hold_count').text(data.hold);
            $('#courier_hold_count').text(data.courier_hold);
            $('#noresponse1_count').text(data.no_response1);
            $('#noresponse2_count').text(data.no_response2);
            $('#cancel_count').text(data.cancel);
            $('#return_count').text(data.return);
            $('#completed_count').text(data.completed);
            $('#total_count_count').text(data.total);

        });


    });

    </script>
@endpush

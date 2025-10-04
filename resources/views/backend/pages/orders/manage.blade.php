@extends('backend.layout.template')
@section('body-content')

@include('backend.includes.statistics')
@include('backend.includes.filter')

    <div class="br-pagebody">
        <div class="br-section-wrapper">

<div class="row justify-content-center">
    <span class="tx-20 text-center mt-1" >All Orders</span>
</div>
            <div class="row">
                    <div class="col-lg-12">
                        <div class="pd-5" style="overflow-x: auto;">

                            <table class="table mg-b-0 table-bordered table-striped" >
                                <thead>
                                <tr>
                                    <th scope="col">
                                        <input type="checkbox" class="chkCheckAll">
                                    </th>
                                    <th scope="col">#Sl</th>
                                    <th scope="col">Invoice </th>
                                    <th scope="col">Customer  </th>
                                    <th scope="col">Products </th>
                                    <th scope="col">Total </th>
                                    <th scope="col">Courier </th>
                                       <th scope="col">Courier Status</th>
                                    <th scope="col">Date </th>
                                    <th scope="col">Status </th>
                                    <th scope="col">Note </th>
                                    <th scope="col">Assigned </th>
                                    <th scope="col">Action </th>
                                </tr>
                                </thead>
                                <tbody id="myTable">

                                @foreach( $orders as $order )


                                        <?php
                                        $check_duplicate = count($total_orders->where('phone',$order->phone)->where('status','!=',5))


                                        ?>
                                    <tr >
                                        <th scope="row">
                                            <input type="checkbox" class="sub_chk" data-id="{{$order->id}}">
                                        </th>
                                        <td>{{$loop->iteration}}</td>
                                        <td >INV-{{$order->id}}</td>
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
                                        <td>৳ {{ $order ->total }}</td>
                                         <td> {!!@$order->my_courier!!} </td>
                                         <td> {{ $order->courier_status }} </td>
                                        <td>
                                            {{date('d M, Y',strtotime($order->created_at))}}<br>
                                            {{date('h:i:s A',strtotime($order->created_at))}}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if($order->status==1)
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Processing</button>
                                                @elseif($order->status==2)
                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pending Delivery</button>
                                                @elseif($order->status==3)
                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> On Hold</button>
                                                @elseif($order->status==4)
                                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cancel</button>
                                                @elseif($order->status==5)
                                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Completed</button>
                                                @elseif($order->status==6)
                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">pending payment</button>
                                                @elseif($order->status==7)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">on delivery</button>
                                                @elseif($order->status==7)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">on delivery</button>
                                                @elseif($order->status==7)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">on delivery</button>
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
                                                    <a class="dropdown-item" href="{{route('order.to_processing',$order->id)}}">Processing</a>
                                                    @endif
                                                    @if($order->status!=2)
                                                        <a class="dropdown-item" href="{{route('order.to_pending',$order->id)}}">Pending Delivery</a>
                                                    @endif
                                                    @if($order->status!=3)
                                                        <a class="dropdown-item" href="{{route('order.to_hold',$order->id)}}">On Hold</a>
                                                    @endif
                                                    @if($order->status!=4)
                                                        <a class="dropdown-item" href="{{route('order.to_cancel',$order->id)}}">Cancel</a>
                                                    @endif
                                                    @if($order->status!=5)
                                                        <a class="dropdown-item" href="{{route('order.to_completed',$order->id)}}">Completed</a>
                                                    @endif

                                                    @if($order->status!=6)
                                                        <a class="dropdown-item" href="{{route('order.to_pending_p',$order->id)}}">Pending Payment</a>
                                                    @endif
                                                    @if($order->status!=7)
                                                        <a class="dropdown-item" href="{{route('order.to_ondelivery',$order->id)}}">On Delivery</a>
                                                    @endif
                                                    @if($order->status!=8)
                                                        <a class="dropdown-item" href="{{route('order.to_noresponse1',$order->id)}}">No Response 1</a>
                                                    @endif
                                                    @if($order->status!=9)
                                                        <a class="dropdown-item" href="{{route('order.to_noresponse2',$order->id)}}">No Response 2</a>
                                                    @endif
                                                    
                                                    @if($order->status!=11)
                                                        <a class="dropdown-item" href="{{route('order.to_courierhold',$order->id)}}">Courier Hold</a>
                                                    @endif
                                                    @if($order->status!=12)
                                                        <a class="dropdown-item" href="{{route('order.to_return',$order->id)}}">Return</a>
                                                    @endif



                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            
                                            
                                            <div class="list-group mt-1">
                                                <div class="list-group-item align-items-center justify-content-start">
                                                    <div class="">
                                                        <p class="mg-b-0 tx-inverse tx-medium">{{$order->order_note ??"N/A"}}</p>
                                                    </div>
                                                    <div class="">
                                                        <a href="" data-toggle="modal" data-target="#noted{{$order->id}}" class="btn btn-outline-primary btn-icon">
                                                            <div class="tx-10"><i class="fa-solid fa-pen-to-square tx-14"></i></div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- assign modaal start -->
                                            <div class="modal fade" id="noted{{$order->id}}" aria-labelledby="exampleModalLabel2" aria-hidden="true" style="overflow: hidden">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header ">
                                                            <h5 class="modal-title " id="exampleModalLabel2">Note </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body ">
                                                            <div class="row justify-content-center">
                                                                <div class="col-lg-12">
                                                                    <form action="{{ route('order.noted_edit', $order->id)}}" method="POST" class="assign_f_button">
                                                                        @csrf
                                                                        <div class="col-md-12">
                                                                            <textarea name="order_noted" id="order_noted" >{{$order->order_note ??"N/A"}} </textarea>
                                                                            <input type="submit" value="Save" name="delete" class="btn btn-success btn-block mt-2 noted_e_button" >
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- assign modaal end -->
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
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
                                            <div class="modal fade" id="assign{{$order->id}}" aria-labelledby="exampleModalLabel" aria-hidden="true" style="overflow: hidden">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header ">
                                                            <h5 class="modal-title " id="exampleModalLabel">Assign User </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body ">
                                                            <div class="row justify-content-center">
                                                                <div class="col-lg-12">
                                                                    <form action="{{ route('order.assign_edit', $order->id)}}" method="POST" class="assign_f_button">
                                                                        @csrf
                                                                        <div class="col-md-12">
                                                                            <select name="order_assign" class="form-control select2">
                                                                                @foreach(App\Models\User::where('role',3)->get() as $user)

                                                                                    <option value="{{$user->id}}" @if($user->id==$order->order_assign)selected @endif>{{$user->name}}</option>
                                                                                @endforeach

                                                                            </select>
                                                                            <input type="submit" value="Assign" name="delete" class="btn btn-success btn-block mt-2 assign_e_button" >
                                                                        </div>

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


@endsection


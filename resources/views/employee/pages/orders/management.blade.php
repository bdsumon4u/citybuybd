@extends('employee.layout.template')
@section('body-content')

@include('employee.includes.statistics')
@include('employee.includes.filter')

    <div class="br-pagebody">
        <div class="br-section-wrapper">

            <div class="row justify-content-center">
                <span class="tx-20 text-center mt-1" >All Processing Orders</span>
            </div>
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
                                    $check_duplicate = count(App\Models\Order::where('phone',$order->phone)->get())


                                    ?>
                                <tr >
                                    <th scope="row">
                                        <input type="checkbox" class="sub_chk" data-id="{{$order->id}}">
                                    </th>
                                    <td>{{$i}}</td>
                                    <td>{{$order->id}} <br>
                                    @if($order->coming=='1')

                                            <span class="tx-10 font-weight-bold text-white bg-success pd-4">Landing</span>
                                        @endif
                                    </td>
                                    <td class='{{$check_duplicate>1?"bg-danger-light":""}}'>
                                        <p class="mb-0">{{ $order->name ?? "N/A" }}</p>
                                        <p class="mb-0">
                                            <a href="tel:{{ $order->phone ?? "N/A" }}"><strong>{{ $order->phone ?? "N/A" }}</strong></a>
                                        </p>
                                        <p class="mb-0">{{ $order->address ?? "N/A" }}</p>
                                    </td>
                                      <td>
                                          <div class="qc_result">
                                             <span class="text-primary">T:{{ $order->delivered + $order->returned }}</span> <br>
                                              <span class="text-success">D:{{$order->delivered ?? 0}}</span> <br>
                                              <span class="text-danger">R:{{ $order->returned ?? 0 }}</span>
                                          </div>
                                          <div class="d-flex g-2">
                                              <a href="javascript:void(0);" data-id="{{ $order->id }}" class="btn qc_btn_modal btn-icon mr-2">
                                                <i class="fa-solid fa-eye tx-17"></i>
                                              </a>

                                          </div>

                                      </td>
                                    <td>

                                        @foreach(App\Models\Cart::where('order_id',$order->id)->get() as $cart)

                                            @if(!is_null($cart->product))

                                                                                                     <a href="{{route('details',$cart->product->slug)}}" target="_blank"><p> <span class="tx-10 font-weight-bold text-white bg-crystal-clear pd-4">{{$cart->quantity}}</span>  {{$cart->product->name }}</p></a>

                                            @else
                                                N/A
                                            @endif

                                        @endforeach

                                    </td>
                                    <td>{{$settings->currency ?? "৳"}} {{ $order ->total }}</td>
<td> {!!@$order->my_courier!!} </td>
 <td> {{ $order ->courier_status }} </td>
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
                                                @elseif($order->status==16)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Total Delivery</button>
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
                                                @elseif($order->status==13)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Partial Delivery</button>
                                                @elseif($order->status==14)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Paid Return</button>
                                                @elseif($order->status==15)
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Stock Out</button>
                                                @endif


                                                <div class="dropdown-menu">
                                                    @if($order->status!=1)
                                                    <a class="dropdown-item" href="{{route('employee.order.statusChange', [1,$order->id])}}">Processing</a>
                                                    @endif
                                                    @if($order->status!=2)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [2,$order->id])}}">Pending Delivery</a>
                                                    @endif
                                                    @if($order->status!=16)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [16,$order->id])}}">Total Delivery</a>
                                                    @endif
                                                    @if($order->status!=3)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [3,$order->id])}}">On Hold</a>
                                                    @endif
                                                    @if($order->status!=4)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [4,$order->id])}}">Cancel</a>
                                                    @endif
                                                    @if($order->status!=5)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [5,$order->id])}}">Completed</a>
                                                    @endif

                                                    @if($order->status!=6)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [6,$order->id])}}">Pending Payment</a>
                                                    @endif
                                                    @if($order->status!=7)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [7,$order->id])}}">On Delivery</a>
                                                    @endif
                                                    @if($order->status!=8)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [8,$order->id])}}">No Response 1</a>
                                                    @endif
                                                    @if($order->status!=9)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [9,$order->id])}}">No Response 2</a>
                                                    @endif

                                                    @if($order->status!=11)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [11,$order->id])}}">Courier Hold</a>
                                                    @endif
                                                    @if($order->status!=12)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [12,$order->id])}}">Return</a>
                                                    @endif
                                                    @if($order->status!=13)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [13,$order->id])}}">Partial Delivery</a>
                                                    @endif
                                                    @if($order->status!=14)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [14,$order->id])}}">Paid Return</a>
                                                    @endif
                                                    @if($order->status!=15)
                                                        <a class="dropdown-item" href="{{route('employee.order.statusChange', [15,$order->id])}}">Stock Out</a>
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
                                                                            <label>Pre-saved Order Note</label>
                                                                            <div class="mb-2" style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                                                              @foreach (App\Models\OrderNote::active()->ordered()->get() as $note)
                                                                                <div class="form-check">
                                                                                  <input class="form-check-input" type="radio" name="pre_saved_note_{{ $order->id }}" id="pre_saved_note_{{ $order->id }}_{{ $note->id }}" value="{{ $note->note }}" @if($order->order_note == $note->note) checked @endif onchange="applyPreSavedNoteToModal({{ $order->id }}, {{ json_encode($note->note) }})">
                                                                                  <label class="form-check-label" for="pre_saved_note_{{ $order->id }}_{{ $note->id }}">
                                                                                    {{ $note->note }}
                                                                                  </label>
                                                                                </div>
                                                                              @endforeach
                                                                            </div>
                                                                            <textarea name="order_noted" id="order_noted_{{ $order->id }}" class="form-control" rows="3">{{$order->order_note ??"N/A"}} </textarea>
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

                                            </div>
                                        </div>


                                        <!-- assign modaal start -->

                                        <!-- assign modaal end -->
                                    </td>
                                    <td class="action-button">


                                        <div class="list-group ">
                                            <div class="list-group-item d-grid">

                                                <a href="{{route('employee.order.edit', $order->id)}}" class="btn  btn-icon">

                                                    <i class="fa-solid fa-pen-to-square tx-17"></i>

                                                </a>

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

<script>
  function applyPreSavedNoteToModal(orderId, noteValue){
    if (noteValue) {
      $('#order_noted_' + orderId).val(noteValue);
    }
  }
</script>

@endsection


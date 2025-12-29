@extends('backend.layout.template')
@section('body-content')
@include('backend.includes.print-filter')

 <div class="br-pagetitle">
      </div>
            <div class="br-pagebody">
                        <div class="br-section-wrapper">
                            <div class="row justify-content-center">
                                <span class="tx-20 text-center mt-1" >All Orders</span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="pd-10" style="overflow-x: auto;">
                                        <table class="table mg-b-0 table-bordered table-striped" >
                                            <thead>
                                            <tr>
                                                <th scope="col"><input type="checkbox" class="chkCheckAll"></th>
                                                <th scope="col">#Sl</th>
                                                <th scope="col">Invoice </th>
                                                <th scope="col">Customer Info </th>
                                                <!-- <th scope="col">Products </th> -->
                                                <th scope="col">Total </th>
                                                <th scope="col">Courier </th>
                                                <th scope="col">Date </th>
                                                <th scope="col">Status </th>
                                                <!-- <th scope="col">Note </th>
                                                <th scope="col">Asigned </th>
                                                <th scope="col">Action </th> -->
                                            </tr>
                                            </thead>
                                            <tbody id="myTable">
                                            @php $i=1 @endphp
                                            @foreach( $orders as $order )
                                                <tr >
                                                    <th scope="row">
                                                        <input type="checkbox" class="sub_chk" data-id="{{$order->id}}">
                                                    </th>
                                                    <td>{{ $i }}</td>
                                                    <td>{{$order->id}}</td>
                                                    <td class="">
                                                        <p class="mb-0">{{ $order->name ?? "N/A" }}</p>
                                                        <p class="mb-0">
                                                            <a href="tel:{{ $order->phone ?? "N/A" }}"><strong>{{ $order->phone ?? "N/A" }}</strong></a>
                                                        </p>
                                                        <p class="mb-0">{{ $order->address ?? "N/A" }}</p>
                                                    </td>
                                                    <td>৳ {{ $order ->total }}</td>                                    
                                           <td> {!!@$order->my_courier!!} </td>
                                                                                    
                                      <td>
                                                        {{date('d M, Y',strtotime($order->created_at))}}<br>
                                            {{date('h:i:s A',strtotime($order->created_at))}}
                                                    </td>
                                                    <td>
                                                    <div class="btn-group">
                @if($order->status==1)
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Processing</button>
                @elseif($order->status==2)
                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Courier Entry</button>
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
</div>
                                                    </td>
                                                    <!-- <td>
                                                
                                            
                                                    </td>
                                                    <td></td> -->
                                                    <!-- <td class="action-button">
                                                        <div class="list-group ">
                                                            <div class="list-group-item d-grid">
                                                                <a href="{{route('order.edit', $order->id)}}" class="btn  btn-icon">

                                                                    <i class="fa-solid fa-pen-to-square tx-17"></i>

                                                                </a>
                                                                <a href="" data-toggle="modal" data-target="#delete{{$order->id}}" class="btn  btn-icon">

                                                                    <i class="text-danger fa-solid fa-delete-left tx-17"></i>

                                                                </a>

                                                            </div>
                                                        </div>


                                                    </td> -->
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
          
          
          


@endsection


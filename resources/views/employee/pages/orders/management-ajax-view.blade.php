<div class="table-responsive">
<table class="table mg-b-0 table-bordered table-striped ">
    <thead>
        <tr>
            <th scope="col">
                <input type="checkbox" class="chkCheckAll">
            </th>
            <th scope="col">#Sl</th>
            <th scope="col">Invoice ID</th>
            <th scope="col">Customer Info </th>
             <th scope="col">Score</th>
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
    <tbody>
        @foreach($orders as $key=>$order)
        <?php
            $check_duplicate = App\Models\Order::where('phone',$order->phone)->take(2)->count();
        ?>
        <tr>
        <th scope="row">
            <input type="checkbox" class="sub_chk" data-id="{{$order->id}}">
        </th>
        <td>{{ $key+1 }}</td>
        <td>{{$order->id}} <br>
            @if($order->coming=='1')
                    
                    <span class="tx-10 font-weight-bold text-white bg-success pd-4">Landing</span>
                @endif</td>
            <!-- <td class='{{$check_duplicate>1?"bg-danger-light":""}}'> -->
        <td class="position-relative {{$order->order_check>1 ? 'bg-danger-light' : ''}}" style="vertical-align:top;">
             @if($order->phone)
        <div style="position:absolute; top:5px; right:5px; display:flex; flex-direction:column; gap:4px; z-index:10;">
            <!-- WhatsApp -->
           @php
            // Remove non-digits
            $raw = preg_replace('/\D/', '', $order->phone);

            // Normalize to digits-only 880 format
            if (substr($raw, 0, 5) === '00880') {
                // Strip leading 00 → 0088017... => 88017...
                $waPhone = substr($raw, 2);
            } elseif (substr($raw, 0, 4) === '8801') {
                // Already valid 8801...
                $waPhone = $raw;
            } elseif (substr($raw, 0, 1) === '0') {
                // Local format 017... → 88017...
                $waPhone = '880' . substr($raw, 1);
            } else {
                // Fallback → prepend 880
                $waPhone = '880' . $raw;
            }

            // Always display with +
            $waPhoneDisplay = '+' . $waPhone;
        @endphp

        <!-- WhatsApp -->
        <a href="https://wa.me/{{ $waPhone }}" target="_blank" title="WhatsApp {{ $waPhoneDisplay }}">
            <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" style="width:18px;height:18px;">
        </a>

            <!-- IMO -->
            <!-- <a href="imo://chat/{{ preg_replace('/\D/', '', $order->phone) }}" target="_blank" title="IMO">
                <img src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/imo-icon.png" style="width:18px;height:18px;">
            </a> -->
        </div>
        @endif

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

                @foreach($order->many_cart as $cart)
                    <a href="{{route('details',$cart->product->slug)}}" target="_blank"><p> <span class="tx-10 font-weight-bold text-white bg-crystal-clear pd-4">{{$cart->quantity}}</span>  {{$cart->product->name }}</p></a>
                    @foreach (@$cart->AtrItem as $atr_item)
                            
                            <strong> {{ @$atr_item->productAttr->name }}: </strong>
                            {{ @$atr_item->name}},
                        @endforeach
                @endforeach

                

                </td>
               


                 <td>৳ {{ $order ->total }}</td>
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
                    <button class="dropdown-item" type="button" onclick="statusChange(1,{{ $order->id }})">Processing</button>
                    @endif
                    @if($order->status!=2)
                        <button type="button" class="dropdown-item" onclick="statusChange(2,{{ $order->id }})" href="#">Pending Delivery</button>
                    @endif
                    @if($order->status!=3)
                        <button type="button" class="dropdown-item" onclick="statusChange(3,{{ $order->id }})" href="#">On Hold</button>
                    @endif
                    @if($order->status!=4)
                        <button type="button" class="dropdown-item" onclick="statusChange(4,{{ $order->id }})" href="#">Cancel</button>
                    @endif
                    @if($order->status!=5)
                        <button type="button" class="dropdown-item" onclick="statusChange(5,{{ $order->id }})" href="#">Completed</button>
                    @endif

                    @if($order->status!=6)
                        <button type="button" class="dropdown-item" onclick="statusChange(6,{{ $order->id }})"  href="#">Pending Payment</button>
                    @endif
                    @if($order->status!=7)
                        <button type="button" class="dropdown-item" onclick="statusChange(7,{{ $order->id }})"  href="#">On Delivery</button>
                    @endif
                    @if($order->status!=8)
                        <button type="button" class="dropdown-item" href="#" onclick="statusChange(8,{{ $order->id }})" >No Response 1</button>
                    @endif
                    @if($order->status!=9)
                        <button type="button" class="dropdown-item" href="#" onclick="statusChange(9,{{ $order->id }})">No Response 2</button>
                    @endif
                    
                    @if($order->status!=11)
                        <button type="button" class="dropdown-item" onclick="statusChange(11,{{ $order->id }})" href="#">Courier Hold</button>
                    @endif
                    @if($order->status!=12)
                        <button type="button" class="dropdown-item" onclick="statusChange(12,{{ $order->id }})" href="#">Return</button>
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
                                        <!-- <form action="{{ route('order.noted_edit', $order->id)}}" method="POST" class="assign_f_button">
                                            @csrf -->
                                            <div class="col-md-12">
                                                <textarea name="order_noted" id="order_noted_{{ $order->id }}" >{{$order->order_note ??"N/A"}} </textarea>
                                                <input onclick="notedEdit({{ $order->id }})" type="button" value="Save" name="delete" class="btn btn-success btn-block mt-2 noted_e_button" >
                                            </div>

                                        <!-- </form> -->  
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
        @endforeach
    </tbody>

     
    @if($orders->count()==0)

<div class="alert alert-danger">sorry! No Orders Found.</div>
@endif
</table>
</div>
<div class="row">
                <div class="col-lg-12">
                    <div style="overflow-x: auto;">
                    </div>
                    <div class="ht-80 bd d-flex align-items-center justify-content-center">
                    <ul class="pagination pagination-basic pagination-danger mg-b-0">
                            <li>{{$orders->withQueryString()->links()}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            
            <script>
  $(function(){
    $(".chkCheckAll").click(function(){
      $(".sub_chk").prop('checked',$(this).prop('checked'));
      $(".checkBoxClass").prop('checked',$(this).prop('checked'));
    })
  })
</script>



<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend.includes.header')
    @include('backend.includes.css')

</head>

<body>


    @include('backend.includes.leftmenu')


    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>

    <div class="br-mainpanel">

        <div class="br-pagebody">
            <form action="{{ route('order.update', $order->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-5">
                        <div class="br-section-wrapper" style="padding: 10px !important;">
                            <div class="col-md-4">
                                <a href="{{ route('order.manage') }}" class="btn btn-sm btn-info w-25"><i
                                        class="fa-solid fa-arrow-left fa-beat-fade"></i></a>
                            </div>
                            <div class="card" data-select2-id="11">
                                <h4 class="card-header">Customer Info</h4>
                                <div class="card-body" data-select2-id="10">
                                    @error('message')
                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                    @enderror
                                    @error('invoice')
                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                    @enderror
                                    @error('merchant_invoice_id')
                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                    @enderror
                                    <div class="form-row">

                                        <div class="form-group col-md-6 col-12">
                                            <label for="order_date">Order Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control datetimepicker" id="order_date"
                                                value="{{ date('d M y', strtotime($order->created_at)) }}"
                                                name="order_date" readonly value="" required="">
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label for="invoice_id">Invoice ID <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="INV{{ $order->id }}"
                                                readonly="" required="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-12">
                                            <label for="customer_name">Customer Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ $order->name }}"
                                                id="customer_name" name="name" required="">
                                            @error('name')
                                                <p class="mt-2 text-danger">{{ $message }}</p>
                                            @enderror
                                            @error('recipient_name')
                                                <p class="mt-2 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label for="customer_phone">Customer Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" value="{{ $order->phone }}" class="form-control"
                                                id="customer_phone" name="phone" required="">
                                            @error('phone')
                                                <p class="mt-2 text-danger">{{ $message }}</p>
                                            @enderror
                                            @error('recipient_phone')
                                                <p class="mt-2 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label for="customer_address">Customer Address <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="address" id="customer_address" class="form-control">{{ $order->address }}</textarea>
                                            @error('address')
                                                <p class="mt-2 text-danger">{{ $message }}</p>
                                            @enderror
                                            @error('recipient_address')
                                                <p class="mt-2 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label for="courier_id">Courier Name </label>
                                            <select name="courier" id="courier_id" class="form-control select2">
                                                <option value="">Select A Courier</option>
                                                @foreach (App\Models\Courier::all() as $key => $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($item->id == old('courier', $order->courier)) selected @endif>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-12 redx_weight" style="@if(old('courier') != 1) display:none @endif" >
                                    <label for="redx_area_id">Area Name </label>
                                    <select name="area_id" id="redx_area_id" data-url="{{route('redx.areas')}}" class="form-control select2">
                                        <option value="">Select A area</option>
                                    </select>
                                    <input name="area_name" id="redx_area_name" type="hidden"/>
                                </div>

                           <div class="form-group col-md-6 col-12 redx_weight d-none" >
                                <label for="redx_weight">Weight (gram) <span
                                        class="text-danger">*</span></label>
                                <input type="text" value=".5" class="form-control" id="redx_weight"
                                    name="gram_weight" step="0.001" readonly>
                                @error('gram_weight')
                                    <p class="mt-2 text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                                    </div>

                                    <div class="pathao-courier" style=" @if(old('courier') != 3) display:none @endif">

                            <div class="form-row d-none">
                                <div class="form-group col-12">
                                <label for="pathao_store_id">Pathao Store Name <span class="text-danger">*</span></label>
                                <select name="pathao_store_id" id="pathao_store_id" class="form-control select2" data-url="{{route('pathao.get.stores')}}">
                                    <option value="" selected disabled>Select A Store</option>
                                </select>
                                <input type="hidden" name="sender_name" value="" id="sender_name"/>
                                <input type="hidden" name="sender_phone" value="" id="sender_phone"/>
                                @error('pathao_store_id')
                                    <p class="mt-2 text-danger">{{$message}}</p>
                                @enderror
                                </div>
                            </div>

                            <div class="form-row d-none">
                                <div class="form-group col-md-6 col-12">
                                <label for="sender_name">Sender Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{$setting->insta_link}}" id="sender_name" name="sender_name"   readonly>
                                @error('sender_name')
                                    <p class="mt-2 text-danger">{{$message}}</p>
                                @enderror
                                </div>
                                <div class="form-group col-md-6 col-12">
                                <label for="sender_phone">Sender Phone <span class="text-danger">*</span></label>
                                <input type="text" value="{{$setting->phone}}" class="form-control" id="sender_phone" name="sender_phone"  readonly>
                                @error('sender_phone')
                                <p class="mt-2 text-danger">{{$message}}</p>
                                @enderror
                                </div>
                            </div>


                            <div class="form-row">

                                <div class="form-group col-md-6 col-12 d-none">
                                    <label for="weight">Weight (0.5kg-10kg) <span class="text-danger">*</span></label>
                                    <input type="text" value=".5" class="form-control" id="weight" name="weight"   step="0.001" readonly>
                                    @error('weight')
                                        <p class="mt-2 text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12 col-12">
                                <label for="pathao_city_id">City Name <span class="text-danger">*</span></label>
                                <select name="pathao_city_id" id="pathao_city_id" class="form-control select2"  data-url="{{route('pathao.get.cities')}}">
                                    <option value="" selected disabled>Select A City</option>
                                </select>
                                @error('pathao_city_id')
                                    <p class="mt-2 text-danger">{{$message}}</p>
                                @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-12">
                                <label for="pathao_zone_id">Zone Name <span class="text-danger">*</span></label>
                                <select name="pathao_zone_id" id="pathao_zone_id" class="form-control select2"  data-url="{{route('pathao.get.zones')}}">
                                    <option value="" selected disabled>Select A Zone</option>
                                </select>
                                @error('pathao_zone_id')
                                <p class="mt-2 text-danger">{{$message}}</p>
                                @enderror
                                </div>
                                <div class="form-group col-md-6 col-12 d-none">
                                <label for="pathao_area_id">Area Name <span class="text-danger">*</span></label>
                                <select name="pathao_area_id" id="pathao_area_id" class="form-control select2" data-url="{{route('pathao.get.areas')}}">
                                    <option value="" selected disabled>Select A Area</option>
                                </select>
                                @error('pathao_area_id')
                                    <p class="mt-2 text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label for="status">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="0" disabled>Select Status</option>
                                                <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>
                                                    Processing</option>
                                                <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>
                                                    Pending Delivery</option>
                                                <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>On
                                                    Hold</option>
                                                <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>
                                                    Cancel</option>
                                                <option value="5" {{ $order->status == 5 ? 'selected' : '' }}>
                                                    Completed</option>
                                                <option value="6" {{ $order->status == 6 ? 'selected' : '' }}>
                                                    Pending Payment</option>
                                                <option value="7" {{ $order->status == 7 ? 'selected' : '' }}>On
                                                    Delivery</option>

                                                <option value="8" {{ $order->status == 8 ? 'selected' : '' }}>On
                                                    No Response 1</option>>


                                                  <option value="9" {{ $order->status == 9 ? 'selected' : '' }}>On
                                                    No Response 2</option>

                                                    <option value="11" {{ $order->status == 11 ? 'selected' : '' }}>On
                                                    Courier Hold</option>
                                                    <option value="12" {{ $order->status == 12 ? 'selected' : '' }}>On
                                                    Return</option>


                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="br-section-wrapper" style="padding: 10px !important;">
                            <div class="card">
                                <h4 class="card-header">Product Info</h4>
                                <div class="card-body">
                                    <div class="mb-3 table-responsive">
                                        <table class="table text-center table-bordered">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Product Name</th>
                                                    <th style="width: 18%;">Qty</th>
                                                    @foreach(App\Models\ProductAttribute::all() as $attribute)
                                                        <th>{{$attribute->name}}</th>
                                                    @endforeach
                                                    <th>Price</th>

                                                </tr>
                                            </thead>
                                            <tbody id="prod_row">

                                                <!-- @if ($order->products)
                                                    @foreach ($order->products as $product)
                                                        @include('backend.pages.orders.edit_product_row', [
                                                            'product' => $product->product,
                                                            'cart'    => $product,
                                                        ])
                                                    @endforeach
                                                @endif -->



                                        @if ($order->products && $order->products->count())
                                            @foreach ($order->products as $product)
                                                @if ($product->product) {{-- Only render if product exists --}}
                                                <tr class="product_item_row" id="product_item_row-{{ $product->product->id }}">
                                                        {{-- Delete button --}}
                                                        <td>
                                                            <a href="javascript:void(0)" class="remove_btn" data-price="{{ $product->price }}" data-row="{{ $product->product->id }}">
                                                                <i class="fa fa-trash text-danger"></i>
                                                            </a>
                                                        </td>

                                                        {{-- Product Name --}}
                                                        <td class="text-left">
                                                            {{ $product->product->name }}
                                                            <input type="hidden" class="product_id" name="products[{{ $product->product->id }}][id]" value="{{ $product->product->id }}">
                                                        </td>

                                                        {{-- Qty controls --}}
                                                        <td class="cart_qty">
                                                            <a href="javascript:void(0)" class="qty_minus btn-qty" data-id="{{ $product->product->id }}" data-price="{{ $product->price }}"><i class="fa fa-minus"></i></a>
                                                            <input type="text" class="qty_input" style="text-align: center; width: 35px; margin: 0 5px;" value="{{ $product->quantity }}" readonly name="products[{{ $product->product->id }}][quantity]" data-id="{{ $product->product->id }}">
                                                            <a href="javascript:void(0)" class="qty_plus btn-qty" data-id="{{ $product->product->id }}" data-price="{{ $product->price }}"><i class="fa fa-plus"></i></a>

                                                        </td>

                                                        {{-- Attribute selects --}}
                                                        @foreach(App\Models\ProductAttribute::all() as $attribute)
                                                            <td>
                                                                <select name="products[{{ $product->product->id }}][attribute][{{ $attribute->id }}]" class="p-2 wide attribute_item_id">
                                                                    <option>{{ $product->attributes[$attribute->id] ?? 'N/A' }}</option>
                                                                </select>
                                                            </td>

                                                        @endforeach

                                                        {{-- Price --}}
                                                        <td class="total_price">
                                                            <div class="unit_price" id="unit_price-{{ $product->product->id }}">{{ $product->price }}</div>
                                                            <input type="hidden" name="products[{{ $product->product->id }}][price]" value="{{ $product->price }}" class="pro_price" id="pro_price-{{ $product->product->id }}">
                                                        </td>

                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            {{-- Fallback row --}}
                                            <tr class="product_item_row fallback_row" id="product_item_row-fallback">
                                                {{-- Delete button --}}
                                                <td>
                                                    <a href="javascript:void(0)" class="remove_btn" data-price="0" data-row="fallback">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </a>
                                                </td>

                                                {{-- Product Name --}}
                                                <td class="text-left">
                                                    {{ $fallbackProductName ?? 'N/A' }}
                                                    <input type="hidden" class="product_id" name="products[fallback][id]" value="0">
                                                </td>

                                                {{-- Qty controls --}}
                                                <td class="cart_qty">
                                                    <a href="javascript:void(0)" class="qty_minus" data-id="fallback" data-price="0"><i class="fa fa-minus"></i></a>
                                                    <input type="text" class="qty_input" style="text-align: center; width: 35px; margin: 0 5px;" value="1" readonly name="products[fallback][quantity]" id="qty-fallback" data-id="fallback">

                                                    <a href="javascript:void(0)" class="qty_plus" data-id="fallback" data-price="0"><i class="fa fa-plus"></i></a>
                                                </td>

                                                {{-- Attribute selects --}}
                                                @foreach(App\Models\ProductAttribute::all() as $attribute)
                                                    <td>
                                                        <select name="products[fallback][attribute][{{ $attribute->id }}]" class="p-2 wide attribute_item_id">
                                                            <option>N/A</option>
                                                        </select>
                                                    </td>
                                                @endforeach

                                                {{-- Price --}}
                                            <td class="total_price">
                                                <div class="unite_price_fallback">0</div>

                                                <input type="hidden" name="products[fallback][price]" value="" id="pro_price-fallback" class="bg-transparent border-0">

                                                </td>



                                            </tr>
                                        @endif


                                            </tbody>
                                            <tbody>
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="form-row">
                                                            <div class="text-left form-group col-12">
                                                                <select id="product_id" class="form-control select2" data-url="{{route('add.product')}}">
                                                                    <option value="">Select A Product</option>
                                                                    @foreach (App\Models\Product::all() as $key => $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- custom cde -->
                                    <!-- custom cde -->
                                    <div class="form-group row" style="padding: 6px 0;">
                                        <div class="mb-0 form-group col-6">
                                            <input type="text" class="form-control" id="memo_number"
                                                placeholder="Memo Number">
                                        </div>
                                        <label for="sub_total" class="text-right col-md-2 col-form-label">Sub
                                            Total</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="sub_total"
                                                name="sub_total" min="0" value="{{ $order->sub_total }}"
                                                readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="padding: 6px 0;">
                                        <label for="shipping_cost"
                                            class="text-right offset-md-6 col-md-2 col-form-label">Shipping</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control shipping" id="shipping_cost"
                                                min="0" value="{{ $order->shipping_cost ?? 0 }}"
                                                name="shipping_cost">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="padding: 6px 0;">
                                        <label for="discount"
                                            class="text-right offset-md-6 col-md-2 col-form-label">Discount</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control pay" id="discount"
                                                value="{{ $order->discount ?? 0 }}" name="discount">
                                        </div>
                                    </div>

                                    <div class="form-group row" style="padding: 6px 0;">
                                        <label for="discount"
                                            class="text-right offset-md-6 col-md-2 col-form-label">Pay</label>
                                        <div class="col-md-4">
                                            <input type="text" value="{{ $order->pay }}"
                                                class="form-control discount" id="pay" name="pay">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="padding: 6px 0;">
                                        <label for="total"
                                            class="text-right offset-md-6 col-md-2 col-form-label">Total</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control total" id="total"
                                                min="0" name="total" value="{{ $order->total }}"
                                                readonly="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <textarea name="order_note" id="order_note" class="form-control" placeholder="Order Note">{{ $order->order_note }}</textarea>
                                        </div>
                                    </div>
                                    <div class="mt-3 row">
                                        <div class="text-center col-12">
                                            <input type="submit" value="Update" class="btn btn-success w-100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </div><!-- br-mainpanel -->
    <script src="{{ asset('backend/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/lib/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <script src="{{ asset('backend/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap-tagsinput.min.js') }}"></script>

    <script src="{{ asset('backend/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('backend/lib/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('backend/lib/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('backend/lib/rickshaw/vendor/d3.min.js') }}"></script>
    <script src="{{ asset('backend/lib/rickshaw/vendor/d3.layout.min.js') }}"></script>
    <script src="{{ asset('backend/lib/rickshaw/rickshaw.min.js') }}"></script>
    <script src="{{ asset('backend/lib/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('backend/lib/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('backend/lib/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
    <script src="{{ asset('backend/lib/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('backend/lib/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('backend/lib/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/lib/gmaps/gmaps.min.js') }}"></script>

    <script src="{{ asset('backend/js/bracket.js') }}"></script>
    <script src="{{ asset('backend/js/map.shiftworker.js') }}"></script>
    <script src="{{ asset('backend/js/ResizeSensor.js') }}"></script>
    <script src="{{ asset('backend/js/dashboard.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $('.select2').select2({
            dropdownCssClass: 'hover-success',
        });
    </script>



    <script type="text/javascript">
        $(document).ready(function() {





            $.ajax({
                type: 'get',
                url: $('#pathao_store_id').data('url'),
                success: function(data) {
                    $('#pathao_store_id').html(data);
                }
            })

            $.ajax({
                type: 'get',
                url: $('#pathao_city_id').data('url'),
                success: function(data) {
                    $('#pathao_city_id').html(data);
                }
            })

            $('#pathao_city_id').on('change', function() {

                $.ajax({
                    type: 'get',
                    url: $('#pathao_zone_id').data('url'),
                    data: {
                        'city_id': $('#pathao_city_id').val()
                    },
                    success: function(data) {
                        $('#pathao_zone_id').html(data);
                    }
                })
            })

            $('#pathao_zone_id').on('change', function() {
                $.ajax({
                    type: 'get',
                    url: $('#pathao_area_id').data('url'),
                    data: {
                        'zone_id': $('#pathao_zone_id').val()
                    },
                    success: function(data) {
                        $('#pathao_area_id').html(data);
                    }
                })
            })

            $('#courier_id').on('change', function() {
                if ($(this).val() == 3) {
                    $('.pathao-courier').show();
                    $('.redx_weight').hide();
                } else if ($(this).val() == 1) { //redx
                    $('.pathao-courier').hide();
                    $('.redx_weight').show();
                    redxAreas();
                } else {
                    $('.pathao-courier').hide();
                    $('.redx_weight').hide();
                }
            });

            //redx
            $('#redx_area_id').on('change', function() {
                var area = $("select#redx_area_id option").filter(":selected").data('area');
                $('#redx_area_name').val(area);
            });

            @if ($order->courier == 1)
                redxAreas();
            @endif

            function redxAreas() {
                $.ajax({
                    type: 'get',
                    url: $('#redx_area_id').data('url'),
                    success: function(data) {
                        $('#redx_area_id').html(data);
                    }
                })
            }

        });
    </script>



<script type="text/javascript">
    $(document).ready(function(){


        function shipping(){
            var discount  = parseInt($("#discount").val());
            var pay       = parseInt($("#pay").val());
            var sub_total = parseInt($("#sub_total").val());
            var shipping  = parseInt($('#shipping_cost').val());

            if(isNaN(discount)){
                discount = 0;
            }

            if(isNaN(pay)){
                pay = 0;
            }
            if(isNaN(sub_total)){
                sub_total = 0;
            }

            if(isNaN(shipping)){
                shipping = 0;
            }

            var calc      = parseInt(((sub_total + shipping ) - discount) - pay) ;
            $("#total").val(calc);
        }

        $(document).on('keyup',"#discount,#pay,#sub_total,#shipping_cost",function(){
            shipping();
        });
        $('#product_id').on('change', function() {

            const products = $('input.product_id').map(function () {
                            return this.value;
                        }).get();

            $.ajax({
                type: 'get',
                url: $(this).data('url'),
                data: {
                    'product_id': $(this).val()
                },
                success: function(data) {
                    if(data != '' && data.view){
                        if(products.includes(data.product.id.toString())){
                            p_price      = data.price;
                            plusQty(p_price,data.product.id);
                        }else{
                            $('#prod_row').append(data.view);
                            var sub_total = parseInt($('#sub_total').val());
                            var p_price   = 0;
                             p_price      = data.price;
                            sub_total     = sub_total + p_price;
                            $('#sub_total').val(sub_total);
                            shipping();
                        }
                        $("#product_id").val('').change();

                    }
                }
            })
        });


        function plusQty(price,product_id){
            var price = price;
            var qty   = parseInt($('#product_item_row-'+product_id).find('#qty').val());

            var total_qty  = (qty + 1);
            $('#product_item_row-'+product_id).find('#qty').val(total_qty)
            var total_unit_price = (price * total_qty);
            $('#product_item_row-'+product_id).find('#unit_price').html(total_unit_price);
            $('#product_item_row-'+product_id).find('#pro_price').val(total_unit_price);
            var sub_total     = parseInt($('#sub_total').val());
                sub_total     = (sub_total + price);
                $('#sub_total').val(sub_total);
                shipping();

        }


            $(document).on('click','.remove_btn',function(){
            var sub_total        = parseInt($('#sub_total').val()) || 0;
            var price            = parseInt($(this).data('price')) || 0;
            var qty   = parseInt($(this).closest('.product_item_row').find('.qty_input').val()) || 0;
            var total_amount     = parseInt($(this).closest('.product_item_row').find('.pro_price').val())*qty || 0;
            sub_total       = (sub_total - total_amount);
            $('#sub_total').val(sub_total);
            $(this).closest('.product_item_row').remove();
            shipping();
            });

        //      $(document).on('click', '.remove_btn', function() {
        //     var row = $(this).closest('tr');
        //     var price = parseInt($(this).data('price')) || 0;
        //     var qty = parseInt(row.find('input[name*="[quantity]"]').val()) || 1;
        //     var sub_total = parseInt($('#sub_total').val()) || 0;

        //     sub_total -= (price * qty);
        //     $('#sub_total').val(sub_total);

        //     row.remove(); // remove the row
        //     shipping();   // recalc total
        // });



        // $(document).on('click','#qty_plus',function(){
        //     var price = parseInt($(this).data('price'));
        //     var qty   = parseInt($(this).parent().find('#qty').val());
        //     var total_qty  = (qty + 1);
        //     $(this).parent().find('#qty').val(total_qty)
        //     var total_unit_price = (price * total_qty);
        //     // $(this).closest('tr').find('.total_price').find('#unit_price').html(total_unit_price);
        //     // $(this).closest('tr').find('.total_price').find('#pro_price').val(total_unit_price);
        //     var sub_total     = parseInt($('#sub_total').val());
        //         sub_total     = (sub_total + price);
        //         $('#sub_total').val(sub_total);
        //         shipping();
        // });


        // $(document).on('click','#qty_minus',function(){
        //     var price = parseInt($(this).data('price'));
        //     var qty   = parseInt($(this).parent().find('#qty').val());
        //     var total_qty  = (qty - 1);

        //     if(total_qty >= 1){
        //         $(this).parent().find('#qty').val(total_qty)
        //         var total_unit_price = (price * total_qty);
        //         // $(this).closest('tr').find('.total_price').find('#unit_price').html(total_unit_price);
        //         // $(this).closest('tr').find('.total_price').find('#pro_price').val(total_unit_price);
        //         var sub_total     = parseInt($('#sub_total').val());
        //             sub_total     = (sub_total - price);
        //             $('#sub_total').val(sub_total);
        //             shipping();
        //     }
        // });
        $(document).on('click', '.qty_plus', function() {
    var price = parseInt($(this).data('price'));
    var product_id = $(this).data('id');
    var qty_input = $('.qty_input[data-id="' + product_id + '"]');
    var qty = parseInt(qty_input.val());
    qty_input.val(qty + 1);

    var unit_price = price * (qty + 1);
    $('#unit_price-' + product_id).text(unit_price);
    $('#pro_price-' + product_id).val(unit_price);

    var sub_total = parseInt($('#sub_total').val()) || 0;
    $('#sub_total').val(sub_total + price);
    shipping();
});

$(document).on('click', '.qty_minus', function() {
    var price = parseInt($(this).data('price'));
    var product_id = $(this).data('id');
    var qty_input = $('.qty_input[data-id="' + product_id + '"]');
    var qty = parseInt(qty_input.val());
    if(qty > 1){
        qty_input.val(qty - 1);

        var unit_price = price * (qty - 1);
        $('#unit_price-' + product_id).text(unit_price);
        $('#pro_price-' + product_id).val(unit_price);

        var sub_total = parseInt($('#sub_total').val()) || 0;
        $('#sub_total').val(sub_total - price);
        shipping();
    }
});


    });
</script>


</body>

</html>

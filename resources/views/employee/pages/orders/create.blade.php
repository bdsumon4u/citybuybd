@extends('employee.layout.template')
@section('body-content')


<style>
    .select2-container{
        width: 100%!important;
    }
</style>

    <div class="br-pagebody">
        <form action="{{ route('employee.order.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="br-section-wrapper" style="padding: 10px !important;">
                        <div class="col-md-4">
                            <a href="{{ route('order.manage') }}" class="btn btn-sm btn-info w-25"><i
                                    class="fa-solid fa-arrow-left fa-beat-fade"></i></a>
                        </div>
                        <div class="card" data-select2-id="11">
                            <h4 class="card-header">Customer Info</h4>
                            <div class="card-body" data-select2-id="10">
                                @error('message')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                                @error('merchant_invoice_id')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                                @error('invoice')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                                <div class="form-row">

                                    <div class="form-group col-md-6 col-12">
                                        <label for="order_date">Order Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control " id="order_date" name="order_date"
                                            value="<?php echo date('Y-m-d'); ?>" required="">
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label for="invoice_id">Invoice ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="" readonly=""
                                            required="">
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-md-6 col-12">
                                        <label for="customer_name">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="" onkeyup='saveValue(this);'
                                            id="customer_name" name="name" required="">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                        @error('recipient_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label for="customer_phone">Customer Phone <span
                                                class="text-danger">*</span></label>
                                        <input type="text" onkeyup='saveValue(this);' class="form-control"
                                            id="customer_phone" name="phone" required="">
                                        @error('phone')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                        @error('recipient_phone')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="customer_address">Customer Address <span
                                                class="text-danger">*</span></label>
                                        <textarea name="address" onkeyup='saveValue(this);' id="customer_address" class="form-control"></textarea>
                                        @error('address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                        @error('recipient_address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
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
                                                    @if ($item->id == old('courier')) selected @endif>{{ $item->name }}
                                                </option>
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
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                                    
                                </div>


                                <div class="pathao-courier  " style=" @if(old('courier') != 3) display:none @endif">

                            <div class="form-row d-none">
                                <div class="form-group col-12">
                                <label for="pathao_store_id">Pathao Store Name <span class="text-danger">*</span></label>
                                <select name="pathao_store_id" id="pathao_store_id" class="form-control select2" data-url="{{route('pathao.get.stores')}}">
                                    <option value="" selected disabled>Select A Store</option>
                                </select>
                                <input type="hidden" name="sender_name" value="" id="sender_name"/>
                                <input type="hidden" name="sender_phone" value="" id="sender_phone"/>
                                @error('pathao_store_id')
                                    <p class="text-danger mt-2">{{$message}}</p>
                                @enderror
                                </div>
                            </div>

                            <div class="form-row d-none">
                                <div class="form-group col-md-6 col-12">
                                <label for="sender_name">Sender Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{$setting->insta_link}}" id="sender_name" name="sender_name"   readonly>
                                @error('sender_name')
                                    <p class="text-danger mt-2">{{$message}}</p>
                                @enderror
                                </div>
                                <div class="form-group col-md-6 col-12">
                                <label for="sender_phone">Sender Phone <span class="text-danger">*</span></label>
                                <input type="text" value="{{$setting->phone}}" class="form-control" id="sender_phone" name="sender_phone"  readonly>
                                @error('sender_phone')
                                <p class="text-danger mt-2">{{$message}}</p>
                                @enderror
                                </div>
                            </div>


                            <div class="form-row">

                                <div class="form-group col-md-6 col-12 d-none">
                                    <label for="weight">Weight (0.5kg-10kg) <span class="text-danger">*</span></label>
                                    <input type="text" value=".5" class="form-control" id="weight" name="weight"   step="0.001" readonly>
                                    @error('weight')
                                        <p class="text-danger mt-2">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12 col-12">
                                <label for="pathao_city_id">City Name <span class="text-danger">*</span></label>
                                <select name="pathao_city_id" id="pathao_city_id" class="form-control select2"  data-url="{{route('pathao.get.cities')}}">
                                    <option value="" selected disabled>Select A City</option>
                                </select>
                                @error('pathao_city_id')
                                    <p class="text-danger mt-2">{{$message}}</p>
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
                                <p class="text-danger mt-2">{{$message}}</p>
                                @enderror
                                </div>
                                <div class="form-group col-md-6 col-12 d-none">
                                <label for="pathao_area_id">Area Name <span class="text-danger">*</span></label>
                                <select name="pathao_area_id" id="pathao_area_id" class="form-control select2" data-url="{{route('pathao.get.areas')}}">
                                    <option value="" selected disabled>Select A Area</option>
                                </select>
                                @error('pathao_area_id')
                                    <p class="text-danger mt-2">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                                
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-12">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control select2">
                                            <option value="0" disabled>Select Status</option>
                                            <option value="1">Processing</option>
                                            <option value="2">Pending Delivery</option>
                                            <option value="3">On Hold</option>
                                            <option value="4">Cancel</option>
                                            <option value="5">Completed</option>
                                            <option value="6">Pending Payment</option>
                                            <option value="7">On Delivery</option>
                                            <option value="8">No Response 1</option>
                                            <option value="9">No Response 2</option>
                                            <option value="10">No Response 3</option>
                                            <option value="11">Courier Hold</option>
                                            <option value="12">Return</option>
                                        </select>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="br-section-wrapper" style="padding: 10px !important;">
                        <div class="card">
                            <h4 class="card-header">Product Info</h4>
                            <div class="card-body">
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                 <th></th>
                                                <th>Product Name</th>
                                                <th>Qty</th>
                                                @foreach(App\Models\ProductAttribute::all() as $attribute)
                                                    <th>{{$attribute->name}}</th>
                                                @endforeach
                                                <th>Price</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody id="prod_row">
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="5">
                                                    <div class="form-row">
                                                        <div class="form-group col-12 text-left">
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
                                    <div class="form-group col-6 mb-0">
                                        <input type="text" class="form-control" id="memo_number"
                                            placeholder="Memo Number">
                                    </div>
                                    <label for="sub_total" class="col-md-2 col-form-label text-right">Sub Total</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="sub_total" name="sub_total"
                                            min="0" value="0" readonly="">
                                    </div>
                                </div>
                                <div class="form-group row" style="padding: 6px 0;">
                                    <label for="shipping_cost"
                                        class="offset-md-6 col-md-2 col-form-label text-right">Shipping</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control shipping" id="shipping_cost"
                                            min="0" value="0" name="shipping_cost">
                                    </div>
                                </div>
                                <div class="form-group row" style="padding: 6px 0;">
                                    <label for="discount"
                                        class="offset-md-6 col-md-2 col-form-label text-right">Discount</label>
                                    <div class="col-md-4">
                                        <input type="text" value="0" class="form-control discount"
                                            id="discount" name="discount">
                                    </div>
                                </div>

                                <div class="form-group row" style="padding: 6px 0;">
                                    <label for="discount"
                                        class="offset-md-6 col-md-2 col-form-label text-right">Pay</label>
                                    <div class="col-md-4">
                                        <input type="text" value="0" class="form-control pay" id="pay"
                                            name="pay">
                                    </div>
                                </div>

                                <div class="form-group row" style="padding: 6px 0;">
                                    <label for="total"
                                        class="offset-md-6 col-md-2 col-form-label text-right">Total</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control total" id="total" min="0"
                                            name="total" value="0" readonly="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <textarea name="order_note" id="order_note" class="form-control" placeholder="Order Note"></textarea>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 text-center">
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


    <script src="{{ asset('backend/lib/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/lib/select2/js/select2.full.min.js') }}"></script>

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

            $('#courier_id').on('change',function(){
                if($(this).val() == 3){
                    $('.pathao-courier').show();
                    $('.redx_weight').hide();
                }else if($(this).val() == 1){ //redx
                    $('.pathao-courier').hide();
                    $('.redx_weight').show();
                    redxAreas();
                }else{
                    $('.pathao-courier').hide();
                    $('.redx_weight').hide();
                }
            });

            //redx
            $('#redx_area_id').on('change',function(){
                var area =  $("select#redx_area_id option").filter(":selected").data('area');
                $('#redx_area_name').val(area);
            });

            function redxAreas(){
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
                var discount  = parseFloat($("#discount").val());
                var pay       = parseFloat($("#pay").val());
                var sub_total = parseFloat($("#sub_total").val());
                var shipping  = parseFloat($('#shipping_cost').val());

                if(isNaN(discount)){ discount = 0; }
                if(isNaN(pay)){ pay = 0; }
                if(isNaN(sub_total)){ sub_total = 0; }
                if(isNaN(shipping)){ shipping = 0; }

                var calc = (sub_total + shipping) - discount - pay;
                $("#total").val(calc);
            }

            function refreshSubTotal(){
                var subTotal = 0;
                $('.product_item_row').each(function(){
                    var qty = parseInt($(this).find('.qty_input').val(), 10) || 0;
                    var unitPrice = parseFloat($(this).find('.pro_price').val()) || 0;
                    var lineTotal = qty * unitPrice;
                    subTotal += lineTotal;
                });
                $('#sub_total').val(subTotal);
                shipping();
            }

            $(document).on('keyup',"#discount,#pay,#shipping_cost",function(){
                shipping();
            });

            $('#product_id').on('change', function() {
                const products = $('input.product_id').map(function () {
                    return this.value;
                }).get();
                var pro_id = $(this).val();
                $.ajax({
                    type: 'get',
                    url: $(this).data('url'),
                    data: {
                        product_id:pro_id
                    },
                    success: function(data) {
                        if(data != '' && data.view){
                            if(products.includes(data.product.id.toString())){
                                var price = parseFloat(data.price);
                                var qtyInput = $('#product_item_row-'+data.product.id).find('.qty_input');
                                var currentQty = parseInt(qtyInput.val(), 10) || 0;
                                qtyInput.val(currentQty + 1);
                                refreshSubTotal();
                            }else{
                                $('#prod_row').append(data.view);
                                refreshSubTotal();
                            }
                            $("#product_id").val('').change();
                        }
                    }
                })
            });

            $(document).on('click','.remove_btn',function(){
                $(this).closest('.product_item_row').remove();
                refreshSubTotal();
            });

            $(document).on('click', '.qty_plus', function() {
                var qtyInput = $(this).closest('.cart_qty').find('.qty_input');
                qtyInput.val((parseInt(qtyInput.val(), 10) || 0) + 1);
                refreshSubTotal();
            });

            $(document).on('click', '.qty_minus', function() {
                var qtyInput = $(this).closest('.cart_qty').find('.qty_input');
                var qty = parseInt(qtyInput.val(), 10) || 0;
                if(qty > 1){
                    qtyInput.val(qty - 1);
                    refreshSubTotal();
                }
            });
            
            $('.select2').select2({
                dropdownCssClass: 'hover-success',
            });

        });
    </script>
@endsection

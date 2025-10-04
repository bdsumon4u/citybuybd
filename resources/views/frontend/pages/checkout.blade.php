@extends('frontend.layout.template')
 @section('body-content')
 @section('pageTitle', 'Checkout')


 <!--------------------------------- CART SECTION START --------------------------------->
 <div class="tab-section py-4">
     <div class="container">
         <div class="row">
             <div class="col-12">
                 <div class="tab-nav justify-content-center">
                     <button class="single-nav active" data-tab="checkOutTab" disabled>
                         <span class="txt btn-bangla">অর্ডারটি কনফার্ম করতে আপনার নাম, ঠিকানা, মোবাইল নাম্বার, লিখে অর্ডার কনফার্ম করুন বাটনে ক্লিক করুন</span>
                         <span class="sl-no">00</span>
                     </button>
                 </div>
                 <div class="tab-contents">


                     <div class="single-tab active" id="checkOutTab">
                         <div class="row gap-1">
                             <div class="col-xl-5 col-lg-5 col-md-5" style="border: 1px dashed #bdbdbd52;padding: 20px;">
                                 <div class="billing-details">
                                     <form action="{{route('order')}}" method="POST" id="checkout_form" class="form-row checkout_form" >
                                         @csrf
                                         <div class="form-col-5">
                                             <label for="customer_name">আপনার নাম </label>
                                             <input type="text"  class="form-control" id="name" name="name" placeholder="আপনার নাম লিখুন" required>
                                         </div>
                                         <div class="form-col-5">
                                             <label for="customer_address">আপনার ঠিকানা</label>
                                             <input type="text"  class="form-control" id="address" name="address" placeholder="আপনার ঠিকানা লিখুন"
                                                    required>
                                         </div>
                                         <div class="form-col-5">
                                             <label for="customer_phone">আপনার মোবাইল</label>
                                             <input type="tel" class="form-control" id="phone" name="phone"
                                            pattern="^(?:\+?88)?01[13-9]\d{8}$"
                                            onkeyup="checkScore()" placeholder="অবশ্যই ১১ অক্ষর হবে" required>

                                             <!-- <input type="number"  class="form-control" id="phone" name="phone" pattern="^(?:\+?88)?01[13-9]\d{8}$" onkeyup="checkScore()" placeholder="অবশ্যই ১১ অক্ষর হবে" required> -->
                                         </div>
                                         <div class="form-col-5">
                                             <label for="shipping_method">আপনার এরিয়া সিলেক্ট করুন</label>
                                             <select name="shipping_method"  id="shipping_method" class="form-control " required>
                                                @foreach(Cart::content() as $cart)
                                                    <?php $prd = App\Models\Product::find($cart->id);  ?> 
                                                    @if($prd->shipping == '1')
                                                        <option value="0" data-amount="0">ঢাকার বাইরে ( ফ্রি ডেলিভারি ) </option>
                                                        <option value="0" data-amount="0">ঢাকার ভিতরে ( ফ্রি ডেলিভারি )</option>
                                                    @elseif($prd->shipping == '0')
                                                        <option value="{{$prd->outside}}" data-amount="{{$prd->outside}}">ঢাকার বাইরে </option>
                                                        <option value="{{$prd->inside}}" data-amount="{{$prd->inside}}">ঢাকার ভিতরে </option>
                                                    @else   
                                                        @foreach($shippings as $shipping)
                                                             <option value="{{$shipping->amount}}" data-amount="{{ $shipping->amount }}">{{$shipping->type}} </option>
                                                        @endforeach
                                                    @endif
                                                    @break;
                                                @endforeach
                                             </select>
                                         </div>

                                         

                                         <input type="hidden" name="sub_total" value="{{ Cart::subtotal() }}">
                                         <button type="submit" class="def-btn palce-order tab-next-btn btn-success w-100 btn-bangla" id="conf_order_btn">অর্ডার কনফার্ম করুন <i class="fa-light fa-truck-arrow-right"></i></button>

                                         <!-- incomplete order hidden fields -->
                                         <input type="hidden" id="incomplete_token" value="{{ $incompleteToken }}">

                                        @foreach(Cart::content() as $cartItem)
                                            <input type="hidden" class="product_id" name="product_ids[]" value="{{ $cartItem->id }}">
                                            <input type="hidden" class="product_slug" name="product_slugs[]" value="{{ $cartItem->options['slug'] ?? '' }}">
                                        @endforeach


                                        <input type="hidden" id="cart_snapshot" value='@json($carts->toArray())'>
                                        <meta name="csrf-token" content="{{ csrf_token() }}">
                                        <!--  -->
                                        @foreach(Cart::content() as $cartItem)
                                        
                                            <input type="hidden" name="product_color[]" value="{{ $cartItem->options['color'] ?? '' }}">
                                            <input type="hidden" name="product_size[]"  value="{{ $cartItem->options['size'] ?? '' }}">
                                            <input type="hidden" name="product_model[]" value="{{ $cartItem->options['model'] ?? '' }}">
                                        @endforeach

                                     </form>
                                 </div>
                             </div>
                             <div class="col-xl-6 col-lg-6 col-md-6" style="border: 1px dashed #bdbdbd52;">
                                 <div class="table-wrap revel-table">
                                     <div class="table-responsive">
                                         <table class="cart_table table text-center table-borderless" >
                                             <thead>
                                             <tr>
                                                 <th></th>
                                                 <th>Product</th>
                                                 <th>Price</th>
                                                 <th>Quantity</th>
                                                 <th>Total</th>
                                                 <th></th>
                                             </tr>
                                             </thead>
                                             <tbody>
                                             @foreach(Cart::content() as $cart)
                                               
                                             <tr>
                                                 <td>
                                                     <div class="product-img">
                                                         <img src="{{  $cart->options['image'] }}" alt="Image">
                                                     </div>
                                                 </td>
                                                 <td>
                                                     <a href="{{route('details',$cart->options['slug'])}}" class="product-name">
                                                         {{$cart->name}} 
                                                     </a>
                                                 </td>
                                                 <td><span class="price-txt">{{$cart->price}}</span></td>
                                                 <td class="cart_qty">
                                                     <div class="product-count cart-product-count">
                                                         <div class="quantity-1 rapper-quantity">
                                                             
                                                             
                                                             <div class="qty_div_1">
                                                                <div class="q-down">
                                                                    <i class="fa-solid fa-minus qty_minus" id="qty_minus{{$cart->id}}" data-id="{{$cart->rowId}}"   data-qty="{{$cart->qty}}"></i>
                                                                </div>
                                                                <div class="qty-div">
                                                                    <input type="number" name="qty" id="qty" min="1" class="qty qty{{$cart->rowId}}" data-id="{{$cart->rowId}}" value="{{$cart->qty}}">
                                                                </div>
                                                                <div class="q-up">
                                                                    <i class="fa-solid fa-plus qty_plus" id="qty_plus{{$cart->id}}" data-id="{{$cart->rowId}}" data-qty="{{$cart->qty}}"></i>
                                                                </div>
                                                            </div>
                                                             
                                                             
                                                             
                                                         </div>
                                                     </div>
                                                 </td>

                                                 <td class="c_price{{$cart->rowId}}">{{$cart->total}}</td>
                                                 <td><a href="{{route('cart.destroy', $cart->rowId)}}" class="cart-delete"><i class="fa-light fa-trash-can"></i></a></td>
                                             </tr>
                                              
                                             @endforeach
                                             </tbody>


                                         </table>
                                     </div>
                                 </div>






                                 <div class="payment-method">
                                     <div class="total-clone">
                                         <ul>
                                             <li>Sub Total <span class="price-txt" id="sub_total">৳{{  Cart::subtotal() }}</span></li>
                                             <li>Shipping <span class="price-txt" id="cart_shipping_cost">0</span></li>
                                             <li class="total-price-wrap">Total <span class="price-txt" id="net_total">৳<span id="totalPrice2">{{ Cart::total() }}</span></span></li>
                                         </ul>
                                     </div>
                                     
                                 </div>
                             </div>
                         </div>
                     </div>

                 </div>
             </div>
         </div>
     </div>
 </div>
 <!--------------------------------- CART SECTION END --------------------------------->




@endsection
@push('child-scripts')
 
<script>

    


        $("#shipping_method").on("change",function(e){
            e.preventDefault();
            
            var shipping_amount= $(this).find(':selected').attr('data-amount');
            $("#cart_shipping_cost").text(shipping_amount);
            var total= {{  Cart::subtotal() }}+ parseInt(shipping_amount);
            $("#net_total").text(total);
  
        });
        
        $( document ).ready(function() {
    var shipping_amount= $(this).find(':selected').attr('data-amount');
            $("#cart_shipping_cost").text(shipping_amount);
            var total= {{  Cart::subtotal() }}+ parseInt(shipping_amount);
            $("#net_total").text(total);
});

     



$(".qty_plus").on("click",function(){
 
      var rowId= $(this).attr("data-id");
      var quantity = parseInt($('.qty'+rowId).val());
         quantity += 1; 
  
     $.ajax({
            type: 'GET',
            url: "/cart_plus",
            dataType:"json",
            data:{
                rowId:rowId,
                qty:quantity, 
            },
            success:function(data){
                    location.reload();
                    // $('.qty'+rowId).val(quantity);
                    // $('.c_price'+rowId).text(quantity*price);

                    // $("#sub_total").text(data.totalPrice);
                    // $("#net_total").text(data.totalPrice+ parseInt($("#cart_shipping_cost").text()));



            }

        })



});

$(".qty").keyup(function(){
    
var id= $(this).attr("data-id");
var qtyy= $(this).val();
var urll= "/cart_input/"+ id + '/' + qtyy;



     $.ajax({
            type: 'GET',
            url: urll,
            dataType:"json",
            success:function(data){
                
                console.log(data);

                    $('.qty'+id).val(data.cart.quantity);
                    $('.c_price'+id).text(data.cart.quantity*data.cart.price);

                    $("#sub_total").text(data.totalPrice);
                    $("#net_total").text(data.totalPrice+ parseInt($("#cart_shipping_cost").text()));



            }

        })



});

$(".qty").keyup(function(){
 
      var rowId= $(this).attr("data-id");
      var qtyy= $(this).val();
  
     $.ajax({
            type: 'GET',
            url: "/cart_plus",
            dataType:"json",
            data:{
                rowId:rowId,
                qty:qtyy, 
            },
            success:function(data){
                    location.reload();
                    // $('.qty'+rowId).val(quantity);
                    // $('.c_price'+rowId).text(quantity*price);

                    // $("#sub_total").text(data.totalPrice);
                    // $("#net_total").text(data.totalPrice+ parseInt($("#cart_shipping_cost").text()));



            }

        })

});



$(".qty_minus").on("click",function(){
 
      var rowId= $(this).attr("data-id");
      var quantity = parseInt($('.qty'+rowId).val());
         quantity -= 1; 
  
     $.ajax({
            type: 'GET',
            url: "/cart_plus",
            dataType:"json",
            data:{
                rowId:rowId,
                qty:quantity, 
            },
            success:function(data){
                    location.reload();
                    // $('.qty'+rowId).val(quantity);
                    // $('.c_price'+rowId).text(quantity*price);

                    // $("#sub_total").text(data.totalPrice);
                    // $("#net_total").text(data.totalPrice+ parseInt($("#cart_shipping_cost").text()));



            }

        })



});

// END


</script>

@endpush

<!-- incomplete order -->

@push('child-scripts')
<script>
/* Ensure CSRF header for all jQuery ajax */
$.ajaxSetup({
  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

function debounce(fn, delay = 600){
    let t = null;
    return function(){ clearTimeout(t); t = setTimeout(()=> fn.apply(this, arguments), delay); };
}

function normalizePhone(raw){
    let digits = String(raw || '').replace(/\D/g,'');
    // if user pasted +880... or 880... this will drop the leading 88
    if (digits.length === 13 && digits.startsWith('88')) digits = digits.slice(2);
    return digits;
}
function phoneIsComplete(raw){
    const p = normalizePhone(raw);
    return p.length === 11 && /^01[13-9]\d{8}$/.test(p);
}

function collectPayload(){
    const shippingOpt = $('#shipping_method option:selected');
    const shippingAmount = parseInt(shippingOpt.data('amount')) || 0;

    return {
        _token: $('meta[name="csrf-token"]').attr('content'), // safe fallback
        token: $('#incomplete_token').val(), // your session incomplete token
        name: $('#name').val() || null,
        address: $('#address').val() || null,
        // send normalized phone so server receives "017xxxxxxxx"
        phone: normalizePhone($('#phone').val()) || null,
        shipping_method_label: shippingOpt.text() || null,
        shipping_amount: shippingAmount,
        sub_total: String($('#sub_total').text() || '{{ Cart::subtotal() }}').replace(/[^\d]/g, ''),
        total: String($('#net_total').text() || '').replace(/[^\d]/g, ''),
        product_ids: $('.product_id').map((i, el) => $(el).val()).get(),
        product_slugs: $('.product_slug').map((i, el) => $(el).val()).get(),
        cart_snapshot: $('#cart_snapshot').val() || null
    };
}

const autoSave = debounce(function(){
    const rawPhone = $('#phone').val();
    const normalized = normalizePhone(rawPhone);
    console.log('[incomplete-order] phone check raw:', rawPhone, 'normalized:', normalized);

    if (!phoneIsComplete(rawPhone)) {
        console.log('[incomplete-order] phone not complete -> skipping save');
        return;
    }

    const payload = collectPayload();
    console.log('[incomplete-order] autoSave -> payload:', payload);

    $.post('{{ route("incomplete-order.auto-save") }}', payload)
      .done(function(resp){
         console.log('[incomplete-order] auto-save OK', resp);
      })
      .fail(function(xhr){
         console.error('[incomplete-order] auto-save failed', xhr.status, xhr.responseText);
      });
}, 700);

/* Bind events */
$('#name, #address, #phone').on('input', autoSave);
$('#shipping_method').on('change', autoSave);
setTimeout(autoSave, 800);
</script>

@endpush



    <tr class="product_item_row" id="product_item_row-{{$product->id}}">
        <td>
            <a href="javascript:void(0)" data-price="{{ ($product->offer_price ?? $product->regular_price) * 1 }}" class="remove_btn"><i class="fa fa-trash  text-danger" style="cursor: pointer"></i></a>
        </td>
    
        <td class="text-left">{{ $product->name ?? 'N/A' }} 
        <input type="hidden" class="product_id" name="products[{{$product->id}}][id]" value="{{$product->id}}"/>
        </td>
        <td width="" class="cart_qty">
            <a href="javascript:void(0)" onclick="PriceMinus({{$product->id}},{{ ($product->offer_price ?? $product->regular_price) * 1 }})" id="qty_minus"  data-price="{{ ($product->offer_price ?? $product->regular_price) * 1 }}"><i class="fa fa-minus "></i></a>
            <input style="text-align: center;  width: 35px; margin: 0 5px 0 5px;" class="qty_{{ $product
            ->id }}" type="text" id="qty"  min="1" value="1" readonly name="products[{{$product->id}}][quantity]">
            <a href="javascript:void(0)" id="qty_plus" onclick="PricePlus({{$product->id}},{{ ($product->offer_price ?? $product->regular_price) * 1 }})" data-price="{{ ($product->offer_price ?? $product->regular_price) * 1 }}"><i class="fa fa-plus"  ></i></a>
        </td>

        @foreach(App\Models\ProductAttribute::all() as $attribute)
        <td >
            <select name="products[{{$product->id}}][attribute][{{$attribute->id}}]" id="" class="p-2 wide attribute_item_id">
                @foreach(App\Models\Atr_item::whereIn('id',json_decode($product->atr_item)?? [])->where('atr_id',$attribute->id)->get() as $atr_item)
                    <option value="{{$atr_item->id}}">{{$atr_item->name}}</option>
                @endforeach
            </select>
        </td>
        @endforeach

        <td class="total_price">
            <div id="unit_price" class="unite_price_{{ $product->id }}">{{ ($product->offer_price ?? $product->regular_price) * 1 }}</div>
             <input type="hidden" id="pro_price" name="products[{{$product->id}}][price]" value="{{ ($product->offer_price ?? $product->regular_price) * 1 }}"/>
        </td>
        
    </tr>


    <script>
         function PricePlus(id,price){
                var price = price;
                var qty   =parseInt($(".qty_"+id).val());
                var total_qty  = (qty + 1);
                $(".qty_"+id).val(total_qty);
                // $(this).parent().find('#qty').val(total_qty)
                var total_unit_price = (price * total_qty);
                console.log(total_unit_price);
                $(".unite_price_"+id).html(total_unit_price);
                // $(this).closest('tr').find('.total_price').find('#unit_price').html(total_unit_price);
                // $(this).closest('tr').find('.total_price').find('#pro_price').val(total_unit_price);
                var sub_total     = parseInt($('#sub_total').val());
                    sub_total     = (sub_total + price);
                    $('#sub_total').val(sub_total);
                    shipping();

                }

                function PriceMinus(id,price){

                    var price = price;
                var qty   = parseInt($(".qty_"+id).val());
                var total_qty  = (qty - 1);

                console.log(price);

                if(total_qty >= 1){
                    $(".qty_"+id).val(total_qty)
                    var total_unit_price = (price * total_qty);
                    console.log(total_unit_price);
                    // $(this).closest('tr').find('.total_price').find('#unit_price').html(total_unit_price);
                    // $(this).closest('tr').find('.total_price').find('#pro_price').val(total_unit_price);
                    $(".unite_price_"+id).html(total_unit_price);
                    var sub_total     = parseInt($('#sub_total').val());
                        sub_total     = (sub_total - price);
                        $('#sub_total').val(sub_total);
                        shipping();
                }
                }

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
    </script>

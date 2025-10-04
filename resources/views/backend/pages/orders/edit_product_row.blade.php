
    <tr class="product_item_row" id="product_item_row-{{$product->id}}">
        
        
     <td>
            <a href="javascript:void(0)"  class="remove_btn"><i class="fa fa-trash  text-danger" style="cursor: pointer"></i></a>
        </td>
        <td class="text-left">{{ $product->name ?? 'N/A' }}
        <input type="hidden" class="product_id" name="products[{{$product->id}}][id]" value="{{$product->id}}"/>
        </td>
        <td width="" class="cart_qty">
            <a href="javascript:void(0)" id="qty_minus"  data-price="{{ ($product->offer_price ?? $product->regular_price) * 1 }}"><i class="fa fa-minus "></i></a>
            <input style="text-align: center;  width: 35px; margin: 0 5px 0 5px;" type="text" id="qty" min="1" value="{{$cart->quantity}}" readonly name="products[{{$product->id}}][quantity]">
            <a href="javascript:void(0)" id="qty_plus" data-price="{{ ($product->offer_price ?? $product->regular_price) * 1 }}"><i class="fa fa-plus"  ></i></a>
        </td>

        @foreach(App\Models\ProductAttribute::all() as $attribute)
        <td>
            <select name="products[{{$product->id}}][attribute][{{$attribute->id}}]" id="" class="p-2 wide attribute_item_id">
                    <option value="" selected>N/A</option>
                @foreach(App\Models\Atr_item::whereIn('id',json_decode($product->atr_item)?? [])->where('atr_id',$attribute->id)->get() as $atr_item)
                     <option value="{{$atr_item->id}}"  @if($cart->attribute != null && is_array($cart->attribute))
                         {{ in_array($atr_item->id,$cart->attribute) == true? 'selected':''}}
                         @endif
                        > {{$atr_item->name}}</option>
                @endforeach
            </select>
        </td>
        @endforeach
        <td class="total_price">
            <div id="unit_price">{{ $cart->price }}</div>
             <input type="hidden" id="pro_price" name="products[{{$product->id}}][price]" value="{{ $cart->price }}"/>
        </td>
        
    </tr>

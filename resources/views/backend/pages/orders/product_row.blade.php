
    @php
        $unitPrice = (float) (($product->offer_price ?? $product->regular_price) * 1);
    @endphp
    <tr class="product_item_row" id="product_item_row-{{$product->id}}">
        <td>
            <a href="javascript:void(0)" class="remove_btn"><i class="fa fa-trash  text-danger" style="cursor: pointer"></i></a>
        </td>
    
        <td class="text-left">{{ $product->name ?? 'N/A' }} 
        <input type="hidden" class="product_id" name="products[{{$product->id}}][id]" value="{{$product->id}}"/>
        </td>
        <td class="cart_qty">
            <a href="javascript:void(0)" class="qty_minus" data-id="{{ $product->id }}" data-price="{{ $unitPrice }}"><i class="fa fa-minus "></i></a>
            <input style="text-align: center; width: 35px; margin: 0 5px;" class="qty_input" type="text" min="1" value="1" readonly name="products[{{$product->id}}][quantity]" data-id="{{ $product->id }}">
            <a href="javascript:void(0)" class="qty_plus" data-id="{{ $product->id }}" data-price="{{ $unitPrice }}"><i class="fa fa-plus"></i></a>
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
            <div class="unit_price_display" id="unit_price-{{ $product->id }}">{{ $unitPrice }}</div>
             <input type="hidden" class="pro_price" id="pro_price-{{ $product->id }}" name="products[{{$product->id}}][price]" value="{{ $unitPrice }}"/>
        </td>
        
    </tr>

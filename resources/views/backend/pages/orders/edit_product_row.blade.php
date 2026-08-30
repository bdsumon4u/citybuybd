
    @php
        $quantity = max(1, (int) ($cart->quantity ?? 1));
        $unitPrice = (float) ($cart->price ?? ($product->offer_price ?? $product->regular_price) ?? 0);
    @endphp
    <tr class="product_item_row" id="product_item_row-{{$product->id}}">
        <td>
            <a href="javascript:void(0)" class="remove_btn">
                <i class="fa fa-trash text-danger" style="cursor: pointer"></i>
            </a>
        </td>
        <td class="text-left">
            <span class="font-weight-bold text-dark">{{ $product->name ?? 'N/A' }}</span>
            @if(!empty($cart->package))
                <div class="mt-1">
                    <span class="badge" style="font-size: 11px; font-weight: 700; background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 2px 7px; border-radius: 4px;">
                        Package: {{ $cart->package }}
                    </span>
                </div>
            @endif
            <input type="hidden" class="product_id" name="products[{{$product->id}}][id]" value="{{$product->id}}"/>
            <input type="hidden" name="products[{{$product->id}}][package]" value="{{ $cart->package ?? '' }}"/>
        </td>
        <td class="cart_qty">
            <a href="javascript:void(0)" class="qty_minus" data-id="{{ $product->id }}" data-price="{{ $unitPrice }}"><i class="fa fa-minus"></i></a>
            <input style="text-align: center; width: 35px; margin: 0 5px;" type="text" class="qty_input" min="1" value="{{$quantity}}" readonly name="products[{{$product->id}}][quantity]" data-id="{{ $product->id }}">
            <a href="javascript:void(0)" class="qty_plus" data-id="{{ $product->id }}" data-price="{{ $unitPrice }}"><i class="fa fa-plus"></i></a>
        </td>

        @foreach(App\Models\ProductAttribute::all() as $attribute)
        @php
            $selected = null;
            $attributeName = strtolower($attribute->name);
            if ($attributeName === 'color') {
                $selected = $cart->color;
            } elseif ($attributeName === 'size') {
                $selected = $cart->size;
            } elseif ($attributeName === 'model') {
                $selected = $cart->model;
            }
        @endphp
        <td>
            <select name="products[{{$product->id}}][attribute][{{$attribute->id}}]" class="p-2 wide attribute_item_id">
                    <option value="" {{ empty($selected) ? 'selected' : '' }}>N/A</option>
                @foreach(App\Models\Atr_item::whereIn('id',json_decode($product->atr_item)?? [])->where('atr_id',$attribute->id)->get() as $atr_item)
                     <option value="{{$atr_item->id}}" {{ ((string)($selected ?? '') === (string) $atr_item->name || (string)($selected ?? '') === (string) $atr_item->id) ? 'selected' : '' }}>
                        {{$atr_item->name}}
                    </option>
                @endforeach
            </select>
        </td>
        @endforeach
        <td class="total_price">
            <div class="unit_price_display" id="unit_price-{{ $product->id }}">{{ $unitPrice }}</div>
            <input type="hidden" class="pro_price" id="pro_price-{{ $product->id }}" name="products[{{$product->id}}][price]" value="{{ $unitPrice }}"/>
        </td>

    </tr>

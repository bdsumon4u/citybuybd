
    @php
        $unitPrice = (float) (($product->offer_price ?? $product->regular_price) * 1);
        $bulkPrices = (!empty($product->bulk_prices) && is_array($product->bulk_prices)) ? $product->bulk_prices : [];
    @endphp
    <tr class="product_item_row" id="product_item_row-{{$product->id}}">
        <td>
            <a href="javascript:void(0)" class="remove_btn"><i class="fa fa-trash  text-danger" style="cursor: pointer"></i></a>
        </td>
    
        <td class="text-left">
            <span class="font-weight-bold text-dark">{{ $product->name ?? 'N/A' }}</span>
            @if(!empty($bulkPrices))
                <div class="mt-1">
                    <select name="products[{{$product->id}}][package]" class="form-control form-control-sm package_select" data-id="{{$product->id}}" style="font-size: 11px; font-weight: 700; height: 28px; width: auto; min-width: 110px; max-width: 100%; display: inline-block; padding: 2px 6px; border-color: #86efac; background-color: #f0fdf4; color: #166534; border-radius: 4px; cursor: pointer;">
                        <option value="" data-price="{{ (float)($product->offer_price ?? $product->regular_price) }}" selected>
                            Regular (৳{{ (float)($product->offer_price ?? $product->regular_price) }})
                        </option>
                        @foreach($bulkPrices as $tier)
                            @php
                                $tierTitle = $tier['title'] ?? ($tier['quantity'] . ' Pcs');
                                $tierPrice = (float) (!empty($tier['offer_price']) ? $tier['offer_price'] : ($tier['regular_price'] ?? ($product->offer_price ?? $product->regular_price)));
                            @endphp
                            <option value="{{ $tierTitle }}" data-price="{{ $tierPrice }}" data-qty="{{ $tier['quantity'] ?? 1 }}">
                                {{ $tierTitle }} (৳{{ $tierPrice }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="products[{{$product->id}}][package]" value=""/>
            @endif
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

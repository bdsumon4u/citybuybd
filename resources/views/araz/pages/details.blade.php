@extends('araz.layouts.app')

@section('title', ($product->name ?? 'প্রোডাক্ট বিস্তারিত') . ' - ' . ($settings->insta_link ?? config('app.name')))

@php
    $hasBulkTiers = !empty($product->bulk_prices) && is_array($product->bulk_prices) && count($product->bulk_prices) > 0;
    
    $hasDiscount = !empty($product->offer_price) && $product->offer_price > 0 && $product->offer_price < $product->regular_price;
    $currentPrice = !empty($product->offer_price) && $product->offer_price > 0 ? $product->offer_price : ($product->regular_price ?? $product->price);
    $oldPrice = !empty($product->offer_price) && $product->offer_price > 0 ? ($product->regular_price ?? $product->price) : null;
    
    if ($hasBulkTiers) {
        $firstTier = $product->bulk_prices[0];
        if (!empty($firstTier['offer_price'])) {
            $currentPrice = (float) $firstTier['offer_price'];
            $oldPrice = !empty($firstTier['regular_price']) ? (float) $firstTier['regular_price'] : null;
        } elseif (!empty($firstTier['regular_price'])) {
            $currentPrice = (float) $firstTier['regular_price'];
            $oldPrice = null;
        }
    }
    
    $discountAmount = ($oldPrice && $oldPrice > $currentPrice) ? ($oldPrice - $currentPrice) : 0;
    
    // Main image
    $mainImage = !empty($product->image) ? asset('backend/img/products/' . $product->image) : asset('frontend/images/product-placeholder.png');
    
    // Gallery images
    $galleryImages = [];
    if (!empty($product->image)) {
        $galleryImages[] = asset('backend/img/products/' . $product->image);
    }
    if (!empty($product->gallery_images)) {
        $galleries = is_array($product->gallery_images) ? $product->gallery_images : json_decode($product->gallery_images, true);
        if (is_array($galleries)) {
            foreach ($galleries as $gImg) {
                if (!empty($gImg)) {
                    $galleryImages[] = asset('backend/img/products/' . $gImg);
                }
            }
        }
    }
    // Product Attributes
    $productAtrIds = [];
    if (!empty($product->atr)) {
        if (is_array($product->atr)) {
            $productAtrIds = $product->atr;
        } else {
            $decodedAtr = json_decode($product->atr, true);
            if (is_array($decodedAtr)) {
                $productAtrIds = $decodedAtr;
            } else {
                $productAtrIds = array_filter(explode('"', (string) $product->atr), fn($v) => is_numeric($v));
            }
        }
    }

    $productAtrItemIds = [];
    if (!empty($product->atr_item)) {
        if (is_array($product->atr_item)) {
            $productAtrItemIds = $product->atr_item;
        } else {
            $decodedItems = json_decode($product->atr_item, true);
            if (is_array($decodedItems)) {
                $productAtrItemIds = $decodedItems;
            } else {
                $productAtrItemIds = array_filter(explode('"', (string) $product->atr_item), fn($v) => is_numeric($v));
            }
        }
    }

    $productAttributes = [];
    if (!empty($productAtrIds) && !empty($productAtrItemIds)) {
        $attributesList = App\Models\ProductAttribute::whereIn('id', $productAtrIds)->get();
        foreach ($attributesList as $pAttr) {
            $items = App\Models\Atr_item::whereIn('id', $productAtrItemIds)
                        ->where('atr_id', $pAttr->id)
                        ->get();
            if ($items->count() > 0) {
                $productAttributes[] = [
                    'attribute' => $pAttr,
                    'items' => $items,
                ];
            }
        }
    }
@endphp

@push('styles')
<style>
    .dtls-qty-wrapper {
        display: inline-flex !important;
        align-items: center !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 4px !important;
        background: #ffffff !important;
        overflow: hidden !important;
        height: 36px !important;
        width: auto !important;
    }
    .dtls-qty-wrapper .dtls-qty-btn {
        width: 36px !important;
        height: 36px !important;
        background: #ffffff !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        color: #1e293b !important;
        font-size: 14px !important;
        padding: 0 !important;
        transition: background 0.2s !important;
    }
    .dtls-qty-wrapper .dtls-qty-btn:hover {
        background: #f1f5f9 !important;
    }
    .dtls-qty-wrapper input.dtls-qty-input {
        width: 48px !important;
        height: 36px !important;
        border: none !important;
        border-left: 1px solid #cbd5e1 !important;
        border-right: 1px solid #cbd5e1 !important;
        text-align: center !important;
        font-weight: 700 !important;
        font-size: 16px !important;
        color: #111827 !important;
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        outline: none !important;
        display: block !important;
    }
    .btn-dtls-order {
        background: #ea580c !important;
        color: #ffffff !important;
        border-radius: 4px !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        height: 42px !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        transition: opacity 0.2s !important;
    }
    .btn-dtls-order.btn-free-shipping {
        font-size: 13px !important;
        padding: 0 4px !important;
        white-space: nowrap !important;
    }
    .btn-dtls-order:hover {
        opacity: 0.92 !important;
        color: #ffffff !important;
    }
    .btn-dtls-cart {
        background: #2b99c7 !important;
        color: #ffffff !important;
        border-radius: 4px !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        height: 42px !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: opacity 0.2s !important;
    }
    .btn-dtls-cart:hover {
        opacity: 0.92 !important;
        color: #ffffff !important;
    }
    .btn-dtls-contact,
    a.btn-dtls-contact,
    .btn-dtls-contact span,
    .btn-dtls-contact i {
        background: #ea644a !important;
        color: #ffffff !important;
        border-radius: 4px !important;
        font-weight: 700 !important;
        font-size: 13.5px !important;
        height: 38px !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        gap: 6px !important;
        padding: 0 8px !important;
    }
    .btn-dtls-contact:hover {
        opacity: 0.92 !important;
        color: #ffffff !important;
    }
    .bulk-pack-container,
    .attr-chip-container {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 8px !important;
        width: auto !important;
    }
    .bulk-pack-btn,
    .attr-chip-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: auto !important;
        min-width: 60px !important;
        max-width: fit-content !important;
        flex: 0 0 auto !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 6px 16px !important;
        border-radius: 8px !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #1e293b !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        margin: 0 !important;
        line-height: 1.4 !important;
        text-decoration: none !important;
        user-select: none !important;
    }
    .bulk-pack-btn:hover,
    .attr-chip-btn:hover {
        background: #f8fafc !important;
        border-color: #94a3b8 !important;
        color: #0f172a !important;
    }
    .bulk-pack-btn.active,
    .attr-chip-btn.active {
        background: #22c55e !important;
        border-color: #22c55e !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.35) !important;
    }
    .attr-group-item {
        margin-bottom: 12px;
    }
    .delivery-options-box {
        background: #f4fbf4 !important;
        border-radius: 4px !important;
        padding: 16px 20px !important;
        border: none !important;
    }
    .delivery-options-box .box-heading {
        color: #1f2937 !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        margin-bottom: 12px !important;
    }
    .delivery-options-box .info-row {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        margin-bottom: 10px !important;
        font-size: 14.5px !important;
        font-weight: 600 !important;
        color: #1f2937 !important;
    }
    .delivery-options-box .info-row i {
        font-size: 18px !important;
        color: #65a30d !important;
        width: 22px !important;
        text-align: center !important;
        flex-shrink: 0 !important;
    }
    .product-tabs-wrapper {
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .product-tabs-wrapper .nav-link {
        color: #475569 !important;
        background: transparent !important;
        border: none !important;
        border-bottom: 3px solid transparent !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        padding: 10px 18px !important;
        margin-bottom: -2px !important;
        border-radius: 6px 6px 0 0 !important;
        transition: all 0.25s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        cursor: pointer !important;
    }
    .product-tabs-wrapper .nav-link:hover {
        color: var(--custom-primary-color) !important;
        background: #f8fafc !important;
    }
    .product-tabs-wrapper .nav-link.active {
        color: var(--custom-primary-color) !important;
        border-bottom-color: var(--custom-primary-color) !important;
        background: #f0fdf4 !important;
    }
    .product-description-body {
        font-size: 15px;
        line-height: 1.85;
        color: #334155;
    }
    .product-description-body img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px;
        margin: 10px 0;
    }
    .product-description-body table {
        width: 100% !important;
        border-collapse: collapse;
        margin: 15px 0;
    }
    .product-description-body table th,
    .product-description-body table td {
        border: 1px solid #e2e8f0;
        padding: 8px 12px;
    }
    .delivery-policy-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
    }
</style>
@endpush

@section('content')
<div class="container py-3">
    <div class="bg-white p-3 p-md-4 rounded-3 shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
        <div class="row g-4">
            
            <!-- Left Column: Product Gallery -->
            <div class="col-lg-5">
                <div class="position-relative">
                    <!-- Main Product Image -->
                    <div class="main-image-container border rounded-3 p-2 text-center bg-light position-relative overflow-hidden mb-3">
                        <img id="mainProductView" src="{{ $mainImage }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 420px; width: 100%; object-fit: contain; cursor: zoom-in;">
                        
                        @if($hasDiscount)
                            <div class="position-absolute top-0 end-0 m-3">
                                <div class="product-badget">
                                    <div class="dicount_text_single">
                                        <span class="discount-val">{{ round($discountAmount) }} ৳</span>
                                        <span class="discount-lbl">ছাড়</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails List -->
                    @if(count($galleryImages) > 1)
                        <div class="d-flex gap-2 overflow-auto pb-2 thumbnail-row">
                            @foreach($galleryImages as $index => $gImage)
                                <div class="thumbnail-item border rounded-2 p-1 bg-white cursor-pointer {{ $index == 0 ? 'border-success' : '' }}" 
                                     style="width: 65px; height: 65px; flex-shrink: 0; cursor: pointer;"
                                     onclick="switchProductImage('{{ $gImage }}', this)">
                                    <img src="{{ $gImage }}" alt="Thumb" class="w-100 h-100 object-fit-cover rounded">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Product Info & Order Form -->
            <div class="col-lg-7">
                <div class="product-details-content">
                    <!-- Product Title -->
                    <h1 class="fw-bold text-dark mb-2" style="font-size: 21px; line-height: 1.4;">
                        {{ $product->name }}
                    </h1>

                    <!-- Price Display -->
                    <div class="d-flex flex-wrap align-items-baseline gap-2 mb-3">
                        <span style="font-size: 28px; color: #dc2626; font-weight: 800;">
                            মূল্য: <span id="displayCurrentPrice">{{ number_format($currentPrice, 0) }}</span> টাকা
                        </span>
                        <span id="displayOldPriceWrapper" style="font-size: 15px; color: #64748b; font-weight: 500; {{ $oldPrice ? '' : 'display: none;' }}">
                            মূল্য: <del id="displayOldPrice">{{ number_format($oldPrice ?? 0, 0) }}</del> টাকা
                        </span>
                    </div>

                    <!-- Main Order Form (Direct Checkout Submission) -->
                    <form method="POST" action="{{ route('o_cart.store') }}" id="detailsOrderForm" class="mb-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_name" value="{{ $product->name }}">
                        <input type="hidden" name="product_image" value="{{ $mainImage }}">
                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                        <input type="hidden" name="price" id="selectedPrice" value="{{ $currentPrice }}">
                        <input type="hidden" name="package" id="selectedPackage" value="{{ $hasBulkTiers ? ($product->bulk_prices[0]['title'] ?? '1 Pcs') : '' }}">
                        <input type="hidden" name="bulk_pack" id="selectedBulkPack" value="{{ $hasBulkTiers ? ($product->bulk_prices[0]['title'] ?? '1 Pcs') : '' }}">
                        @if(!empty($product->model))
                            <input type="hidden" name="model" value="{{ $product->model }}">
                        @endif

                        <!-- Bulk Quantity / Size Tier Selection (When Configured) -->
                        @if($hasBulkTiers)
                            <div class="bulk-tier-selection mb-3">
                                <label class="fw-bold mb-2 d-block text-dark" style="font-size: 14.5px;">প্যাকেজ / Quantity:</label>
                                <div class="bulk-pack-container" id="bulkPackContainer">
                                    @foreach($product->bulk_prices as $index => $tier)
                                        @php
                                            $tierTitle = $tier['title'] ?? ($tier['quantity'] . ' Pcs');
                                            $tierQty = $tier['quantity'] ?? 1;
                                            $tierPrice = !empty($tier['offer_price']) ? (float)$tier['offer_price'] : (!empty($tier['regular_price']) ? (float)$tier['regular_price'] : $currentPrice);
                                            $tierRegPrice = !empty($tier['regular_price']) ? (float)$tier['regular_price'] : '';
                                            $isFirst = ($index === 0);
                                        @endphp
                                        <button type="button" 
                                                class="bulk-pack-btn {{ $isFirst ? 'active' : '' }}" 
                                                data-title="{{ $tierTitle }}"
                                                data-qty="{{ $tierQty }}"
                                                data-price="{{ $tierPrice }}"
                                                data-oldprice="{{ $tierRegPrice }}"
                                                onclick="selectBulkTier(this)">
                                            {{ $tierTitle }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Attributes Selection (Color, Size, Model, etc.) as Chip Boxes -->
                        @if(count($productAttributes) > 0)
                            <div class="product-attributes-wrapper mb-3">
                                @foreach($productAttributes as $attrGroup)
                                    @php
                                        $attr = $attrGroup['attribute'];
                                        $items = $attrGroup['items'];
                                        $firstItem = $items->first();
                                    @endphp
                                    <div class="attr-group-item mb-3">
                                        <label class="fw-bold mb-2 d-block text-dark" style="font-size: 14.5px;">
                                            {{ $attr->name }}: <span class="selected-attr-text text-muted fw-normal" id="selected-attr-val-{{ $attr->id }}">{{ $firstItem ? $firstItem->name : '' }}</span>
                                        </label>
                                        <input type="hidden" name="attribute_id[]" value="{{ $attr->id }}">
                                        <input type="hidden" name="attribute[{{ $attr->id }}]" id="attr-input-{{ $attr->id }}" value="{{ $firstItem ? $firstItem->id : '' }}">
                                        
                                        <div class="attr-chip-container" data-attr-id="{{ $attr->id }}">
                                            @foreach($items as $idx => $item)
                                                <button type="button" 
                                                        class="attr-chip-btn {{ $idx === 0 ? 'active' : '' }}" 
                                                        data-attr-id="{{ $attr->id }}" 
                                                        data-item-id="{{ $item->id }}" 
                                                        data-item-name="{{ $item->name }}"
                                                        onclick="selectAttributeChip(this)">
                                                    {{ $item->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Quantity Selector -->
                        <div class="mb-3">
                            <label class="fw-bold mb-2 d-block text-dark" style="font-size: 14.5px;">পরিমাণ / Quantity:</label>
                            <div class="dtls-qty-wrapper">
                                <button type="button" class="dtls-qty-btn" onclick="decrementQty()">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="text" id="orderQty" name="quantity" class="dtls-qty-input" value="1" readonly>
                                <button type="button" class="dtls-qty-btn" onclick="incrementQty()">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons in One Row -->
                        <div class="row g-2 mb-3">
                            <!-- Direct Order Button -->
                            <div class="col-6">
                                <button type="submit" class="btn btn-dtls-order w-100 shadow-sm {{ $product->shipping == 1 ? 'btn-free-shipping' : '' }}">
                                    @if ($product->shipping == 1)
                                        ফ্রি ডেলিভারিতে অর্ডার করুন
                                    @else
                                        অর্ডার করুন
                                    @endif
                                </button>
                            </div>

                            <!-- Add To Cart Button (AJAX) -->
                            <div class="col-6">
                                <button type="button" onclick="submitAjaxCart()" class="btn btn-dtls-cart w-100 shadow-sm">
                                    কার্টে রাখুন
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Contact Options in Next Row (Dynamically Displayed) -->
                    @php
                        $hasPhone = !empty($settings->phone);
                        $hasMessenger = ($settings->contact_phone_plus == 'messenger' || $settings->contact_phone_plus == 'both') && (!empty($settings->messenger_username) || !empty($settings->fb_link));
                        $hasWhatsapp = ($settings->contact_phone_plus == 'whatsapp' || $settings->contact_phone_plus == 'both' || !empty($settings->whatsapp_number) || !empty($settings->whatsapp));
                    @endphp

                    @if($hasPhone || $hasMessenger || $hasWhatsapp)
                        <div class="row g-2 mb-3">
                            @if($hasPhone)
                                <div class="col">
                                    <a href="tel:{{ $settings->phone }}" class="btn-dtls-contact w-100 text-truncate" style="color: #ffffff !important;">
                                        <i class="fa-solid fa-phone" style="color: #ffffff !important;"></i>
                                        <span style="color: #ffffff !important;" class="text-truncate">{{ $settings->phone }}</span>
                                    </a>
                                </div>
                            @endif

                            @if($hasMessenger)
                                @php
                                    $mUrl = !empty($settings->messenger_username) ? $settings->messenger_username : (!empty($settings->fb_link) ? $settings->fb_link : '#');
                                @endphp
                                <div class="col">
                                    <a href="{{ $mUrl }}" target="_blank" class="btn-dtls-contact w-100 text-truncate" style="color: #ffffff !important;">
                                        <img src="https://cdn-icons-png.flaticon.com/512/5968/5968771.png" alt="Messenger" style="width: 17px; height: 17px;">
                                        <span style="color: #ffffff !important;">MESSENGER</span>
                                    </a>
                                </div>
                            @endif

                            @if($hasWhatsapp)
                                @php
                                    $rawWa = !empty($settings->whatsapp_number) ? $settings->whatsapp_number : ($settings->whatsapp ?? '');
                                    $waDigits = preg_replace('/\D/', '', $rawWa);
                                    if (!str_starts_with($waDigits, '88') && strlen($waDigits) == 11) {
                                        $waDigits = '88' . $waDigits;
                                    }
                                @endphp
                                <div class="col">
                                    <a href="https://wa.me/{{ $waDigits }}" target="_blank" class="btn-dtls-contact w-100 text-truncate" style="color: #ffffff !important;">
                                        <i class="fa-brands fa-whatsapp" style="font-size: 17px; color: #22c55e;"></i>
                                        <span style="color: #ffffff !important;" class="text-truncate">{{ !empty($settings->whatsapp_number) ? $settings->whatsapp_number : $rawWa }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Assurance Banner -->
                    <div class="p-2 mb-3 rounded-2 text-center" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                        @if ($product->shipping == 1)
                            <div class="text-danger fw-bold mb-1" style="font-size: 13.5px;">
                                <i class="fa-solid fa-circle-check text-success me-1"></i> ফ্রি ডেলিভারি চার্জে অর্ডার করুন
                            </div>
                        @endif
                        <div class="text-success fw-semibold mb-1" style="font-size: 13px;">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> ১০০% শিউর না হয়ে অহেতুক অর্ডার করবেন না
                        </div>
                        <div class="text-success fw-semibold" style="font-size: 13px;">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> পোডাক্ট হাতে পেয়ে দেখে নিতে পারবেন
                        </div>
                    </div>

                    <!-- Delivery Option & Values Strip (Matched to Screenshot) -->
                    <div class="delivery-options-box mb-3">
                        <div class="box-heading">Delivery Option</div>
                        
                        <div class="info-row">
                            <i class="fa-solid fa-location-arrow"></i>
                            <span>Cash On Delivery Available</span>
                        </div>
                        
                        <div class="info-row">
                            <i class="fa-solid fa-house"></i>
                            <span>ঢাকায় ডেলিভারি খরচ ৳ {{ $product->inside ?? (isset($shipping_charge[1]) ? $shipping_charge[1]->amount : 80) }}</span>
                        </div>
                        
                        <div class="info-row">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span>ঢাকার বাইরের ডেলিভারি খরচ ৳ {{ $product->outside ?? (isset($shipping_charge[0]) ? $shipping_charge[0]->amount : 130) }}</span>
                        </div>

                        <hr style="border-color: #dbe7db; margin: 14px 0;">

                        <div class="box-heading">Our values</div>
                        
                        <div class="info-row">
                            <i class="fa-solid fa-certificate"></i>
                            <span>100% authentic</span>
                        </div>
                        
                        <div class="info-row" style="margin-bottom: 0 !important;">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span>instant return</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Product Description Tabs -->
    <div class="bg-white p-3 p-md-4 rounded-3 shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
        <ul class="nav product-tabs-wrapper" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab" aria-selected="true">
                    <i class="fa-solid fa-file-lines me-2 text-success"></i> পণ্য বিবরণী (Description)
                </button>
            </li>
            @if(!empty($product->video))
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video-pane" type="button" role="tab" aria-selected="false">
                        <i class="fa-solid fa-circle-play me-2 text-danger"></i> ভিডিও (Video)
                    </button>
                </li>
            @endif
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery-pane" type="button" role="tab" aria-selected="false">
                    <i class="fa-solid fa-truck-fast me-2 text-primary"></i> ডেলিভারি ও রিটার্ন পলিসি
                </button>
            </li>
        </ul>
        <div class="tab-content pt-2" id="productTabContent">
            <!-- Description Pane -->
            <div class="tab-pane fade show active" id="desc-pane" role="tabpanel" aria-labelledby="desc-tab">
                <div class="product-description-body">
                    {!! $product->description ?? '<p class="text-muted">কোনো বিবরণ দেওয়া হয়নি।</p>' !!}
                </div>
            </div>

            <!-- Video Pane (If video exists) -->
            @if(!empty($product->video))
                <div class="tab-pane fade" id="video-pane" role="tabpanel" aria-labelledby="video-tab">
                    <div class="text-center p-2">
                        <video style="max-width: 100%; max-height: 480px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);" controls>
                            <source src="{{ asset('backend/img/products/video/' . $product->video) }}" type="video/mp4">
                            আপনার ব্রাউজারে ভিডিও প্লেয়ারটি সাপোর্ট করছে না।
                        </video>
                    </div>
                </div>
            @endif

            <!-- Delivery Pane -->
            <div class="tab-pane fade" id="delivery-pane" role="tabpanel" aria-labelledby="delivery-tab">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="delivery-policy-card h-100">
                            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 15px;">
                                <i class="fa-solid fa-truck text-success" style="font-size: 18px;"></i> ডেলিভারি সংক্রান্ত নিয়মাবলী
                            </h6>
                            <ul class="list-unstyled mb-0 d-flex flex-column gap-2" style="font-size: 13.5px; color: #475569;">
                                <li class="d-flex align-items-start gap-2">
                                    <i class="fa-solid fa-circle-check text-success mt-1" style="font-size: 13px;"></i>
                                    <span>সারাদেশে ক্যাশ অন ডেলিভারিতে নিশ্চিত হোম ডেলিভারি সুবিধা।</span>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <i class="fa-solid fa-circle-check text-success mt-1" style="font-size: 13px;"></i>
                                    <span>ঢাকার ভিতরে ২৪ থেকে ৪৮ ঘণ্টা এবং ঢাকার বাইরে ৪৮ থেকে ৭২ ঘণ্টার মধ্যে দ্রুত ডেলিভারি।</span>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <i class="fa-solid fa-circle-check text-success mt-1" style="font-size: 13px;"></i>
                                    <span>ডেলিভারিম্যানের সামনে পণ্য চেক করে বুঝে নেওয়ার পূর্ণ সুবিধা রয়েছে।</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="delivery-policy-card h-100">
                            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 15px;">
                                <i class="fa-solid fa-rotate-left text-primary" style="font-size: 18px;"></i> রিটার্ন ও এক্সচেঞ্জ পলিসি
                            </h6>
                            <ul class="list-unstyled mb-0 d-flex flex-column gap-2" style="font-size: 13.5px; color: #475569;">
                                <li class="d-flex align-items-start gap-2">
                                    <i class="fa-solid fa-circle-check text-primary mt-1" style="font-size: 13px;"></i>
                                    <span>পণ্য পছন্দ না হলে বা ডিফেক্ট থাকলে ডেলিভারিম্যানের সামনে সাথে সাথেই রিটার্ন করতে পারবেন।</span>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <i class="fa-solid fa-circle-check text-primary mt-1" style="font-size: 13px;"></i>
                                    <span>যেকোনো সমস্যায় আমাদের হটলাইন অথবা WhatsApp এ যোগাযোগ করে সমাধান পাবেন।</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mb-4">
            <div class="section-title-wrapper header_container">
                <h2 class="header_name">সম্পর্কিত পণ্যসমূহ</h2>
                <p class="catagory_usertext">এই ক্যাটাগরির অন্যান্য জনপ্রিয় প্রোডাক্ট</p>
            </div>

            <div class="row g-2 g-md-3 row-cols-2 row-cols-md-3 row-cols-lg-4">
                @foreach($relatedProducts->take(6) as $rProduct)
                    <div class="col">
                        @include('araz.partials.product_card', ['product' => $rProduct])
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    function selectBulkTier(element) {
        var $btn = $(element);
        $('.bulk-pack-btn').removeClass('active');
        $btn.addClass('active');

        var title = $btn.data('title');
        var price = parseFloat($btn.data('price')) || 0;
        var oldPrice = $btn.data('oldprice');

        $('#selectedPrice').val(price);
        $('#selectedBulkPack').val(title);
        $('#selectedPackage').val(title);

        $('#displayCurrentPrice').text(Math.round(price).toLocaleString());

        if (oldPrice && parseFloat(oldPrice) > 0) {
            $('#displayOldPrice').text(Math.round(parseFloat(oldPrice)).toLocaleString());
            $('#displayOldPriceWrapper').show();
        } else {
            $('#displayOldPriceWrapper').hide();
        }
    }

    function switchProductImage(src, element) {
        $('#mainProductView').attr('src', src);
        $('.thumbnail-item').removeClass('border-success');
        $(element).addClass('border-success');
    }

    function selectAttributeChip(element) {
        var $btn = $(element);
        var attrId = $btn.data('attr-id');
        var itemId = $btn.data('item-id');
        var itemName = $btn.data('item-name');

        $('.attr-chip-container[data-attr-id="' + attrId + '"] .attr-chip-btn').removeClass('active');
        $btn.addClass('active');

        $('#attr-input-' + attrId).val(itemId);
        $('#selected-attr-val-' + attrId).text(itemName);
    }

    function incrementQty() {
        var input = $('#orderQty');
        var val = parseInt(input.val()) || 1;
        input.val(val + 1);
    }

    function decrementQty() {
        var input = $('#orderQty');
        var val = parseInt(input.val()) || 1;
        if (val > 1) {
            input.val(val - 1);
        }
    }

    function submitAjaxCart() {
        var $form = $('#detailsOrderForm');
        var formData = $form.serialize();

        $.ajax({
            url: "{{ route('o_cart.store') }}",
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success('পণ্যটি সফলভাবে কার্টে যোগ করা হয়েছে!');
                let cur = parseInt($('#floating-cart-count').text()) || 0;
                let addQty = parseInt($('#orderQty').val()) || 1;
                updateGlobalCart(cur + addQty);
            },
            error: function() {
                toastr.error('একটি সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন।');
            }
        });
    }
</script>
@endpush
@endsection

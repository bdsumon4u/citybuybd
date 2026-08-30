@php
    $hasDiscount = !empty($product->offer_price) && $product->offer_price > 0 && $product->offer_price < $product->regular_price;
    $currentPrice = !empty($product->offer_price) && $product->offer_price > 0 ? $product->offer_price : ($product->regular_price ?? $product->price);
    $oldPrice = !empty($product->offer_price) && $product->offer_price > 0 ? ($product->regular_price ?? $product->price) : null;
    $discountAmount = $hasDiscount ? (($product->regular_price ?? $product->price) - $product->offer_price) : 0;
    
    // Product images path
    $productImage = !empty($product->image) ? asset('backend/img/products/' . $product->image) : asset('frontend/images/product-placeholder.png');
    
    // Hover image from gallery
    $hoverImage = $productImage;
    if (!empty($product->gallery_images)) {
        $decodedGallery = is_array($product->gallery_images) ? $product->gallery_images : json_decode($product->gallery_images, true);
        if (!empty($decodedGallery) && is_array($decodedGallery) && !empty($decodedGallery[0])) {
            $hoverImage = asset('backend/img/products/' . $decodedGallery[0]);
        }
    }
@endphp

<div class="box axil-product product-style-one h-100">
    <!-- Thumbnail & Badges -->
    <div class="thumbnail">
        <a href="{{ route('details', $product->slug) }}">
            <img loading="lazy" src="{{ $productImage }}" class="product_img main_img" alt="{{ $product->name }}">
            <img loading="lazy" src="{{ $hoverImage }}" class="product_img hover_img" alt="{{ $product->name }}">
        </a>

        @if($hasDiscount)
            <div class="label-block label-right">
                <div class="product-badget">
                    <div class="dicount_text_single">
                        <span class="discount-val">{{ round($discountAmount) }} ৳</span>
                        <span class="discount-lbl">ছাড়</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Product Details -->
    <div class="product-content">
        <div class="inner">
            <h5 class="title text-start two-line-title mt-2 mb-1">
                <a href="{{ route('details', $product->slug) }}" title="{{ $product->name }}">
                    {{ $product->name }}
                </a>
            </h5>

            <div class="product-price-variant text-start mb-1">
                <span class="price current-price">
                    ৳ {{ number_format($currentPrice, 0) }}
                </span>
                @if($oldPrice)
                    <span class="price old-price">
                        ৳ {{ number_format($oldPrice, 0) }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions (Direct Order & Add to Cart) -->
    <div class="product-action-buttons">
        <!-- Direct Order Button (Proceeds to Checkout / Product Form) -->
        <form method="POST" action="{{ route('o_cart.store') }}" class="w-100 m-0">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="product_name" value="{{ $product->name }}">
            <input type="hidden" name="product_image" value="{{ $productImage }}">
            <input type="hidden" name="slug" value="{{ $product->slug }}">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="price" value="{{ $currentPrice }}">

            <button type="submit" class="btn order-button jdx-pulse">
                <i class="fa-solid fa-basket-shopping me-1"></i> অর্ডার করুন
            </button>
        </form>

        <!-- Add to Cart AJAX Button -->
        <form method="POST" action="{{ route('o_cart.store') }}" class="w-100 m-0">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="product_name" value="{{ $product->name }}">
            <input type="hidden" name="product_image" value="{{ $productImage }}">
            <input type="hidden" name="slug" value="{{ $product->slug }}">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="price" value="{{ $currentPrice }}">

            <button type="submit" class="btn cart-button">
                <i class="fa-solid fa-bag-shopping me-1"></i> কার্টে যোগ করুন
            </button>
        </form>
    </div>
</div>

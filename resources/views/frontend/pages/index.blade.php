@extends('frontend.layout.template')
@section('pageTitle', 'Homepage')
@section('body-content')

    @if(isset($home_sections) && $home_sections->count() > 0)
        @foreach($home_sections as $section)
            @php
                $secType = $section->section_type;
                $secProducts = $section->resolved_products ?? collect();
            @endphp

            {{-- 1. BANNER SLIDER --}}
            @if($secType === 'banner_slider')
                @if(isset($sliders) && $sliders->count() > 0)
                    <div class="banner banner-2">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-3 col-lg-2 category-col"></div>
                                <div class="col-xl-9 col-lg-10">
                                    <div class="slider-area">
                                        @foreach($sliders as $slider)
                                            <div class="Slide slide-{{ $loop->iteration }}" style="background: url({{ asset('backend/img/sliders/' . ($slider->name ?? $slider->image)) }}) center center no-repeat; background-size: contain;">
                                                <div class="banner-txt">
                                                    <h6 style="color: #ff000000;">.</h6>
                                                    <h1 style="color: #ff000000;">.</h1>
                                                    <p style="color: #ff000000;">.</p>
                                                    <div class="price" style="color: #ff000000;">.</div>
                                                    <a href="{{ url('hot_deals') }}" class="def-btn" tabindex="-1">Order Now</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            {{-- 2. CATEGORIES GRID / SLIDER --}}
            @elseif($secType === 'categories_grid')
                @if(isset($categories) && $categories->count() > 0)
                    <div class="popular-categories">
                        <div class="container">
                            <div class="panel">
                                <div class="panel-header">
                                    <div class="row align-items-center g-lg-4 g-1">
                                        <div class="col-lg-6 col-9">
                                            <h2 class="title">{{ $section->title ?: "TOP CATEGORY'S" }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="slider slider-nav">
                                        @foreach($categories->take($section->product_limit ?: 12) as $category)
                                            <div class="category-card" style="width: 150px !important;">
                                                <div class="part-img">
                                                    <a href="{{ route('category', $category->id) }}" style="min-height: 80px;">
                                                        <img src="{{ asset('backend/img/category/' . $category->image) }}" alt="Image">
                                                    </a>
                                                </div>
                                                <div class="part-txt">
                                                    <h3 style="font-size: 13px; font-weight: 400;">
                                                        <a href="{{ route('category', $category->id) }}">{{ $category->title }}</a>
                                                    </h3>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            {{-- 3. CATEGORY PRODUCTS (MULTI-CATEGORY LOOP OR SINGLE) --}}
            @elseif($secType === 'category_products' && !$section->category_id)
                @php
                    $catList = $section->category_list ?? ($category_products ?? collect());
                @endphp
                @foreach($catList as $category)
                    @if($category->products && $category->products->count() > 0)
                        <div class="flash-deal">
                            <div class="container">
                                <div class="panel">
                                    <div class="panel-header">
                                        <div class="row align-items-center">
                                            <div class="col-lg-4 col-md-4 col-6">
                                                <h2 class="title">{{ $category->title }}</h2>
                                            </div>
                                            <div class="col-lg-6 col-md-6 d-none d-md-block"></div>
                                            <div class="col-lg-2 col-md-2 col-6">
                                                <div class="text-end">
                                                    <a href="{{ route('category', $category->id) }}" class="explore-section">View more</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            @foreach($category->products->take($section->product_limit ?: 6) as $product)
                                                <div class="col-md-2 col-6">
                                                    <div class="mb-3 single-product-card">
                                                        <div class="part-img">
                                                            @if(!is_null($product->offer_price) && $product->offer_price > 0 && $product->regular_price > 0)
                                                                <span class="off-tag" style="background-color: #{{ $settings->website_color ?? '2fb14d' }}">
                                                                    {{ round((100 - (($product->offer_price / $product->regular_price) * 100))) }}%
                                                                </span>
                                                            @endif
                                                            <a href="{{ route('details', $product->slug) }}">
                                                                <img src="{{ asset('backend/img/products/' . $product->image) }}" alt="Product">
                                                            </a>
                                                        </div>
                                                        <div class="part-txt">
                                                            <h4 class="product-name">
                                                                <a href="{{ route('details', $product->slug) }}">
                                                                    {{ \Illuminate\Support\Str::limit($product->name, 20) }}
                                                                </a>
                                                            </h4>
                                                            @if(!is_null($product->offer_price) && $product->offer_price > 0)
                                                                <span class="price" style="color: #{{ $settings->website_color ?? '2fb14d' }}">
                                                                    {{ $settings->currency ?? "৳" }} {{ $product->offer_price }}
                                                                    <span>{{ $product->regular_price }}</span>
                                                                </span>
                                                            @else
                                                                <span class="price" style="color: #{{ $settings->website_color ?? '2fb14d' }}">
                                                                    {{ $settings->currency ?? "৳" }} {{ $product->regular_price }}
                                                                </span>
                                                            @endif

                                                            <form action="{{ route('o_cart.store', $product->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                <input type="hidden" name="slug" value="{{ $product->slug }}">
                                                                <input type="hidden" name="product_image" value="{{ asset('backend/img/products/' . $product->image) ?? '' }}">
                                                                <input type="hidden" name="product_name" value="{{ $product->name }}">
                                                                <input type="hidden" name="price" value="@if(is_null($product->offer_price)){{ $product->regular_price }}@else {{ $product->offer_price }} @endif">
                                                                <input type="hidden" name="quantity" value="1">
                                                                <button type="submit" class="btn btn-sm order-btn order_now_btn"><i class="fa-solid fa-cart-shopping"></i> অর্ডার করুন</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            {{-- 4. PRODUCT SECTIONS (HOT DEALS, BEST SELLING, LATEST, CUSTOM, SINGLE CATEGORY, ALL PRODUCTS) --}}
            @elseif(isset($secProducts) && count($secProducts) > 0)
                @php
                    $isHotDeal = ($secType === 'hot_deals');
                    $viewAllLink = url('all-Products');
                    if ($secType === 'hot_deals') {
                        $viewAllLink = url('hot_deals');
                    } elseif ($secType === 'category_products' && $section->category_id) {
                        $viewAllLink = route('category', $section->category_id);
                    }
                @endphp
                <div class="flash-deal">
                    <div class="container">
                        <div class="panel" @if($isHotDeal) style="background: #ffa7003b !important;" @endif>
                            <div class="panel-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-4 col-md-4 col-6">
                                        <h2 class="title">{{ $section->title }}</h2>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-none d-md-block"></div>
                                    <div class="col-lg-2 col-md-2 col-6">
                                        <div class="text-end">
                                            <a href="{{ $viewAllLink }}" class="explore-section">View more</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    @foreach($secProducts as $product)
                                        <div class="col-md-2 col-6">
                                            <div class="mb-3 single-product-card">
                                                <div class="part-img" style="min-height: 100px;">
                                                    @if(!is_null($product->offer_price) && $product->offer_price > 0 && $product->regular_price > 0)
                                                        <span class="off-tag" style="background-color: #{{ $settings->website_color ?? '2fb14d' }}">
                                                            {{ round((100 - (($product->offer_price / $product->regular_price) * 100))) }}%
                                                        </span>
                                                    @endif
                                                    <a href="{{ route('details', $product->slug) }}">
                                                        <img src="{{ asset('backend/img/products/' . $product->image) }}" alt="Product" style="min-height: 100px; height: 100px; object-fit: fill;">
                                                    </a>
                                                </div>
                                                <div class="part-txt" style="margin-top: 0px;">
                                                    <h4 class="product-name">
                                                        <a href="{{ route('details', $product->slug) }}">
                                                            {{ \Illuminate\Support\Str::limit($product->name, 20) }}
                                                        </a>
                                                    </h4>
                                                    @if(!is_null($product->offer_price) && $product->offer_price > 0)
                                                        <span class="price" style="color: #{{ $settings->website_color ?? '2fb14d' }}">
                                                            {{ $settings->currency ?? "৳" }} {{ $product->offer_price }} <br>
                                                            <span>{{ $settings->currency ?? "৳" }} {{ $product->regular_price }}</span>
                                                        </span>
                                                    @else
                                                        <span class="price" style="color: #{{ $settings->website_color ?? '2fb14d' }}">
                                                            <span>{{ $settings->currency ?? "৳" }} {{ $product->regular_price }}</span>
                                                        </span>
                                                    @endif

                                                    <form action="{{ route('o_cart.store', $product->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                                                        <input type="hidden" name="product_image" value="{{ asset('backend/img/products/' . $product->image) ?? '' }}">
                                                        <input type="hidden" name="product_name" value="{{ $product->name }}">
                                                        <input type="hidden" name="price" value="@if(is_null($product->offer_price)){{ $product->regular_price }}@else {{ $product->offer_price }} @endif">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="btn btn-sm order-btn order_now_btn"><i class="fa-solid fa-cart-shopping"></i> অর্ডার করুন</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

@endsection


@extends('araz.layouts.app')

@section('title', ($settings->insta_link ?? config('app.name')) . ' - বিশ্বস্ত অনলাইন শপ')

@section('content')
<div class="container-fluid px-1 px-sm-2 px-md-3 px-lg-4 py-2">

    <!-- 1. Hero Sliders (Desktop & Mobile) -->
    @if(isset($sliders) && $sliders->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div id="heroSlider" class="carousel slide rounded-3 overflow-hidden shadow-sm" data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-indicators">
                        @foreach($sliders as $key => $slider)
                            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($sliders as $key => $slider)
                            @php
                                $sliderImgName = $slider->name ?? $slider->image;
                                $sliderSrc = !empty($sliderImgName) ? asset('backend/img/sliders/' . $sliderImgName) : '';
                            @endphp
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                @if(!empty($slider->url))
                                    <a href="{{ $slider->url }}">
                                        <img src="{{ $sliderSrc }}" class="d-block w-100" alt="Slider Banner" style="max-height: 460px; object-fit: cover;">
                                    </a>
                                @else
                                    <img src="{{ $sliderSrc }}" class="d-block w-100" alt="Slider Banner" style="max-height: 460px; object-fit: cover;">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Trust Badges / Services Strip (Horizontally scrollable on mobile) -->
    <div class="trust-badges-container mb-4">
        <div class="row g-2 g-md-3 trust-badges-scroll-row">
            <div class="col-8 col-sm-6 col-md-3 trust-badge-col">
                <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center h-100" style="gap: 12px; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #e8f5e9; color: var(--custom-primary-color); font-size: 20px;">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px; white-space: nowrap;">ক্যাশ অন ডেলিভারি</h6>
                        <small class="text-muted" style="font-size: 11px; white-space: nowrap;">পণ্য হাতে পেয়ে মূল্য পরিশোধ</small>
                    </div>
                </div>
            </div>
            <div class="col-8 col-sm-6 col-md-3 trust-badge-col">
                <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center h-100" style="gap: 12px; border: 1px solid #e5e7eb;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: rgba(47, 177, 77, 0.12); color: var(--custom-primary-color); font-size: 20px;">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px; white-space: nowrap;">দ্রুত ডেলিভারি</h6>
                        <small class="text-muted" style="font-size: 11px; white-space: nowrap;">২৪ থেকে ৭২ ঘণ্টার মধ্যে</small>
                    </div>
                </div>
            </div>
            <div class="col-8 col-sm-6 col-md-3 trust-badge-col">
                <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center h-100" style="gap: 12px; border: 1px solid #e5e7eb;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: rgba(47, 177, 77, 0.12); color: var(--custom-primary-color); font-size: 20px;">
                        <i class="fa-solid fa-medal"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px; white-space: nowrap;">১০০% অরিজিনাল পণ্য</h6>
                        <small class="text-muted" style="font-size: 11px; white-space: nowrap;">গুণগতমানের সর্বোচ্চ নিশ্চয়তা</small>
                    </div>
                </div>
            </div>
            <div class="col-8 col-sm-6 col-md-3 trust-badge-col">
                <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center h-100" style="gap: 12px; border: 1px solid #e5e7eb;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: rgba(47, 177, 77, 0.12); color: var(--custom-primary-color); font-size: 20px;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px; white-space: nowrap;">কাস্টমার সাপোর্ট</h6>
                        <small class="text-muted" style="font-size: 11px; white-space: nowrap;">যে কোনো প্রয়োজনে পাশে আছি</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Category Showcase Grid -->
    @if(isset($categories) && $categories->count() > 0)
        <div class="categories-section mb-4 p-2 p-sm-3 bg-white rounded-3 shadow-sm" style="border: 1px solid #e5e7eb; overflow: hidden; box-sizing: border-box;">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                <h5 class="fw-bold m-0 d-flex align-items-center" style="color: #111827; font-size: 16px;">
                    <span style="display: inline-block; width: 4px; height: 18px; background: var(--custom-primary-color); border-radius: 2px; margin-right: 8px;"></span>
                    জনপ্রিয় ক্যাটাগরি
                </h5>
                <a href="{{ route('allProducts') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold flex-shrink-0" style="font-size: 11.5px; white-space: nowrap;">
                    সকল ক্যাটাগরি <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-1 g-sm-2 g-md-3 row-cols-3 row-cols-md-4 row-cols-lg-6 text-center justify-content-center mx-0">
                @foreach($categories as $cat)
                    <div class="col px-1 mb-2">
                        <a href="{{ route('category', $cat->id) }}" class="category-card-box text-decoration-none d-block p-2 rounded-3 h-100 transition-hover" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                            <div class="cat-img-wrap mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.06); overflow: hidden; padding: 5px;">
                                @if(!empty($cat->image))
                                    <img src="{{ asset('backend/img/category/' . $cat->image) }}" alt="{{ $cat->title }}" style="width: 100%; height: 100%; object-fit: contain;">
                                @else
                                    <i class="fa-solid fa-tag text-success" style="font-size: 24px;"></i>
                                @endif
                            </div>
                            <span class="d-block fw-semibold text-dark text-truncate" style="font-size: 12px;">{{ $cat->title }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 4. Recommended / Best Selling Products Section -->
    @if(isset($best_selling) && $best_selling->count() > 0)
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold text-dark m-0 d-flex align-items-center" style="font-size: 17px;">
                        <span style="display: inline-block; width: 4px; height: 18px; background: var(--custom-primary-color); border-radius: 2px; margin-right: 8px;"></span>
                        রেকম্যান্ডেবল পণ্য
                    </h4>
                    <small class="text-muted d-none d-sm-inline-block" style="font-size: 12px; margin-left: 12px;">আমাদের সর্বাধিক বিক্রিত এবং সেরা পছন্দের কালেকশন</small>
                </div>
                <a href="{{ route('allProducts') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold flex-shrink-0" style="font-size: 11.5px; white-space: nowrap;">
                    সব দেখুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-1 g-sm-2 row-cols-2 row-cols-md-3 row-cols-lg-4 product-grid-tight">
                @foreach($best_selling->take(12) as $product)
                    <div class="col">
                        @include('araz.partials.product_card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 5. Hot Deals / Flash Sale Section -->
    @if(isset($hots) && $hots->count() > 0)
        <div class="mb-4 p-2 p-sm-3 rounded-3 position-relative" style="background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); border: 2px dashed #fda4af; box-sizing: border-box;">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-danger border-opacity-25 flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold text-danger m-0 d-flex align-items-center" style="font-size: 17px;">
                        <i class="fa-solid fa-fire text-danger me-2"></i> হট ডিল ও বিশেষ অফার
                    </h4>
                    <small class="text-muted d-none d-sm-inline-block" style="font-size: 12px; margin-left: 24px;">সীমিত সময়ের জন্য বিশেষ মূল্যছাড়ের সুযোগ নিন</small>
                </div>
                <a href="{{ route('hot_deals') }}" class="btn btn-sm btn-danger rounded-pill px-3 py-1 fw-bold flex-shrink-0 text-white" style="font-size: 11.5px; white-space: nowrap;">
                    সব অফার দেখুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-1 g-sm-2 g-md-3 row-cols-2 row-cols-md-3 row-cols-lg-4 mx-0">
                @foreach($hots->take(6) as $product)
                    <div class="col px-1">
                        @include('araz.partials.product_card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 6. New / Trending Products Section -->
    @if(isset($products) && $products->count() > 0)
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold text-dark m-0 d-flex align-items-center" style="font-size: 17px;">
                        <span style="display: inline-block; width: 4px; height: 18px; background: var(--custom-primary-color); border-radius: 2px; margin-right: 8px;"></span>
                        আমাদের নতুন প্রোডাক্টস
                    </h4>
                    <small class="text-muted d-none d-sm-inline-block" style="font-size: 12px; margin-left: 12px;">সদ্য যুক্ত হওয়া ট্রেন্ডিং পণ্যসমূহ</small>
                </div>
                <a href="{{ route('allProducts') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold flex-shrink-0" style="font-size: 11.5px; white-space: nowrap;">
                    সব দেখুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-1 g-sm-2 row-cols-2 row-cols-md-3 row-cols-lg-4 product-grid-tight">
                @foreach($products as $product)
                    <div class="col">
                        @include('araz.partials.product_card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if(method_exists($products, 'links'))
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- 7. Category Wise Showcase Sections -->
    @if(isset($category_products) && $category_products->count() > 0)
        @foreach($category_products as $cat)
            @if($cat->products && $cat->products->count() > 0)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                        <div>
                            <h4 class="fw-bold text-dark m-0 d-flex align-items-center" style="font-size: 17px;">
                                <span style="display: inline-block; width: 4px; height: 18px; background: var(--custom-primary-color); border-radius: 2px; margin-right: 8px;"></span>
                                {{ $cat->title }}
                            </h4>
                        </div>
                        <a href="{{ route('category', $cat->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold flex-shrink-0" style="font-size: 11.5px; white-space: nowrap;">
                            সব দেখুন <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <div class="row g-1 g-sm-2 row-cols-2 row-cols-md-3 row-cols-lg-4 product-grid-tight">
                        @foreach($cat->products->take(6) as $product)
                            <div class="col">
                                @include('araz.partials.product_card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @endif

</div>

<style>
    .hover-scale:hover {
        transform: translateY(-4px);
        border-color: var(--custom-primary-color) !important;
    }
</style>
@endsection

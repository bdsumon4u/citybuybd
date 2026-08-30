@php
    $navCategories = \App\Models\Category::with(['subcategories' => function($q) {
        $q->where('status', 1);
    }])->where('status', 1)->orderBy('title', 'asc')->get();
@endphp

<!-- Desktop Header -->
<header class="desktop header axil-header header-style-5 d-none d-lg-block">
    <!-- Marquee Bar -->
    <div class="d-flex align-items-center h-final-marquee-wrapper" style="background: #a90404 !important;">
        <marquee direction="left" scrollamount="4" style="color: #ffffff !important; font-weight: 600; font-size: 14px; margin: 0; padding: 2px 0;">
            {{ $settings->marque_text ?? 'আপনাকে স্বাগতম । বাংলাদেশের বিশ্বস্ত অনলাইন শপ । সারাদেশে ক্যাশ অন ডেলিভারি (৪৮ থেকে ৭২ ঘণ্টার মধ্যে নিশ্চিত ডেলিভারি) হটলাইনঃ ' . ($settings->phone ?? '01601745352') }}
        </marquee>
    </div>

    <!-- Mainmenu Area -->
    <div class="axil-mainmenu" style="background: #ffffff; border-bottom: 1px solid #e5e7eb;">
        <div class="container-fluid" style="padding: 12px 25px;">
            <div class="row align-items-center">
                <!-- Brand Logo -->
                <div class="col-3 d-flex align-items-center">
                    <a class="brand" href="{{ url('/') }}">
                        @if(!empty($settings->logo))
                            <img src="{{ asset('backend/img/' . $settings->logo) }}" alt="{{ $settings->insta_link ?? config('app.name') }}" style="max-height: 52px; max-width: 200px; object-fit: contain;">
                        @else
                            <h2 style="margin: 0; color: var(--custom-primary-color); font-weight: 800; font-size: 26px;">{{ $settings->insta_link ?? config('app.name') }}</h2>
                        @endif
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="col-6">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="axil-search" style="position: relative; width: 100%;">
                            <input type="search" class="form-control" name="search" id="desktop-search" value="{{ request('search') }}"
                                   placeholder="প্রোডাক্ট খুঁজুন এখানে..." autocomplete="off"
                                   style="height: 46px; border-radius: 25px; padding-left: 20px; padding-right: 50px; border: 2px solid var(--custom-primary-color); font-size: 14px; width: 100%;">
                            <button type="submit" style="position: absolute; right: 5px; top: 5px; bottom: 5px; width: 40px; background: var(--custom-primary-color); color: #fff; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Actions: Phone Hotline & Cart -->
                <div class="col-3 text-end d-flex align-items-center justify-content-end" style="gap: 15px;">
                    @if(!empty($settings->phone))
                        <a href="tel:{{ $settings->phone }}" class="btn btn-outline-success rounded-pill px-3 py-2 d-inline-flex align-items-center fw-bold" style="font-size: 14px; border-color: var(--custom-primary-color);">
                            <i class="fa-solid fa-phone me-2"></i> {{ $settings->phone }}
                        </a>
                    @endif

                    <a href="{{ route('checkout') }}" class="btn rounded-pill px-3 py-2 d-inline-flex align-items-center text-white fw-bold position-relative" style="background: var(--custom-primary-color); font-size: 14px;">
                        <i class="fa-solid fa-basket-shopping me-2"></i>
                        <span>Cart</span>
                        <span class="badge bg-danger rounded-pill ms-2 cart-count" style="font-size: 14px; padding: 2px 4px; color: white;">
                            {{ \Gloudemans\Shoppingcart\Facades\Cart::count() }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mega Menu Bar -->
    @include('araz.partials.megamenu')
</header>

<!-- Mobile Header -->
<header class="mobile header axil-header d-block d-lg-none" style="background: #ffffff; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 1000;">
    <!-- Marquee Bar Mobile -->
    <div class="d-flex align-items-center h-final-marquee-wrapper" style="background: #a90404 !important; padding: 4px 10px;">
        <marquee direction="left" scrollamount="4" style="color: #ffffff !important; font-weight: 600; font-size: 12px; margin: 0;">
            {{ $settings->marque_text ?? 'আপনাকে স্বাগতম । সারাদেশের বিশ্বস্ত অনলাইন শপ । হটলাইনঃ ' . ($settings->phone ?? '01601745352') }}
        </marquee>
    </div>

    <div class="container-fluid" style="padding: 8px 15px;">
        <div class="row align-items-center justify-content-between">
            <!-- Mobile Menu Toggle Button -->
            <div class="col-2">
                <button class="btn p-0 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileOffcanvasMenu" aria-controls="mobileOffcanvasMenu" style="font-size: 24px; color: var(--custom-primary-color);">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Logo -->
            <div class="col-7 text-center">
                <a href="{{ url('/') }}">
                    @if(!empty($settings->logo))
                        <img src="{{ asset('backend/img/' . $settings->logo) }}" alt="{{ $settings->insta_link ?? config('app.name') }}" style="max-height: 40px; max-width: 150px; object-fit: contain;">
                    @else
                        <h4 style="margin: 0; color: var(--custom-primary-color); font-weight: 800; font-size: 20px;">{{ $settings->insta_link ?? config('app.name') }}</h4>
                    @endif
                </a>
            </div>

            <!-- Mobile Search Toggle & Cart Icon -->
            <div class="col-3 text-end d-flex align-items-center justify-content-end pe-3" style="gap: 14px;">
                <button class="btn p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearchCollapse" aria-expanded="false" style="font-size: 19px; color: #374151;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="{{ route('checkout') }}" class="position-relative d-inline-flex align-items-center me-1" style="color: var(--custom-primary-color); font-size: 22px; text-decoration: none;">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <span class="badge bg-danger rounded-pill position-absolute cart-count" style="font-size: 10px; padding: 2px 5px; top: -5px; right: -8px; color: #ffffff !important; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                        {{ \Gloudemans\Shoppingcart\Facades\Cart::count() }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Collapsible Search Form -->
        <div class="collapse mt-2" id="mobileSearchCollapse">
            <form action="{{ route('search') }}" method="GET" class="px-1 py-1">
                <div style="position: relative; width: 100%;">
                    <input type="search" class="form-control shadow-none" name="search" value="{{ request('search') }}" placeholder="প্রোডাক্ট খুঁজুন এখানে..." autocomplete="off" style="height: 38px !important; min-height: 38px !important; border-radius: 20px !important; padding-left: 16px !important; padding-right: 44px !important; border: 1.5px solid var(--custom-primary-color) !important; font-size: 13px !important; width: 100% !important; background: #ffffff !important;">
                    <button type="submit" style="position: absolute; right: 3px; top: 3px; bottom: 3px; width: 32px; height: 32px; background: var(--custom-primary-color); color: #fff; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 13px;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-start mobile-side-drawer" tabindex="-1" id="mobileOffcanvasMenu" aria-labelledby="mobileOffcanvasMenuLabel" style="width: 320px; border-radius: 0 18px 18px 0; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.25);">
    <!-- Drawer Header -->
    <div class="offcanvas-header py-3 px-3 d-flex align-items-center justify-content-between text-white" style="background: linear-gradient(135deg, #2fb14d 0%, #15803d 100%);">
        <div class="d-flex align-items-center" style="gap: 10px;">
            @if(!empty($settings->logo))
                <img src="{{ asset('backend/img/' . $settings->logo) }}" alt="Logo" style="max-height: 34px; max-width: 130px; object-fit: contain; background: #fff; padding: 2px 6px; border-radius: 6px;">
            @else
                <h5 class="offcanvas-title fw-bold m-0 text-white" id="mobileOffcanvasMenuLabel">
                    {{ $settings->insta_link ?? config('app.name') }}
                </h5>
            @endif
        </div>
        <button type="button" class="btn p-0 d-flex align-items-center justify-content-center text-white" data-bs-dismiss="offcanvas" aria-label="Close" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); border: none;">
            <i class="fa-solid fa-xmark" style="font-size: 16px;"></i>
        </button>
    </div>

    <div class="offcanvas-body p-0 d-flex flex-column" style="background: #ffffff;">
        <!-- Drawer Search -->
        <div class="px-3 py-2 bg-light border-bottom">
            <form action="{{ route('search') }}" method="GET">
                <div style="position: relative; width: 100%;">
                    <input type="search" class="form-control shadow-none" name="search" value="{{ request('search') }}" placeholder="প্রোডাক্ট খুঁজুন..." autocomplete="off" style="height: 36px !important; min-height: 36px !important; border-radius: 18px !important; padding-left: 14px !important; padding-right: 40px !important; border: 1.5px solid #cbd5e1 !important; font-size: 13px !important; width: 100% !important; background: #ffffff !important;">
                    <button type="submit" style="position: absolute; right: 3px; top: 3px; bottom: 3px; width: 30px; height: 30px; background: var(--custom-primary-color); color: #fff; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 12px;"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Navigation Shortcuts -->
        <div class="px-3 py-2 border-bottom bg-white">
            <div class="row g-2 text-center">
                <div class="col-6">
                    <a href="{{ url('/') }}" class="d-flex align-items-center justify-content-center p-2 rounded-3 text-dark text-decoration-none fw-bold" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 12.5px; gap: 6px;">
                        <i class="fa-solid fa-house text-success"></i> হোম পেইজ
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('hot_deals') }}" class="d-flex align-items-center justify-content-center p-2 rounded-3 text-danger text-decoration-none fw-bold" style="background: #fef2f2; border: 1px solid #fecaca; font-size: 12.5px; gap: 6px;">
                        <i class="fa-solid fa-fire text-danger"></i> হট ডিল
                    </a>
                </div>
            </div>
        </div>

        <!-- Categories Accordion List -->
        <div class="flex-grow-1 overflow-auto px-2 py-2">
            <div class="px-2 py-1 mb-1 text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 0.5px;">
                ক্যাটাগরি সমূহ
            </div>

            <div class="drawer-category-list">
                @foreach($navCategories as $mCat)
                    @php $hasSub = $mCat->subcategories && $mCat->subcategories->count() > 0; @endphp
                    <div class="drawer-category-item mb-1 rounded-2 overflow-hidden" style="border: 1px solid #f1f5f9; background: #ffffff;">
                        <div class="d-flex align-items-center justify-content-between px-2 py-2">
                            <a href="{{ route('category', $mCat->id) }}" class="text-dark text-decoration-none d-flex align-items-center flex-grow-1 fw-semibold" style="font-size: 13.5px; gap: 8px;">
                                @if(!empty($mCat->image))
                                    <img src="{{ asset('backend/img/category/' . $mCat->image) }}" alt="{{ $mCat->title }}" style="width: 24px; height: 24px; object-fit: contain; border-radius: 4px;">
                                @else
                                    <i class="fa-solid fa-folder text-success" style="font-size: 14px;"></i>
                                @endif
                                <span class="text-truncate">{{ $mCat->title }}</span>
                            </a>

                            @if($hasSub)
                                <button class="btn btn-sm p-0 drawer-toggle-btn d-flex align-items-center justify-content-center" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#subCatCollapse{{ $mCat->id }}" 
                                        aria-expanded="false" 
                                        style="width: 32px; height: 32px; border-radius: 50%; background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; outline: none; box-shadow: none;">
                                    <i class="fa-solid fa-chevron-down" style="font-size: 11px; transition: transform 0.25s ease;"></i>
                                </button>
                            @endif
                        </div>

                        @if($hasSub)
                            <div class="collapse" id="subCatCollapse{{ $mCat->id }}">
                                <div class="px-3 py-2" style="background: #f8fafc; border-top: 1px dashed #e2e8f0; border-left: 3px solid var(--custom-primary-color);">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($mCat->subcategories as $mSubCat)
                                            <li class="py-1">
                                                <a href="{{ route('subcategory', $mSubCat->id) }}" class="text-secondary text-decoration-none d-flex align-items-center" style="font-size: 13px; gap: 6px;">
                                                    <i class="fa-solid fa-chevron-right text-success" style="font-size: 9px;"></i>
                                                    <span>{{ $mSubCat->title }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Other Links Section -->
            <div class="px-2 pt-3 pb-1 text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 0.5px;">
                অন্যান্য পেইজ
            </div>
            <div class="drawer-links-list rounded-2 overflow-hidden mb-3" style="border: 1px solid #f1f5f9;">
                <a href="{{ route('front.about') }}" class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none border-bottom" style="font-size: 13px; gap: 10px; background: #fff;">
                    <i class="fa-solid fa-circle-info text-muted"></i> আমাদের সম্পর্কে
                </a>
                <a href="{{ route('front.contact') }}" class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none border-bottom" style="font-size: 13px; gap: 10px; background: #fff;">
                    <i class="fa-solid fa-phone text-muted"></i> যোগাযোগ
                </a>
                <a href="{{ route('front.termCondition') }}" class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none border-bottom" style="font-size: 13px; gap: 10px; background: #fff;">
                    <i class="fa-solid fa-file-contract text-muted"></i> শর্তাবলী
                </a>
                <a href="{{ route('front.return_policy') }}" class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none border-bottom" style="font-size: 13px; gap: 10px; background: #fff;">
                    <i class="fa-solid fa-rotate-left text-muted"></i> রিটার্ন পলিসি
                </a>
                <a href="{{ route('front.privacy_policy') }}" class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none" style="font-size: 13px; gap: 10px; background: #fff;">
                    <i class="fa-solid fa-shield-halved text-muted"></i> প্রাইভেসি পলিসি
                </a>
            </div>
        </div>

        <!-- Drawer Footer Support Action -->
        @if(!empty($settings->phone))
            <div class="p-3 border-top bg-light mt-auto">
                <a href="tel:{{ $settings->phone }}" class="btn btn-success w-100 rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center shadow-sm" style="font-size: 13.5px; gap: 8px;">
                    <i class="fa-solid fa-phone-volume"></i> কল করুন: {{ $settings->phone }}
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    /* Drawer Specific Smooth Styling */
    .drawer-toggle-btn[aria-expanded="true"] i {
        transform: rotate(180deg);
        color: var(--custom-primary-color) !important;
    }
    .drawer-toggle-btn:focus,
    .drawer-toggle-btn:active {
        outline: none !important;
        box-shadow: none !important;
        background: #e2e8f0 !important;
    }
    .drawer-category-item a:hover {
        color: var(--custom-primary-color) !important;
    }
</style>

@php
    $navCategories = \App\Models\Category::with(['subcategories' => function($q) {
        $q->where('status', 1);
    }])->where('status', 1)->orderBy('title', 'asc')->get();
@endphp

<div class="header_top" id="header" style="background: var(--header-menu-bg, #a90404); box-shadow: 0 2px 8px rgba(0,0,0,0.12);">
    <nav class="navbar container-fluid" style="padding: 0 20px;">
        <section class="navbar-center w-100">
            <div class="menu" id="menu">
                <ul class="menu-inner d-flex align-items-center mb-0 list-unstyled" style="gap: 8px; padding: 4px 0;">
                    <!-- Home Link -->
                    <li class="menu-item main_menu">
                        <a href="{{ url('/') }}" class="nav-item-link text-white fw-bold text-decoration-none px-3 py-2 rounded-pill d-inline-flex align-items-center" style="font-size: 14.5px; transition: all 0.2s;">
                            <i class="fa-solid fa-house me-2" style="font-size: 14px;"></i> Home
                        </a>
                    </li>

                    <!-- Categories Mega Dropdown -->
                    <li class="menu-item menu-dropdown catagory_for_menu position-relative">
                        <span class="nav-item-link sub_menu_header text-white fw-bold px-3 py-2 rounded-pill d-inline-flex align-items-center" style="font-size: 14.5px; cursor: pointer; transition: all 0.2s;">
                            <i class="fa-solid fa-bars me-2" style="font-size: 14px;"></i> Categories
                            <i class="fa-solid fa-chevron-down ms-2" style="font-size: 11px; transition: transform 0.2s;"></i>
                        </span>
                        
                        <!-- Mega Menu Panel -->
                        <div class="megamenu-panel">
                            <div class="row g-4">
                                @foreach($navCategories as $cat)
                                    <div class="col-md-3 megamenu-col">
                                        <div class="megamenu-cat-card h-100">
                                            <a href="{{ route('category', $cat->id) }}" class="megamenu-cat-title text-decoration-none d-flex align-items-center">
                                                @if(!empty($cat->image))
                                                    <img src="{{ asset('backend/img/category/' . $cat->image) }}" alt="{{ $cat->title }}" style="width: 22px; height: 22px; object-fit: contain; margin-right: 8px; border-radius: 4px;">
                                                @else
                                                    <i class="fa-solid fa-folder text-success me-2" style="font-size: 14px;"></i>
                                                @endif
                                                <span>{{ $cat->title }}</span>
                                            </a>

                                            @if($cat->subcategories && $cat->subcategories->count() > 0)
                                                <ul class="megamenu-sub-list list-unstyled mb-0 mt-2">
                                                    @foreach($cat->subcategories->take(6) as $subcat)
                                                        <li class="megamenu-sub-item">
                                                            <a href="{{ route('subcategory', $subcat->id) }}" class="megamenu-sub-link text-decoration-none d-flex align-items-center">
                                                                <i class="fa-solid fa-chevron-right text-muted me-2" style="font-size: 9px;"></i>
                                                                <span>{{ $subcat->title }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                    @if($cat->subcategories->count() > 6)
                                                        <li class="megamenu-sub-item mt-1">
                                                            <a href="{{ route('category', $cat->id) }}" class="text-success text-decoration-none fw-bold" style="font-size: 12px;">
                                                                + আরো {{ $cat->subcategories->count() - 6 }} টি দেখুন
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="megamenu-bottom-bar mt-3 pt-3 d-flex justify-content-between align-items-center border-top">
                                <span class="text-muted fw-semibold" style="font-size: 13px;">
                                    <i class="fa-solid fa-circle-check text-success me-1"></i> আমাদের সকল পণ্য ১০০% কোয়ালিটি পরীক্ষিত
                                </span>
                                <a href="{{ route('allProducts') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" style="font-size: 12px;">
                                    সকল পণ্য দেখুন <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- Direct top categories list -->
                    @foreach($navCategories->take(6) as $topCat)
                        <li class="menu-item d-none d-lg-inline-block">
                            <a href="{{ route('category', $topCat->id) }}" class="nav-item-link text-white text-decoration-none px-3 py-2 rounded-pill" style="font-size: 14px; opacity: 0.95; transition: all 0.2s;">
                                {{ $topCat->title }}
                            </a>
                        </li>
                    @endforeach

                    <!-- Hot Deals Pill -->
                    <li class="menu-item ms-auto d-none d-md-inline-block">
                        <a href="{{ route('hot_deals') }}" class="btn btn-warning text-dark rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center shadow-sm" style="font-size: 13px;">
                            <i class="fa-solid fa-fire text-danger me-1"></i> Hot Deal
                        </a>
                    </li>
                </ul>
            </div>
        </section>
    </nav>
</div>

<style>
    /* Megamenu Modern Styles */
    .nav-item-link:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }
    .catagory_for_menu {
        position: relative;
    }
    .catagory_for_menu:hover .megamenu-panel {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
    }
    .catagory_for_menu:hover .sub_menu_header .fa-chevron-down {
        transform: rotate(180deg);
    }
    .megamenu-panel {
        display: none;
        opacity: 0;
        visibility: hidden;
        position: absolute;
        top: 100%;
        left: 0;
        width: 1000px;
        max-width: 90vw;
        max-height: 75vh;
        overflow-y: auto;
        background: #ffffff;
        z-index: 1050;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18);
        padding: 24px;
        transform: translateY(10px);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .megamenu-panel::-webkit-scrollbar {
        width: 6px;
    }
    .megamenu-panel::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 6px;
    }
    .megamenu-cat-card {
        background: transparent;
        padding: 4px;
    }
    .megamenu-cat-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #111827 !important;
        padding-bottom: 6px;
        border-bottom: 2px solid #2fb14d;
        transition: all 0.2s;
    }
    .megamenu-cat-title:hover {
        color: var(--custom-primary-color) !important;
        padding-left: 3px;
    }
    .megamenu-sub-list {
        padding-left: 2px;
    }
    .megamenu-sub-item {
        margin: 4px 0;
    }
    .megamenu-sub-link {
        font-size: 13px;
        font-weight: 500;
        color: #4b5563 !important;
        padding: 4px 0;
        transition: all 0.2s ease;
    }
    .megamenu-sub-link:hover {
        color: var(--custom-primary-color) !important;
        padding-left: 6px;
        font-weight: 600;
    }
    .megamenu-sub-link:hover i {
        color: var(--custom-primary-color) !important;
    }
</style>

<!--------------------------------- HEADER SECTION START --------------------------------->
<div class="header">
    @if($settings->marque_status == 1)
        <div class="top-header">
            <div class="container">
                <div class="row">
                    <marquee direction="left" scrollamount="3" style="font-weight:450;color:white;">
                        {{ $settings->marque_text }}
                    </marquee>
                </div>
            </div>
        </div>
    @endif

    <div class="bottom-header">
        <div class="container">
            <div class="row justify-content-between align-items-center g-md-4 g-sm-0">
            <!-- Logo -->
                <div class="col-xxl-3 col-xl-2 col-lg-2 col-sm-3 col-5 logo-col">
                    <div class="logo">
                        <a href="{{url('/')}}">
                            @if($settings)
                                <img src="{{ asset('backend/img/'.$settings->logo)  }}" alt="logo">
                            @endif
                        </a>
                    </div>
                </div>

                {{-- Categories are shared via AppServiceProvider --}}

                <!-- Search (Desktop Only) -->
                <div class="col-xxl-6 col-xl-7 col-lg-8 col-sm-6 col-12 search-col d-none d-lg-block">
                    <div class="header-search">
                        <form action="{{route('search')}}" id="search_form" method="get">
                            <div class="wrap">
                                <span class="devider"></span>
                                <input type="search" name="search" placeholder="What do you need?">
                            </div>
                            <button type="submit">
                                <span><i class="fa-light fa-magnifying-glass"></i></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Cart & Mobile Search -->
                <div class="col-xl-3 col-lg-2 col-sm-2 col-6 cart-col">
                    <ul class="bottom-header-right d-flex align-items-center justify-content-end">
                        <li class="live-chat d-xl-flex d-none align-items-center">
                            <div class="icon">
                                <img src="{{ asset('frontend/images/call-icon.png')  }}" alt="call">
                            </div>
                            <div class="txt">
                                <span class="d-block">Live Chat or :</span>
                                <a class="d-block" href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a>
                            </div>
                        </li>

                        <!-- Mobile Search Icon -->
                        <li class="mobile-search d-lg-none me-2">
                            <button class="search-toggle">
                                <i class="fa-light fa-magnifying-glass"></i>
                            </button>
                            <div class="mobile-search-box">
                                <form action="{{route('search')}}" method="get">
                                    <input type="search" name="search" placeholder="What do you need?">
                                    <button type="submit"><i class="fa-solid fa-arrow-right"></i></button>
                                </form>
                            </div>
                        </li>

                        <!-- Cart -->
                        <li class="header-cart-options">
                            <a href="{{route('checkout')}}" class="cart-list-btn">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="quantity fa-fade fa-beat">
                                    {{ count(Cart::content()) }}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu -->
    <div class="menu-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-3 col-xl-3 col-lg-4">
                    <div class="all-department">
                        <span>All Categories</span>
                        <button class="category-list-close"><i class="fa-light fa-bars"></i></button>
                        <div class="banner {{ \Request::route()->getName() == 'homepage' ? '' : 'd-none' }}">
                            <div class="category-list">
                                <ul>
                                    @foreach($categories as $category)
                                        <li class="category-item">
                                            <a href="{{route('category', $category->id)}}">
                                                <div class="icon">
                                                    <span>
                                                        <i class="fa-duotone fa-hyphen fa-fade" style="--fa-secondary-opacity: .5;"></i>
                                                    </span>
                                                </div>
                                                <span>{{$category->title}}</span>
                                            </a>

                                            @if(count($category->subcategories) > 0)
                                                <div class="category-sub-menu bg-1">
                                                    <div class="row g-4">
                                                        @foreach($category->subcategories as $subcategory)
                                                            <div class="col-lg-4">
                                                                <h4>
                                                                    <span>
                                                                        <i class="fa-duotone fa-hyphen fa-fade" style="--fa-secondary-opacity: .5;"></i>
                                                                    </span>
                                                                    <a href="{{route('subcategory', $subcategory->id)}}">
                                                                        {{ $subcategory->title }}
                                                                    </a>
                                                                </h4>

                                                                @if($subcategory->childcategories)
                                                                    <ul>
                                                                        @foreach($subcategory->childcategories as $childcategory)
                                                                            <li>
                                                                                <a href="{{route('childcategory', $childcategory->id)}}">
                                                                                    {{$childcategory->title}}
                                                                                </a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navbar -->
                <div class="col-xxl-7 col-xl-7 col-lg-6">
                    <nav class="navbar navbar-expand-lg">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <nav id="revel-mobile-menu" class="revel-mobile-menu">
                                    <ul class="mb-2 navbar-nav me-auto mb-lg-0">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('homepage') }}">Home</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('front.about') }}">About</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('front.termCondition') }}">Terms & Conditions</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('front.contact') }}">Contact</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>
    </div>
</div>


<!--------------------------------- HEADER SECTION END --------------------------------->

<!-- sticky header -->
<style>
.header {
    position: sticky;
    top: 0;
    z-index: 9999;
    background-color: #fff;
}
.header .menu-bar {
    transition: top 0.3s ease-in-out;
}
.header .menu-bar.navbar-fixed{
    top: 145px;
    transition: top 5s ease-in-out;
}

/* Mobile Search Styles */
.mobile-search button.search-toggle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.mobile-search-box {
    display: none;
    position: absolute;
    top: 60px;
    right: 60px;
    background: #fff;
    border: 1px solid #ddd;
    padding: 8px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.mobile-search-box form {
    display: flex;
    align-items: center;
}
.mobile-search-box input {
    border: 1px solid #ddd;
    padding: 6px 10px;
    border-radius: 6px;
    outline: none;
    width: 180px;
}
.mobile-search-box button {
    background: #000;
    color: #fff;
    border: none;
    padding: 6px 10px;
    margin-left: 5px;
    border-radius: 6px;
}
</style>

<!-- Toggle Script -->
<script>
document.addEventListener("DOMContentLoaded", function(){
    const toggleBtn = document.querySelector(".mobile-search .search-toggle");
    const searchBox = document.querySelector(".mobile-search-box");

    if(toggleBtn){
        toggleBtn.addEventListener("click", function(e){
            e.preventDefault();
            searchBox.style.display = searchBox.style.display === "block" ? "none" : "block";
        });
    }
});
</script>


{{-- Settings shared via AppServiceProvider --}}


<!doctype html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $settings->insta_link ?? '' }} - @yield('pageTitle')
    </title>
    <meta name="description" content="">
    <meta property="og:site_name" content="{{ $settings->insta_link ?? '' }}">
    <meta property="og:image" content="">
    <meta property="og:title" content="{{ $settings->insta_link ?? '' }}">
    <meta property="og:description" content="">
    <meta property="og:url" content="">
    <meta property="og:type" content="e-commerce">
    <meta name="twitter:title" content="{{ $settings->insta_link ?? '' }}">
    <meta name="twitter:description" content="">




    <link rel="shortcut icon" href="">

    <link
        href="https://fonts.googleapis.com/css?family=Raleway:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap"
        rel="stylesheet">

    <link href="frontEnd/plugins/font-awesome/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">



    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/flaticon/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/nice-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/flags.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/meanmenu.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}" />

    @stack('css')
    <style>
        .single-product-card .part-txt .order-btn {
            background-color: #{{ $settings->website_color ?? '000' }};
        }

        .single-product-card:hover {
            border: 2px solid #{{ $settings->website_color ?? '000' }};
        }

        .header .menu-bar .all-department {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .header .bottom-header .header-search form button {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .header .bottom-header .bottom-header-right .header-cart-options a:last-child .quantity {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .panel .panel-header .explore-section {
            color: #{{ $settings->website_color ?? '000' }};
        }

        .panel .panel-header:after {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .product-view-area .part-txt .btn-box a {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .shop-details .details-area .nav-link.active {
            color: #{{ $settings->website_color ?? '000' }};
        }

        .tab-section .tab-nav .single-nav.active::before,
        .tab-section .tab-nav .single-nav.active::after {
            background: #{{ $settings->website_color ?? '000' }};
            border-color: #{{ $settings->website_color ?? '000' }};
        }

        .tab-section .tab-nav .single-nav.active::before,
        .tab-section .tab-nav .single-nav.active::after {
            background: #{{ $settings->website_color ?? '000' }};
            border-color: #{{ $settings->website_color ?? '000' }};
        }

        .slick-next:before,
        .slick-prev:before {
            color: #{{ $settings->website_color ?? '000' }};
        }

        .def-btn {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .single-product-card .part-img .off-tag {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .mobile-menu .mobile-nav li a.center {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .product-view-area .part-img .img-box .quick-view {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .product-quick-view-panel-2 .panel-arrow button {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .product-view-area .part-img .img-box .quick-view {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .product-view-area .part-img .img-box .quick-view {
            background: #{{ $settings->website_color ?? '000' }};
        }

        .product-view-area .part-img .img-box .quick-view {
            background: #{{ $settings->website_color ?? '000' }};
        }
    </style>
    @if ($settings)
        <link rel="icon" type="image/x-icon" href="{{ asset('backend/img/' . $settings->favicon) }}">
        {!! $settings->fb_pixel ?? null !!}
    @endif


</head>

<body>

    @if (session('message'))
        @php
            $type = session('alert-type', 'info');
            $typeClass = match ($type) {
                'danger' => 'alert-danger',
                'warning' => 'alert-warning',
                'success' => 'alert-success',
                default => 'alert-info',
            };
        @endphp
        <div class="container mt-3">
            <div class="alert {{ $typeClass }} text-center" role="alert">
                {{ session('message') }}
            </div>
        </div>
    @endif




    <!--------------------------------- MOBILE MENU START --------------------------------->
    @php
        // Categories are shared via AppServiceProvider
    @endphp

    @if (\Illuminate\Support\Facades\Route::current()->getName() != 'details')
        <div class="mobile-menu d-lg-none d-block">
            <div class="mobile-category-list">
                <button class="mobile-menu-close-btn"><i class="fa-solid fa-xmark-large"></i></button>
                <ul class="category-nav">
                    <li class="title">All Categories</li>

                    @foreach ($categories as $category)
                        <li class="category-item @if ($category->subcategories) has-sub @endif">
                            <a role="button">
                                <div class="icon">
                                    <span><i class="fa-duotone fa-hyphen fa-fade"
                                            style="--fa-secondary-opacity: .5;"></i></span>
                                </div>
                                <span>{{ $category->title }}</span>
                            </a>

                            <div class="category-sub-menu" style="display: none;">
                                <div class="row g-4">
                                    @foreach ($category->subcategories as $subcategory)
                                        <div class="col-lg-4">
                                            <h4><span><i class="fa-duotone fa-hyphen fa-fade"
                                                        style="--fa-secondary-opacity: .5;"></i></span><a
                                                    href="{{ route('subcategory', $subcategory->id) }}">
                                                    {{ $subcategory->title }} </a></h4>
                                            @if ($subcategory->childcategories)
                                                <ul>
                                                    @foreach ($subcategory->childcategories as $childcategory)
                                                        <li>
                                                            <a href="{{ route('childcategory', $childcategory->id) }}"><i
                                                                    class="fa-solid fa-hyphen"
                                                                    style="--fa-secondary-opacity: .5;"></i>{{ $childcategory->title }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="m-main-menu">
                <button class="mobile-menu-close-btn"><i class="fa-solid fa-xmark-large"></i></button>
                <ul class="menu-bar">
                    <li class="logo">
                        <img src="assets/images/Logo.html" alt="Logo">
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Home</a>
                        <ul class="dropdown-menu">
                            <li><a href="index.html" class="dropdown-item">Home Page 01</a></li>
                            <li><a href="index-2.html" class="dropdown-item">Home Page 02</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="account.html" class="nav-link">Account</a>
                    </li>
                    <li class="nav-item">
                        <a href="register.html" class="nav-link">Login / Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.html" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Shop</a>
                        <ul class="dropdown-menu">
                            <li><a href="shop.html" class="dropdown-item">Shop Left Bar</a></li>
                            <li><a href="shop-right-bar.html" class="dropdown-item">Shop Right Bar</a></li>
                            <li><a href="shop-details.html" class="dropdown-item">Shop Details Page</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="faq.html" class="nav-link">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Blog</a>
                        <ul class="dropdown-menu">
                            <li><a href="blog.html" class="dropdown-item">Blog Page</a></li>
                            <li><a href="blog-details.html" class="dropdown-item">Blog Details Page</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="contact.html" class="nav-link">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Pages</a>
                        <ul class="dropdown-menu">
                            <li><a href="cart.html" class="dropdown-item">Cart Page</a></li>
                            <li><a href="compare.html" class="dropdown-item">Compare Page</a></li>
                            <li><a href="wishlist.html" class="dropdown-item">Wishlist Page</a></li>
                            <li><a href="register.html" class="dropdown-item">Register Page</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <ul class="mobile-nav">
                <li><a role="button" data-target="mobile-category-list" class="m-nav-link"><i
                            class="fa-light fa-grid-2"></i></a></li>
                <li><a href="{{ route('checkout') }}" class="m-nav-link"><i
                            class="fa-light fa-cart-shopping"></i></a></li>
                <li><a href="{{ url('/') }}" class="center"><i class="fa-light fa-house"></i></a></li>
                <li><a href="tel:{{ $settings->phone }}" class="m-nav-link"><i
                            class="fa-regular fa-phone-volume"></i></a></li>
                <li><a href="https://wa.me/{{ $settings->whatsapp_number }}" target="_blank" class="m-nav-link"><i
                            class="fa-brands fa-whatsapp"></i></a></li>
            </ul>
        </div>
    @endif

    <!--------------------------------- MOBILE MENU END --------------------------------->

    <div class="main-wrapper">

        @include('frontend.includes.header')

        @yield('body-content')

        @include('frontend.includes.footer')


    </div>



    <script src="{{ asset('frontend/vendor/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/jquery.flagstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/jquery.meanmenu.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/jquery.syotimer.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/jquery-modal-video.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('frontend/js/cart.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>


    @stack('child-scripts')


    <script>
        $(document).ready(function() {
            $('.order_now_btn, .add_cart_btn').click(function() {
                swal.fire({
                    title: "Product Added To Cart Successfully",
                    icon: "success",
                    timer: 500
                });

            });
        });

        function checkScore() {

            var charLength = $('#phone').val().length;
            if (charLength == 11) {
                $('#conf_order_btn').prop('disabled', false)
            } else if (charLength > 11) {
                $('#conf_order_btn').prop('disabled', true)
            } else {
                $('#conf_order_btn').prop('disabled', true)
            }
        }
    </script>



    <script>
        $('#account-btn').on('click', function() {
            $('.login-float').toggle()
        });

        $('#header-top-menu-btn').on('click', function() {
            $('.header-top-menu-m').toggle()
        });

        $('#cat_menu_mobile_btn').on('click', function() {
            $('.cat_menu_m').toggle()
        });

        $('#search_mobile_btn').on('click', function() {
            $('.search-form-m').toggle()
        });

        $('.search_btnclose').on('click', function() {
            $('.search-form-m').toggle()
        });

        var prd_dtls_qty = $('#prd-dtls-qty');
        var prd_dtls_btn = $('#prd-dtls-btn');

        $(window).scroll(function() {
            if ($(window).scrollTop() > 1200) {
                prd_dtls_qty.addClass('product-count');
                prd_dtls_btn.addClass('btn-box-mobile');
                prd_dtls_qty.removeClass('d-none');
            } else {
                prd_dtls_qty.removeClass('product-count');
                prd_dtls_btn.removeClass('btn-box-mobile');
                prd_dtls_btn.addClass('d-none');
            }
        });


        // START





        $('.slider-nav').slick({
            slidesToShow: 8,
            slidesToScroll: 3,
            focusOnSelect: true,
            autoplay: true,
            dots: false,
            autoplaySpeed: 3000,
            pauseOnHover: true,
            arrows: false,
            responsive: [{
                breakpoint: 600,
                settings: {
                    slidesToShow: 4,
                }
            }, {
                breakpoint: 320,
                slidesToShow: 3,
            }]



        });
        $('.slider-hot').slick({
            slidesToShow: 6,
            slidesToScroll: 3,
            focusOnSelect: true,
            autoplay: true,
            dots: false,
            autoplaySpeed: 3000,
            pauseOnHover: true,
            arrows: false,
            responsive: [{
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                }
            }, {
                breakpoint: 320,
                slidesToShow: 3,
            }]


        });
    </script>
    <script type="text/javascript"></script>
    <script>
        $(".q-up").on('click', function() {

            var qty = $('#qtyyy').val();
            qty++;
            $('#qtyyy').val(qty);
        });

        $(".q-down").on('click', function() {
            var qty = $('#qtyyy').val();
            qty--;
            if (qty < 1) {
                $('#qtyyy').val(1);
            } else {
                $('#qtyyy').val(qty);
            }

        });
    </script>


</body>

</html>

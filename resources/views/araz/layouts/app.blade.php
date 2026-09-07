<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', $settings->insta_link ?? config('app.name'))</title>
    <meta name="robots" content="index, follow" />
    <meta name="description" content="@yield('meta_description', $settings->meta_description ?? '')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="var(--custom-primary-color, #2fb14d)">

    <!-- Favicon -->
    @if(!empty($settings->favicon))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/img/' . $settings->favicon) }}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/favicon.png') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@400;500;600;700;800&family=Hind+Siliguri:wght@300;400;500;600;700&family=Oswald:wght@300;400;500;600;700&family=Quicksand:wght@400;500;600;700&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('araz_assets/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('araz_assets/css/vendor/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('araz_assets/css/vendor/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('araz_assets/css/vendor/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('araz_assets/css/vendor/sal.css') }}">
    <link rel="stylesheet" href="{{ asset('araz_assets/css/vendor/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('araz_assets/css/vendor/base.css') }}">

    <!-- Theme Styles -->
    <link rel="stylesheet" href="{{ asset('araz_assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('araz_assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('araz_assets/css/update.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Icons (Loaded after theme styles so nothing overrides font-family) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/v4-shims.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    @stack('css')

    <style>
        :root {
            --dynamic-font: 'Hind Siliguri', 'Baloo Da 2', system-ui, -apple-system, sans-serif;
            --custom-primary-color: #2fb14d;
            --primary-hover: #269841;
            --header-menu-bg: #a90404;
            --header-menu-text-color: #ffffff;
            --header-search-bg: #ffffff;
            --secondary-color: #f0f5ff;
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --border-color: #e5e7eb;
            --danger-color: #ef4444;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --border-radius-sm: 6px;
            --border-radius: 12px;
        }

        body {
            font-family: var(--dynamic-font);
            background: #f0f5ff;
            color: #212121;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Protect Webfont Icons from font-family overrides */
        .fa, .fas, .far, .fal, .fad, .fab, .fa-solid, .fa-regular, .fa-light, .fa-thin, .fa-duotone, .fa-brands,
        [class^="fa-"], [class*=" fa-"],
        i[class*="fa"] {
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
        }
        .fa, .fas, .fa-solid {
            font-family: 'Font Awesome 6 Free', 'FontAwesome' !important;
            font-weight: 900 !important;
        }
        .far, .fa-regular {
            font-family: 'Font Awesome 6 Free', 'FontAwesome' !important;
            font-weight: 400 !important;
        }
        .fab, .fa-brands {
            font-family: 'Font Awesome 6 Brands', 'FontAwesome' !important;
            font-weight: 400 !important;
        }
        .bx, [class^="bx-"], [class*=" bx-"] {
            font-family: 'boxicons' !important;
            font-style: normal;
        }

        /* Announcement Marquee */
        .h-final-marquee-wrapper {
            background: #a90404 !important;
            color: #ffffff !important;
            padding: 7px 15px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            box-sizing: border-box;
            line-height: 1.4;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        .h-final-marquee-wrapper marquee,
        .h-final-marquee-wrapper span,
        .h-final-marquee-text {
            color: #ffffff !important;
            font-weight: 600 !important;
            letter-spacing: 0.3px;
        }
        .h-final-marquee-text {
            display: inline-block;
            padding-left: 100%;
            animation: marqueeText 25s linear infinite;
        }
        @keyframes marqueeText {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100%, 0); }
        }

        /* Eye-Catching Modern Pagination */
        .pagination {
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 8px 16px !important;
            background: #ffffff !important;
            border-radius: 50px !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06) !important;
            border: 1px solid #e2e8f0 !important;
            margin: 20px 0 !important;
            list-style: none !important;
        }
        .pagination .page-item .page-link {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 38px !important;
            height: 38px !important;
            padding: 0 14px !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            color: #374151 !important;
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 50px !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        }
        .pagination .page-item .page-link:hover {
            color: #ffffff !important;
            background: var(--custom-primary-color) !important;
            border-color: var(--custom-primary-color) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(47, 177, 77, 0.35) !important;
        }
        .pagination .page-item.active .page-link {
            color: #ffffff !important;
            background: linear-gradient(135deg, #2fb14d 0%, #16a34a 100%) !important;
            border-color: #16a34a !important;
            box-shadow: 0 4px 14px rgba(47, 177, 77, 0.45) !important;
            transform: scale(1.06) !important;
        }
        .pagination .page-item.disabled .page-link {
            color: #9ca3af !important;
            background: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            cursor: not-allowed !important;
            opacity: 0.7 !important;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Scroll to top */
        .back-to-top {
            position: fixed;
            bottom: 75px;
            right: 20px;
            width: 42px;
            height: 42px;
            background: var(--custom-primary-color);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            z-index: 999;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .back-to-top:hover {
            color: #fff;
            transform: translateY(-3px);
            background: var(--primary-hover);
        }

        /* Responsive Mobile Layout Adjustments */
        @media (max-width: 991px) {
            .desktop.header { display: none !important; }
            .mobile.header { display: block !important; }
            body { padding-bottom: 60px; }
        }
        @media (min-width: 992px) {
            .desktop.header { display: block !important; }
            .mobile.header { display: none !important; }
        }

        /* Trust Badges Mobile Horizontal Scroll */
        @media (max-width: 767.98px) {
            .trust-badges-scroll-row {
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
                scroll-snap-type: x mandatory;
                padding-bottom: 8px;
                padding-top: 2px;
                margin-left: -5px !important;
                margin-right: -5px !important;
            }
            .trust-badges-scroll-row::-webkit-scrollbar {
                height: 3px;
            }
            .trust-badges-scroll-row::-webkit-scrollbar-thumb {
                background: rgba(47, 177, 77, 0.4);
                border-radius: 4px;
            }
            .trust-badge-col {
                flex: 0 0 74% !important;
                max-width: 74% !important;
                scroll-snap-align: start;
                padding-left: 5px !important;
                padding-right: 5px !important;
            }

            /* Categories Single Row Mobile Horizontal Scroll */
            .categories-scroll-mobile {
                display: flex !important;
                flex-wrap: nowrap !important;
                justify-content: flex-start !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
                scroll-snap-type: x mandatory;
                padding: 2px 4px 6px 4px !important;
                margin: 0 !important;
                gap: 8px !important;
            }
            .categories-scroll-mobile::-webkit-scrollbar {
                height: 3px;
            }
            .categories-scroll-mobile::-webkit-scrollbar-thumb {
                background: rgba(47, 177, 77, 0.35);
                border-radius: 4px;
            }
            .categories-scroll-mobile .category-item-col {
                flex: 0 0 29% !important;
                max-width: 29% !important;
                min-width: 90px !important;
                scroll-snap-align: start;
                padding: 0 !important;
                margin: 0 !important;
            }
        }

        /* Two Line Title */
        .two-line-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            width: 100%;
        }

        /* Eye-Catching Rectangular Glowing Border Badge */
        @keyframes rotateNeonBeam {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes badgePulseGlow {
            0%, 100% {
                box-shadow: 0 0 8px rgba(255, 0, 85, 0.5), 0 2px 8px rgba(0, 0, 0, 0.3);
            }
            50% {
                box-shadow: 0 0 16px rgba(255, 230, 0, 0.7), 0 0 24px rgba(255, 0, 85, 0.8), 0 4px 12px rgba(0, 0, 0, 0.4);
            }
        }

        .product-badget {
            position: relative !important;
            overflow: hidden !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: auto !important;
            min-width: 62px !important;
            height: auto !important;
            border-radius: 8px !important;
            padding: 2.5px !important;
            background: #000000 !important;
            animation: badgePulseGlow 2.5s ease-in-out infinite !important;
            z-index: 3 !important;
        }

        .product-badget::before {
            content: '' !important;
            position: absolute !important;
            width: 300% !important;
            height: 300% !important;
            top: 50% !important;
            left: 50% !important;
            background: conic-gradient(
                from 0deg,
                transparent 0deg,
                transparent 220deg,
                #ff0055 260deg,
                #00ffea 300deg,
                #ffe600 335deg,
                #ffffff 355deg,
                #ff0055 360deg
            ) !important;
            animation: rotateNeonBeam 2.2s linear infinite !important;
            z-index: 0 !important;
            border-radius: 0 !important;
            border: none !important;
        }

        .dicount_text_single {
            position: relative !important;
            z-index: 1 !important;
            background: linear-gradient(135deg, #c0062a 0%, #750017 100%) !important;
            color: #ffffff !important;
            border-radius: 6px !important;
            padding: 4px 8px !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            text-align: center !important;
            line-height: 1.15 !important;
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.35) !important;
            letter-spacing: 0.3px !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6) !important;
        }

        .dicount_text_single .discount-val {
            font-size: 13px;
            font-weight: 900;
            color: #fff;
            line-height: 1;
        }

        .dicount_text_single .discount-lbl {
            font-size: 10px;
            color: #ffe600;
            font-weight: 700;
            line-height: 1.1;
            margin-top: 1px;
        }

        /* Premium Fixed Floating Cart Pill (Desktop) */
        .desktop-floating-cart {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1040;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none !important;
            display: block;
        }
        .desktop-floating-cart:hover {
            transform: translateY(-50%) translateX(-6px);
        }
        .floating-cart-inner {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            padding: 6px 7px 5px 7px;
            border-radius: 16px 0 0 16px;
            box-shadow: 0 10px 30px rgba(22, 163, 74, 0.4), -3px 0 15px rgba(0, 0, 0, 0.12);
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-right: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 86px;
            text-align: center;
            backdrop-filter: blur(8px);
        }
        .floating-cart-icon-wrap {
            color: #ffffff;
            font-size: 22px;
            margin-bottom: 5px;
            animation: cartPulse 2s ease-in-out infinite;
        }
        @keyframes cartPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.12); }
        }
        .floating-cart-badge {
            background: #ffffff;
            color: #15803d;
            font-weight: 800;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.2px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            white-space: nowrap;
            display: inline-block;
        }
        .floating-cart-price-box {
            background: rgba(0, 0, 0, 0.28);
            border-radius: 8px;
            padding: 4px 8px;
            margin-top: 6px;
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        .floating-cart-amount {
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.3px;
            white-space: nowrap;
            display: block;
        }
        @media (max-width: 991px) {
            .desktop-floating-cart {
                display: none !important;
            }
        }

        /* Animations */
        @keyframes jdx-pulse-kf {
            from { transform: scale(1); }
            50% { transform: scale(1.06); }
            to { transform: scale(1); }
        }
        .jdx-pulse {
            animation: jdx-pulse-kf 1.5s ease-in-out infinite;
        }

        /* Prevent horizontal overflow */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* Tight & Clean Product Grid Gutters (Desktop & Mobile) */
        .row.row-cols-2,
        .row.row-cols-md-3,
        .row.row-cols-lg-4,
        .row.row-cols-xl-4,
        .product-grid-tight {
            --bs-gutter-x: 10px !important;
            --bs-gutter-y: 12px !important;
        }

        .row.row-cols-2 > *,
        .row.row-cols-md-3 > *,
        .row.row-cols-lg-4 > *,
        .row.row-cols-xl-4 > * {
            padding-left: 5px !important;
            padding-right: 5px !important;
            margin-bottom: 10px !important;
        }

        /* Product Card Styles (Matches ArazShopBD) */
        .axil-product {
            background: #fff;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #eef2f6;
            margin-bottom: 0px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .axil-product:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            transform: translateY(-3px);
            border-color: #cbd5e1;
        }
        .axil-product .thumbnail {
            position: relative;
            padding: 0 !important;
            text-align: center;
            overflow: hidden;
            background: #f8fafc;
            width: 100%;
        }
        .axil-product .thumbnail img.product_img {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-top-left-radius: 11px;
            border-top-right-radius: 11px;
            transition: transform 0.4s ease;
            display: block;
        }
        .axil-product:hover .thumbnail img.product_img {
            transform: scale(1.05);
        }
        .axil-product .thumbnail .hover_img {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .axil-product:hover .thumbnail .hover_img {
            opacity: 1;
        }
        .axil-product .label-block {
            position: absolute;
            top: 8px !important;
            right: 8px !important;
            z-index: 2;
        }
        .axil-product .product-content {
            padding: 10px 10px 4px 10px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .axil-product .product-content .title {
            margin-top: 2px !important;
            margin-bottom: 3px !important;
        }
        .axil-product .product-content .title a {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            text-decoration: none;
            transition: color 0.2s;
            line-height: 1.35;
        }
        .axil-product .product-content .title a:hover {
            color: var(--custom-primary-color);
        }
        .axil-product .product-price-variant {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 3px;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }
        .axil-product .current-price {
            font-size: 16px;
            font-weight: 700;
            color: #f85606;
        }
        .axil-product .old-price {
            font-size: 11px;
            background: #ff49a5;
            color: #fff;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
            text-decoration: line-through;
        }
        .axil-product .product-action-buttons {
            padding: 0 10px 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: auto;
        }
        .axil-product .order-button {
            background: var(--custom-primary-color) !important;
            color: #fff !important;
            border-radius: 8px;
            padding: 7px 0;
            font-weight: 700;
            font-size: 13px;
            border: 2px solid transparent;
            text-align: center;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: block;
        }
        .axil-product .order-button:hover {
            background: transparent !important;
            color: var(--custom-primary-color) !important;
            border-color: var(--custom-primary-color) !important;
        }
        .axil-product .cart-button {
            background: transparent !important;
            color: #374151 !important;
            border-radius: 8px;
            padding: 6px 0;
            font-weight: 600;
            font-size: 12px;
            border: 1px solid #d1d5db;
            text-align: center;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
        }
        .axil-product .cart-button:hover {
            background: var(--custom-primary-color) !important;
            color: #fff !important;
            border-color: var(--custom-primary-color) !important;
        }

        /* Mobile Viewport Fine-tuning (Compact padding and product card gaps) */
        @media (max-width: 767.98px) {
            html, body {
                overflow-x: clip !important;
                max-width: 100% !important;
                width: 100% !important;
                position: relative !important;
            }
            .container-fluid,
            .container {
                padding-left: 8px !important;
                padding-right: 16px !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
                box-sizing: border-box !important;
            }
            .main-wrapper {
                overflow-x: hidden !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            .mb-4, .mb-5 {
                margin-bottom: 16px !important;
            }
            .row {
                margin-left: -3px !important;
                margin-right: -3px !important;
            }
            .row > * {
                padding-left: 3px !important;
                padding-right: 3px !important;
            }
            .categories-section {
                padding: 10px 8px !important;
            }
            .axil-product {
                border-radius: 8px !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
            }
            .axil-product .thumbnail {
                background: #f8fafc;
                border-bottom: 1px solid #f1f5f9;
            }
            .axil-product .thumbnail img.product_img {
                border-top-left-radius: 7px !important;
                border-top-right-radius: 7px !important;
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }
            .axil-product .product-content {
                padding: 8px 6px 2px 6px !important;
                margin-top: 8px !important;
                margin-bottom: 0 !important;
            }
            .axil-product .product-content .title {
                margin-top: 0 !important;
                margin-bottom: 2px !important;
            }
            .axil-product .product-content .title a {
                font-size: 13px !important;
                font-weight: 700 !important;
                color: #111827 !important;
                line-height: 1.3 !important;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .axil-product .product-price-variant {
                margin-top: 2px !important;
                margin-bottom: 4px !important;
                gap: 4px !important;
            }
            .axil-product .current-price {
                font-size: 14px !important;
                font-weight: 800 !important;
            }
            .axil-product .old-price {
                font-size: 9.5px !important;
                padding: 1px 4px !important;
            }
            .axil-product .product-action-buttons {
                padding: 0 6px 8px !important;
                gap: 4px !important;
                margin-top: auto !important;
            }
            .axil-product .order-button {
                padding: 5px 0 !important;
                font-size: 11.5px !important;
                border-radius: 5px !important;
            }
            .axil-product .cart-button {
                padding: 4px 0 !important;
                font-size: 10.5px !important;
                border-radius: 5px !important;
            }
            .product-badget {
                min-width: 44px !important;
                border-radius: 5px !important;
                padding: 1.5px !important;
            }
            .dicount_text_single {
                font-size: 9px !important;
                padding: 2px 4px !important;
                border-radius: 4px !important;
            }
            .dicount_text_single .discount-val {
                font-size: 10.5px !important;
            }
            .dicount_text_single .discount-lbl {
                font-size: 8.5px !important;
            }
        }

        /* Section Title Header */
        .header_container {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
        }
        .header_name {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
            display: inline-block;
            position: relative;
        }
        .catagory_usertext {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }
        .see_all {
            text-align: right;
            margin-top: -30px;
            margin-bottom: 15px;
        }
        .viewall-right {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--custom-primary-color);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            background: #e8f5e9;
            padding: 5px 14px;
            border-radius: 20px;
            transition: all 0.3s;
        }
        .viewall-right:hover {
            background: var(--custom-primary-color);
            color: #fff;
        }

        @media (max-width: 768px) {
            .see_all {
                text-align: center;
                margin-top: 10px;
                margin-bottom: 15px;
            }
            .header_name {
                font-size: 20px;
            }
            .main_cart_for {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="sticky-header">

    <!-- Scroll To Top Button -->
    <a href="#top" class="back-to-top" id="backto-top" title="Back to Top">
        <i class="fa fa-chevron-up"></i>
    </a>

    <!-- Fixed Floating Cart Pill (Desktop) -->
    <a href="{{ route('checkout') }}" class="desktop-floating-cart">
        <div class="floating-cart-inner">
            <div class="floating-cart-icon-wrap">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <span class="floating-cart-badge" id="floating-cart-count">
                {{ \Gloudemans\Shoppingcart\Facades\Cart::count() }} Items
            </span>
            <div class="floating-cart-price-box">
                <span class="floating-cart-amount" id="floating-cart-total">
                    {{ \Gloudemans\Shoppingcart\Facades\Cart::total() }} TK
                </span>
            </div>
        </div>
    </a>

    <!-- Header Section -->
    @include('araz.partials.header')

    <!-- Main Content -->
    <main class="main-wrapper">
        @yield('content')
    </main>

    <!-- Footer Section -->
    @include('araz.partials.footer')

    <!-- Mobile Bottom Navigation -->
    @include('araz.partials.mobile_bottom_nav')

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('araz_assets/js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('araz_assets/js/vendor/slick.min.js') }}"></script>
    <script src="{{ asset('araz_assets/js/vendor/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('araz_assets/js/vendor/jquery.magnific-popup.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "timeOut": "3000"
        };

        // Scroll to top button handling
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                $('#backto-top').css('display', 'flex');
            } else {
                $('#backto-top').fadeOut();
            }
        });

        $('#backto-top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 500);
        });

        // Flash message handling
        @if(Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch(type){
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'danger':
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif

        // Global function to update cart counts across the page
        function updateGlobalCart(itemCount, totalAmount) {
            if (itemCount !== undefined) {
                $('.cart-count').text(itemCount);
                $('#floating-cart-count').text(itemCount + ' Items');
                $('#mobile-bottom-cart-count').text(itemCount);
            }
            if (totalAmount !== undefined) {
                $('.amount_main .amount').text(totalAmount + ' TK');
                $('#floating-cart-total').text(totalAmount + ' TK');
            }
        }

        // Global AJAX Cart Form Submission
        $(document).on('submit', '.ajax-cart-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var originalBtnHtml = $button.html();

            $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> প্রসেসিং...');

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $button.prop('disabled', false).html(originalBtnHtml);
                    toastr.success('পণ্যটি কার্টে যুক্ত হয়েছে!');
                    if (response && response.count !== undefined) {
                        updateGlobalCart(response.count, response.total);
                    } else {
                        let cur = parseInt($('#floating-cart-count').text()) || 0;
                        updateGlobalCart(cur + 1);
                    }
                },
                error: function(xhr) {
                    $button.prop('disabled', false).html(originalBtnHtml);
                    toastr.error('কার্টে যুক্ত করতে সমস্যা হয়েছে, আবার চেষ্টা করুন।');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

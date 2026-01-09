<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landing->product->sku }}</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="{{ asset('backend/landing_page/css/owl.css') }}">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!--<link rel="stylesheet" href="{{ asset('backend/landing_page/css/owl.theme.min.css') }}">-->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('backend/css/landing.css') }}">
    <!--<link rel="stylesheet" href="{{ asset('backend/landing_page/css/media.css') }}">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /*       .whats_btn{*/
        /*  position: fixed; */
        /*  bottom: 9rem; */
        /*  left: 35px; */
        /*  background: #ffffff96; */
        /*  border-radius: 50px; */
        /*  height: 60px; */
        /*  width: 60px; */
        /*  cursor: pointer; */
        /*  box-shadow: 2px 2px 8px gray; */
        /*  text-align: center; */
        /*  display: flex; */
        /*  align-items: center; */
        /*  justify-content: center;*/
        /*  transition: 0.5s;*/
        /*  z-index: 9999;*/
        /*}*/
        @media screen and (max-width: 500px) {
            .discount_price {
                text-align: center;
            }

            .regular_price {
                text-align: center;
            }
        }
    </style>
    {!! $settings->fb_pixel ?? null !!}

</head>

<body>

    <div class="main-wrapper">
        <div class="top-div">
            <div class="container">
                <div class="element-width" data-aos="fade-down" data-aos-duration="1000">
                    <div class="top-box-weight">
                        <h2 class="top-heading-title">

                            {{ $landing->heading }}
                        </h2>
                    </div>
                </div>
                <div class="text-info-box qa" data-aos="fade-up">
                    <h2 class="top-heading-title">
                        {!! $landing->subheading !!}
                    </h2>
                </div>

            </div>
            <div class="element-shape"></div>
        </div>
        <style>
            .video-box iframe {
                width: 100% !important;
            }

            ul {
                list-style: none;
            }

            ul li {
                padding-left: 20px;
                line-height: 1.5;
            }

            ul li::before {
                content: "";
                display: inline-block;
                width: 20px;
                height: 20px;
                background-image: url({{ asset('frontend/images/check.webp') }});
                background-size: cover;
                background-repeat: no-repeat;
                margin-right: 10px;
            }

            .top-heading-title {
                /*color: black !important;*/
            }

            .top_div {
                position: relative;
                height: 70px;
                background: red;
                margin-top: 20px;

            }

            .shape {
                position: absolute;
                width: 50px;
                height: 50px;
                background: red;
                transform: rotate(45deg);
                right: -7%;
                top: 15%;
            }

            .another_div {
                position: relative;
                height: 70px;
                margin-top: 20px;
                border: 1px solid black;
                border-left: none;
            }

            @media screen and (min-width: 992px) and (max-width: 1199px) {

                .price_section {
                    padding-left: 0px !important;
                }

                .shape {
                    position: absolute;
                    width: 48px !important;
                    height: 48px !important;
                    background: red;
                    transform: rotate(45deg);
                    right: -9% !important;
                    top: 15% !important;
                }

                .top_div h3 {
                    font-size: 30px !important;
                }

                .another_div h3 {
                    font-size: 30px !important;
                }
            }

            @media screen and (min-width: 768px) and (max-width: 991px) {

                .top-div .container .element-width .top-box-weight .top-heading-title {
                    font-size: 44px;
                }

                .top-div .container .text-info-box .top-heading-title {
                    font-size: 24px;
                    line-height: 1.3em;
                }

                /*Price Section*/
                .price_section {
                    padding-left: 0px !important;
                }

                .shape {
                    right: -12% !important;
                    width: 48px !important;
                    height: 48px !important;
                }

                .top_div h3 {
                    font-size: 35px !important;
                    float: none !important;
                }

                .another_div h3 {
                    font-size: 35px !important;
                    float: none !important;
                }

            }

            @media screen and (min-width: 576px) and (max-width: 767px) {


                .top-div .container .element-width {
                    padding: 26px 0px 0px 0px !important;
                    margin-bottom: 10px !important;
                }

                .top-div .container .element-width .top-box-weight .top-heading-title {
                    font-size: 15px !important;
                    line-height: 1.3em;
                }

                .top-div .container .text-info-box .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em;
                }

                .video-box iframe {
                    height: 210px !important;
                }

                .overview {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .feature-list {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .ord {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .address_section {
                    width: 100% !important;
                }

                .order-col {
                    width: 100% !important;
                }

                /*Price Section*/
                .price_section {
                    padding-left: 0px !important;
                }

                .phone img {
                    height: 25px !important;
                    width: 25px !important;
                }


                .shape {
                    right: -13% !important;
                    width: 50px !important;
                    height: 50px !important;
                }

                .top_div {
                    width: 42% !important;
                }

                .another_div {
                    width: 42% !important;
                }

                .top_div h3 {
                    font-size: 30px !important;
                    float: none !important;
                }

                .another_div h3 {
                    font-size: 30px !important;
                    float: none !important;
                }

            }

            @media screen and (min-width: 484px) and (max-width: 575px) {

                .top-div .container .element-width {
                    padding: 26px 0px 0px 0px !important;
                    margin-bottom: 10px !important;
                }

                .top-div .container .element-width .top-box-weight .top-heading-title {
                    font-size: 15px !important;
                    line-height: 1.3em;
                }

                .top-div .container .text-info-box .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em;
                }

                .video-box iframe {
                    height: 210px !important;
                }

                .overview {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .feature-list {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .ord {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .address_section {
                    width: 100% !important;
                }

                .order-col {
                    width: 100% !important;
                }

                /*Price Section*/

                .price_section {
                    padding-left: 38px !important;
                }

                .phone img {
                    height: 25px !important;
                    width: 25px !important;
                }


                .shape {
                    right: -13% !important;
                    width: 50px !important;
                    height: 50px !important;
                }

                .top_div {
                    width: 45% !important;
                }

                .another_div {
                    width: 45% !important;
                }

                .top_div h3 {
                    font-size: 30px !important;
                    float: none !important;
                }

                .another_div h3 {
                    font-size: 30px !important;
                    float: none !important;
                }

                .container .ani-btn-box .inner-padding .btn {
                    font-size: 18px !important;
                }

            }

            @media screen and (min-width: 424px) and (max-width: 483px) {

                .top-div .container .element-width {
                    padding: 26px 0px 0px 0px !important;
                    margin-bottom: 10px !important;
                }

                .top-div .container .element-width .top-box-weight .top-heading-title {
                    font-size: 15px !important;
                    line-height: 1.3em;
                }

                .top-div .container .text-info-box .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em;
                }

                .video-box iframe {
                    height: 210px !important;
                }

                .overview {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .feature-list {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .ord {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .address_section {
                    width: 100% !important;
                }

                .order-col {
                    width: 100% !important;
                }

                /*Price Section*/

                .price_section {
                    padding-left: 38px !important;
                }

                .phone img {
                    height: 25px !important;
                    width: 25px !important;
                }


                .shape {
                    right: -13% !important;
                    width: 35px !important;
                    height: 35px !important;
                }

                .top_div {
                    width: 45% !important;
                    height: 50px !important;
                }

                .another_div label {
                    font-size: 12px !important;
                }

                .top_div label {
                    font-size: 12px !important;
                }

                .another_div {
                    width: 45% !important;
                    height: 50px !important;
                }

                .top_div h3 {
                    font-size: 25px !important;
                    float: none !important;
                }

                .another_div h3 {
                    font-size: 26px !important;
                    float: none !important;
                }

                .container .ani-btn-box .inner-padding .btn {
                    font-size: 18px !important;
                }

            }

            @media screen and (min-width: 400px) and (max-width: 423px) {

                .top-div .container .element-width {
                    padding: 26px 0px 0px 0px !important;
                    margin-bottom: 10px !important;
                }

                .top-div .container .element-width .top-box-weight .top-heading-title {
                    font-size: 15px !important;
                    line-height: 1.3em;
                }

                .top-div .container .text-info-box .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em;
                }

                .video-box iframe {
                    height: 210px !important;
                }

                .overview {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .feature-list {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .ord {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .address_section {
                    width: 100% !important;
                }

                .order-col {
                    width: 100% !important;
                }

                /*Price Section*/
                .price_section {
                    padding-left: 38px !important;
                }

                .phone img {
                    height: 25px !important;
                    width: 25px !important;
                }


                .shape {
                    right: -13% !important;
                    width: 35px !important;
                    height: 35px !important;
                }

                .top_div {
                    width: 45% !important;
                    height: 50px !important;
                }

                .another_div label {
                    font-size: 12px !important;
                    margin-left: 12px !important;
                }

                .top_div label {
                    font-size: 12px !important;
                    margin-left: 12px !important;
                }

                .another_div {
                    width: 45% !important;
                    height: 50px !important;
                }

                .top_div h3 {
                    font-size: 25px !important;
                    float: none !important;
                }

                .another_div h3 {
                    font-size: 26px !important;
                    float: none !important;
                }

                .container .ani-btn-box .inner-padding .btn {
                    font-size: 18px !important;
                }

            }

            @media screen and (min-width: 375px) and (max-width: 399px) {


                .top-div .container .element-width {
                    padding: 26px 0px 0px 0px !important;
                    margin-bottom: 10px !important;
                }

                .top-div .container .element-width .top-box-weight .top-heading-title {
                    font-size: 15px !important;
                    line-height: 1.3em;
                }

                .top-div .container .text-info-box .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em;
                }

                .video-box iframe {
                    height: 210px !important;
                }

                .overview {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .feature-list {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .ord {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .address_section {
                    width: 100% !important;
                }

                .order-col {
                    width: 100% !important;
                }

                /*Price Section*/

                .price_section {
                    padding-left: 38px !important;
                }

                .phone img {
                    height: 25px !important;
                    width: 25px !important;
                }

                .shape {
                    right: -13% !important;
                    width: 35px !important;
                    height: 35px !important;
                }

                .top_div {
                    width: 45% !important;
                    height: 50px !important;
                }

                .another_div label {
                    font-size: 8px !important;
                    margin-left: 12px !important;
                }

                .top_div label {
                    font-size: 8px !important;
                    margin-left: 12px !important;
                }

                .another_div {
                    width: 45% !important;
                    height: 50px !important;
                }

                .top_div h3 {
                    font-size: 25px !important;
                    float: none !important;
                }

                .another_div h3 {
                    font-size: 26px !important;
                    float: none !important;
                }

                /*Price Section*/

                .container .ani-btn-box .inner-padding .btn {
                    font-size: 18px !important;
                }
            }

            @media screen and (min-width: 320px) and (max-width: 374px) {
                .top-div .container .element-width {
                    padding: 26px 0px 0px 0px !important;
                    margin-bottom: 10px !important;
                }

                .top-div .container .element-width .top-box-weight .top-heading-title {
                    font-size: 15px !important;
                    line-height: 1.3em;
                }

                .top-div .container .text-info-box .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em;
                }

                .overview {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px;
                }

                .slide_top {
                    padding-left: 0px !important;
                }

                .feature-list {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget .top-heading-title {
                    font-size: 17px !important;
                    line-height: 1.3em !important;
                }

                ul {
                    padding-left: 0px !important;
                }

                .feature-list ul li {
                    padding-left: 0px !important;
                }

                .down-div .container .element-widget-wrap {
                    padding: 10px !important;
                }

                .ord {
                    padding-left: 0px !important;
                }

                .ord {
                    padding-left: 0px !important;
                    line-height: 1.3em !important;
                }

                .price_section {
                    padding-left: 25px !important;
                }

                .phone img {
                    height: 25px !important;
                    width: 25px !important;
                }

                .shape {
                    right: -13% !important;
                    width: 35px !important;
                    height: 35px !important;
                }

                .top_div {
                    width: 45% !important;
                    height: 50px !important;
                }

                .another_div label {
                    font-size: 8px !important;
                    margin-left: 5px !important;
                }

                .top_div label {
                    font-size: 8px !important;
                    margin-left: 5px !important;
                }

                .another_div {
                    width: 45% !important;
                    height: 50px !important;
                }

                .top_div h3 {
                    font-size: 25px !important;
                    float: none !important;
                }

                .another_div h3 {
                    font-size: 26px !important;
                    float: none !important;
                }

                .address_section {
                    width: 100% !important;
                }

                .container .ani-btn-box .inner-padding .btn {
                    font-size: 18px !important;
                }

            }




            .video-responsive {
                position: relative;
                padding-bottom: 56.25%;
                /* 16:9 aspect ratio */
                height: 0;
                overflow: hidden;
                max-width: 100%;
                background: #000;
            }

            .video-responsive video {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .whats_btn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #25D366;
                border-radius: 50%;
                height: 60px;
                width: 60px;
                cursor: pointer;
                box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                z-index: 9999;
            }

            .whats_btn:hover {
                transform: scale(1.1);
                box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.4);
            }

            .whats_btn img {
                filter: brightness(0) invert(1);
            }

            .feature-list ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .feature-list ul li {
                padding: 5px 0 5px 40px;
                margin: 8px 0;
                line-height: 1.6;
                font-size: 16px;
                color: #333;
                font-weight: 500;
                position: relative;
                transition: all 0.3s ease;
                border-left: 3px solid transparent;
                padding-left: 35px;
            }

            .feature-list ul li:hover {
                border-left-color: #c2050b;
                padding-left: 40px;
                color: #c2050b;
            }

            .feature-list ul li::before {
                content: "✓";
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 24px;
                height: 24px;
                background: #c2050b;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                font-weight: bold;
                margin-right: 15px;
            }

            .countdown-timer {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 15px;
                padding: 20px;
                margin-bottom: 30px;
                flex-wrap: wrap;
                flex-direction: column;
            }

            .countdown-title {
                font-size: 28px;
                font-weight: 700;
                color: #e91e63;
                margin-bottom: 20px;
                text-transform: uppercase;
                letter-spacing: 2px;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            }

            .countdown-boxes {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 15px;
                flex-wrap: wrap;
            }

            .timer-box {
                background: rgba(255, 255, 255, 0.95);
                border: 3px solid #e91e63;
                border-radius: 15px;
                padding: 20px 30px;
                text-align: center;
                min-width: 120px;
                box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
                transition: transform 0.3s ease;
            }

            .timer-box:hover {
                transform: translateY(-5px);
            }

            .timer-number {
                font-size: 48px;
                font-weight: 700;
                color: #e91e63;
                line-height: 1;
                display: block;
                margin-bottom: 5px;
            }

            .timer-label {
                font-size: 18px;
                font-weight: 600;
                color: #e91e63;
                text-transform: capitalize;
            }

            @media screen and (max-width: 768px) {
                .countdown-title {
                    font-size: 22px;
                    margin-bottom: 15px;
                }

                .timer-box {
                    min-width: 90px;
                    padding: 15px 20px;
                }

                .timer-number {
                    font-size: 36px;
                }

                .timer-label {
                    font-size: 14px;
                }
            }

            @media screen and (max-width: 480px) {
                .countdown-title {
                    font-size: 18px;
                    margin-bottom: 10px;
                }

                .countdown-timer {
                    gap: 10px;
                    padding: 15px;
                }

                .countdown-boxes {
                    gap: 10px;
                }

                .timer-box {
                    min-width: 70px;
                    padding: 12px 15px;
                }

                .timer-number {
                    font-size: 28px;
                }

                .timer-label {
                    font-size: 12px;
                }
            }
        </style>
        <div class="down-div">
            <div class="container">
                <!-- Countdown Timer -->
                <div class="countdown-timer" data-aos="fade-down">
                    <h2 class="countdown-title">অফার শেষ হচ্ছে</h2>
                    <div class="countdown-boxes">
                        <div class="timer-box">
                            <span class="timer-number" id="timer-hours">00</span>
                            <span class="timer-label">ঘন্টা</span>
                        </div>
                        <div class="timer-box">
                            <span class="timer-number" id="timer-minutes">00</span>
                            <span class="timer-label">মিনিট</span>
                        </div>
                        <div class="timer-box">
                            <span class="timer-number" id="timer-seconds">00</span>
                            <span class="timer-label">সেকেন্ড</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="video-box" data-aos="zoom-in">
                        <div class="video-responsive">
                            <video controls autoplay>
                                <source src="{{ asset('backend/img/landing/' . $landing->video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>

                    </div>
                    <div class="ani-btn-box" style="margin-top: 45px;">
                        <div class="inner-padding" data-aos="fade-up">
                            <button id="order_btn" class="btn btn-danger" style="border: 3px solid white;">
                                অর্ডার করতে চাই
                            </button>
                        </div>
                    </div>
                </div>

                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="element-widget overview" style="margin-top: 40px;color: black;">
                        <h2 class="top-heading-title" style="color: white;">
                            {!! $landing->heading_middle !!}
                        </h2>
                    </div>
                </div>

                <div class="element-widget-cover">
                    <div class="element-widget-wrap">
                        <div class="element-widget slide_top">
                            <h2 class="top-heading-title">
                                {{ $landing->gallery_title ?? '' }}
                            </h2>
                        </div>
                        <div class="owl-carousel img-gallery">
                            @if ($landing->slider)
                                @foreach (json_decode($landing->slider) as $slide)
                                    <div class="">
                                        <img src="{{ asset('backend/img/landing/' . $slide) }}" alt="img">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="ani-btn-box">
                            <div class="inner-padding" data-aos="fade-up">
                                <button id="order_btn2" class="btn btn-danger" style="border: 3px solid white;">
                                    অর্ডার করতে চাই
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="element-widget-cover">
                    <div class="element-widget-wrap">
                        <div class="element-widget slide_top">
                            <h2 class="top-heading-title">
                                {{ $landing->testimonial_title ?? 'Testimonials' }}
                            </h2>
                        </div>
                        <div class="owl-carousel testimonial-gallery">
                            @if ($landing->testimonials)
                                @foreach (json_decode($landing->testimonials) as $testimonial)
                                    <div class="">
                                        <img src="{{ asset('backend/img/landing/' . $testimonial) }}"
                                            alt="testimonial">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="ani-btn-box">
                            <div class="inner-padding" data-aos="fade-up">
                                <button id="order_btn3" class="btn btn-danger" style="border: 3px solid white;">
                                    অর্ডার করতে চাই
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="element-widget-cover">
                    <div class="element-widget-wrap">
                        <div class="element-widget feature-list"
                            style="background: #ffffff;text-align: left;padding: 20px 60px;margin-bottom:10px;">
                            <ul>
                                @foreach (explode("\r\n", $landing->bullet) as $bullet_line)
                                    @if (trim($bullet_line))
                                        <li>{!! $bullet_line !!}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <div class="element-widget price_section"
                            style="background: #ffffff;text-align: left;padding-left: 58px;">
                            <div class="text-center row">

                                <div class="col-md-4 top_div offset-md-2 offset-sm-1">

                                    <label style="font-size: 17px;float: left;color: white;margin-left: 25px;">Regular
                                        Price:</label>

                                    <h3
                                        style="float: left;margin-top: 5%;font-weight: 700;font-size: 40px;color: white;">
                                        <del> {{ $landing->product->regular_price }}</del>Tk
                                    </h3>
                                    <div class="shape">

                                    </div>
                                </div>
                                <div class="col-md-4 another_div">
                                    <label style="font-size: 17px;float: left;color: green;margin-left: 25px;">Discount
                                        Price:</label>

                                    <h3
                                        style="float: left;margin-top: 5%;font-weight: 700;font-size: 40px;color: green;">
                                        {{ $landing->product->offer_price }}Tk</h3>

                                </div>
                                <div class="text-center col-md-12 phone">
                                    <h2 class="top-heading-title" style="color: #000000;">
                                        <label>Call Us:</label> <img width="40" class="phone_img" height="40"
                                            src="https://img.icons8.com/ios/50/000000/phone-disconnected.png"
                                            alt="phone-disconnected" />
                                        <a href="tel:{{ $settings->phone }}"
                                            style="text-decoration: none;color: green;">{{ $settings->phone }}</a>
                                    </h2>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div id="element_widget" class="element-widget-cover">
                    <div class="element-widget-wrap">
                        <div class="element-widget ord" style="margin-bottom: 25px;">
                            <h2 class="top-heading-title bg-light-green">
                                অর্ডার করতে নিচের ফর্মটি সঠিক তথ্য দিয়ে পূরণ করুন
                            </h2>
                        </div>
                        <div class="form-wrapper">
                            <form action="{{ route('landing.order') }}" method="POST" id="checkout_land_form">
                                @csrf
                                <div class="row">
                                    <div class="address_section col-md-6" style="width: 50%;float: left;">
                                        <div class="form-address">
                                            <div class="address-col">
                                                <h3>Billing Address</h3>
                                                <div class="billing-fields">
                                                    <div class="form-group">
                                                        <label for="">আপনার নাম<span>*</span></label>
                                                        <input type="text" name="name" class="form-control">
                                                        <input type="hidden" value="{{ $landing->product->id }}"
                                                            name="prd_id" class="form-control" required>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">আপনার ১১ ডিজিট মোবাইল নাম্বর
                                                            <span>*</span></label>
                                                        <input type="text" name="phone" class="form-control"
                                                            required maxlength="11" pattern="[0-9]{11}"
                                                            inputmode="numeric" title="Please enter exactly 11 digits"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)">
                                                    </div>


                                                    <input type="hidden" name="product_id"
                                                        value="{{ $landing->product->id }}">
                                                    <input type="hidden" name="price"
                                                        value="{{ $landing->product->offer_price }}">
                                                    <input type="hidden" name="sub_total"
                                                        value="{{ $landing->product->offer_price }}">
                                                    <input type="hidden" name="quantity" value="1">


                                                    <div class="form-group">
                                                        <label for=""> আপনার সম্পূর্ণ ঠিকানা
                                                            <span>*</span></label>
                                                        <input type="text" name="address" class="form-control"
                                                            required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="exampleInputPassword1" style="float: left;">
                                                            যেকোনো একটি এলাকা নির্বাচন করুন </label>
                                                        <select required name="shipping_method"
                                                            style="min-height: 30px !important;"
                                                            onchange="getCharge()" id="delivery_charge_id"
                                                            class="form-control" style="font-size:12px !important;">

                                                            @foreach ($shippings as $shipping)
                                                                @php
                                                                    $freeshipcheck = DB::table('products')
                                                                        ->where('id', $landing->product->id)
                                                                        ->where('shipping', 1)
                                                                        ->first();

                                                                @endphp


                                                                <option value="{{ $shipping->id }}" id="charge"
                                                                    data-charge="{{ $freeshipcheck ? 0 : $shipping->amount }}">
                                                                    {{ $shipping->type }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <style>
                                        .sizes {
                                            display: flex;
                                        }

                                        .sizes .size {
                                            padding: 3px;
                                            margin: 5px;
                                            border: 1px solid #c2050b;
                                            width: auto;
                                            text-align: center;
                                            cursor: pointer;
                                        }

                                        .sizes .size.active {
                                            background: #c2050b;
                                            color: white;
                                        }

                                        .increase-qty {
                                            width: 32px;
                                            display: block;
                                            float: left;
                                            line-height: 26px;
                                            cursor: pointer;
                                            text-align: center;
                                            font-size: 16px;
                                            font-weight: 300;
                                            color: #000;
                                            height: 32px;
                                            background: #f6f7fb;
                                            border-radius: 50%;
                                            transition: .3s;
                                            border: 2px solid rgba(0, 0, 0, 0);
                                            background: #ffffff;
                                            border: 1px solid #ddd;
                                            border-radius: 10%;
                                        }

                                        .decrease-qty {
                                            width: 32px;
                                            display: block;
                                            float: left;
                                            line-height: 26px;
                                            cursor: pointer;
                                            text-align: center;
                                            font-size: 16px;
                                            font-weight: 300;
                                            color: #000;
                                            height: 32px;
                                            background: #f6f7fb;
                                            border-radius: 50%;
                                            transition: .3s;
                                            border: 2px solid rgba(0, 0, 0, 0);
                                            background: #ffffff;
                                            border: 1px solid #ddd;
                                            border-radius: 10%;
                                        }
                                    </style>

                                    <div class="col-md-6">
                                        <div class="order-col" style="width: 100%;">
                                            <h3>Your Order</h3>
                                            <div id="order_review" class="review-order">
                                                <table class="shop_table review-order-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="product-name">Product</th>
                                                            <th class="product-total">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="cart_item">
                                                            <td class="product-name">
                                                                <div class="product-image">
                                                                    <div class="product-thumbnail"><img width="100%"
                                                                            src="{{ asset('backend/img/products/' . $landing->product->image) ?? '' }}"
                                                                            class="" alt=""> </div>
                                                                    <div class="product-name-td">
                                                                        {{ $landing->product->name }}</div>
                                                                </div>

                                                            </td>
                                                            <td class="product-total">
                                                                <span id="price" class="price-amount amount">


                                                                    {{ $landing->product->offer_price }}

                                                                    <span
                                                                        class="price-currencySymbol">&nbsp;</span></span>


                                                                <input type="hidden" id="price_val"
                                                                    value="{{ $landing->product->offer_price }}">

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span>Select Variation: </span>
                                                            </td>
                                                            <td>
                                                                <div class="container row g-xl-4 g-3">
                                                                    @if ($landing->product->atr_item != null)
                                                                        @foreach (App\Models\ProductAttribute::whereIn('id', explode('"', $landing->product->atr))->get() as $b)
                                                                            <div class="col-12">
                                                                                <label
                                                                                    for="">{{ $b->name }}
                                                                                </label>
                                                                                <input type="hidden"
                                                                                    name="attribute_id[]"
                                                                                    value="{{ $b->id }}">
                                                                                <select
                                                                                    name="attribute[{{ $b->id }}]"
                                                                                    id=""
                                                                                    class="select wide attribute_item_id form-control">
                                                                                    @foreach (App\Models\Atr_item::whereIn('id', explode('"', $landing->product->atr_item))->where('atr_id', $b->id)->get() as $c)
                                                                                        <option
                                                                                            value="{{ $c->id }}">
                                                                                            {{ $c->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif

                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span>Select Quantity: </span>
                                                            </td>
                                                            <td style="width: 45%;">

                                                                <div style="display: flex;"
                                                                    class="pro-qty item-quantity">
                                                                    <span class="decrease-qty quantity-button">-</span>
                                                                    <input type="text"
                                                                        style="width: 25%;text-align: center;"
                                                                        class="inner_qty qty-input quantity-input"
                                                                        value="1" name="quantity" />
                                                                    <span class="increase-qty quantity-button">+</span>
                                                                </div>
                                                                <!--    <div class="sizes" id="sizes">-->
                                                                <!--    <div class="pro-qty item-quantity">-->
                                                                <!--    <span class="dec qtybtn">-</span>-->
                                                                <!--    <input type="number" class="quantity-input" value="1" name="quantity">-->
                                                                <!--    <span class="inc qtybtn">+</span>-->
                                                                <!--</div>-->
                                                                <!--</div>-->
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="cart-subtotal">
                                                            <th>Subtotal</th>
                                                            <td><span class="final-price-amount amount">

                                                                    {{ $landing->product->offer_price }}

                                                                    <span
                                                                        class="price-currencySymbol">&nbsp;</span></span>
                                                            </td>
                                                        </tr>
                                                        <tr class="shipping-totals shipping">
                                                            <th>Shipping</th>
                                                            <td>
                                                                <li style="list-style: none;">
                                                                    <span id="delvry_charge">0</span>
                                                                </li>
                                                            </td>
                                                        </tr>
                                                        <tr class="order-total">
                                                            <th>Total</th>
                                                            <td><strong><span id="total"
                                                                        class="Price-amount amount">

                                                                        {{ $landing->product->offer_price }}

                                                                        <span
                                                                            class="Price-currencySymbol">&nbsp;</span></span></strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <div id="payment" class="checkout-payment">
                                                    <ul class="payment_methods methods">
                                                        <li class="payment_method payment_method_cod">
                                                            <label for="payment_method_cod">
                                                                Cash on delivery </label>
                                                            <div class="payment_box payment_method_cod">
                                                                <p>Pay with cash upon delivery.</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <p style="color: green;">* ১০০% শিউর হয়ে অর্ডার করুন,অহেতুক অর্ডার
                                                        করবেন না ।</p>
                                                    <div class="form-row place-order">
                                                        <button type="submit" class="button" name=""
                                                            id=""> অর্ডার কনফার্ম করুন </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="https://wa.me/{{ str_starts_with($settings->whatsapp_number, '01') ? '88' . $settings->whatsapp_number : $settings->whatsapp_number }}"
            target="_blank" class="whats_btn">
            <span>

                <img width="45" height="45" src="https://img.icons8.com/windows/45/whatsapp--v1.png"
                    alt="whatsapp--v1" />

            </span>
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!--<link rel="stylesheet" href="{{ asset('backend/landing_page/js/carousel.min.js') }}">-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!--<link rel="stylesheet" href="{{ asset('backend/landing_page/js/main.js') }}">-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
</body>

</html>


<script>
    // Countdown Timer Logic
    window.timerState = {
        endTime: null,
        intervalId: null,

        generateRandomEndTime() {
            const randomHours = Math.floor(Math.random() * (24 - 20 + 1)) + 20; // 20-24h range
            return Date.now() + randomHours * 60 * 60 * 1000;
        },

        loadOrCreateTimer() {
            const stored = localStorage.getItem('timerEndTime');
            const storedTime = stored ? parseInt(stored, 10) : null;
            const now = Date.now();

            if (storedTime && storedTime > now) {
                const timeLeft = storedTime - now;
                // Reset if under 6 hours remaining or above 24 hours
                if (timeLeft < 6 * 60 * 60 * 1000 || timeLeft > 24 * 60 * 60 * 1000) {
                    localStorage.removeItem('timerEndTime');
                    this.endTime = this.generateRandomEndTime();
                    localStorage.setItem('timerEndTime', this.endTime.toString());
                } else {
                    this.endTime = storedTime;
                }
            } else {
                localStorage.removeItem('timerEndTime');
                this.endTime = this.generateRandomEndTime();
                localStorage.setItem('timerEndTime', this.endTime.toString());
            }
        },

        updateTimer() {
            const now = Date.now();
            const distance = this.endTime - now;

            if (distance < 0) {
                $('#timer-hours').text('00');
                $('#timer-minutes').text('00');
                $('#timer-seconds').text('00');
                // Reset timer when it expires
                this.endTime = this.generateRandomEndTime();
                localStorage.setItem('timerEndTime', this.endTime.toString());
            } else {
                let hours = Math.floor(distance / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $('#timer-hours').text(String(hours).padStart(2, '0'));
                $('#timer-minutes').text(String(minutes).padStart(2, '0'));
                $('#timer-seconds').text(String(seconds).padStart(2, '0'));
            }
        },

        start() {
            this.loadOrCreateTimer();
            this.updateTimer();
            
            if (this.intervalId) {
                clearInterval(this.intervalId);
            }
            
            this.intervalId = setInterval(() => this.updateTimer(), 1000);
        }
    };

    // Initialize timer
    window.timerState.start();

    $(document).ready(function() {
        getCharge();

        $(".img-gallery").owlCarousel({
            loop: true,
            autoplay: true,
            dots: false,
            margin: 10,
            nav: false,
            responsive: {
                0: {
                    items: 1,
                },
                700: {
                    items: 2,
                },
                1200: {
                    items: 3,
                },
            },
        });

        $(".testimonial-gallery").owlCarousel({
            loop: true,
            autoplay: true,
            dots: false,
            margin: 10,
            nav: false,
            responsive: {
                0: {
                    items: 1,
                },
                700: {
                    items: 2,
                },
                1200: {
                    items: 3,
                },
            },
        });
    });

    function getCharge() {

        let delivery_charge = $('#delivery_charge_id').find("option:selected");
        var crg_id = delivery_charge.val();
        var testval = delivery_charge.data('charge');
        $('span#delvry_charge').text(testval);
        $('span#charge').text(Number(testval).toFixed(2));
        var price = $('span.final-price-amount').text();
        let total = Number(testval) + Number(price);
        $('#total').text(total);
        $('#total_price_val').val(total);
    }
    $("#order_btn").click(function() {
        $('html,body').animate({
                scrollTop: $("#element_widget").offset().top
            },
            'slow');
    });

    $("#order_btn2").click(function() {
        $('html,body').animate({
                scrollTop: $("#element_widget").offset().top
            },
            'slow');
    });

    $("#order_btn3").click(function() {
        $('html,body').animate({
                scrollTop: $("#element_widget").offset().top
            },
            'slow');
    });
    AOS.init({
        duration: 1200,
    });
</script>
<script type="text/javascript">
    $('#sizes .size').on('click', function() {
        $('#sizes .size').removeClass('active');
        $(this).addClass('active');
        let value = $(this).attr('value');
        let variation_price = $(this).data('value');
        let delivery_charge = $('#delivery_charge_id').find("option:selected");

        var testval = delivery_charge.data('charge');
        var total_price = Number(variation_price) + Number(testval);
        $('span#total').text(total_price);
        $('#total_price_val').val(total_price);
        $('#product_price').val(variation_price);
        // $('span#charge').text(Number(testval).toFixed(2));
        // var price = $('span#price').text();
        // let total=Number(testval)+Number(price);
        // $('#total').text(total);


        $('.price-amount').text(variation_price);
        $('span.final-price-amount').text(variation_price);
        $('#price_val').val(variation_price);
        $("#variation_id").val(value);
    });

    $('.increase-qty').on('click', function() {
        var sub_total_price = 0;
        var product_price = $('input#price_val').val();

        var qtyInput = $(this).siblings('.inner_qty');
        var newQuantity = parseInt(qtyInput.val()) + 1;

        $('input#product_quantity').val(newQuantity);
        var delivery_charge = $('span#delvry_charge').text();

        var sub_total_price = Number(product_price) * Number(newQuantity);

        var total_with_delivery = Number(sub_total_price) + Number(delivery_charge);

        // $('span#price').text(sub_total_price);
        $('span.final-price-amount').text(sub_total_price);
        $('span#total').text(total_with_delivery);
        $('#total_price_val').val(total_with_delivery);
        qtyInput.val(newQuantity);
    });



    $('.decrease-qty').on('click', function() {
        var qtyInput = $(this).siblings('.inner_qty');
        var newQuantity = parseInt(qtyInput.val()) - 1;
        if (newQuantity >= 0) {
            qtyInput.val(newQuantity);
            $('#product_quantity').val(newQuantity);
        }
        var product_price = $('input#price_val').val();
        var delivery_charge = $('span#delvry_charge').text();
        var sub_total_price = Number(product_price) * Number(newQuantity);
        var total_with_delivery = Number(sub_total_price) + Number(delivery_charge);
        $('#total_price_val').val(total_with_delivery);
        $('span#total').text(total_with_delivery);
        $('span.final-price-amount').text(sub_total_price);

    });
</script>

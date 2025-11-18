@extends('frontend.layout.template')
@section('pageTitle')
{{$product->name}}
@endsection
@section('body-content')
<div class="product-quick-view-panel-2">
    <div class="panel-content">
        <div class="img">
            <img class="quick-view-image" src="assets/images/index.html" alt="image">
        </div>
    </div>
    <div class="notification">
        <p><span><i class="fa-light fa-triangle-exclamation"></i></span> You are at the end</p>
    </div>
    <div class="panel-arrow">
        <button id="prevImg"><i class="fa-light fa-caret-left"></i></button>
        <button id="nextImg"><i class="fa-light fa-caret-right"></i></button>
    </div>
</div>



<div class="pb-4 shop-details">
    <div class="container">
        <div class="product-view-area">
            <div class="row">
                <div class="col-xl-6 col-lg-5 col-md-6 col-12">
                    <div class="part-img mr-30">

                        <!--         <div class="flash-sale-container">-->

                        <!--    <div class="flash-sale-slider">-->
                        <!--        @if(!is_null($product->gallery_images))-->
                        <!--                @foreach (json_decode($product->gallery_images) as $area)-->
                        <!--        <div class="flash-sale-card">-->
                        <!--            <div class="flash-sale-img">-->
                        <!--                  <img src="{{ asset('backend/img/products/'.$area)  }}" alt="Image" class="w-100">-->



                        <!--            </div>-->

                        <!--        </div>-->
                        <!--        @endforeach-->
                        <!--            @endif-->




                        <!--    </div>-->
                        <!--</div>-->


                        <div class="img-box" id="bigPreview">

                            <img src="{{ asset('backend/img/products/'. $product->image)  }}" alt="Image" class="w-100">
                            <button class="quick-view"><i class="fa-thin fa-arrows-maximize"></i></button>



                        </div>

                        <div class="btn-box">
                            @if(!is_null($product->gallery_images))

                            @foreach (json_decode($product->gallery_images) as $area)

                            <button class="small-thumb {{ $loop->iteration == 1 ? 'active' : '' }}">
                                <img src="{{ asset('backend/img/products/'.$area)  }}" alt="image">
                            </button>
                            @endforeach

                            @endif
                        </div>




                    </div>
                </div>
                <div class="panel col-xl-6 col-lg-7 col-md-6">
                    <div class="part-txt">
                        <h2 class="main-product-title">{{ $product->name  }}</h2>
                        {{-- <div class="review">--}}
                        {{-- <span class="star">--}}
                        {{-- <i class="fa-solid fa-star-sharp rated"></i>--}}
                        {{-- <i class="fa-solid fa-star-sharp rated"></i>--}}
                        {{-- <i class="fa-solid fa-star-sharp rated"></i>--}}
                        {{-- <i class="fa-solid fa-star-sharp rated"></i>--}}
                        {{-- <i class="fa-solid fa-star-sharp"></i>--}}
                        {{-- </span>--}}
                        {{-- <span class="rating-amount">3 Reviews</span>--}}
                        {{-- </div>--}}
                        <h2 class="font-weight-bold single_prod_prices d-none d-md-block" style="font-weight:900">

                            @if(!is_null($product->offer_price))
                            <span class="old_price" style="text-decoration: line-through; color: #555; opacity: 0.5;">{{$settings->currency ?? "৳"}} {{$product->regular_price+0}}</span>


                            <span style="color: #{{ $settings->website_color }}">{{$settings->currency ?? "৳"}} {{$product->offer_price+0}}</span>


                            @else
                            <span style="color: #{{ $settings->website_color }}">{{$settings->currency ?? "৳"}} {{$product->regular_price+0}}</span>

                            @endif

                        </h2>

                        <div class="price-qty-wrapper">
                            <h2 class="font-weight-bold d-md-none single_prod_prices" style="font-weight:900">
                                @if(!is_null($product->offer_price))
                                <span class="old_price" style="text-decoration: line-through; color: #555; opacity: 0.5;">
                                    {{$settings->currency ?? "৳"}} {{$product->regular_price+0}}
                                </span>
                                <span style="color: #{{ $settings->website_color }}">
                                    {{$settings->currency ?? "৳"}} {{$product->offer_price+0}}
                                </span>
                                @else
                                <span style="color: #{{ $settings->website_color }}">
                                    {{$settings->currency ?? "৳"}} {{$product->regular_price+0}}
                                </span>
                                @endif
                            </h2>

                            <!--  -->
                            {{-- ✅ Show Model as colorful badge --}}

                            {{-- ✅ Show Model as colorful box --}}
                            @if(!empty($product->model))
                            <div class="product-model-box">
                                <span class="box-label">Model</span>
                                <span class="box-value">{{ $product->model }}</span>

                            </div>
                            @endif

                            {{-- Quantity selector (mobile only) --}}
                            <div class="prd-dtls-qty-mobile d-md-none">
                                <div class="mx-2 qty_div_1">
                                    <div class="q-down"><i class="py-1 fa fa-minus"></i></div>
                                    <div class="qty-div">
                                        <input type="number" name="quantity_mobile" min="1" value="1">
                                    </div>
                                    <div class="q-up"><i class="py-1 fa fa-plus"></i></div>
                                </div>
                            </div>
                        </div>

                        {{-- <ul class="short-details"> --}}
                            {{-- <li>Product Code: <span>#{{ $product->sku ?? ''  }}</span></li>--}}
                            {{-- <li>Total Sold: <strong> {{ $total_sold }}</strong></li>--}}
                        {{-- </ul> --}}

                        <form action="{{route('o_cart.store',$product->id)}}" method="POST">
                            @csrf
                            <div class="row" style="padding: 0px 14px 0px 8px;">
                                @if($product->atr_item !=NULL)
                                @foreach(App\Models\ProductAttribute::whereIn('id',explode('"',$product->atr))->get() as $b)
                                <div class="col-lg-3 col-md-4 col-sm-4 col-4">
                                    <label for="">{{$b->name}} </label>
                                    <input type="hidden" name="attribute_id[]" value="{{$b->id}}">
                                    <select name="attribute[{{$b->id}}]" id="" class="select wide attribute_item_id">
                                        <option>Select</option>
                                        @foreach(App\Models\Atr_item::whereIn('id',explode('"',$product->atr_item))->where('atr_id',$b->id)->get() as $c)
                                        <option value="{{$c->id}}">{{$c->name}}</option>

                                        @endforeach
                                    </select>
                                </div>
                                @endforeach
                                @endif
                                <div class="col-lg-3 col-md-3 col-sm-4 col-12 d-none d-md-block">
                                    <label for="">Quantity </label>
                                    <div class="" id="prd-dtls-qty">

                                        <div class="mx-2 qty_div_1">
                                            <div class="q-down">
                                                <i class="fa fa-minus"></i>
                                            </div>
                                            <div class="qty-div">
                                                <input type="number" name="quantity" id="qtyyy" class="qtyyy" min="1" value="1">
                                            </div>
                                            <div class="q-up">
                                                <i class="fa fa-plus"></i>
                                            </div>
                                        </div>







                                    </div>
                                </div>
                            </div>



                            <input type="hidden" name="model" value="{{$product->model}}">
                            <input type="hidden" name="product_id" value="{{$product->id}}">
                            <input type="hidden" name="slug" value="{{$product->slug}}">
                            <input type="hidden" name="product_image" value="{{ asset('backend/img/products/'.$product->image) ?? ''  }}">
                            <input type="hidden" name="product_name" value="{{$product->name}}">
                            <input type="hidden" name="price" value="@if(is_null($product->offer_price)){{$product->regular_price}}@else {{$product->offer_price}} @endif">


                            <div class="gap-2 mt-0 btn-box mt-md-3 d-flex">
                                <button style="background: #{{ $settings->website_color }}"><input type="submit" class="text-white order_now_btn order_now_btn_m btn-bangla" name="order_now" value="অর্ডার করুন" /></button>
                                <button style="background: #37A1D1"><input type="submit" class="px-4 text-white add_cart_btn btn-bangla" name="add_cart" value="কার্টে রাখুন" /></button>

                            </div>

                            {{-- Desktop contact options (visible on md and above) --}}
                            <div class="btn-box mt-md-3 d-none d-md-flex align-items-center">
                                {{-- Phone 1 – Call --}}
                                @if($settings->phone)
                                <a href="tel:{{ $settings->phone }}" class="me-3">
                                    <i class="fa fa-phone" style="font-size:18px; margin-right:6px;"></i>
                                    {{ $settings->phone }}
                                </a>
                                @endif

                                {{-- Phone 2 – WhatsApp --}}
                                @if($settings->phone_two)
                                @php
                                // Prepare numeric WhatsApp number with country code
                                $phoneTwoNumber = preg_replace('/\D/', '', $settings->whatsapp_number); // remove non-digit characters
                                $phoneTwoNumber = '+88' .$phoneTwoNumber;
                                @endphp
                                <a href="https://wa.me/{{ $phoneTwoNumber }}"
                                    target="_blank"
                                    class="me-3">
                                    <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png"
                                        alt="WhatsApp"
                                        style="width:22px;height:22px;margin-right:6px;">
                                    {{ $settings->whatsapp_number }}
                                </a>
                                @endif


                                {{-- Phone 3 – IMO --}}
                                <!-- @if($settings->phone_three)
                                    <a href="imo://chat/{{ $settings->phone_three }}">
                                        <img src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/imo-icon.svg" alt="IMO" style="width:22px;height:22px;margin-right:6px;">
                                        {{ $settings->phone_three }}
                                    </a>
                                @endif -->
                            </div>

                            <!-- Payment Info Section -->
                            <div class="p-1 mt-3 text-center rounded border payment-info">
                                @if($product->shipping == 1)
                                <p class="mb-0 text-success" style="font-weight:600;">
                                    <i class="fa fa-check-circle me-2"></i> ফ্রি শিপিং এ অর্ডার করুন
                                </p>
                                @endif
                                <p class="mb-0 text-success" style="font-weight:600;">
                                    <i class="fa fa-check-circle me-2"></i> পোডাক্ট হাতে পেয়ে দেখে নিতে পারবেন
                                </p>
                            </div>


                            {{-- Mobile sticky bottom bar --}}
                            <div class="mobile-bottom-bar d-md-none">
                                <!-- <div class="mobile-product-info">
                                    <h2 class="mobile-product-name">{{ $product->name }}</h2>
                                    <h3 class="mobile-product-price">
                                        @if(!is_null($product->offer_price))
                                            <span class="old-price">{{ $settings->currency ?? "৳" }} {{ $product->regular_price+0 }}</span>
                                            <span class="offer-price" style="color:#{{ $settings->website_color }}">{{ $settings->currency ?? "৳" }} {{ $product->offer_price+0 }}</span>
                                        @else
                                            <span class="offer-price" style="color:#{{ $settings->website_color }}">{{ $settings->currency ?? "৳" }} {{ $product->regular_price+0 }}</span>
                                        @endif
                                    </h3>
                                </div> -->

                                <!-- contact icon for mobile device  -->
                                <div class="mobile-contact-bar">
                                    @if($settings->phone)
                                    <a href="tel:{{ $settings->phone }}">
                                        <i class="fa fa-phone"></i>
                                        <span>{{ $settings->phone }}</span>
                                    </a>
                                    @endif

                                    @if($settings->phone_two)
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->phone_two) }}" target="_blank">
                                        <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp">
                                        <span>{{ $settings->phone }}</span>
                                    </a>
                                    @endif

                                    <!-- @if($settings->phone_three)
                                        <a href="imo://chat/{{ $settings->phone_three }}">
                                            <img src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/imo-icon.svg" alt="IMO">
                                            <span>{{ $settings->phone_three }}</span>
                                        </a>
                                    @endif -->
                                </div>
                            </div>

                        </form>

                        {{-- <div class="btn-box">--}}
                        {{-- <button onclick="buyNow({{ $product->id }})"><span><i class="fa-light fa-cart-shopping"></i></span> অর্ডার করুন</button>--}}
                        {{-- <a href="tel:{{ Settings::get('phone_number') }}" ><i class="fa fa-phone" aria-hidden="true"></i> {{$settings->phone }}</a>--}}
                        {{-- </div>--}}








                        <div class="mt-1 col-lg-12 delivery__details__info">
                            <div class="p-2 delivery-info-wrapper">
                                <div class="dif">
                                    <p class="font-weight-bold">Delivery Option</p>

                                    <p><i class="fa fa-location-arrow"></i>
                                        Cash On Delivery Available
                                    </p>
                                    <p><i class="fa fa-home"></i>ঢাকায় ডেলিভারি খরচ ৳ {{ $shipping_charge[1]->amount }} </p>
                                    <p><i class="fa fa-shopping-bag"></i>
                                        ঢাকার বাইরের ডেলিভারি খরচ ৳ {{ $shipping_charge[0]->amount }} </p>
                                </div>
                                <hr>
                                <div class="rwif">
                                    <p class="font-weight-bold">Our values</p>
                                    <p><i class="fa-solid fa-badge-check"></i> 100% authentic</p>
                                    <p><i class="fa fa-undo"></i> instant return</p>
                                </div>
                            </div>
                        </div>


                        <br />
                        <div class="product-share">
                            <span>Share Link:</span>
                            <div class="social">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-google-plus-g"></i></a>
                                <a href="#"><i class="fa-solid fa-rss"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!is_null($product->video))
        <div class="panel details-area">
            <nav>
                <div class="nav" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-dscr-tab" data-bs-toggle="tab" data-bs-target="#nav-dscr" type="button" role="tab" aria-controls="nav-dscr" aria-selected="true">Video</button>

                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-dscr" role="tabpanel" aria-labelledby="nav-dscr-tab" tabindex="0">
                    <div class="product-dscr">
                        @if(!is_null($product->video))
                        <video width="350" controls>
                            <source src="{{ asset('backend/img/products/video/'.$product->video)  }}" type="video/mp4">
                        </video>
                        @endif

                    </div>
                </div>

            </div>
        </div>
        </br>
        @endif
        <div class="panel details-area">
            <nav>
                <div class="nav" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-dscr-tab" data-bs-toggle="tab" data-bs-target="#nav-dscr" type="button" role="tab" aria-controls="nav-dscr" aria-selected="true">Description</button>

                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-dscr" role="tabpanel" aria-labelledby="nav-dscr-tab" tabindex="0">
                    <div class="product-dscr" style="height: auto;">
                        <div style="padding-top: 20px;padding-bottom: 20px;">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="py-2 products">
    <div class="container">
        <div class="panel panel-shadow">
            <div class="panel-header">
                <div class="row gy-3">
                    <div class="col-lg-4 col-sm-3">
                        <h2 class="title">Related Products</h2>
                    </div>
                    <div class="col-lg-8 col-sm-9">

                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-sweatshirts" role="tabpanel" aria-labelledby="nav-sweatshirts-tab" tabindex="0">
                        <div class="row">
                            @foreach($relatedProducts as $product)
                            <div class="col-md-2 col-6">
                                <div class="mb-3 single-product-card">
                                    <div class="part-img">
                                        @if(!is_null($product->offer_price))
                                        <span class="off-tag" style="background-color: #{{ $settings->website_color }}"> {{ round((100 - (($product->offer_price / $product->regular_price) * 100))) }} %</span>
                                        @endif
                                        <a href="{{route('details',$product->slug)}}"><img src="{{ asset('backend/img/products/'.$product->image)  }}" alt="Product"></a>

                                    </div>
                                    <div class="part-txt">
                                        <h4 class="product-name">
                                            <a href="{{route('details',$product->slug)}}">
                                                {{ \Illuminate\Support\Str::limit($product->name, 40) }}
                                            </a>
                                        </h4>


                                        @if(!is_null($product->offer_price))
                                        <span class="price" style="color: #{{ $settings->website_color }}">
                                            {{$settings->currency ?? "৳"}} {{$product->offer_price }}
                                            <span> {{$product->regular_price }} </span>
                                        </span>
                                        @else


                                        <span class="price" style="color: #{{ $settings->website_color }}">
                                            {{$settings->currency ?? "৳"}} {{$product->regular_price }}
                                            <span> {{$product->offer_price }} </span>
                                        </span>

                                        @endif



                                        <form action="{{route('o_cart.store',$product->id)}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{$product->id}}">
                                            <input type="hidden" name="slug" value="{{$product->slug}}">
                                            <input type="hidden" name="product_image" value="{{ asset('backend/img/products/'.$product->image) ?? ''  }}">
                                            <input type="hidden" name="product_name" value="{{$product->name}}">
                                            <input type="hidden" name="price" value="@if(is_null($product->offer_price)){{$product->regular_price}}@else {{$product->offer_price}} @endif">
                                            <input type="hidden" name="quantity" value="1">

                                            <button type="submit" class="btn btn-sm order-btn order_now_btn"><i class="fa-solid fa-cart-shopping"> </i> অর্ডার করুন</button>
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
    </div>
</div>

<style>
    /* Mobile sticky bottom bar */
    .mobile-bottom-bar {
        /* position: fixed; */
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
        background: #fff;
        box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
    }

    /* Product info above the call bar */
    .mobile-product-info {
        padding: 8px 10px;
        border-bottom: 1px solid #e0e0e0;
        text-align: center;
    }

    .mobile-product-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .mobile-product-price {
        font-size: 14px;
        font-weight: 700;
    }

    .mobile-product-price .old-price {
        text-decoration: line-through;
        color: #555;
        opacity: 0.6;
        margin-right: 6px;
    }

    /* Contact icons row */
    .mobile-contact-bar {
        display: flex;
        justify-content: space-around;
        padding: 6px 0;
    }

    .mobile-contact-bar a {
        flex: 1;
        text-align: center;
        font-size: 12px;
        color: #333;
        text-decoration: none;
        border-right: 1px solid #e0e0e0;
        padding: 0;
    }

    .mobile-contact-bar a:last-child {
        border-right: none;
    }

    .mobile-contact-bar a i,
    .mobile-contact-bar a img {
        width: 22px;
        height: 22px;
        margin-bottom: 2px;
    }

    .mobile-contact-bar a span {
        display: block;
        font-size: 12px;
        font-weight: 600;
    }

    /* Hide sticky on tablet/desktop */
    /* @media (min-width: 768px) {
    .mobile-bottom-bar {
        display: none !important;
    }
} */

    /* product image */
    /* Mobile view only */
    @media (max-width: 767px) {

        /* Shrink the product image container */
        .img-box {
            /* height: 200px;  */
            overflow: hidden;
        }

        .img-box img {
            height: 100%;
            width: auto;
            object-fit: cover;
        }

        /* Gallery thumbnails */
        .btn-box {
            display: flex;
            gap: 5px;
            overflow-x: auto;
        }

        .btn-box button img {
            height: 30px;
            object-fit: cover;
        }
    }

    /* btn box of product image*/
    @media screen and (max-width: 479px) and (min-width: 320px) {
        .product-view-area .part-img {
            margin: 5px;
        }
    }

    @media screen and (max-width: 479px) and (min-width: 320px) {
        .product-view-area .part-img .img-box {
            margin-bottom: 5px;
        }
    }

    /* search box */
    /* @media (max-width: 767px) {
    .search-col {
        display: none !important;
    }
} */
    /* price  */
    @media screen and (max-width: 479px) and (min-width: 320px) {
        .product-view-area .part-txt .main-product-title {
            font-size: 14px;
            margin-top: -7px;
            margin-bottom: 1px;
        }
    }

    @media screen and (max-width: 479px) and (min-width: 320px) {
        .product-view-area .part-txt .btn-box {
            display: block;
            margin-bottom: -18px;
        }
    }

    @media screen and (max-width: 479px) and (min-width: 320px) {
        .product-view-area .part-txt .btn-box button {
            height: 36px;
        }
    }

    /* Responsive payment info */
    .payment-info {
        background: #f8f9fa;
        /* light background */
    }

    @media (max-width: 767px) {
        .payment-info p {
            font-size: 14px;
            /* smaller text for mobile */
            text-align: center;
            /* center on mobile */
        }
    }


    /* Price + Quantity side by side on mobile */
    @media (max-width: 767px) {
        .price-qty-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .price-qty-wrapper h2 {
            font-size: 14px;
            margin: 0;
        }

        .price-qty-wrapper .prd-dtls-qty {
            flex-shrink: 0;
        }

        .qty_div_1 {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        .qty_div_1 .q-down,
        .qty_div_1 .q-up {
            padding: 0px 10px;
            cursor: pointer;
            background: #f8f9fa;
        }

        .qty_div_1 input {
            width: 40px;
            text-align: center;
            border: none;
        }
    }

    /* model design */
    .product-model-box {
        display: inline-flex;
        align-items: center;
        background: #f9f9f9;
        border: 1px solid #2ba0d6;
        /* theme color */
        border-radius: 4px;
        padding: 4px 8px;
        /* smaller padding */
        font-size: 13px;
        /* smaller text */
        font-weight: 500;
    }

    .product-model-box .box-label {
        font-weight: 600;
        margin-right: 6px;
        background: #2ba0d6;
        color: #fff;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 12px;
    }

    .product-model-box .box-value {
        color: #111;
        font-weight: 600;
        font-size: 13px;
    }

    /* select option of color and size */
    .product-view-area .part-txt form .select {
        height: 34px;
        padding: 0 32px 0 12px;
        /* adjust for arrow space */
        border-radius: 0;
        border: 1.9px solid black;
        font-size: 14px;
        line-height: 36px;
        /* text vertical align */
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #fff url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'><path fill='%23333' d='M7 10l5 5 5-5z'/></svg>") no-repeat right 10px center;
        background-size: 12px;
    }

    /* *************** */
    /* @media (max-width: 767px) {
  .img-box {
    height: 250px;
    overflow: hidden;
  }

  .img-box img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
} */

    @media (max-width: 767px) {
        .img-box {
            /* height: 250px; */
            overflow: hidden;
            text-align: center;
            /* centers image if narrower */
        }

        .img-box img {
            min-width: 90%;
            /* at least 90% width */
            max-width: 100%;
            /* but never exceed container */
            height: 100%;
            object-fit: contain;
            /* keep aspect ratio, no cropping */
            display: inline-block;
            /* needed for centering */
        }

    }

    .product-view-area .part-txt form label {
        margin-bottom: 6px;
        padding-left: 4px;
    }
</style>


@endsection

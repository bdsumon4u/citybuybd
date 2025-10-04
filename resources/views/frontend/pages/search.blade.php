@extends('frontend.layout.template')
@section('pageTitle', 'Searched Product')
     @section('body-content')
     <section>
        <div class="main-products-section">
            <div class="container-fluid container-97">

                <div class="flash-deal">
                    <div class="container">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-2 col-md-2 col-6">
                                        <h2 class="title">Searched For <b>"{{$search}}"</b></h2>
                                    </div>
                                    <div class="col-lg-8 col-md-8 countdown-col">
                                        {{--                            <div class="countdown-wrap d-flex">--}}
                                        {{--                                <h3>Ending Soon...</h3>--}}
                                        {{--                                <div id="flashDealCountdown" class="countdown" data-countdown=""></div>--}}
                                        {{--                            </div>--}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-6">
                                        <div class="text-end">
                                            <a href="{{url('hot_deals')}}" class="explore-section">View more</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    @foreach($products as $product)
                                        <div class="col-md-2 col-6">
                                            <div class="single-product-card mb-3">
                                                <div class="part-img">
                                                    @if(!is_null($product->offer_price))
                                                        <span class="off-tag"> {{ round((100 - (($product->offer_price / $product->regular_price) * 100))) }} %</span>
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
                                    <span class="old_price" style="text-decoration: line-through; color: #555; opacity: 0.5;">{{$settings->currency ?? "৳"}} {{$product->regular_price+0}}</span>


                                    <span style="color: #{{ $settings->website_color }}">{{$settings->currency ?? "৳"}} {{$product->offer_price+0}}</span>


                                @else
                                    <span style="color: #{{ $settings->website_color }}">{{$settings->currency ?? "৳"}} {{$product->regular_price+0}}</span>

                                @endif

                                                    <form action="{{route('o_cart.store',$product->id)}}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{$product->id}}" >
                                    <input type="hidden" name="slug" value="{{$product->slug}}" >
                                    <input type="hidden" name="product_image" value="{{ asset('backend/img/products/'.$product->image) ?? ''  }}" >
                                    <input type="hidden" name="product_name" value="{{$product->name}}" >
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

                <div class="row mt-md-4 mt-2">
                    <div class="col-12">
                        <nav>
                            {{ $products->links() }}

                         </nav>

                    </div>
                </div>
            </div>
        </div>
    </section>

     @endsection

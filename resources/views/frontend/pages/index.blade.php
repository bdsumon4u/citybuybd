@extends('frontend.layout.template')
@section('pageTitle', 'Homepage')
@section('body-content')

    <!--------------------------------- BANNER SECTION START --------------------------------->
    <div class="banner banner-2">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-2 category-col">

                </div>
                <div class="col-xl-9 col-lg-10">
                    <div class="slider-area">
                        @foreach($sliders as  $slider)
                            <div class="Slide slide-{{ $loop->iteration }}" style="background: url({{ asset('backend/img/sliders/'.$slider->name) }}) center center no-repeat;background-size: contain;">
                            <div class="banner-txt">
                                <h6  style="    color: #ff000000;">.</h6>
                                <h1  style="    color: #ff000000;">.</h1>
                                <p  style="    color: #ff000000;">.</p>
                                <div class="price" style="    color: #ff000000;">.</div>
                                <a href="{{url('hot_deals')}}" class="def-btn" tabindex="-1">Order Now</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                
                
                <!--<div class="col-xl-3 col-lg-4">-->
                <!--    <div class="sidebar-area">-->
                <!--        <div class="best-selling-panel">-->
                <!--            <div class="panel-header">-->
                <!--                <h4>Best Selling</h4>-->
                <!--            </div>-->
                <!--            <div class="panel-body">-->
                <!--                <div class="product-slider-1">-->

                <!--                    @foreach($best_selling as $item)-->
                <!--                        @php $product = App\Models\Product::find($item->id); @endphp-->

                <!--                        <div class="single-product-slider">-->
                <!--                            <div class="part-img">-->
                <!--                                <a href="{{route('details',$product->slug)}}"><img src="{{ asset('backend/img/products/'.$product->image)  }}" alt="Product"></a>-->
                <!--                            </div>-->
                <!--                            <div class="part-txt">-->
                <!--                                    <h4 class="product-name"><a href="{{route('details',$product->slug)}}">{{$product->name}}</a></h4>-->

                                                
                <!--                                @if(!is_null($product->offer_price))-->
                <!--                        <p class="price">-->
                <!--                            {{$settings->currency ?? "৳"}} {{$product->offer_price }} </p>-->
                <!--                    @else-->
                <!--                        <p class="price">{{$settings->currency ?? "৳"}} {{$product->regular_price }}</span>-->
                <!--                    @endif-->
                                                
                                                
                                        
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    @endforeach-->



                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                
                
                
                
            </div>
        </div>
    </div>
    <!--------------------------------- BANNER SECTION END --------------------------------->




    
    
    <div class="popular-categories">
        <div class="container">
            <div class="panel">
                <div class="panel-header">
                    <div class="row align-items-center g-lg-4 g-1">
                        <div class="col-lg-6 col-9">
                            <h2 class="title">TOP CATEGORY'S</h2>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
              
                        <div class="slider slider-nav">
                        @foreach($categories as $category)
               
                                <div class="category-card" style="width:150px !important; ">
                                    <div class="part-img">
                                        <a href="{{route('category', $category->id)}}" style="min-height: 80px;">
                                            <img src="{{ asset('backend/img/category/'.$category->image)  }}" alt="Image" >
                                        </a>
                                    </div>

                                    <div class="part-txt">
                                        <h3 style="font-size: 13px;
    font-weight: 400;">
                                            <a href="{{route('category', $category->id)}}">{{$category->title}}</a>
                                        </h3>
                                    </div>
                                </div>
                  
                        @endforeach
                        </div>
                  
                </div>
            </div>
        </div>
    </div>
    

    <!--------------------------------- FLASH DEAL SECTION START --------------------------------->
    <div class="flash-deal">
        <div class="container">
            <div class="panel" style="background: #ffa7003b !important;">
                <div class="panel-header">
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-2 col-6">
                            <h2 class="title">HOT DEAL!</h2>
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
                <div class="panel-body" >
                  
                        <div class="slider slider-hot">
                        @foreach($hots as $product)
                        <div class="col-md-2 col-6">
                            
                            <div class="single-product-card ">
                                <div class="part-img" style="min-height:100px">
                                    @if(!is_null($product->offer_price))
                                    <span class="off-tag" style="background-color: #{{ $settings->website_color }}"> {{ round((100 - (($product->offer_price / $product->regular_price) * 100))) }} %</span>
                                    @endif
                                    <a href="{{route('details',$product->slug)}}">
                                        <img src="{{ asset('backend/img/products/'.$product->image)  }}" alt="Product" style="min-height: 100px;height: 100px;object-fit: fill;">
                                        </a>
                                </div>
                                
                                <div class="part-txt" style="margin-top: 0px;">
                                   
                                    @if(!is_null($product->offer_price))
                                        <span class="price" style="color: #{{ $settings->website_color }}">
                                            {{$settings->currency ?? "৳"}} {{$product->offer_price }} </br>
                                            <span> {{$settings->currency ?? "৳"}} {{$product->regular_price }} </span>
                                            </span>
                                    @else
                                        <span class="price" style="color: #{{ $settings->website_color }}"><span>{{$settings->currency ?? "৳"}} {{$product->regular_price }}</span>
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
    <!--------------------------------- FLASH DEAL SECTION END --------------------------------->

    <!--------------------------------- Category Products SECTION START --------------------------------->
    @foreach($category_products as $category)
    <div class="flash-deal">
        <div class="container">
            <div class="panel">
                <div class="panel-header">
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-2 col-6">
                            <h2 class="title">{{ $category->title }}</h2>
                        </div>
                        <div class="col-lg-8 col-md-8 countdown-col">
                            {{--                            <div class="countdown-wrap d-flex">--}}
                            {{--                                <h3>Ending Soon...</h3>--}}
                            {{--                                <div id="flashDealCountdown" class="countdown" data-countdown=""></div>--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-6">
                            <div class="text-end">
                                <a href="{{route('category', $category->id)}}" class="explore-section">View more</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        @foreach($category->products as $product)
                            <div class="col-md-2 col-6">
                                <div class="single-product-card mb-3">
                                    <div class="part-img">
                                        @if(!is_null($product->offer_price))
                                            <span class="off-tag"  style="background-color: #{{ $settings->website_color }}"> {{ round((100 - (($product->offer_price / $product->regular_price) * 100))) }} %</span>
                                        @endif
                                        <a href="{{route('details',$product->slug)}}"><img src="{{ asset('backend/img/products/'.$product->image)  }}" alt="Product"></a>

                                    </div>
                                    <div class="part-txt">
                                        <h4 class="product-name">
                                            <a href="{{route('details',$product->slug)}}">
                                                {{ \Illuminate\Support\Str::limit($product->name, 20) }}
                                            </a>
                                        </h4>
                                        @if(!is_null($product->offer_price))
                                            <span class="price" style="color: #{{ $settings->website_color }}">
                                                {{$settings->currency ?? "৳"}} {{$product->offer_price }}
                                            <span>  {{$product->regular_price }} </span>
                                            </span>
                                        @else
                                            
                                            
                                            <span class="price" style="color: #{{ $settings->website_color }}">
                                                {{$settings->currency ?? "৳"}} {{$product->regular_price }}  
                                            <span> {{$product->offer_price }} </span>
                                            </span>
                                            
                                            
                                            
                                            
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
                            @if ($loop->iteration == 12)
                                @break
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <!--------------------------------- Category Products SECTION END --------------------------------->

    <!--------------------------------- FLASH DEAL SECTION START --------------------------------->
    <div class="flash-deal">
        <div class="container">
            <div class="panel">
                <div class="panel-header">
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-2 col-6">
                            <h2 class="title">All Products</h2>
                        </div>
                        <div class="col-lg-8 col-md-8 countdown-col">
                            {{--                            <div class="countdown-wrap d-flex">--}}
                            {{--                                <h3>Ending Soon...</h3>--}}
                            {{--                                <div id="flashDealCountdown" class="countdown" data-countdown=""></div>--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-6">
                            <div class="text-end">
                                <a href="{{url('all-Products')}}" class="explore-section">View more</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--------------------------------- FLASH DEAL SECTION END --------------------------------->

    @endsection

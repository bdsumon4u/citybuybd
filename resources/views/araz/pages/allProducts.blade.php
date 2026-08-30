@extends('araz.layouts.app')

@section('title', 'সকল পণ্য - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container-fluid px-lg-4 py-4">

<div class="container-fluid px-1 px-sm-2 px-md-3 px-lg-4 py-3">

    <!-- Page Header Banner -->
    <div class="p-3 p-md-4 mb-4 rounded-3 text-center bg-white shadow-sm" style="border: 1px solid #e5e7eb;">
        <h2 class="fw-bold text-dark mb-1" style="font-size: 22px;">
            <i class="fa-solid fa-boxes-stacked text-success me-2"></i> আমাদের সকল প্রোডাক্ট
        </h2>
        <p class="text-muted mb-0" style="font-size: 13px;">সেরা কোয়ালিটি এবং সুলভ মূল্যে আপনার পছন্দের পণ্যটি বেছে নিন</p>
    </div>

    <!-- Product Grid -->
    @if(isset($products) && $products->count() > 0)
        <div class="row g-1 g-sm-2 row-cols-2 row-cols-md-3 row-cols-lg-4 product-grid-tight">
            @foreach($products as $product)
                <div class="col mb-1 mb-sm-2">
                    @include('araz.partials.product_card', ['product' => $product])
                </div>
            @endforeach
        </div>

        @if(method_exists($products, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div class="bg-white p-5 rounded-3 text-center shadow-sm" style="border: 1px solid #e2e8f0;">
            <h5 class="fw-bold text-dark">কোনো পণ্য পাওয়া যায়নি</h5>
            <a href="{{ url('/') }}" class="btn text-white mt-3 px-4 py-2" style="background: var(--custom-primary-color); border-radius: 20px;">
                হোম পেইজে যান
            </a>
        </div>
    @endif

</div>
@endsection

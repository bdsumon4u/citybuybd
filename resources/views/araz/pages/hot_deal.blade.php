@extends('araz.layouts.app')

@section('title', 'হট ডিল অফার - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container-fluid px-1 px-sm-2 px-md-3 px-lg-4 py-3">

    <!-- Page Header Banner -->
    <div class="p-3 p-md-4 mb-4 rounded-3 text-center" style="background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); border: 2px dashed #fda4af;">
        <h2 class="fw-bold text-danger mb-1" style="font-size: 22px;">
            <i class="fa-solid fa-fire me-2"></i> হট ডিল ও স্পেশাল অফার
        </h2>
        <p class="text-muted mb-0" style="font-size: 13px;">সেরা মূল্যে আকর্ষণীয় ডিসকাউন্টে প্রোডাক্ট কিনুন</p>
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

        <!-- Pagination -->
        @if(method_exists($products, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div class="bg-white p-5 rounded-3 text-center shadow-sm" style="border: 1px solid #e2e8f0;">
            <h5 class="fw-bold text-dark">বর্তমানে কোনো হট ডিল অফার নেই</h5>
            <a href="{{ url('/') }}" class="btn text-white mt-3 px-4 py-2" style="background: var(--custom-primary-color); border-radius: 20px;">
                সকল পণ্য দেখুন
            </a>
        </div>
    @endif

</div>
@endsection

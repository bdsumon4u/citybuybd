@extends('araz.layouts.app')

@section('title', 'অনুসন্ধান: ' . ($search ?? '') . ' - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container-fluid px-1 px-sm-2 px-md-3 px-lg-4 py-3">

    <!-- Search Results Header -->
    <div class="bg-white p-3 rounded-3 shadow-sm mb-4 d-flex justify-content-between align-items-center" style="border: 1px solid #e2e8f0;">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1" style="font-size: 20px;">
                "{{ $search ?? '' }}" এর সার্চ ফলাফল
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 13px;">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">হোম</a></li>
                    <li class="breadcrumb-item active text-success" aria-current="page">সার্চ</li>
                </ol>
            </nav>
        </div>

        <div>
            <span class="badge bg-light text-dark border px-3 py-2" style="font-size: 13px;">
                মোট পাওয়া গেছে: {{ $products->total() ?? $products->count() }} টি
            </span>
        </div>
    </div>

    <!-- Products Grid -->
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
                {{ $products->appends(['search' => $search])->links() }}
            </div>
        @endif
    @else
        <div class="bg-white p-5 rounded-3 text-center shadow-sm" style="border: 1px solid #e2e8f0;">
            <div class="text-muted mb-3" style="font-size: 48px;">
                <i class="fas fa-search-minus"></i>
            </div>
            <h5 class="fw-bold text-dark">দুঃখিত, আপনার অনুসন্ধানের সাথে মিলে এমন কোনো পণ্য পাওয়া যায়নি</h5>
            <p class="text-muted">বানান ঠিক আছে কিনা পরীক্ষা করুন অথবা অন্য কী-ওয়ার্ড দিয়ে সার্চ করুন।</p>
            <a href="{{ url('/') }}" class="btn text-white mt-2 px-4 py-2" style="background: var(--custom-primary-color); border-radius: 20px;">
                সকল পণ্য দেখুন
            </a>
        </div>
    @endif

</div>
@endsection

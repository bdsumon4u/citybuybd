@extends('araz.layouts.app')

@section('title', ($category->title ?? 'সাবক্যাটাগরি') . ' - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container-fluid px-1 px-sm-2 px-md-3 px-lg-4 py-3">

    <!-- Breadcrumb & Header -->
    <div class="bg-white p-3 rounded-3 shadow-sm mb-4 d-flex justify-content-between align-items-center" style="border: 1px solid #e2e8f0;">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1" style="font-size: 20px;">
                {{ $category->title ?? 'সাবক্যাটাগরি পণ্য' }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 13px;">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">হোম</a></li>
                    @if(isset($category->category))
                        <li class="breadcrumb-item"><a href="{{ route('category', $category->category->id) }}" class="text-decoration-none text-muted">{{ $category->category->title }}</a></li>
                    @endif
                    <li class="breadcrumb-item active text-success" aria-current="page">{{ $category->title ?? 'সাবক্যাটাগরি' }}</li>
                </ol>
            </nav>
        </div>

        <div>
            <span class="badge bg-light text-dark border px-3 py-2" style="font-size: 13px;">
                মোট পণ্য: {{ $products->total() ?? $products->count() }} টি
            </span>
        </div>
    </div>

    <!-- Product Grid -->
    @if($products->count() > 0)
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
            <div class="text-muted mb-3" style="font-size: 48px;">
                <i class="fas fa-box-open"></i>
            </div>
            <h5 class="fw-bold text-dark">এই সাবক্যাটাগরিতে কোনো পণ্য পাওয়া যায়নি</h5>
            <a href="{{ url('/') }}" class="btn text-white mt-3 px-4 py-2" style="background: var(--custom-primary-color); border-radius: 20px;">
                অন্যান্য পণ্য দেখুন
            </a>
        </div>
    @endif

</div>
@endsection

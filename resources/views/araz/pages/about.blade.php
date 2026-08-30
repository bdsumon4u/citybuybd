@extends('araz.layouts.app')

@section('title', 'আমাদের সম্পর্কে - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm" style="border: 1px solid #e2e8f0;">
        <h1 class="h3 fw-bold text-dark mb-4 pb-2 border-bottom">আমাদের সম্পর্কে (About Us)</h1>

        <div style="font-size: 15px; line-height: 1.9; color: #334155;">
            @if(!empty($settings->about))
                {!! $settings->about !!}
            @else
                <p>
                    <strong>{{ config('app.name', 'ArazShopBD') }}</strong> বাংলাদেশের অন্যতম বিশ্বস্ত ও নির্ভরযোগ্য অনলাইন শপিং প্ল্যাটফর্ম। আমরা সর্বদা ক্রেতাদের সর্বোত্তম মানের পণ্য এবং দ্রুততম ডেলিভারি সেবা প্রদানে প্রতিশ্রুতিবদ্ধ।
                </p>
                <h5 class="fw-bold mt-4 mb-2 text-dark">আমাদের মূল লক্ষ্য:</h5>
                <ul>
                    <li>১০০% গুণগতমান সম্পন্ন এবং নিখুঁত পণ্য সরবরাহ।</li>
                    <li>সারা বাংলাদেশে দ্রুততম সময়ে ক্যাশ অন ডেলিভারি নিশ্চিতকরণ।</li>
                    <li>গ্রাহকদের সর্বোচ্চ সন্তুষ্টি এবং সার্বক্ষণিক আন্তরিক সাপোর্ট প্রদান।</li>
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

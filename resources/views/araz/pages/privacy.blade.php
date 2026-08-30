@extends('araz.layouts.app')

@section('title', 'প্রাইভেসি পলিসি - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm" style="border: 1px solid #e2e8f0;">
        <h1 class="h3 fw-bold text-dark mb-4 pb-2 border-bottom">প্রাইভেসি পলিসি (Privacy Policy)</h1>

        <div style="font-size: 15px; line-height: 1.9; color: #334155;">
            @if(!empty($settings->privacy_policy))
                {!! $settings->privacy_policy !!}
            @else
                <p>আমরা গ্রাহকদের তথ্যের গোপনীয়তা রক্ষায় সর্বোচ্চ যত্নশীল। আপনার নাম, মোবাইল নাম্বার এবং ডেলিভারি ঠিকানা শুধুমাত্র আপনার অর্ডার সম্পন্ন এবং ডেলিভারি করার কাজে ব্যবহৃত হয়। তৃতীয় কোনো পক্ষের নিকট আপনার তথ্য প্রকাশ করা হয় না।</p>
            @endif
        </div>
    </div>
</div>
@endsection

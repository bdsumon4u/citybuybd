@extends('araz.layouts.app')

@section('title', 'রিটার্ন ও রিফান্ড পলিসি - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm" style="border: 1px solid #e2e8f0;">
        <h1 class="h3 fw-bold text-dark mb-4 pb-2 border-bottom">রিটার্ন ও রিফান্ড পলিসি (Return Policy)</h1>

        <div style="font-size: 15px; line-height: 1.9; color: #334155;">
            @if(!empty($settings->return_policy))
                {!! $settings->return_policy !!}
            @else
                <p>গ্রাহক সন্তুষ্টিই আমাদের প্রধান অগ্রাধিকার। পণ্য প্রাপ্তির সময় কোনো ত্রুটি বা অমিল পরিলক্ষিত হলে নিচের নীতি প্রযোজ্য হবে:</p>
                <ul>
                    <li>ডেলিভারিম্যানের উপস্থিতিতে পণ্য চেক করে গ্রহণ করুন।</li>
                    <li>পণ্য ভাঙা বা ক্ষতিগ্রস্ত থাকলে তাৎক্ষণিকভাবে রিটার্ন করতে পারবেন কোনো অতিরিক্ত ফি ছাড়াই।</li>
                    <li>পরবর্তীতে কোনো সমস্যা হলে পণ্য প্রাপ্তির ২৪ ঘণ্টার মধ্যে আমাদের কাস্টমার কেয়ারে জানান।</li>
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

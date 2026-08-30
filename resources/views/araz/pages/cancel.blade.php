@extends('araz.layouts.app')

@section('title', 'অর্ডার বাতিল পলিসি - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm" style="border: 1px solid #e2e8f0;">
        <h1 class="h3 fw-bold text-dark mb-4 pb-2 border-bottom">অর্ডার বাতিল পলিসি (Cancellation Policy)</h1>

        <div style="font-size: 15px; line-height: 1.9; color: #334155;">
            <p>অর্ডার প্লেস করার পর আপনি চাইলে ডেলিভারির পূর্বে আমাদের কাস্টমার সার্ভিসে যোগাযোগ করে অর্ডার বাতিল করতে পারেন। পণ্য কুরিয়ারে হস্তান্তর করার পূর্ব পর্যন্ত অর্ডার ফ্রিতে বাতিলযোগ্য।</p>
        </div>
    </div>
</div>
@endsection

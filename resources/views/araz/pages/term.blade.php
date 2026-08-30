@extends('araz.layouts.app')

@section('title', 'শর্তাবলী ও নিয়মাবলী - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm" style="border: 1px solid #e2e8f0;">
        <h1 class="h3 fw-bold text-dark mb-4 pb-2 border-bottom">শর্তাবলী ও নিয়মাবলী (Terms & Conditions)</h1>

        <div style="font-size: 15px; line-height: 1.9; color: #334155;">
            @if(!empty($settings->terms))
                {!! $settings->terms !!}
            @else
                <p>আমাদের ওয়েবসাইট ব্যবহারের মাধ্যমে আপনি নিচের শর্তাবলীতে সম্মতি প্রদান করছেন:</p>
                <ol>
                    <li><strong>অর্ডার নিশ্চিতকরণ:</strong> ওয়েবসাইট থেকে অর্ডার প্লেস করার পর আমাদের প্রতিনিধি কল বা এসএমএস এর মাধ্যমে নিশ্চিত করবেন।</li>
                    <li><strong>মূল্য ও পেমেন্ট:</strong> পণ্যের উল্লেখিত মূল্য ক্যাশ অন ডেলিভারিতে প্রদেয়। কোনো হিডেন চার্জ নেই।</li>
                    <li><strong>ডেলিভারি সময়সীমা:</strong> ঢাকার মধ্যে সাধারণত ২৪-৪৮ ঘণ্টা এবং ঢাকার বাইরে ৪৮-৭২ ঘণ্টার মধ্যে ডেলিভারি সম্পন্ন হয়।</li>
                </ol>
            @endif
        </div>
    </div>
</div>
@endsection

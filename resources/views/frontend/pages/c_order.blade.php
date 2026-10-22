@extends('frontend.layout.template')
@section('pageTitle', 'Confirm Order')
 @section('body-content')

     
 <div class="tab-section py-5">
     <div class="container">
         <div class="row">
             <div class="col-12">
                 <div class="tab-contents">
                     <div class="single-tab active" id="orderCompletedTab" style="padding: 15px;background: white;">
                         <div class="check-icon">
                             <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"> <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/> <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
                         </div>
                         <div class="order-complete-msg">
                             <h6 class="btn-bangla">প্রিয় {{ $order->name }}</h6>
                                 
                                     <h6 class="btn-bangla">  আপনার অর্ডার নাম্বার - {{ $order->id }}</h6>
                                         <h6 class="btn-bangla">   পণ্যের মূল্য - {{ $order->sub_total }} TK</h6>
                                            <h6 class="btn-bangla">   ডেলিভারি চার্জ - {{ $order->shipping_cost }} TK</h6>
                                            <h6 class="btn-bangla">  {{ $settings->yt_link }}  </h6>


                             <br/>
                                             <h6 class="btn-bangla">   ধন্যবাদন্তে</h6>
                                                <h5 class="btn-bangla"> {{ $settings->insta_link}}</h5>
                         </div>
                         <div class="order-complete-msg py-3">
                             <a href="{{url('/')}}" class="def-btn palce-order tab-next-btn btn-success px-5 btn-bangla" style="background-color: green">প্রোডাক্ট বাছাই করুন</a>
                         </div>

                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

@if(isset($order))
    @php
        $order->loadMissing(['items.product']);
        $fbContentIds = $order->items ? $order->items->pluck('product_id')->map(fn($id) => (string)$id)->values()->all() : [];
        $fbContents = $order->items ? $order->items->map(fn($item) => [
            'id' => (string)$item->product_id,
            'quantity' => (int)($item->quantity ?? 1),
            'item_price' => (float)($item->price ?? 0),
        ])->values()->all() : [];
        $fbValue = (float)($order->total ?? $order->sub_total ?? 0);
        $fbNumItems = (int)($order->ordered_quantity ?? ($order->items ? $order->items->sum('quantity') : 1));

        $fbPhoneDigits = preg_replace('/\D+/', '', (string)($order->phone ?? ''));
        if (str_starts_with($fbPhoneDigits, '01') && strlen($fbPhoneDigits) === 11) {
            $fbPhoneDigits = '88' . $fbPhoneDigits;
        }

        $fbNameParts = preg_split('/\s+/', trim((string)($order->name ?? ''))) ?: [];
        $fbFirstName = array_shift($fbNameParts) ?: '';
        $fbLastName = !empty($fbNameParts) ? implode(' ', $fbNameParts) : '';
        $fbUserMatch = array_filter([
            'ph' => $fbPhoneDigits ?: null,
            'em' => !empty($order->email) ? strtolower(trim($order->email)) : null,
            'fn' => $fbFirstName ? strtolower($fbFirstName) : null,
            'ln' => $fbLastName ? strtolower($fbLastName) : null,
            'external_id' => (string) $order->id,
            'country' => 'bd',
        ], static fn($v) => $v !== null && $v !== '');
        $fbPixelId = trim((string) ($settings->fb_pixel_id ?? ''));
    @endphp
    <script>
        (function() {
            function executePurchaseTrack() {
                if (typeof window.fbq === 'function') {
                    @if($fbPixelId !== '')
                    try {
                        window.fbq('init', @json($fbPixelId), @json($fbUserMatch));
                    } catch(e) {}
                    @endif

                    if (typeof window.citybuybdFacebookTrack === 'function') {
                        window.citybuybdFacebookTrack('Purchase', {
                            content_type: 'product',
                            content_ids: @json($fbContentIds),
                            contents: @json($fbContents),
                            value: {{ $fbValue }},
                            currency: 'BDT',
                            num_items: {{ $fbNumItems }}
                        }, 'order_{{ $order->id }}');
                    }
                }
            }

            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                executePurchaseTrack();
            } else {
                document.addEventListener('DOMContentLoaded', executePurchaseTrack);
            }
        })();
    </script>
@endif

@endsection

@extends('araz.layouts.app')

@section('title', 'অর্ডার সফল হয়েছে! ধন্যবাদ')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white p-4 p-md-5 rounded-3 shadow text-center" style="border: 2px solid #10b981;">
                
                <!-- Success Animated Check Icon -->
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: #d1fae5; color: #059669; font-size: 38px;">
                    <i class="fas fa-check"></i>
                </div>

                <h2 class="fw-bold text-success mb-2" style="font-size: 26px;">
                    অভিনন্দন! আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে।
                </h2>
                <p class="text-muted mb-4" style="font-size: 15px;">
                    আমাদের কাস্টমার প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করে অর্ডারটি কনফার্ম করবেন।
                </p>

                @if(isset($order))
                    <div class="bg-light p-4 rounded-3 text-start border mb-4" style="font-size: 14px; line-height: 1.8;">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">অর্ডার বিবরণী (Order Summary):</h5>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>ইনভয়েস আইডি:</strong> #{{ $order->id }}</p>
                                <p class="mb-1"><strong>গ্রাহকের নাম:</strong> {{ $order->name }}</p>
                                <p class="mb-1"><strong>মোবাইল নাম্বার:</strong> {{ $order->phone }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>ডেলিভারি ঠিকানা:</strong> {{ $order->address }}</p>
                                <p class="mb-1"><strong>পেমেন্ট মেথড:</strong> ক্যাশ অন ডেলিভারি</p>
                                <p class="mb-1"><strong>সর্বমোট বিল:</strong> <span class="text-danger fw-bold fs-6">৳ {{ number_format($order->total, 0) }}</span></p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-center flex-wrap gap-2 gap-md-3">
                    <a href="{{ url('/') }}" class="btn text-white px-4 py-2 fw-bold" style="background: var(--custom-primary-color); border-radius: 20px;">
                        <i class="fas fa-home me-2"></i> হোম পেইজে ফিরে যান
                    </a>
                    @if(!empty($settings->phone))
                        <a href="tel:{{ $settings->phone }}" class="btn btn-outline-success px-4 py-2 fw-bold" style="border-radius: 20px;">
                            <i class="fa fa-phone-alt me-2"></i> কাস্টমার কেয়ার
                        </a>
                    @endif
                    @php
                        $rawWa = !empty($settings->whatsapp_number) ? $settings->whatsapp_number : ($settings->whatsapp ?? '');
                        $waDigits = preg_replace('/\D/', '', (string) $rawWa);
                        if (!str_starts_with($waDigits, '88') && strlen($waDigits) == 11) {
                            $waDigits = '88' . $waDigits;
                        }
                    @endphp
                    @if(!empty($waDigits))
                        <a href="https://wa.me/{{ $waDigits }}" target="_blank" class="btn text-white px-4 py-2 fw-bold d-inline-flex align-items-center" style="background: #25d366; border-radius: 20px;">
                            <i class="fab fa-whatsapp me-2 fs-5"></i> WhatsApp
                        </a>
                    @endif
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

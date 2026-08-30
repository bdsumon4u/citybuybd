@extends('araz.layouts.app')

@section('title', 'চেকআউট - অর্ডার সম্পন্ন করুন')

@push('styles')
<style>
    .checkout-compact-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px 18px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .checkout-form .form-label {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #374151 !important;
        margin-bottom: 3px !important;
        display: block;
    }
    .checkout-form .form-control,
    .checkout-form .form-select {
        height: 38px !important;
        min-height: 38px !important;
        padding: 5px 12px !important;
        font-size: 13.5px !important;
        border-radius: 6px !important;
        border: 1.5px solid #d1d5db !important;
        background-color: #ffffff !important;
        color: #1f2937 !important;
        line-height: normal !important;
    }
    .checkout-form .form-control:focus,
    .checkout-form .form-select:focus {
        border-color: var(--custom-primary-color) !important;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15) !important;
        outline: none !important;
    }
    .checkout-form textarea.form-control {
        height: 56px !important;
        min-height: 56px !important;
        padding: 6px 12px !important;
        resize: none;
    }
    .checkout-form .form-group-compact {
        margin-bottom: 10px !important;
    }
    .cart-item-compact {
        padding: 6px 10px !important;
        margin-bottom: 6px !important;
        border-radius: 8px !important;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="container py-3">

    <!-- Notice Header Banner -->
    <div class="alert text-center p-2 mb-3 rounded-3 shadow-sm" style="background: #e8f5e9; border: 1px solid #a7f3d0; color: #065f46;">
        <h5 class="mb-0 fw-bold" style="font-size: 14px;">
            <i class="fa-solid fa-circle-check me-1 text-success"></i> 
            অর্ডারটি কনফার্ম করতে আপনার নাম, ঠিকানা ও মোবাইল নাম্বার লিখে নিচের "অর্ডার কনফার্ম করুন" বাটনে ক্লিক করুন।
        </h5>
    </div>

    @if(\Gloudemans\Shoppingcart\Facades\Cart::count() < 1)
        <!-- Empty Cart Notice -->
        <div class="bg-white p-5 rounded-3 text-center shadow-sm" style="border: 1px solid #e2e8f0;">
            <div class="mb-3 text-muted" style="font-size: 50px;">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">আপনার কার্ট বর্তমানে খালি আছে</h4>
            <p class="text-muted mb-4">পছন্দের পণ্যটি নির্বাচন করে কার্টে যোগ করুন।</p>
            <a href="{{ url('/') }}" class="btn text-white px-4 py-2 fw-bold" style="background: var(--custom-primary-color); border-radius: 20px;">
                <i class="fa-solid fa-arrow-left me-2"></i> শপিং চালিয়ে যান
            </a>
        </div>
    @else
        <div class="row g-3">

            <!-- Left Column: Customer Information & Delivery -->
            <div class="col-lg-6">
                <div class="checkout-compact-card h-100">
                    <h4 class="fw-bold mb-3 pb-2 border-bottom text-dark d-flex align-items-center" style="font-size: 16px;">
                        <i class="fa-solid fa-user-pen me-2 text-success"></i> আপনার বিলিং ও ডেলিভারি তথ্য
                    </h4>

                    <form action="{{ route('order') }}" method="POST" id="checkout_form" class="checkout_form checkout-form">
                        @csrf

                        <!-- Customer Name -->
                        <div class="form-group-compact">
                            <label for="name" class="form-label">
                                আপনার নাম লিখুন <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="সম্পূর্ণ নাম লিখুন" required>
                        </div>

                        <!-- Customer Phone -->
                        <div class="form-group-compact">
                            <label for="phone" class="form-label">
                                মোবাইল নাম্বার <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone" pattern="^(?:\+?88)?01[13-9]\d{8}$" placeholder="১১ ডিজিটের মোবাইল নাম্বার (যেমন: 017XXXXXXXX)" required>
                        </div>

                        <!-- Customer Address -->
                        <div class="form-group-compact">
                            <label for="address" class="form-label">
                                সম্পূর্ণ ঠিকানা <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="জেলা, থানা/উপজেলা, গ্রাম বা বাসার ঠিকানা লিখুন" required></textarea>
                        </div>

                        <!-- Shipping Area Selection -->
                        @php
                            $firstCart = \Gloudemans\Shoppingcart\Facades\Cart::content()?->first();
                            $prd = $firstCart ? ($cartProducts[$firstCart->id] ?? null) : null;
                            $isFreeDelivery = $prd && $prd->shipping == '1';
                        @endphp

                        <div class="form-group-compact mb-3">
                            <label class="form-label">
                                ডেলিভারি এরিয়া সিলেক্ট করুন <span class="text-danger">*</span>
                            </label>

                            @if ($isFreeDelivery)
                                <div class="p-2 bg-light rounded border text-success fw-bold d-flex align-items-center" style="font-size: 13px;">
                                    <i class="fa-solid fa-gift me-2 fs-6"></i> এই অর্ডারে ফ্রি ডেলিভারি পাচ্ছেন!
                                    <input type="hidden" name="shipping_method" id="shipping_method" value="0" data-amount="0">
                                </div>
                            @else
                                <select name="shipping_method" id="shipping_method" class="form-select" required onchange="calculateTotal()">
                                    @foreach (\Gloudemans\Shoppingcart\Facades\Cart::content() as $cart)
                                        @php $prd = $cartProducts[$cart->id] ?? null; @endphp
                                        @if ($prd && $prd->shipping == '1')
                                            <option value="0" data-amount="0">ঢাকার বাইরে (ফ্রি ডেলিভারি)</option>
                                            <option value="0" data-amount="0">ঢাকার ভিতরে (ফ্রি ডেলিভারি)</option>
                                        @elseif($prd && $prd->shipping == '0')
                                            <option value="{{ $prd->inside ?? 60 }}" data-amount="{{ $prd->inside ?? 60 }}">ঢাকার ভিতরে (৳ {{ $prd->inside ?? 60 }})</option>
                                            <option value="{{ $prd->outside ?? 120 }}" data-amount="{{ $prd->outside ?? 120 }}" selected>ঢাকার বাইরে (৳ {{ $prd->outside ?? 120 }})</option>
                                        @else
                                            @foreach ($shippings as $shipping)
                                                <option value="{{ $shipping->amount }}" data-amount="{{ $shipping->amount }}" {{ $loop->last ? 'selected' : '' }}>
                                                    {{ $shipping->type }} (৳ {{ $shipping->amount }})
                                                </option>
                                            @endforeach
                                        @endif
                                        @break
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <!-- Payment Method Badge -->
                        <div class="p-2 mb-3 rounded-2 border bg-light d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-hand-holding-dollar text-success fs-5"></i>
                                <div>
                                    <span class="d-block fw-bold text-dark" style="font-size: 13px;">ক্যাশ অন ডেলিভারি (COD)</span>
                                    <small class="text-muted" style="font-size: 11px;">পণ্য বুঝে পেয়ে মূল্য পরিশোধ করুন</small>
                                </div>
                            </div>
                            <span class="badge bg-success" style="font-size: 10.5px;">ডিফল্ট</span>
                        </div>

                        <!-- Hidden Form Attributes for Orders & Fingerprinting -->
                        <input type="hidden" name="sub_total" id="cart_subtotal" value="{{ \Gloudemans\Shoppingcart\Facades\Cart::total() }}">
                        <input type="hidden" name="timezone" id="fp_timezone">
                        <input type="hidden" name="screen" id="fp_screen">
                        <input type="hidden" name="platform" id="fp_platform">
                        <input type="hidden" name="cpu_class" id="fp_cpu_class">
                        <input type="hidden" name="touch_points" id="fp_touch_points">
                        <input type="hidden" name="webgl" id="fp_webgl">
                        <input type="hidden" name="canvas" id="fp_canvas">
                        <input type="hidden" name="plugins_hash" id="fp_plugins_hash">
                        <input type="hidden" id="incomplete_token" value="{{ $incompleteToken ?? '' }}">

                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::content() as $cartItem)
                            <input type="hidden" class="product_id" name="product_ids[]" value="{{ $cartItem->id }}">
                            <input type="hidden" class="product_slug" name="product_slugs[]" value="{{ $cartItem->options['slug'] ?? '' }}">
                            <input type="hidden" name="product_color[]" value="{{ $cartItem->options['color'] ?? '' }}">
                            <input type="hidden" name="product_size[]" value="{{ $cartItem->options['size'] ?? '' }}">
                            <input type="hidden" name="product_model[]" value="{{ $cartItem->options['model'] ?? '' }}">
                        @endforeach

                        <!-- Submit CTA Button -->
                        <button type="submit" class="btn text-white w-100 py-2 fw-bold jdx-pulse shadow-sm" id="conf_order_btn" style="background: var(--custom-primary-color); font-size: 16px; border-radius: 6px;">
                            <i class="fa-solid fa-truck-arrow-right me-2"></i> অর্ডার কনফার্ম করুন (৳ <span id="btn_total_amount">{{ \Gloudemans\Shoppingcart\Facades\Cart::total() }}</span>)
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Order Summary & Cart Items Table -->
            <div class="col-lg-6">
                <div class="checkout-compact-card h-100">
                    <h4 class="fw-bold mb-3 pb-2 border-bottom text-dark d-flex align-items-center" style="font-size: 16px;">
                        <i class="fa-solid fa-cart-shopping me-2 text-success"></i> আপনার অর্ডার তালিকা
                    </h4>

                    <!-- Cart Items List -->
                    <div class="cart-items-list mb-3">
                        @foreach(\Gloudemans\Shoppingcart\Facades\Cart::content() as $cart)
                            <div class="d-flex align-items-center justify-content-between cart-item-compact" id="cart_row_{{ $cart->rowId }}">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $cart->options['image'] ?? asset('frontend/images/product-placeholder.png') }}" alt="{{ $cart->name }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px;">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 13px; max-width: 200px;">{{ $cart->name }}</h6>
                                        <small class="text-muted" style="font-size: 11.5px;">
                                            ৳ {{ number_format($cart->price, 0) }} &times; {{ $cart->qty }}
                                            @if(!empty($cart->options['color'])) | কালার: {{ $cart->options['color'] }} @endif
                                            @if(!empty($cart->options['size'])) | সাইজ: {{ $cart->options['size'] }} @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-danger" style="font-size: 14px;">
                                        ৳ {{ number_format($cart->price * $cart->qty, 0) }}
                                    </span>
                                    <a href="{{ route('cart.destroy', $cart->rowId) }}" class="btn btn-sm btn-outline-danger p-0" style="border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;" title="মুছে ফেলুন">
                                        <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary Table -->
                    <div class="p-2 px-3 bg-light rounded-2 border" style="font-size: 13.5px;">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">সাবটোটাল (Subtotal):</span>
                            <span class="fw-bold text-dark">৳ <span id="summary_subtotal">{{ \Gloudemans\Shoppingcart\Facades\Cart::total() }}</span></span>
                        </div>

                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">ডেলিভারি চার্জ (Delivery):</span>
                            <span class="fw-bold text-success">+ ৳ <span id="summary_shipping">0</span></span>
                        </div>

                        <div class="d-flex justify-content-between py-2">
                            <span class="fw-bold text-dark" style="font-size: 15px;">সর্বমোট (Total):</span>
                            <span class="fw-bold text-danger" style="font-size: 16px;">৳ <span id="summary_grandtotal">{{ \Gloudemans\Shoppingcart\Facades\Cart::total() }}</span></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>

@push('scripts')
<script>
    function calculateTotal() {
        var subtotal = parseFloat("{{ \Gloudemans\Shoppingcart\Facades\Cart::total() }}") || 0;
        var selectedShipping = $('#shipping_method option:selected').data('amount');
        if (selectedShipping === undefined) {
            selectedShipping = parseFloat($('#shipping_method').data('amount')) || 0;
        } else {
            selectedShipping = parseFloat(selectedShipping) || 0;
        }

        var grandTotal = subtotal + selectedShipping;

        $('#summary_shipping').text(selectedShipping);
        $('#summary_grandtotal').text(grandTotal);
        $('#btn_total_amount').text(grandTotal);
    }

    $(document).ready(function() {
        calculateTotal();

        // Populate basic client device fingerprints
        try {
            $('#fp_timezone').val(Intl.DateTimeFormat().resolvedOptions().timeZone || '');
            $('#fp_screen').val(window.screen.width + 'x' + window.screen.height);
            $('#fp_platform').val(navigator.platform || '');
        } catch(e) {}
    });
</script>
@endpush
@endsection

@extends('araz.layouts.app')

@section('title', 'শপিং কার্ট')

@section('content')
<div class="container py-4">
    <div class="bg-white p-4 rounded-3 shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
        <h4 class="fw-bold mb-4 pb-2 border-bottom text-dark d-flex align-items-center" style="font-size: 20px;">
            <i class="fas fa-shopping-cart me-2 text-success"></i> আপনার শপিং কার্ট
        </h4>

        @if(\Gloudemans\Shoppingcart\Facades\Cart::count() < 1)
            <div class="text-center py-5">
                <div class="text-muted mb-3" style="font-size: 50px;">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <h5 class="fw-bold text-dark">আপনার কার্ট বর্তমানে খালি আছে</h5>
                <a href="{{ url('/') }}" class="btn text-white mt-3 px-4 py-2" style="background: var(--custom-primary-color); border-radius: 20px;">
                    শপিং করুন
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>পণ্য</th>
                            <th>মূল্য</th>
                            <th>পরিমাণ</th>
                            <th>মোট</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\Gloudemans\Shoppingcart\Facades\Cart::content() as $cart)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $cart->options['image'] ?? asset('frontend/images/product-placeholder.png') }}" alt="{{ $cart->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $cart->name }}</h6>
                                            <small class="text-muted">
                                                @if(!empty($cart->options['package']) || !empty($cart->options['bulk_pack']))
                                                    প্যাকেজ: {{ $cart->options['package'] ?? $cart->options['bulk_pack'] }}
                                                @endif
                                                @if(!empty($cart->options['size']))
                                                    | সাইজ: {{ $cart->options['size'] }}
                                                @endif
                                                @if(!empty($cart->options['color'])) | কালার: {{ $cart->options['color'] }} @endif
                                                @if(!empty($cart->options['model'])) | মডেল: {{ $cart->options['model'] }} @endif
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>৳ {{ number_format($cart->price, 0) }}</td>
                                <td>{{ $cart->qty }}</td>
                                <td class="fw-bold text-danger">৳ {{ number_format($cart->price * $cart->qty, 0) }}</td>
                                <td>
                                    <a href="{{ route('cart.destroy', $cart->rowId) }}" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top gap-3">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 20px;">
                    <i class="fas fa-arrow-left me-2"></i> আরও পণ্য দেখুন
                </a>

                <div class="text-end">
                    <h5 class="fw-bold text-dark mb-3">সাবটোটাল: <span class="text-danger">৳ {{ \Gloudemans\Shoppingcart\Facades\Cart::total() }}</span></h5>
                    <a href="{{ route('checkout') }}" class="btn text-white px-5 py-2 fw-bold" style="background: var(--custom-primary-color); border-radius: 20px; font-size: 16px;">
                        অর্ডার সম্পন্ন করুন <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

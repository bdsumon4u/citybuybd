<!-- Mobile Bottom Floating Navigation Bar -->
<div class="d-block d-lg-none fixed-bottom bg-white border-top shadow-lg" style="z-index: 1040; height: 56px;">
    <div class="row h-100 g-0 text-center align-items-center">
        <!-- Home -->
        <div class="col-3">
            <a href="{{ url('/') }}" class="text-decoration-none d-flex flex-column align-items-center justify-content-center text-dark" style="font-size: 11px;">
                <i class="fa-solid fa-house text-success" style="font-size: 18px;"></i>
                <span class="mt-1">হোম</span>
            </a>
        </div>

        <!-- Categories / Offcanvas trigger -->
        <div class="col-3">
            <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#mobileOffcanvasMenu" class="text-decoration-none d-flex flex-column align-items-center justify-content-center text-dark" style="font-size: 11px;">
                <i class="fa-solid fa-table-cells-large text-secondary" style="font-size: 18px;"></i>
                <span class="mt-1">ক্যাটাগরি</span>
            </a>
        </div>

        <!-- Cart with badge -->
        <div class="col-3">
            <a href="{{ route('checkout') }}" class="text-decoration-none d-flex flex-column align-items-center justify-content-center text-dark position-relative" style="font-size: 11px;">
                <div class="position-relative">
                    <i class="fa-solid fa-basket-shopping text-danger" style="font-size: 18px;"></i>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle cart-count" id="mobile-bottom-cart-count" style="font-size: 9px; padding: 2px 4px;">
                        {{ \Gloudemans\Shoppingcart\Facades\Cart::count() }}
                    </span>
                </div>
                <span class="mt-1">কার্ট</span>
            </a>
        </div>

        <!-- Direct Phone Call -->
        <div class="col-3">
            <a href="tel:{{ $settings->phone ?? '01601745352' }}" class="text-decoration-none d-flex flex-column align-items-center justify-content-center text-dark" style="font-size: 11px;">
                <i class="fa-solid fa-phone text-primary" style="font-size: 18px;"></i>
                <span class="mt-1">কল করুন</span>
            </a>
        </div>
    </div>
</div>

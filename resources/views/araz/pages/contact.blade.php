@extends('araz.layouts.app')

@section('title', 'যোগাযোগ - ' . ($settings->insta_link ?? config('app.name')))

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Contact Information Cards -->
        <div class="col-lg-5">
            <div class="bg-white p-4 rounded-3 shadow-sm h-100" style="border: 1px solid #e2e8f0;">
                <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom">আমাদের সাথে যোগাযোগ করুন</h4>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #e8f5e9; color: var(--custom-primary-color); font-size: 18px;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">অফিসের ঠিকানা</h6>
                        <p class="text-muted mb-0" style="font-size: 14px;">{{ $settings->address ?? 'ঢাকা, বাংলাদেশ' }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb; font-size: 18px;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">হটলাইন নাম্বার</h6>
                        <a href="tel:{{ $settings->phone ?? '01601745352' }}" class="text-decoration-none text-muted" style="font-size: 14px;">
                            {{ $settings->phone ?? '01601745352' }}
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #fdf2f8; color: #db2777; font-size: 18px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">ইমেইল অ্যাড্রেস</h6>
                        <p class="text-muted mb-0" style="font-size: 14px;">{{ $settings->email ?? 'info@citybuybd.com' }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #ecfdf5; color: #059669; font-size: 18px;">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">হোয়াটসঅ্যাপ সাপোর্ট</h6>
                        <a href="https://wa.me/{{ $settings->whatsapp ?? '01601745352' }}" target="_blank" class="text-decoration-none text-muted" style="font-size: 14px;">
                            {{ $settings->whatsapp ?? '01601745352' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="bg-white p-4 rounded-3 shadow-sm h-100" style="border: 1px solid #e2e8f0;">
                <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom">আমাদের সরাসরি মেসেজ পাঠান</h4>

                <form onsubmit="event.preventDefault(); toastr.success('আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে! আমরা শীঘ্রই যোগাযোগ করব।'); this.reset();">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">আপনার নাম</label>
                        <input type="text" class="form-control py-2" placeholder="নাম লিখুন" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">মোবাইল নাম্বার</label>
                        <input type="tel" class="form-control py-2" placeholder="১১ ডিজিটের মোবাইল নাম্বার" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">বার্তা বা অনুসন্ধান</label>
                        <textarea class="form-control" rows="4" placeholder="আপনার বক্তব্য এখানে লিখুন..." required></textarea>
                    </div>

                    <button type="submit" class="btn text-white px-4 py-2 fw-bold" style="background: var(--custom-primary-color); border-radius: 8px;">
                        বার্তা পাঠান <i class="fas fa-paper-plane ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

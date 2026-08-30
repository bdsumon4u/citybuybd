<footer class="footer-section" style="background: #151922; color: #d1d5db; padding-top: 40px; margin-top: 40px;">
    <div class="container">
        <!-- 3-Column CTA Section -->
        <div class="footer-cta pb-4 mb-4" style="border-bottom: 1px solid #2d3748;">
            <div class="row g-3 g-md-4 mx-0">
                <div class="col-xl-4 col-md-4 mb-3">
                    <div class="single-cta d-flex align-items-center" style="gap: 15px;">
                        <div class="cta-icon d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; background: var(--custom-primary-color); color: #fff; font-size: 20px; flex-shrink: 0;">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="cta-text">
                            <h4 class="text-white fw-bold mb-1" style="font-size: 16px;">আমাদের ঠিকানা</h4>
                            <span style="font-size: 13px; color: #9ca3af;">{{ $settings->address ?? 'ঢাকা, বাংলাদেশ' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 mb-3">
                    <div class="single-cta d-flex align-items-center" style="gap: 15px;">
                        <div class="cta-icon d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; background: var(--custom-primary-color); color: #fff; font-size: 20px; flex-shrink: 0;">
                            <i class="fa-solid fa-phone-volume"></i>
                        </div>
                        <div class="cta-text">
                            <h4 class="text-white fw-bold mb-1" style="font-size: 16px;">সরাসরি কল করুন</h4>
                            <a href="tel:{{ $settings->phone ?? '01601745352' }}" class="text-decoration-none" style="font-size: 14px; color: #9ca3af;">
                                {{ $settings->phone ?? '01601745352' }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 mb-3">
                    <div class="single-cta d-flex align-items-center" style="gap: 15px;">
                        <div class="cta-icon d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; background: var(--custom-primary-color); color: #fff; font-size: 20px; flex-shrink: 0;">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <div class="cta-text">
                            <h4 class="text-white fw-bold mb-1" style="font-size: 16px;">ইমেইল ও সাপোর্ট</h4>
                            <span style="font-size: 13px; color: #9ca3af;">{{ $settings->email ?? 'info@citybuybd.com' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div class="footer-content pb-4">
            <div class="row g-3 g-md-4 mx-0">
                <!-- Brand Info -->
                <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <div class="footer-logo mb-3">
                            @if(!empty($settings->logo))
                                <img src="{{ asset('backend/img/' . $settings->logo) }}" alt="{{ $settings->insta_link ?? config('app.name') }}" style="max-height: 52px; max-width: 220px; object-fit: contain; background: #ffffff; padding: 4px 10px; border-radius: 8px;">
                            @else
                                <h3 class="text-white fw-bold">{{ $settings->insta_link ?? config('app.name') }}</h3>
                            @endif
                        </div>
                        <div class="footer-text mb-3">
                            <p style="font-size: 13px; line-height: 1.8; color: #9ca3af;">
                                {{ $settings->short_des ?? 'গুণগতমান সম্পূর্ণ এবং ক্রেতার সন্তুষ্টির জন্য একমাত্র বিশ্বস্ত অনলাইন শপ। সারাদেশে দ্রুততম সময়ে ক্যাশ অন ডেলিভারি সুবিধা।' }}
                            </p>
                        </div>
                        <div class="footer-social-icon d-flex align-items-center" style="gap: 12px;">
                            @if(!empty($settings->fb_link ?? $settings->facebook))
                                <a href="{{ $settings->fb_link ?? $settings->facebook }}" target="_blank" style="width: 36px; height: 36px; background: #1877f2; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            @endif
                            @if(!empty($settings->whatsapp_number ?? $settings->whatsapp))
                                <a href="https://wa.me/{{ $settings->whatsapp_number ?? $settings->whatsapp }}" target="_blank" style="width: 36px; height: 36px; background: #25d366; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            @endif
                            @if(!empty($settings->yt_link ?? $settings->youtube))
                                <a href="{{ $settings->yt_link ?? $settings->youtube }}" target="_blank" style="width: 36px; height: 36px; background: #ff0000; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                    <i class="fa-brands fa-youtube"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Useful Links -->
                <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <div class="footer-widget-heading mb-3">
                            <h4 class="text-white fw-bold position-relative pb-2" style="font-size: 16px; border-bottom: 2px solid var(--custom-primary-color); display: inline-block;">
                                গুরুত্বপূর্ণ লিংকসমূহ
                            </h4>
                        </div>
                        <ul class="list-unstyled p-0 mb-0" style="font-size: 13px; line-height: 2.2;">
                            <li><a href="{{ route('front.about') }}" class="text-decoration-none" style="color: #9ca3af;"><i class="fa-solid fa-chevron-right me-2 text-success" style="font-size: 12px;"></i> আমাদের সম্পর্কে</a></li>
                            <li><a href="{{ route('front.contact') }}" class="text-decoration-none" style="color: #9ca3af;"><i class="fa-solid fa-chevron-right me-2 text-success" style="font-size: 12px;"></i> যোগাযোগ করুন</a></li>
                            <li><a href="{{ route('front.termCondition') }}" class="text-decoration-none" style="color: #9ca3af;"><i class="fa-solid fa-chevron-right me-2 text-success" style="font-size: 12px;"></i> শর্তাবলী ও নিয়মাবলী</a></li>
                            <li><a href="{{ url('return-policy') }}" class="text-decoration-none" style="color: #9ca3af;"><i class="fa-solid fa-chevron-right me-2 text-success" style="font-size: 12px;"></i> রিটার্ন পলিসি</a></li>
                            <li><a href="{{ url('privacy-policy') }}" class="text-decoration-none" style="color: #9ca3af;"><i class="fa-solid fa-chevron-right me-2 text-success" style="font-size: 12px;"></i> প্রাইভেসি পলিসি</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Delivery & Payment Security Info -->
                <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <div class="footer-widget-heading mb-3">
                            <h4 class="text-white fw-bold position-relative pb-2" style="font-size: 16px; border-bottom: 2px solid var(--custom-primary-color); display: inline-block;">
                                ডেলিভারি ও সার্ভিস
                            </h4>
                        </div>
                        <p style="font-size: 13px; color: #9ca3af; line-height: 1.8;">
                            আমরা সারাদেশের যেকোনো প্রান্তে ৪৮ থেকে ৭২ ঘণ্টার মধ্যে ক্যাশ অন ডেলিভারিতে হোম ডেলিভারি নিশ্চিত করি।
                        </p>
                        <div class="payment-badges p-2 rounded bg-dark d-inline-block">
                            <span class="badge bg-success me-1"><i class="fa-solid fa-truck-fast me-1"></i> Cash On Delivery</span>
                            <span class="badge bg-primary"><i class="fa-solid fa-shield-halved me-1"></i> 100% Authentic</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="copyright-area py-3" style="background: #0d1117; border-top: 1px solid #1f2937;">
        <div class="container text-center">
            <p class="mb-0" style="font-size: 13px; color: #6b7280;">
                Copyright &copy; {{ date('Y') }} <strong>{{ config('app.name', 'ArazShopBD') }}</strong>. সর্বস্বত্ব সংরক্ষিত।
            </p>
        </div>
    </div>
</footer>

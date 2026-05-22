@php
    $whiteLogo = home_url('/wp-content/uploads/2025/05/logo-dlv-white.png');
    $appStore = home_url('/wp-content/themes/dailyve-theme/resources/images/download-app-store.png');
    $googlePlay = home_url('/wp-content/themes/dailyve-theme/resources/images/download-gg-play.png');
    $logoWhiteBg = home_url('/wp-content/themes/dailyve-theme/resources/images/logo-dailyve.png');

    $footerGroups = [
        [
            'label' => 'Đặt vé',
            'links' => [
                ['label' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
                ['label' => 'Vé tàu hỏa', 'url' => home_url('/ve-tau-hoa/')],
                ['label' => 'Vé máy bay', 'url' => home_url('/ve-may-bay/')],
                ['label' => 'Tra cứu vé', 'url' => home_url('/don-hang-cua-toi/')],
            ],
        ],
        [
            'label' => 'Tuyến phổ biến',
            'links' => [
                ['label' => 'Sài Gòn - Đà Lạt', 'url' => home_url('/ve-xe-khach/')],
                ['label' => 'Sài Gòn - Nha Trang', 'url' => home_url('/ve-xe-khach/')],
                ['label' => 'Hà Nội - Sapa', 'url' => home_url('/ve-xe-khach/')],
                ['label' => 'Xem tất cả tuyến', 'url' => home_url('/ve-xe-khach/')],
            ],
        ],
        [
            'label' => 'Về Dailyve',
            'links' => [
                ['label' => 'Giới thiệu', 'url' => home_url('/gioi-thieu/')],
                ['label' => 'Tin tức', 'url' => home_url('/tin-tuc/')],
                ['label' => 'Tuyển dụng', 'url' => home_url('/tuyen-dung/')],
                ['label' => 'Liên hệ', 'url' => home_url('/lien-he/')],
            ],
        ],
        [
            'label' => 'Hỗ trợ',
            'links' => [
                ['label' => 'Trung tâm hỗ trợ', 'url' => home_url('/trung-tam-ho-tro/')],
                ['label' => 'Hướng dẫn đặt vé', 'url' => home_url('/huong-dan-dat-ve/')],
                ['label' => 'Chính sách hoàn hủy', 'url' => home_url('/chinh-sach-hoan-huy/')],
                ['label' => 'Điều khoản sử dụng', 'url' => home_url('/dieu-khoan-su-dung/')],
            ],
        ],
    ];
@endphp

<footer class="bg-[#041224] text-white border-t border-white/5">
    <div class="dailyve-container pt-12 pb-6 md:pt-16">

        {{-- Top Section: Logo & Links Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 mb-12">

            {{-- Left Column: Brand & Trust --}}
            <div class="lg:col-span-4 flex flex-col gap-6 lg:border-r lg:border-white/10 lg:pr-10">
                <a href="{{ esc_url(home_url('/')) }}" aria-label="Dailyve Trang chủ" class="inline-block" title="Dailyve">
                    <img src="{{ esc_url($logoWhiteBg) }}" alt="Dailyve Logo" width="140" height="40"
                        class="w-[140px] h-auto object-contain" loading="lazy" decoding="async">
                </a>
                <p class="text-[13px] text-gray-300 leading-relaxed max-w-sm m-0">
                    Đặt vé xe khách, tàu hỏa, máy bay nhanh chóng với giá minh bạch và hỗ trợ 24/7.
                </p>

                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-[#0a2344] flex items-center justify-center shrink-0">
                            <i class="fas fa-shield-alt text-[#3b82f6] text-lg" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white m-0">Thanh toán an toàn</p>
                            <p class="text-xs text-gray-400 m-0 mt-0.5">Bảo mật tuyệt đối</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-[#0a2344] flex items-center justify-center shrink-0">
                            <i class="fas fa-headset text-[#3b82f6] text-lg" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white m-0">Hỗ trợ 24/7</p>
                            <p class="text-xs text-gray-400 m-0 mt-0.5">Luôn sẵn sàng phục vụ</p>
                        </div>
                    </div>
                </div>

                {{-- Socials --}}
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" aria-label="Facebook"
                        class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors"
                        title="Facebook">
                        <i class="fab fa-facebook-f text-sm" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="TikTok"
                        class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors"
                        title="TikTok">
                        <i class="fab fa-tiktok text-sm" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="YouTube"
                        class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors"
                        title="YouTube">
                        <i class="fab fa-youtube text-sm" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="Instagram"
                        class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors"
                        title="Instagram">
                        <i class="fab fa-instagram text-sm" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            {{-- Right Column: Nav Links --}}
            <div class="lg:col-span-8 grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ($footerGroups as $group)
                    <nav aria-label="{{ esc_attr($group['label']) }}">
                        <h3 class="text-[15px] font-bold text-white m-0 mb-5 relative inline-block">
                            {{ $group['label'] }}
                            <span class="absolute left-0 -bottom-2 w-8 h-[2px] bg-[#3b82f6]"></span>
                        </h3>
                        <ul class="flex flex-col gap-3.5 m-0 p-0 list-none mt-2">
                            @foreach ($group['links'] as $link)
                                <li>
                                    <a href="{{ esc_url($link['url']) }}" title="{{ esc_attr($link['label']) }}"
                                        class="text-[13px] text-gray-300 hover:text-white transition-colors flex items-center gap-2 group no-underline">
                                        <i class="fas fa-chevron-right text-[10px] text-[#3b82f6] transition-transform group-hover:translate-x-1"
                                            aria-hidden="true"></i>
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endforeach
            </div>
        </div>

        <hr class="border-white/10 m-0">

        {{-- Middle Section: Contact & Apps --}}
        <div class="py-8 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-8 xl:gap-4">

            <div
                class="flex flex-col md:flex-row gap-6 md:gap-10 w-full xl:w-auto xl:border-r xl:border-white/10 xl:pr-10">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-[#0a2344] flex items-center justify-center shrink-0">
                        <i class="fas fa-phone-alt text-[#3b82f6] text-sm" aria-hidden="true"></i>
                    </div>
                    <div>
                        <a href="tel:19005155"
                            class="text-[15px] font-bold text-white hover:text-[#3b82f6] m-0 no-underline block"
                            title="Gọi Tổng đài">1900 5155</a>
                        <p class="text-xs text-gray-400 m-0 mt-0.5">Tổng đài hỗ trợ</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-[#0a2344] flex items-center justify-center shrink-0">
                        <i class="fas fa-envelope text-[#3b82f6] text-sm" aria-hidden="true"></i>
                    </div>
                    <div>
                        <a href="mailto: info.dailyve@gmail.com"
                            class="text-[15px] font-bold text-white hover:text-[#3b82f6] m-0 no-underline block"
                            title="Gửi Email">info.dailyve@gmail.com</a>
                        <p class="text-xs text-gray-400 m-0 mt-0.5">Email hỗ trợ</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-[#0a2344] flex items-center justify-center shrink-0">
                        <i class="fas fa-clock text-[#3b82f6] text-sm" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-[15px] font-bold text-white m-0">Phục vụ khách hàng 24/7</p>
                        <p class="text-xs text-gray-400 m-0 mt-0.5">Kể cả ngày lễ & Tết</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row xl:flex-col justify-center gap-3 shrink-0">
                <p class="text-[13px] text-white font-bold m-0 sm:w-full">Tải ứng dụng Dailyve</p>
                <div class="flex items-center gap-3">
                    <a href="https://apps.apple.com/vn/app/dailyve-%C4%91%E1%BA%B7t-v%C3%A9-xe-24-7/id6748101538"
                        aria-label="Tải ứng dụng trên App Store" title="App Store"
                        class="hover:opacity-90 transition-opacity block rounded-lg overflow-hidden border border-white/20 bg-black">
                        <img src="{{ esc_url($appStore) }}" alt="Tải trên App Store" loading="lazy" decoding="async"
                            width="135" height="40" class="w-[135px] h-[40px] object-cover">
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.dailyve"
                        aria-label="Tải ứng dụng trên Google Play" title="Google Play"
                        class="hover:opacity-90 transition-opacity block rounded-lg overflow-hidden border border-white/20 bg-black">
                        <img src="{{ esc_url($googlePlay) }}" alt="Tải trên Google Play" loading="lazy"
                            decoding="async" width="135" height="40" class="w-[135px] h-[40px] object-cover">
                    </a>
                </div>
            </div>
        </div>

        <hr class="border-white/10 m-0">

        {{-- Bottom Section: Copyright & Payments --}}
        <div class="pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[12px] text-gray-400 m-0 order-2 md:order-1 text-center md:text-left">
                &copy; {{ date('Y') }} Dailyve. Bản quyền thuộc về Dailyve.
            </p>

            <div class="flex items-center gap-3 order-1 md:order-2 flex-wrap justify-center">
                <span class="text-[12px] text-gray-400 mr-2">Thanh toán an toàn với</span>
                <span
                    class="inline-flex items-center justify-center h-[26px] px-3 rounded text-white text-[11px] font-bold tracking-wide bg-[#1a1f71] select-none">VISA</span>
                <span
                    class="inline-flex items-center justify-center h-[26px] px-3 rounded text-white text-[11px] font-bold tracking-wide bg-[#eb001b] select-none"
                    style="background: linear-gradient(90deg, #eb001b 0%, #f79e1b 100%);">Mastercard</span>
                <span
                    class="inline-flex items-center justify-center h-[26px] px-3 rounded text-white text-[11px] font-bold tracking-wide bg-[#a50064] select-none">MoMo</span>
                <span
                    class="inline-flex items-center justify-center h-[26px] px-3 rounded text-white text-[11px] font-bold tracking-wide bg-[#0068ff] select-none">ZaloPay</span>
            </div>
        </div>

    </div>
</footer>

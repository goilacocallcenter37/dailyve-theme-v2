@php
    $whiteLogo = home_url('/wp-content/uploads/2025/05/logo-dlv-white.png');
    $appStore = home_url('/wp-content/uploads/2025/01/download-app-store.png');
    $googlePlay = home_url('/wp-content/uploads/2025/01/download-gg-play.png');
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

<footer class="dailyve-footer" style="background: linear-gradient(180deg, #031a38 0%, #052a52 100%);">
    {{-- Top Section: Brand + Nav Groups --}}
    <div class="dailyve-container py-12 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1.35fr_repeat(4,minmax(0,1fr))] gap-8 lg:gap-9">

            {{-- Brand Column --}}
            <div class="space-y-4">
                <a href="{{ esc_url(home_url('/')) }}" aria-label="Dailyve" class="inline-block">
                    <img src="{{ esc_url($whiteLogo) }}" alt="Dailyve" class="w-[132px] h-auto">
                </a>
                <p class="text-[13px] font-bold text-white/85 leading-relaxed max-w-xs">
                    Đặt vé xe khách, tàu hỏa, máy bay nhanh chóng với giá minh bạch và hỗ trợ 24/7.
                </p>

                {{-- Trust Badges --}}
                <div class="flex flex-wrap items-center gap-3 text-[13px] font-semibold text-white/80">
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fas fa-shield-alt text-xs text-emerald-400" aria-hidden="true"></i>
                        Thanh toán an toàn
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fas fa-headset text-xs text-sky-400" aria-hidden="true"></i>
                        Hỗ trợ 24/7
                    </span>
                </div>

                {{-- Social Icons --}}
                <div class="flex items-center gap-3 pt-1">
                    <a href="#" aria-label="Facebook" class="w-[34px] h-[34px] inline-flex items-center justify-center rounded-full bg-white text-[#075bd1] hover:bg-white/90 transition-colors duration-200">
                        <i class="fab fa-facebook-f text-sm" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="YouTube" class="w-[34px] h-[34px] inline-flex items-center justify-center rounded-full bg-white text-[#075bd1] hover:bg-white/90 transition-colors duration-200">
                        <i class="fab fa-youtube text-sm" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="w-[34px] h-[34px] inline-flex items-center justify-center rounded-full bg-white text-[#075bd1] hover:bg-white/90 transition-colors duration-200">
                        <i class="fab fa-instagram text-sm" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="TikTok" class="w-[34px] h-[34px] inline-flex items-center justify-center rounded-full bg-white text-[#075bd1] hover:bg-white/90 transition-colors duration-200">
                        <i class="fab fa-tiktok text-sm" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            {{-- Navigation Columns --}}
            @foreach ($footerGroups as $group)
                <nav aria-label="{{ esc_attr($group['label']) }}" class="space-y-3">
                    <h2 class="text-[15px] font-black text-white m-0">{{ $group['label'] }}</h2>
                    <div class="flex flex-col gap-2.5">
                        @foreach ($group['links'] as $link)
                            <a href="{{ esc_url($link['url']) }}" 
                               class="text-[13px] font-semibold text-white/85 hover:text-white transition-colors duration-200 no-underline">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </nav>
            @endforeach
        </div>
    </div>

    {{-- Middle Section: Contact + App Downloads --}}
    <div class="dailyve-container">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 py-6 border-t border-white/[0.14]">
            {{-- Contact Info --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-[13px] font-semibold text-white/85" aria-label="Thông tin liên hệ">
                <a href="tel:19005155" class="inline-flex items-center gap-2 text-white/85 hover:text-white no-underline transition-colors duration-200">
                    <i class="fas fa-phone-alt text-xs text-emerald-400" aria-hidden="true"></i>
                    1900 5155
                </a>
                <a href="mailto:support@dailyve.com" class="inline-flex items-center gap-2 text-white/85 hover:text-white no-underline transition-colors duration-200">
                    <i class="fas fa-envelope text-xs text-sky-400" aria-hidden="true"></i>
                    support@dailyve.com
                </a>
                <span class="inline-flex items-center gap-2">
                    <i class="fas fa-clock text-xs text-amber-400" aria-hidden="true"></i>
                    Phục vụ khách hàng 24/7
                </span>
            </div>

            {{-- App Download Badges --}}
            <div class="flex items-center gap-3" aria-label="Tải ứng dụng Dailyve">
                <img src="{{ esc_url($appStore) }}" alt="Tải trên App Store" loading="lazy" class="w-[132px] h-auto block">
                <img src="{{ esc_url($googlePlay) }}" alt="Tải trên Google Play" loading="lazy" class="w-[132px] h-auto block">
            </div>
        </div>
    </div>

    {{-- Bottom Section: Copyright + Payments --}}
    <div class="dailyve-container">
        <div class="flex flex-wrap items-center justify-between gap-4 py-4 pb-6 border-t border-white/[0.14]">
            <p class="m-0 text-xs font-bold text-white/85">
                © {{ date('Y') }} Dailyve. Bản quyền thuộc về Dailyve.
            </p>

            {{-- Payment Methods --}}
            <div class="flex flex-wrap items-center gap-2.5 text-xs font-bold text-white/85" aria-label="Phương thức thanh toán">
                <span>Thanh toán an toàn với</span>
                <span class="inline-flex items-center h-7 px-2.5 rounded-md text-white text-[11px] font-black tracking-wide bg-[#1a1f71]">VISA</span>
                <span class="inline-flex items-center h-7 px-2.5 rounded-md text-white text-[11px] font-black tracking-wide bg-[#eb001b]">Mastercard</span>
                <span class="inline-flex items-center h-7 px-2.5 rounded-md text-white text-[11px] font-black tracking-wide bg-[#a50064]">MoMo</span>
                <span class="inline-flex items-center h-7 px-2.5 rounded-md text-white text-[11px] font-black tracking-wide bg-[#0068ff]">ZaloPay</span>
            </div>
        </div>
    </div>
</footer>

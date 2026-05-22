@php
    $logo = home_url('/wp-content/themes/dailyve-theme/resources/images/logo-dailyve.png');
    $appStore = home_url('/wp-content/themes/dailyve-theme/resources/images/download-app-store.png');
    $googlePlay = home_url('/wp-content/themes/dailyve-theme/resources/images/download-gg-play.png');

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
                ['label' => 'Xem tất cả tuyến', 'url' => home_url('/ve-xe-khach/tuyen-duong/')],
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

    $socialLinks = [
        ['label' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => '#'],
        ['label' => 'TikTok', 'icon' => 'fab fa-tiktok', 'url' => '#'],
        ['label' => 'YouTube', 'icon' => 'fab fa-youtube', 'url' => '#'],
        ['label' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => '#'],
    ];

    $contactItems = [
        ['label' => '1900 5155', 'meta' => 'Tổng đài hỗ trợ', 'icon' => 'fas fa-phone-alt', 'url' => 'tel:19005155'],
        ['label' => 'support@dailyve.com', 'meta' => 'Email hỗ trợ', 'icon' => 'far fa-envelope', 'url' => 'mailto:support@dailyve.com'],
        ['label' => 'Phục vụ khách hàng 24/7', 'meta' => 'Kể cả ngày lễ & Tết', 'icon' => 'far fa-clock', 'url' => null],
    ];
@endphp

<footer class="bg-[#041224] text-white border-t border-white/5">
    <div class="dailyve-container pt-12 pb-6 md:pt-16">
        
        {{-- Top Section: Logo & Links Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 mb-12">
            
            {{-- Left Column: Brand & Trust --}}
            <div class="lg:col-span-4 flex flex-col gap-6 lg:border-r lg:border-white/10 lg:pr-10">
                <a href="{{ esc_url(home_url('/')) }}" class="inline-block" aria-label="Trang chủ Dailyve">
                    <img src="{{ esc_url($logo) }}" alt="Dailyve Logo" class="h-10 w-auto object-contain">
                </a>
                <p class="text-[13px] text-gray-300 leading-relaxed m-0">
                    Đặt vé xe khách, tàu hỏa, máy bay nhanh chóng với giá minh bạch và hỗ trợ 24/7.
                </p>

                <div class="grid gap-4 mt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full border border-[#1677ff]/30 bg-[#06152c] flex items-center justify-center shrink-0">
                            <i class="fas fa-shield-alt text-[#3b82f6]" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-white m-0">Thanh toán an toàn</p>
                            <p class="text-xs text-gray-400 m-0 mt-0.5">Bảo mật tuyệt đối</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full border border-[#1677ff]/30 bg-[#06152c] flex items-center justify-center shrink-0">
                            <i class="fas fa-headset text-[#3b82f6]" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-white m-0">Hỗ trợ 24/7</p>
                            <p class="text-xs text-gray-400 m-0 mt-0.5">Luôn sẵn sàng phục vụ</p>
                        </div>
                    </div>
                </div>

                {{-- Socials --}}
                <div class="flex items-center gap-3 pt-2">
                    @foreach ($socialLinks as $social)
                        <a href="{{ esc_url($social['url']) }}" aria-label="{{ esc_attr($social['label']) }}" class="w-9 h-9 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors">
                            <i class="{{ esc_attr($social['icon']) }} text-sm" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right Columns: Links --}}
            <div class="lg:col-span-8 grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ($footerGroups as $group)
                    <div>
                        <h3 class="text-[14px] font-bold text-white m-0 pb-3 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-6 after:h-[2px] after:bg-[#3b82f6]">{{ $group['label'] }}</h3>
                        <ul class="flex flex-col gap-3.5 m-0 p-0 list-none mt-5">
                            @foreach ($group['links'] as $link)
                                <li>
                                    <a href="{{ esc_url($link['url']) }}" class="text-[13px] text-gray-300 hover:text-white transition-colors flex items-center gap-2 group no-underline">
                                        <i class="fas fa-chevron-right text-[10px] text-[#3b82f6] transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Middle Section: Contact & Apps --}}
        <div class="py-8 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-8 xl:gap-4 border-t border-white/10">
            <div class="flex flex-col md:flex-row gap-6 md:gap-8 w-full xl:w-auto">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#06152c] border border-white/10 flex items-center justify-center shrink-0">
                        <i class="fas fa-phone-alt text-[#3b82f6] text-sm" aria-hidden="true"></i>
                    </div>
                    <div>
                        <a href="tel:19005155" class="text-[14px] font-bold text-white hover:text-[#3b82f6] m-0 no-underline block">1900 5155</a>
                        <p class="text-xs text-gray-400 m-0 mt-0.5">Tổng đài hỗ trợ</p>
                    </div>
                </div>
                
                <div class="hidden md:block w-px h-10 bg-white/10"></div>
                
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#06152c] border border-white/10 flex items-center justify-center shrink-0">
                        <i class="fas fa-envelope text-[#3b82f6] text-sm" aria-hidden="true"></i>
                    </div>
                    <div>
                        <a href="mailto:support@dailyve.com" class="text-[14px] font-bold text-white hover:text-[#3b82f6] m-0 no-underline block">support@dailyve.com</a>
                        <p class="text-xs text-gray-400 m-0 mt-0.5">Email hỗ trợ</p>
                    </div>
                </div>
                
                <div class="hidden md:block w-px h-10 bg-white/10"></div>
                
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#06152c] border border-white/10 flex items-center justify-center shrink-0">
                        <i class="fas fa-clock text-[#3b82f6] text-sm" aria-hidden="true"></i>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-white m-0">Phục vụ khách hàng 24/7</p>
                        <p class="text-xs text-gray-400 m-0 mt-0.5">Kể cả ngày lễ & Tết</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row xl:flex-row items-center gap-4 shrink-0 xl:border-l xl:border-white/10 xl:pl-8">
                <p class="text-[13px] text-white font-bold m-0 text-center sm:text-left">Tải ứng dụng Dailyve</p>
                <div class="flex items-center gap-3">
                    <a href="https://apps.apple.com/vn/app/dailyve-%C4%91%E1%BA%B7t-v%C3%A9-xe-24-7/id6748101538" aria-label="App Store" class="hover:opacity-90 transition-opacity block rounded-lg overflow-hidden border border-white/20 bg-[#06152c]">
                        <img src="{{ esc_url($appStore) }}" alt="App Store" class="w-[125px] h-[38px] object-cover">
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.dailyve" aria-label="Google Play" class="hover:opacity-90 transition-opacity block rounded-lg overflow-hidden border border-white/20 bg-[#06152c]">
                        <img src="{{ esc_url($googlePlay) }}" alt="Google Play" class="w-[125px] h-[38px] object-cover">
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom Section: Copyright & Payment --}}
        <div class="pt-6 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[12px] text-gray-400 m-0 text-center md:text-left">
                &copy; {{ date('Y') }} Dailyve. Bản quyền thuộc về Dailyve.
            </p>

            <div class="flex items-center gap-3 flex-wrap justify-center">
                <span class="text-[12px] text-gray-400 mr-2">Thanh toán an toàn với</span>
                <span class="inline-flex items-center justify-center h-[26px] px-3 rounded text-white text-[11px] font-bold tracking-wide bg-[#1a1f71]">VISA</span>
                <span class="inline-flex items-center justify-center h-[26px] px-3 rounded text-white text-[11px] font-bold tracking-wide" style="background: linear-gradient(90deg, #eb001b 0%, #f79e1b 100%);">Mastercard</span>
                <span class="inline-flex items-center justify-center h-[26px] px-3 rounded text-white text-[11px] font-bold tracking-wide bg-[#a50064]">MoMo</span>
                <span class="inline-flex items-center justify-center h-[26px] px-3 rounded text-white text-[11px] font-bold tracking-wide bg-[#0068ff]">ZaloPay</span>
            </div>
        </div>

    </div>
</footer>

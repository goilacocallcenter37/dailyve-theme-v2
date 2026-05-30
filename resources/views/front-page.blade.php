@extends('layouts.app')

@php
    $upload = fn($path) => home_url('/wp-content/uploads/' . ltrim($path, '/'));
    $screenHome = home_url('/wp-content/themes/dailyve-theme/resources/images/screen_home_dailyve.jpg');
    $hero_background_url = \App\dailyve_home_hero_background_url();
    $hero_background_style = $hero_background_url
        ? sprintf('--dailyve-hero-bg-image: url("%s");', esc_url($hero_background_url))
        : '';

    $offers = array_slice(\App\dailyve_get_mock_coupons(), 0, 6);

    $serviceTypes = [
        [
            'id' => 'bus',
            'label' => 'Xe khách',
            'icon' => $upload('images/bus-icon-web.png'),
            'tabs' => [
                'intro' => [
                    'label' => 'Giới thiệu',
                    'text' =>
                        'Dailyve hỗ trợ đặt vé xe khách trực tuyến với hàng trăm tuyến đường trên khắp Việt Nam. Hệ thống kết nối hơn 100 nhà xe uy tín, đa dạng dòng xe và khung giờ di chuyển, giúp bạn dễ dàng chọn tuyến, chọn ghế, so sánh giá và đặt vé nhanh chóng.',
                ],
                'routes' => [
                    'label' => 'Tuyến nổi bật',
                    'text' =>
                        'Hà Nội – Sapa, TP.HCM – Đà Lạt, Đà Nẵng – Huế, Hà Nội – Hải Phòng và nhiều tuyến phổ biến khác được cập nhật giá theo thời gian thực.',
                ],
                'operators' => [
                    'label' => 'Nhà xe',
                    'text' =>
                        'Hợp tác với hàng trăm nhà xe uy tín: Phương Trang, Hoàng Long, Kumho Samco, Futa Bus Lines và nhiều đối tác chất lượng trên toàn quốc.',
                ],
            ],
            'link' => home_url('/ve-xe-khach/'),
        ],
        [
            'id' => 'plane',
            'label' => 'Máy bay',
            'icon' => $upload('images/plane-icon-web.png'),
            'tabs' => [
                'intro' => [
                    'label' => 'Giới thiệu',
                    'text' =>
                        'Đặt vé máy bay nội địa và quốc tế với giá minh bạch, hỗ trợ chọn hành trình, hành lý và dịch vụ bổ sung ngay trên Dailyve.',
                ],
                'routes' => [
                    'label' => 'Tuyến nổi bật',
                    'text' =>
                        'Hà Nội – TP.HCM, Đà Nẵng – Nha Trang, Cần Thơ – Phú Quốc và các chặng bay thường xuyên được ưu đãi mỗi tuần.',
                ],
                'operators' => [
                    'label' => 'Hãng bay',
                    'text' =>
                        'Kết nối Vietnam Airlines, Vietjet Air, Bamboo Airways, Pacific Airlines và các hãng bay quốc tế đối tác.',
                ],
            ],
            'link' => home_url('/ve-may-bay/'),
        ],
        [
            'id' => 'train',
            'label' => 'Tàu hỏa',
            'icon' => $upload('images/train-icon-web.png'),
            'tabs' => [
                'intro' => [
                    'label' => 'Giới thiệu',
                    'text' =>
                        'Đặt vé tàu hỏa trực tuyến, chọn toa và chỗ ngồi dễ dàng. Dailyve đồng bộ lịch tàu và hỗ trợ thanh toán an toàn 24/7.',
                ],
                'routes' => [
                    'label' => 'Tuyến nổi bật',
                    'text' =>
                        'Hà Nội – TP.HCM, Hà Nội – Đà Nẵng, Hà Nội – Lào Cai, Sài Gòn – Nha Trang là những tuyến được đặt nhiều nhất.',
                ],
                'operators' => [
                    'label' => 'Đối tác',
                    'text' =>
                        'Phục vụ đầy đủ các loại toa: ghế ngồi, giường nằm, khoang VIP trên mạng đường sắt quốc gia.',
                ],
            ],
            'link' => home_url('/ve-tau-hoa/'),
        ],
    ];

    $reasons = [
        [
            'title' => 'An Toàn - Tiện Lợi',
            'desc' => 'Hệ thống bảo mật cao, thông tin minh bạch và quy trình đặt vé rõ ràng.',
            'icon' => 'fas fa-shield-alt',
        ],
        [
            'title' => 'Giá Vé Ưu Đãi',
            'desc' => 'Giá cạnh tranh, nhiều mã giảm giá và chương trình ưu đãi độc quyền.',
            'icon' => 'fas fa-wallet',
        ],
        [
            'title' => '1500+ Đối Tác Vận Tải',
            'desc' => 'Mạng lưới nhà xe, hãng bay và tàu hỏa phủ khắp cả nước.',
            'icon' => 'fas fa-bus',
        ],
        [
            'title' => 'Luôn Hỗ Trợ 24/7',
            'desc' => 'Đội ngũ chăm sóc khách hàng sẵn sàng hỗ trợ mọi lúc, mọi nơi.',
            'icon' => 'fas fa-headset',
        ],
    ];

    $acf_press_slider = function_exists('get_field') ? get_field('slider_bao_chi', 'option') : [];
    $press = [];
    if (!empty($acf_press_slider) && is_array($acf_press_slider)) {
        foreach ($acf_press_slider as $item) {
            $image_url = '';
            if (!empty($item['img'])) {
                $image_url = is_array($item['img']) ? $item['img']['url'] ?? '' : $item['img'];
            }
            $link_url = '#';
            if (!empty($item['link'])) {
                $link_url = is_array($item['link']) ? $item['link']['url'] ?? '#' : $item['link'];
            }
            $press[] = [
                'brand' => '',
                'title' => $item['title'] ?? '',
                'image' => $image_url,
                'url' => $link_url,
            ];
        }
    }

    $testimonials = [
        [
            'name' => 'Nguyễn Minh Anh',
            'city' => 'Hà Nội',
            'quote' =>
                'Đặt vé trên Dailyve rất nhanh chóng, giao diện dễ dùng, nhiều ưu đãi hấp dẫn. Mình sẽ tiếp tục ủng hộ!',
        ],
        [
            'name' => 'Trần Quốc Bảo',
            'city' => 'Đà Nẵng',
            'quote' =>
                'Tôi thường xuyên đặt vé xe và vé máy bay qua Dailyve. Giá tốt, hỗ trợ nhiệt tình, rất hài lòng!',
        ],
        [
            'name' => 'Lê Thị Thu Hương',
            'city' => 'TP. Hồ Chí Minh',
            'quote' => 'Ứng dụng ổn định, thanh toán tiện lợi, ưu đãi tới ngay. Khuyến khích mọi người sử dụng!',
        ],
        [
            'name' => 'Phạm Văn Đức',
            'city' => 'Cần Thơ',
            'quote' => 'Tìm vé và so sánh giá rất tiện. Đội hỗ trợ phản hồi nhanh khi cần đổi lịch chuyến đi.',
        ],
    ];

    $customerReviews = [
        [
            'name' => 'Nguyễn Minh Anh',
            'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
            'rating' => 5,
            'quote' => 'Đặt vé rất nhanh, giao diện dễ dùng. Mọi thứ đều tiện lợi!',
        ],
        [
            'name' => 'Trần Quốc Bảo',
            'avatar' => 'https://randomuser.me/api/portraits/men/46.jpg',
            'rating' => 5,
            'quote' => 'Săn ưu đãi tiện hơn trên app. Nhiều chương trình hấp dẫn!',
        ],
        [
            'name' => 'Lê Thu Hà',
            'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
            'rating' => 5,
            'quote' => 'Tìm chuyến và thanh toán rất mượt. Trải nghiệm tuyệt vời!',
        ],
        [
            'name' => 'Phạm Hoàng Nam',
            'avatar' => 'https://randomuser.me/api/portraits/men/22.jpg',
            'rating' => 5,
            'quote' => 'Hỗ trợ nhanh chóng 24/7. Mình rất yên tâm khi đặt vé.',
        ],
        [
            'name' => 'Đỗ Thanh Vy',
            'avatar' => 'https://randomuser.me/api/portraits/women/12.jpg',
            'rating' => 5,
            'quote' => 'Vé điện tử rõ ràng, không phải chờ lâu khi cần đổi lịch.',
        ],
        [
            'name' => 'Huỳnh Tuấn Anh',
            'avatar' => 'https://randomuser.me/api/portraits/men/33.jpg',
            'rating' => 5,
            'quote' => 'Thường xuyên có mã giảm giá. Đặt vé chưa bao giờ rẻ và dễ đến thế.',
        ],
        [
            'name' => 'Huỳnh Quốc Cường',
            'avatar' => 'https://randomuser.me/api/portraits/men/23.jpg',
            'rating' => 5,
            'quote' => 'Hỗ trợ nhanh chóng 24/7. Mình rất yên tâm khi đặt vé.',
        ],
    ];

    $reviewStats = [
        ['value' => '1.000.000+', 'label' => 'Khách hàng tin tưởng', 'icon' => 'fas fa-users'],
        ['value' => '5.000.000+', 'label' => 'Vé đã được đặt', 'icon' => 'fas fa-ticket-alt'],
        ['value' => '4.8/5', 'label' => 'Đánh giá trung bình', 'icon' => 'fas fa-star'],
        ['value' => '24/7', 'label' => 'Hỗ trợ tận tâm', 'icon' => 'fas fa-headset'],
    ];

    $appBenefits = [
        ['label' => 'Ưu đãi độc quyền', 'icon' => 'fas fa-percent'],
        ['label' => 'Đặt vé siêu nhanh', 'icon' => 'fas fa-bolt'],
        ['label' => 'An toàn & bảo mật', 'icon' => 'fas fa-shield-alt'],
    ];
@endphp

@section('content')
    <article class="dailyve-home">
        <section class="dailyve-hero relative overflow-visible grid grid-cols-1 grid-rows-1 place-items-center"
            aria-labelledby="dailyve-home-title">
            @if ($hero_background_url)
                <img src="{{ esc_url($hero_background_url) }}" alt="Dailyve Banner"
                    class="col-start-1 row-start-1 w-full h-full object-cover md:object-contain min-h-[350px] md:min-h-0">
            @else
                <div class="col-start-1 row-start-1 w-full h-full bg-[#dff4ff] min-h-[400px]"></div>
            @endif

            <div
                class="col-start-1 row-start-1 w-full flex flex-col justify-center items-center pointer-events-none py-8 z-10">
                <div class="dailyve-container dailyve-hero__search pointer-events-auto w-full">
                    {!! do_shortcode('[react_search_form]') !!}
                </div>
            </div>
        </section>

        <!-- Offers Section -->
        <section class="dailyve-section dailyve-offers" aria-labelledby="dailyve-offers-title">
            <div class="dailyve-container dailyve-offers__layout">
                <div class="dailyve-offers__intro">
                    <h2 id="dailyve-offers-title">
                        <span class="title-main">Ưu đãi</span>
                        <span class="title-badge-row">
                            <span class="hot-badge">HOT</span>
                        </span>
                        <span class="title-sub">cho người đẹp</span>
                    </h2>

                    <div class="dailyve-offers__ticket-art" aria-hidden="true">
                        <div class="ticket-art-container">
                            <div class="ticket-art-main">
                                <div class="ticket-art-bus">
                                    <i class="fas fa-bus"></i>
                                </div>
                                <div class="ticket-art-divider"></div>
                                <div class="ticket-art-lines">
                                    <span class="line-1"></span>
                                    <span class="line-2"></span>
                                    <span class="line-3"></span>
                                </div>
                                <img src="{{ esc_url(home_url('/wp-content/themes/dailyve-theme/resources/images/icon_fire.webp')) }}"
                                    alt="Ưu đãi Hot" width="120" height="120" loading="lazy" decoding="async"
                                    class="absolute -right-[20px] -bottom-[60px] w-24 h-24 md:w-30 md:h-30 object-contain z-20 drop-shadow-md">
                            </div>
                            <div class="ticket-art-sparkle sparkle-1">✦</div>
                            <div class="ticket-art-sparkle sparkle-2">✦</div>
                            <div class="ticket-art-sparkle sparkle-3">✦</div>
                        </div>
                    </div>
                </div>

                <div class="dailyve-offer-grid">
                    @foreach ($offers as $offer)
                        <article class="dailyve-offer-card">
                            <div class="dailyve-offer-card__body">
                                <p class="dailyve-offer-card__discount">Giảm Giá {{ $offer['discount'] }}</p>
                                <p class="dailyve-offer-card__label">{{ $offer['label'] }}</p>
                                <button type="button" class="dailyve-offer-card__save"
                                    data-code="{{ esc_attr($offer['code']) }}">
                                    Lưu Mã
                                </button>
                            </div>
                            <div class="dailyve-offer-card__badge" aria-hidden="true">
                                <span>%</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="dailyve-container dailyve-offers__footer">
                <a class="dailyve-btn dailyve-btn--outline-light" href="{{ esc_url(home_url('/khuyen-mai/')) }}">
                    Xem tất cả ưu đãi
                </a>
            </div>
        </section>

        <!-- Services Section -->
        <section class="dailyve-section dailyve-services" aria-labelledby="dailyve-services-title" data-home-services>
            <div class="dailyve-container">
                <div class="dailyve-services__head">
                    <h2 id="dailyve-services-title"><span>Dailyve</span> cung cấp<br>những dịch vụ gì?</h2>
                    <div class="dailyve-services__types" role="tablist" aria-label="Loại dịch vụ">
                        @foreach ($serviceTypes as $index => $type)
                            <button type="button" class="dailyve-services__type{{ $index === 0 ? ' is-active' : '' }}"
                                role="tab" aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                data-service-type="{{ esc_attr($type['id']) }}">
                                <span class="dailyve-services__type-label">{{ $type['label'] }}</span>
                                <img src="{{ esc_url($type['icon']) }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                </div>

                @foreach ($serviceTypes as $index => $type)
                    <div class="dailyve-services__panel{{ $index === 0 ? ' is-active' : '' }}"
                        data-service-panel="{{ esc_attr($type['id']) }}" @if ($index !== 0) hidden @endif>
                        <div class="dailyve-services__tabs" role="tablist" aria-label="Nội dung dịch vụ">
                            @foreach ($type['tabs'] as $tabKey => $tab)
                                <button type="button" class="dailyve-services__tab{{ $loop->first ? ' is-active' : '' }}"
                                    role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    data-service-tab="{{ esc_attr($tabKey) }}">
                                    {{ $tab['label'] }}
                                </button>
                            @endforeach
                        </div>

                        <div class="dailyve-services__content">
                            <div class="dailyve-services__map" aria-hidden="true">
                                <svg class="map-curve-svg" viewBox="0 0 1000 200" fill="none" preserveAspectRatio="none">
                                    <path d="M 80 120 Q 250 20, 500 120 T 920 120" stroke="#cbd5e1" stroke-width="2.5"
                                        stroke-dasharray="6,8" stroke-linecap="round" />
                                </svg>
                                <div class="map-pin map-pin--left">
                                    <div class="map-pin-badge">
                                        <div class="map-pin-marker">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="map-pin map-pin--right">
                                    <div class="map-pin-badge">
                                        <div class="map-pin-marker">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach ($type['tabs'] as $tabKey => $tab)
                                <div class="dailyve-services__text{{ $loop->first ? ' is-active' : '' }}"
                                    data-service-content="{{ esc_attr($tabKey) }}"
                                    @if (!$loop->first) hidden @endif>
                                    <p>{{ $tab['text'] }}</p>
                                </div>
                            @endforeach

                            <a class="dailyve-btn dailyve-btn--accent" href="{{ esc_url($type['link']) }}">Xem thêm</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Reasons Section -->
        <section class="dailyve-section dailyve-reasons bg-[#1374ed] py-14 md:py-20 relative overflow-hidden"
            aria-labelledby="dailyve-reasons-title">
            <!-- Decorative Background -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden flex justify-center items-center">
                <div
                    class="absolute w-full h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent opacity-60">
                </div>
                <i class="fas fa-plane text-white/10 text-6xl absolute top-12 right-8 md:right-24 -rotate-45"></i>
            </div>

            <div class="dailyve-container relative z-10">
                <div class="text-center mb-12 md:mb-16">
                    <h2 id="dailyve-reasons-title"
                        class="text-[26px] md:text-[42px] font-black text-white mb-5 flex flex-col md:flex-row flex-wrap justify-center items-center gap-x-4 gap-y-2 md:gap-y-3 !border-0 !pb-0 !after:hidden tracking-tight">
                        <span
                            class="bg-[#ffbe1a] text-[#0a1e40] px-6 md:px-8 py-1.5 md:py-2 rounded-[40px] shadow-[0_8px_24px_rgba(255,190,26,0.35)] leading-none uppercase mb-1 md:mb-0">TẠI
                            SAO</span>
                        <span class="text-center leading-snug">nên sử dụng dịch vụ của<br class="md:hidden">
                            Dailyve?</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                    @foreach ($reasons as $reason)
                        <article
                            class="bg-white rounded-[20px] p-5 md:p-8 flex flex-row items-start sm:items-center gap-4 sm:gap-6 shadow-[0_12px_36px_rgba(0,0,0,0.08)] transition-transform hover:-translate-y-1">
                            <!-- Illustration Area -->
                            <div class="w-[70px] sm:w-[130px] flex items-center justify-center shrink-0 pt-1 sm:pt-0">
                                <i
                                    class="{{ esc_attr($reason['icon']) }} text-[45px] sm:text-[80px] md:text-[90px] text-[#1374ed]"></i>
                            </div>

                            <!-- Content Area -->
                            <div class="flex-1 text-left pt-0">
                                <div class="flex items-center justify-start gap-2.5 sm:gap-3 mb-2.5">
                                    <span
                                        class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 rounded-full bg-[#1374ed] text-white flex items-center justify-center font-bold text-[11px] sm:text-[13px] md:text-[14px] shrink-0">
                                        {{ sprintf('%02d', $loop->iteration) }}
                                    </span>
                                    <h3
                                        class="text-[16px] sm:text-[17px] md:text-[19px] font-bold text-[#0a1e40] leading-tight m-0">
                                        {{ $reason['title'] }}</h3>
                                </div>
                                <div class="w-6 sm:w-8 h-[2px] bg-[#1374ed] mb-2 sm:mb-3 mx-0"></div>
                                <p class="text-slate-800 text-[13px] sm:text-[14px] md:text-[15px] leading-relaxed m-0">
                                    {{ $reason['desc'] }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div
                    class="mt-14 flex items-center justify-center gap-2.5 text-white/95 text-[14px] md:text-[15px] font-medium border-t border-white/20 pt-6 max-w-lg mx-auto">
                    <i class="fas fa-shield-alt text-white/90 text-[18px]"></i>
                    <span>Dailyve - Đồng hành cùng bạn trên mọi hành trình</span>
                </div>
            </div>
        </section>

        <!-- Press Section -->
        <section class="dailyve-section dailyve-press bg-[#f8f9fa] py-12 md:py-16" aria-labelledby="dailyve-press-title">
            <div class="dailyve-container relative">
                <div class="text-center mb-12 md:mb-16">
                    <h2 id="dailyve-press-title"
                        class="text-[30px] md:text-[42px] font-black text-[#0a1e40] mb-5 flex flex-wrap justify-center items-center gap-x-4 gap-y-2 !border-0 !pb-0 !after:hidden tracking-tight">
                        <span
                            class="bg-[#ffbe1a] text-[#0a1e40] px-6 md:px-8 py-2 rounded-[40px] shadow-[0_8px_24px_rgba(255,190,26,0.35)] leading-none uppercase">BÁO
                            CHÍ</span>
                        <span>nói gì về Dailyve?</span>
                    </h2>
                    <p class="text-[#64748b] text-[15px] md:text-[16px] max-w-2xl mx-auto leading-relaxed">
                        Những đánh giá và tin tức nổi bật từ các cơ quan báo chí uy tín<br class="hidden md:block"> về hành
                        trình phát triển của Dailyve.
                    </p>
                </div>

                <div class="dailyve-press__carousel-wrapper relative">
                    <button type="button"
                        class="dailyve-press__nav dailyve-press__nav--prev absolute left-0 md:-left-5 top-1/2 -translate-y-1/2 z-10 w-10 h-10 md:w-11 md:h-11 bg-white rounded-full flex items-center justify-center shadow-[0_2px_12px_rgba(0,0,0,0.1)] text-[#0068ff] hover:bg-slate-50 transition-colors"
                        aria-label="Trước" data-press-nav-prev>
                        <i class="fas fa-chevron-left text-[14px]"></i>
                    </button>
                    <div class="dailyve-press__track scrollbar-none flex gap-6 overflow-x-auto snap-x snap-mandatory pb-4 px-2"
                        data-press-track>
                        @php
                            $bgStyles = [
                                [
                                    'wrapper' => 'bg-gradient-to-br from-[#fdf2f8] to-[#fce7f3]',
                                    'shapes' =>
                                        '<div class="absolute -left-10 -bottom-10 w-40 h-40 rounded-full border-[20px] border-white/40"></div><div class="absolute -left-20 -bottom-20 w-56 h-56 rounded-full border-[20px] border-white/20"></div>',
                                ],
                                [
                                    'wrapper' => 'bg-gradient-to-br from-[#fff7ed] to-[#ffedd5]',
                                    'shapes' =>
                                        '<div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full border-[20px] border-white/40"></div><div class="absolute -right-20 -bottom-20 w-56 h-56 rounded-full border-[20px] border-white/20"></div>',
                                ],
                                [
                                    'wrapper' => 'bg-gradient-to-br from-[#f0f9ff] to-[#e0f2fe]',
                                    'shapes' =>
                                        '<div class="absolute -right-10 -top-10 w-40 h-40 rounded-full border-[20px] border-white/40"></div><div class="absolute -right-20 -top-20 w-56 h-56 rounded-full border-[20px] border-white/20"></div>',
                                ],
                            ];
                        @endphp
                        @foreach ($press as $item)
                            @php
                                $bgStyle = $bgStyles[$loop->index % count($bgStyles)];
                            @endphp
                            <article
                                class="dailyve-press-card flex flex-col rounded-[20px] bg-white border border-slate-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] overflow-hidden min-w-[85%] sm:min-w-[340px] lg:min-w-[calc((100%-48px)/3)] snap-start">
                                <!-- Image container -->
                                <div
                                    class="h-[150px] md:h-[180px] w-full {{ $bgStyle['wrapper'] }} flex items-center justify-center p-6 relative overflow-hidden border-b border-slate-100">
                                    {!! $bgStyle['shapes'] !!}
                                    <img src="{{ esc_url($item['image']) }}" alt="{{ esc_attr($item['title']) }}"
                                        class="max-h-full max-w-[75%] object-contain relative z-10 mix-blend-multiply"
                                        loading="lazy">
                                </div>

                                <!-- Content container -->
                                <div class="p-5 md:p-7 flex flex-col flex-1 bg-white text-left">
                                    <!-- Label -->
                                    <div class="flex items-center gap-1.5 mb-3">
                                        <i class="far fa-newspaper text-[#0068ff] text-[13px]" aria-hidden="true"></i>
                                        <span class="text-[13px] font-medium text-slate-500">Báo chí</span>
                                    </div>

                                    <!-- Title -->
                                    <h3
                                        class="text-[16px] md:text-[18px] font-bold text-[#0a1e40] leading-snug mb-6 line-clamp-3">
                                        {{ $item['title'] }}
                                    </h3>

                                    <!-- Footer -->
                                    <div
                                        class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100/80">
                                        <a href="{{ esc_url($item['url']) }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center bg-[#2196f3] hover:bg-[#1565c0] text-white text-[13px] font-semibold px-5 py-2.5 rounded-[10px] transition-colors">
                                            Xem bài viết <i class="fas fa-chevron-right ml-2 text-[10px]"
                                                aria-hidden="true"></i>
                                        </a>
                                        <div class="flex items-center gap-1.5 text-[12px] text-slate-400 font-medium">
                                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                            <span>{{ date('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <button type="button"
                        class="dailyve-press__nav dailyve-press__nav--next absolute right-0 md:-right-5 top-1/2 -translate-y-1/2 z-10 w-10 h-10 md:w-11 md:h-11 bg-white rounded-full flex items-center justify-center shadow-[0_2px_12px_rgba(0,0,0,0.1)] text-[#0068ff] hover:bg-slate-50 transition-colors"
                        aria-label="Tiếp" data-press-nav-next>
                        <i class="fas fa-chevron-right text-[14px]"></i>
                    </button>
                </div>

                <div class="dailyve-press__footer mt-10 md:mt-12 flex justify-center">
                    <a class="inline-flex items-center justify-center bg-[#ffc107] text-[#0a1e40] font-semibold text-[15px] px-8 py-3 rounded-full hover:-translate-y-0.5 hover:shadow-[0_6px_20px_rgba(255,193,7,0.4)] transition-all"
                        href="{{ esc_url(home_url('/tin-tuc/')) }}">Xem thêm</a>
                </div>
            </div>
        </section>

        @include('partials.home-app-reviews')

        @if (false)
            <!-- Merged App & Testimonials Band -->
            <section class="dailyve-merged-band" aria-labelledby="dailyve-merged-title">
                <div class="dailyve-container dailyve-merged-band__inner">
                    <!-- Left Column: App Downloads -->
                    <div class="dailyve-merged-band__left">
                        <h2 id="dailyve-merged-title">
                            <span>KHÁCH HÀNG</span> nói gì về Dailyve?
                        </h2>

                        <div class="dailyve-merged-band__app-downloads">
                            <div class="dailyve-app-download-item">
                                <p class="dailyve-app-download-label">Dailyve đã cập bến tại <strong>App Store</strong></p>
                                <a href="https://apps.apple.com/vn/app/dailyve-%C4%91%E1%BA%B7t-v%C3%A9-xe-24-7/id6748101538"
                                    class="dailyve-store-link">
                                    <img src="{{ esc_url($appStore) }}" alt="Tải trên App Store" loading="lazy">
                                </a>
                            </div>

                            <div class="dailyve-app-download-item">
                                <p class="dailyve-app-download-label">Và tại <strong>Play Store</strong> cũng có Dailyve
                                </p>
                                <a href="https://play.google.com/store/apps/details?id=com.dailyve"
                                    class="dailyve-store-link">
                                    <img src="{{ esc_url($googlePlay) }}" alt="Tải trên Google Play" loading="lazy">
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Center Column: iPhone Mockup -->
                    <div class="dailyve-merged-band__center" aria-hidden="true">
                        <div class="dailyve-phone-mockup">
                            <div class="dailyve-phone-screen">
                                <img src="{{ esc_url($screenHome) }}" alt="Dailyve App" loading="lazy">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Chat Bubble & QR Code -->
                    <div class="dailyve-merged-band__right">
                        <!-- Chat Bubble -->
                        <div class="dailyve-chat-bubble">
                            <span class="dailyve-chat-bubble__text">Tiện lợi quá điiii!</span>
                            <span class="dailyve-chat-bubble__subtext">Mười điểmmm!</span>
                        </div>

                        <!-- QR Code Card -->
                        <div class="dailyve-qr-card">
                            <div class="dailyve-qr-card__code">
                                <img src="https://object.dailyve.com/dailyve/wp-content/uploads/2025/08/QR-CODE-APP-DLV.png"
                                    alt="QR Code" loading="lazy">
                            </div>
                            <div class="dailyve-qr-card__info">
                                <p class="dailyve-qr-card__title">Quét mã để tải app</p>
                                <p class="dailyve-qr-card__subtitle">Hoặc tìm "Dailyve" trên App Store & Google Play</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </article>
@endsection

@extends('layouts.app')

@php
    $upload = fn ($path) => home_url('/wp-content/uploads/' . ltrim($path, '/'));

    $offers = [
        ['discount' => '10%', 'label' => 'Cho Người Mới', 'code' => 'DLVNEW10'],
        ['discount' => '50K', 'label' => 'Vé xe khách', 'code' => 'DAILY50'],
        ['discount' => '30K', 'label' => 'Vé tàu hỏa', 'code' => 'TAU30'],
        ['discount' => '100K', 'label' => 'Vé máy bay', 'code' => 'MAYBAY100'],
        ['discount' => '15%', 'label' => 'Khách sạn', 'code' => 'KS15'],
        ['discount' => '20K', 'label' => 'Đơn từ 200K', 'code' => 'DLV20'],
    ];

    $serviceTypes = [
        [
            'id' => 'bus',
            'label' => 'Xe khách',
            'icon' => $upload('images/front-of-bus.png'),
            'tabs' => [
                'intro' => [
                    'label' => 'Giới thiệu',
                    'text' => 'Dailyve hỗ trợ đặt vé xe khách trực tuyến với hàng trăm tuyến đường trên khắp Việt Nam. So sánh giá, chọn chỗ ngồi và thanh toán nhanh chóng trên mọi thiết bị.',
                ],
                'routes' => [
                    'label' => 'Tuyến nổi bật',
                    'text' => 'Hà Nội – Sapa, TP.HCM – Đà Lạt, Đà Nẵng – Huế, Hà Nội – Hải Phòng và nhiều tuyến phổ biến khác được cập nhật giá theo thời gian thực.',
                ],
                'operators' => [
                    'label' => 'Nhà xe',
                    'text' => 'Hợp tác với hàng trăm nhà xe uy tín: Phương Trang, Hoàng Long, Kumho Samco, Futa Bus Lines và nhiều đối tác chất lượng trên toàn quốc.',
                ],
            ],
            'link' => home_url('/ve-xe-khach/'),
        ],
        [
            'id' => 'plane',
            'label' => 'Máy bay',
            'icon' => $upload('images/plane.png'),
            'tabs' => [
                'intro' => [
                    'label' => 'Giới thiệu',
                    'text' => 'Đặt vé máy bay nội địa và quốc tế với giá minh bạch, hỗ trợ chọn hành trình, hành lý và dịch vụ bổ sung ngay trên Dailyve.',
                ],
                'routes' => [
                    'label' => 'Tuyến nổi bật',
                    'text' => 'Hà Nội – TP.HCM, Đà Nẵng – Nha Trang, Cần Thơ – Phú Quốc và các chặng bay thường xuyên được ưu đãi mỗi tuần.',
                ],
                'operators' => [
                    'label' => 'Hãng bay',
                    'text' => 'Kết nối Vietnam Airlines, Vietjet Air, Bamboo Airways, Pacific Airlines và các hãng bay quốc tế đối tác.',
                ],
            ],
            'link' => home_url('/ve-may-bay/'),
        ],
        [
            'id' => 'train',
            'label' => 'Tàu hỏa',
            'icon' => $upload('images/train.png'),
            'tabs' => [
                'intro' => [
                    'label' => 'Giới thiệu',
                    'text' => 'Đặt vé tàu hỏa trực tuyến, chọn toa và chỗ ngồi dễ dàng. Dailyve đồng bộ lịch tàu và hỗ trợ thanh toán an toàn 24/7.',
                ],
                'routes' => [
                    'label' => 'Tuyến nổi bật',
                    'text' => 'Hà Nội – TP.HCM, Hà Nội – Đà Nẵng, Hà Nội – Lào Cai, Sài Gòn – Nha Trang là những tuyến được đặt nhiều nhất.',
                ],
                'operators' => [
                    'label' => 'Đối tác',
                    'text' => 'Phục vụ đầy đủ các loại toa: ghế ngồi, giường nằm, khoang VIP trên mạng đường sắt quốc gia.',
                ],
            ],
            'link' => home_url('/ve-tau-hoa/'),
        ],
    ];

    $reasons = [
        ['title' => 'An Toàn - Tiện Lợi', 'desc' => 'Hệ thống bảo mật cao, thông tin minh bạch và quy trình đặt vé rõ ràng.', 'icon' => 'fas fa-shield-alt'],
        ['title' => 'Giá Vé Ưu Đãi', 'desc' => 'Giá cạnh tranh, nhiều mã giảm giá và chương trình ưu đãi độc quyền.', 'icon' => 'fas fa-wallet'],
        ['title' => '1500+ Đối Tác Vận Tải', 'desc' => 'Mạng lưới nhà xe, hãng bay và tàu hỏa phủ khắp cả nước.', 'icon' => 'fas fa-bus'],
        ['title' => 'Luôn Hỗ Trợ 24/7', 'desc' => 'Đội ngũ chăm sóc khách hàng sẵn sàng hỗ trợ mọi lúc, mọi nơi.', 'icon' => 'fas fa-headset'],
    ];

    $press = [
        [
            'brand' => 'VNEXPRESS',
            'title' => 'Dailyve – Nền tảng đặt vé xe khách tiện lợi và hiện đại',
            'image' => $upload('2025/06/nha-xe-hong-thinh-ha-noi-di-ha-tinh-gia-tot-nhat.jpg'),
            'url' => home_url('/tin-tuc/'),
        ],
        [
            'brand' => 'DANTRI',
            'title' => 'Dailyve hợp tác cùng nhiều hãng xe lớn, mang đến trải nghiệm thuận tiện',
            'image' => $upload('2025/08/chuyen-tau-hoa-da-nang-di-hue.jpg'),
            'url' => home_url('/tin-tuc/'),
        ],
        [
            'brand' => 'TUỔI TRẺ',
            'title' => 'Đặt vé tàu, vé máy bay siêu nhanh với Dailyve',
            'image' => $upload('2025/08/chang-bay-tu-san-bay-lien-khuong-di-ha-noi.jpg'),
            'url' => home_url('/tin-tuc/'),
        ],
        [
            'brand' => 'ZING NEWS',
            'title' => 'Dailyve – Ứng dụng đặt vé toàn diện cho mọi hành trình',
            'image' => $upload('2025/03/cach-dat-ve-xe-tai-dailyve-1.png'),
            'url' => home_url('/tin-tuc/'),
        ],
    ];

    $testimonials = [
        ['name' => 'Nguyễn Minh Anh', 'city' => 'Hà Nội', 'quote' => 'Đặt vé trên Dailyve rất nhanh chóng, giao diện dễ dùng, nhiều ưu đãi hấp dẫn. Mình sẽ tiếp tục ủng hộ!'],
        ['name' => 'Trần Quốc Bảo', 'city' => 'Đà Nẵng', 'quote' => 'Tôi thường xuyên đặt vé xe và vé máy bay qua Dailyve. Giá tốt, hỗ trợ nhiệt tình, rất hài lòng!'],
        ['name' => 'Lê Thị Thu Hương', 'city' => 'TP. Hồ Chí Minh', 'quote' => 'Ứng dụng ổn định, thanh toán tiện lợi, ưu đãi tới ngay. Khuyến khích mọi người sử dụng!'],
        ['name' => 'Phạm Văn Đức', 'city' => 'Cần Thơ', 'quote' => 'Tìm vé và so sánh giá rất tiện. Đội hỗ trợ phản hồi nhanh khi cần đổi lịch chuyến đi.'],
    ];
@endphp

@section('content')
    <article class="dailyve-home">
        <!-- Hero Section -->
        <section class="dailyve-hero" aria-labelledby="dailyve-home-title">
            <div class="dailyve-hero__clouds" aria-hidden="true"></div>
            
            <div class="dailyve-hero__balloon dailyve-hero__balloon--left" aria-hidden="true">
                <span class="dailyve-balloon-text">Xin chào!</span>
            </div>
            <div class="dailyve-hero__balloon dailyve-hero__balloon--right" aria-hidden="true">
                <span class="dailyve-balloon-text">Tìm ngay!</span>
            </div>

            <div class="dailyve-container dailyve-hero__top">
                <div class="dailyve-hero__copy">
                    <h1 id="dailyve-home-title">
                        Đặt vé tại đây
                        <span>vừa <strong>NHANH</strong> vừa <em>RẺ</em></span>
                    </h1>
                    <p class="dailyve-hero__subtitle">Hệ thống đặt vé xe khách trực tuyến hàng đầu Việt Nam</p>
                </div>

                <div class="dailyve-hero__visual" aria-hidden="true">
                    <div class="dailyve-hero__route"></div>
                    <div class="dailyve-vehicle dailyve-vehicle--bus">
                        <img src="{{ esc_url($upload('images/front-of-bus.png')) }}" alt="">
                    </div>
                    <div class="dailyve-mascot-card">
                        <img src="{{ esc_url($upload('2025/06/banner-sale.png')) }}" alt="">
                    </div>
                </div>
            </div>

            <div class="dailyve-container dailyve-hero__search">
                {!! do_shortcode('[react_search_form]') !!}
            </div>

            <div class="dailyve-hero__scroll" aria-hidden="true">
                <span>Lướt xuống để nhận ngay ưu đãi!</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </section>

        <!-- Offers Section -->
        <section class="dailyve-section dailyve-offers" aria-labelledby="dailyve-offers-title">
            <div class="dailyve-container dailyve-offers__layout">
                <div class="dailyve-offers__intro">
                    <h2 id="dailyve-offers-title">Ưu đãi <span>HOT</span> cho người đẹp</h2>
                    <div class="dailyve-offers__ticket-art" aria-hidden="true">
                        <i class="fas fa-ticket-alt"></i>
                        <i class="fas fa-fire"></i>
                    </div>
                </div>

                <div class="dailyve-offer-grid">
                    @foreach ($offers as $offer)
                        <article class="dailyve-offer-card">
                            <div class="dailyve-offer-card__body">
                                <p class="dailyve-offer-card__discount">Giảm Giá {{ $offer['discount'] }}</p>
                                <p class="dailyve-offer-card__label">{{ $offer['label'] }}</p>
                                <button
                                    type="button"
                                    class="dailyve-offer-card__save"
                                    data-code="{{ esc_attr($offer['code']) }}"
                                >
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
                    <h2 id="dailyve-services-title"><span>Dailyve</span> cung cấp những dịch vụ gì?</h2>
                    <div class="dailyve-services__types" role="tablist" aria-label="Loại dịch vụ">
                        @foreach ($serviceTypes as $index => $type)
                            <button
                                type="button"
                                class="dailyve-services__type{{ $index === 0 ? ' is-active' : '' }}"
                                role="tab"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                data-service-type="{{ esc_attr($type['id']) }}"
                            >
                                <img src="{{ esc_url($type['icon']) }}" alt="" loading="lazy">
                                <span>{{ $type['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                @foreach ($serviceTypes as $index => $type)
                    <div
                        class="dailyve-services__panel{{ $index === 0 ? ' is-active' : '' }}"
                        data-service-panel="{{ esc_attr($type['id']) }}"
                        @if ($index !== 0) hidden @endif
                    >
                        <div class="dailyve-services__tabs" role="tablist" aria-label="Nội dung dịch vụ">
                            @foreach ($type['tabs'] as $tabKey => $tab)
                                <button
                                    type="button"
                                    class="dailyve-services__tab{{ $loop->first ? ' is-active' : '' }}"
                                    role="tab"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    data-service-tab="{{ esc_attr($tabKey) }}"
                                >
                                    {{ $tab['label'] }}
                                </button>
                            @endforeach
                        </div>

                        <div class="dailyve-services__content">
                            <div class="dailyve-services__map" aria-hidden="true">
                                <span class="dailyve-services__pin dailyve-services__pin--a"></span>
                                <span class="dailyve-services__pin dailyve-services__pin--b"></span>
                                <span class="dailyve-services__pin dailyve-services__pin--c"></span>
                                <span class="dailyve-services__route-line"></span>
                            </div>

                            @foreach ($type['tabs'] as $tabKey => $tab)
                                <div
                                    class="dailyve-services__text{{ $loop->first ? ' is-active' : '' }}"
                                    data-service-content="{{ esc_attr($tabKey) }}"
                                    @if (! $loop->first) hidden @endif
                                >
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
        <section class="dailyve-section dailyve-reasons" aria-labelledby="dailyve-reasons-title">
            <div class="dailyve-container">
                <h2 id="dailyve-reasons-title"><span>TẠI SAO</span> nên sử dụng dịch vụ của Dailyve?</h2>
                <div class="dailyve-reasons__grid">
                    @foreach ($reasons as $reason)
                        <article>
                            <i class="{{ esc_attr($reason['icon']) }}" aria-hidden="true"></i>
                            <h3>{{ $reason['title'] }}</h3>
                            <p>{{ $reason['desc'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Press Section -->
        <section class="dailyve-section dailyve-press" aria-labelledby="dailyve-press-title">
            <div class="dailyve-container">
                <h2 id="dailyve-press-title"><span>BÁO CHÍ</span> nói gì về Dailyve?</h2>

                <div class="dailyve-press__grid">
                    @foreach ($press as $item)
                        <article>
                            <img src="{{ esc_url($item['image']) }}" alt="{{ esc_attr($item['title']) }}" loading="lazy">
                            <div class="dailyve-press__meta">
                                <span class="dailyve-press__brand">{{ $item['brand'] }}</span>
                                <h3>{{ $item['title'] }}</h3>
                                <a class="dailyve-btn dailyve-btn--primary-sm" href="{{ esc_url($item['url']) }}">Xem ngay</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="dailyve-press__footer">
                    <a class="dailyve-btn dailyve-btn--accent" href="{{ esc_url(home_url('/tin-tuc/')) }}">Xem thêm</a>
                </div>
            </div>
        </section>

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
                            <a href="#" class="dailyve-store-link">
                                <img src="{{ esc_url($upload('2025/01/download-app-store.png')) }}" alt="Tải trên App Store" loading="lazy">
                            </a>
                        </div>

                        <div class="dailyve-app-download-item">
                            <p class="dailyve-app-download-label">Và tại <strong>Play Store</strong> cũng có Dailyve</p>
                            <a href="#" class="dailyve-store-link">
                                <img src="{{ esc_url($upload('2025/01/download-gg-play.png')) }}" alt="Tải trên Google Play" loading="lazy">
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Center Column: iPhone Mockup -->
                <div class="dailyve-merged-band__center" aria-hidden="true">
                    <div class="dailyve-phone-mockup">
                        <div class="dailyve-phone-screen">
                            <img src="{{ esc_url($upload('2025/03/cach-dat-ve-xe-tai-dailyve-1.png')) }}" alt="Dailyve App" loading="lazy">
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
                            <img src="{{ esc_url($upload('2025/01/App-QR-Code-1-300x300.png.avif')) }}" alt="QR Code" loading="lazy">
                        </div>
                        <div class="dailyve-qr-card__info">
                            <p class="dailyve-qr-card__title">Quét mã để tải app</p>
                            <p class="dailyve-qr-card__subtitle">Hoặc tìm "Dailyve" trên App Store & Google Play</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
@endsection

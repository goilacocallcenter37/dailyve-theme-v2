@extends('layouts.app')

@php
    $upload = fn ($path) => home_url('/wp-content/uploads/' . ltrim($path, '/'));

    $offers = [
        [
            'eyebrow' => 'Giảm giá vé xe',
            'value' => '50K',
            'note' => 'Đơn từ 300K',
            'code' => 'DAILY50',
            'icon' => $upload('images/front-of-bus.png?v=2'),
        ],
        [
            'eyebrow' => 'Giảm giá vé tàu',
            'value' => '30K',
            'note' => 'Đơn từ 250K',
            'code' => 'TAU30',
            'icon' => $upload('images/train.png?v=2'),
        ],
        [
            'eyebrow' => 'Giảm giá vé máy bay',
            'value' => '100K',
            'note' => 'Đơn từ 1.000K',
            'code' => 'MAYBAY100',
            'icon' => $upload('images/plane.png?v=2'),
        ],
        [
            'eyebrow' => 'Giảm giá khách sạn',
            'value' => '15%',
            'note' => 'Tối đa 300K',
            'code' => 'KS15',
            'icon' => $upload('images/deal.png?v=2'),
        ],
    ];

    $services = [
        ['title' => 'Vé xe khách', 'desc' => 'Đa dạng tuyến đường khắp cả nước', 'image' => $upload('images/front-of-bus.png?v=2')],
        ['title' => 'Vé tàu hỏa', 'desc' => 'Đặt vé nhanh chóng, chọn chỗ dễ dàng', 'image' => $upload('images/train.png?v=2')],
        ['title' => 'Vé máy bay', 'desc' => 'Giá tốt mỗi ngày, ưu đãi liên tục', 'image' => $upload('images/plane.png?v=2')],
        ['title' => 'Khách sạn', 'desc' => 'Đa dạng lựa chọn, giá tốt nhất', 'image' => $upload('images/deal.png?v=2')],
        ['title' => 'Bảo hiểm', 'desc' => 'An tâm trên mọi chuyến đi', 'image' => $upload('images/insurance.png?v=2')],
    ];

    $reasons = [
        ['title' => 'An toàn - Tin cậy', 'desc' => 'Hệ thống bảo mật cao, thông tin minh bạch, đối tác uy tín.', 'icon' => 'fas fa-shield-alt'],
        ['title' => 'Giá tốt - Ưu đãi', 'desc' => 'Giá cạnh tranh, nhiều chương trình ưu đãi độc quyền.', 'icon' => 'fas fa-wallet'],
        ['title' => 'Hỗ trợ 24/7', 'desc' => 'Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ.', 'icon' => 'fas fa-headset'],
        ['title' => 'Tiện lợi - Nhanh chóng', 'desc' => 'Đặt vé mọi lúc mọi nơi, nhận vé điện tử ngay lập tức.', 'icon' => 'fas fa-ticket-alt'],
    ];

    $press = [
        [
            'brand' => 'VNEXPRESS',
            'title' => 'Dailyve - Nền tảng đặt vé xe khách được tin dùng hàng đầu Việt Nam',
            'date' => '10/05/2024',
            'image' => $upload('2025/06/nha-xe-hong-thinh-ha-noi-di-ha-tinh-gia-tot-nhat.jpg'),
        ],
        [
            'brand' => 'DANTRI',
            'title' => 'Dailyve hợp tác cùng nhiều hãng xe lớn, mang đến trải nghiệm thuận tiện',
            'date' => '08/05/2024',
            'image' => $upload('2025/08/chuyen-tau-hoa-da-nang-di-hue.jpg'),
        ],
        [
            'brand' => 'tuổi trẻ',
            'title' => 'Đặt vé tàu, vé máy bay siêu nhanh với Dailyve',
            'date' => '05/05/2024',
            'image' => $upload('2025/08/chang-bay-tu-san-bay-lien-khuong-di-ha-noi.jpg'),
        ],
        [
            'brand' => 'Zing news',
            'title' => 'Dailyve - Ứng dụng đặt vé toàn diện cho mọi hành trình',
            'date' => '02/05/2024',
            'image' => $upload('2025/03/cach-dat-ve-xe-tai-dailyve-1.png'),
        ],
        [
            'brand' => 'CAFEF',
            'title' => 'Dailyve không ngừng đổi mới để phục vụ khách hàng tốt hơn',
            'date' => '28/04/2024',
            'image' => $upload('2025/08/phuong-tien-di-chuyen-taxi-tu-san-bay-noi-bai-ve-trung-tam-ha-noi.png'),
        ],
    ];

    $testimonials = [
        ['name' => 'Nguyễn Minh Anh', 'city' => 'Hà Nội', 'quote' => 'Đặt vé trên Dailyve rất nhanh chóng, giao diện dễ dùng, nhiều ưu đãi hấp dẫn. Mình sẽ tiếp tục ủng hộ!'],
        ['name' => 'Trần Quốc Bảo', 'city' => 'Đà Nẵng', 'quote' => 'Tôi thường xuyên đặt vé xe và vé máy bay qua Dailyve. Giá tốt, hỗ trợ nhiệt tình, rất hài lòng!'],
        ['name' => 'Lê Thị Thu Hương', 'city' => 'TP. Hồ Chí Minh', 'quote' => 'Ứng dụng ổn định, thanh toán tiện lợi, ưu đãi tới ngay. Khuyến khích mọi người sử dụng!'],
    ];
@endphp

@section('content')
    <article class="dailyve-home">
        <section class="dailyve-hero" aria-labelledby="dailyve-home-title">
            <div class="dailyve-hero__skyline" aria-hidden="true"></div>
            <div class="dailyve-hero__balloon dailyve-hero__balloon--left" aria-hidden="true"></div>
            <div class="dailyve-hero__balloon dailyve-hero__balloon--right" aria-hidden="true"></div>

            <div class="dailyve-container dailyve-hero__inner">
                <div class="dailyve-hero__copy">
                    <h1 id="dailyve-home-title">
                        Đặt vé tại đây
                        <span>vừa <strong>NHANH</strong> vừa <em>RẺ</em></span>
                    </h1>

                    <ul class="dailyve-hero__proofs" aria-label="Cam kết Dailyve">
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i>Đa dạng tuyến đường</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i>Giá tốt mỗi ngày</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i>Thanh toán an toàn</li>
                    </ul>
                </div>

                <div class="dailyve-hero__visual" aria-hidden="true">
                    <div class="dailyve-hero__route"></div>
                    <div class="dailyve-vehicle dailyve-vehicle--bus">
                        <img src="{{ esc_url($upload('images/front-of-bus.png')) }}" alt="">
                    </div>
                    <div class="dailyve-vehicle dailyve-vehicle--train">
                        <img src="{{ esc_url($upload('images/train.svg')) }}" alt="">
                    </div>
                    <div class="dailyve-mascot-card">
                        <img src="{{ esc_url($upload('2025/06/banner-sale.png')) }}" alt="">
                    </div>
                </div>

                <div class="dailyve-hero__search">
                    {!! do_shortcode('[react_search_form]') !!}
                </div>
            </div>
        </section>

        <section class="dailyve-section dailyve-offers" aria-labelledby="dailyve-offers-title">
            <div class="dailyve-container">
                <div class="dailyve-section__heading">
                    <h2 id="dailyve-offers-title">Ưu đãi <span>HOT</span> cho người đi đẹp</h2>
                    <a href="{{ esc_url(home_url('/khuyen-mai/')) }}">Xem tất cả ưu đãi</a>
                </div>

                <div class="dailyve-offer-grid">
                    @foreach ($offers as $offer)
                        <article class="dailyve-offer-card">
                            <div class="dailyve-offer-card__left">
                                <p class="dailyve-offer-card__eyebrow">{{ $offer['eyebrow'] }}</p>
                                <strong class="dailyve-offer-card__value">{{ $offer['value'] }}</strong>
                                <span class="dailyve-offer-card__note">{{ $offer['note'] }}</span>
                                <div class="dailyve-offer-card__code">
                                    NHẬP MÃ: <span>{{ $offer['code'] }}</span>
                                </div>
                            </div>
                            <div class="dailyve-offer-card__right">
                                <div class="dailyve-offer-card__badge-wrapper">
                                    <svg class="dailyve-offer-card__blue-coupon" viewBox="0 0 80 50" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5,10 C5,7.2 7.2,5 10,5 L70,5 C72.8,5 75,7.2 75,10 C75,12 73.5,13.5 73.5,15 C73.5,16.5 75,18 75,20 C75,22 73.5,23.5 73.5,25 C73.5,26.5 75,28 75,30 C75,32.8 72.8,35 70,35 L10,35 C7.2,35 5,32.8 5,30 C5,28 6.5,26.5 6.5,25 C6.5,23.5 5,22 5,20 C5,18 6.5,16.5 6.5,15 C6.5,13.5 5,12 5,10 Z" fill="#0064d2" />
                                        <line x1="20" y1="5" x2="20" y2="35" stroke="#ffffff" stroke-width="1.5" stroke-dasharray="3 3" opacity="0.8" />
                                        <text x="45" y="24" fill="#ffffff" font-family="'Plus Jakarta Sans', sans-serif" font-size="14" font-weight="900" text-anchor="middle" alignment-baseline="middle">%</text>
                                    </svg>
                                    <img class="dailyve-offer-card__icon" src="{{ esc_url($offer['icon']) }}" alt="{{ esc_attr($offer['eyebrow']) }}" loading="lazy">
                                </div>
                                <button type="button" class="dailyve-offer-card__btn-copy">Sao chép</button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="dailyve-section dailyve-services" aria-labelledby="dailyve-services-title">
            <div class="dailyve-container">
                <div class="dailyve-services__panel">
                    <h2 id="dailyve-services-title"><span>Dailyve</span> cung cấp những dịch vụ gì?</h2>
                    <div class="dailyve-services__grid">
                        @foreach ($services as $service)
                            <article>
                                <img class="dailyve-services__icon" src="{{ esc_url($service['image']) }}" alt="{{ esc_attr($service['title']) }}" loading="lazy">
                                <h3>{{ $service['title'] }}</h3>
                                <p>{{ $service['desc'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="dailyve-section dailyve-reasons" aria-labelledby="dailyve-reasons-title">
            <div class="dailyve-container">
                <h2 id="dailyve-reasons-title"><span>Tại sao</span> nên sử dụng dịch vụ của <em>Dailyve?</em></h2>
                <div class="dailyve-reasons__grid">
                    @foreach ($reasons as $reason)
                        <article>
                            <i class="{{ esc_attr($reason['icon']) }}" aria-hidden="true"></i>
                            <div>
                                <h3>{{ $reason['title'] }}</h3>
                                <p>{{ $reason['desc'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="dailyve-section dailyve-press" aria-labelledby="dailyve-press-title">
            <div class="dailyve-container">
                <div class="dailyve-section__heading">
                    <h2 id="dailyve-press-title">Báo chí nói gì về <span>Dailyve?</span></h2>
                    <a href="{{ esc_url(home_url('/tin-tuc/')) }}">Xem tất cả</a>
                </div>

                <div class="dailyve-press__grid">
                    @foreach ($press as $item)
                        <article>
                            <div class="dailyve-press__brand">{{ $item['brand'] }}</div>
                            <img src="{{ esc_url($item['image']) }}" alt="{{ esc_attr($item['title']) }}" loading="lazy">
                            <h3>{{ $item['title'] }}</h3>
                            <time datetime="{{ esc_attr(date('Y-m-d', strtotime(str_replace('/', '-', $item['date'])))) }}">{{ $item['date'] }}</time>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="dailyve-section dailyve-testimonials" aria-labelledby="dailyve-testimonials-title">
            <div class="dailyve-container">
                <div class="dailyve-section__heading">
                    <h2 id="dailyve-testimonials-title"><span>Khách hàng</span> nói gì về <em>Dailyve?</em></h2>
                    <a href="{{ esc_url(home_url('/danh-gia/')) }}">Xem tất cả</a>
                </div>

                <div class="dailyve-testimonials__grid">
                    @foreach ($testimonials as $testimonial)
                        <article>
                            <div class="dailyve-testimonial__profile">
                                <div class="dailyve-testimonial__avatar" aria-hidden="true">{{ substr($testimonial['name'], 0, 1) }}</div>
                                <div>
                                    <h3>{{ $testimonial['name'] }}</h3>
                                    <span>{{ $testimonial['city'] }}</span>
                                </div>
                            </div>
                            <div class="dailyve-stars" aria-label="5 sao">★★★★★</div>
                            <p>{{ $testimonial['quote'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="dailyve-app-band" aria-labelledby="dailyve-app-title">
            <div class="dailyve-container dailyve-app-band__inner">
                <div class="dailyve-app-band__copy">
                    <h2 id="dailyve-app-title">Trải nghiệm tiện lợi hơn với ứng dụng <span>Dailyve</span></h2>
                    <p>Tải app ngay để nhận nhiều ưu đãi hấp dẫn và quản lý hành trình dễ dàng hơn.</p>
                    <div class="dailyve-app-band__stores">
                        <img src="{{ esc_url($upload('2025/01/App-QR-Code-1-300x300.png.avif')) }}" alt="QR tải ứng dụng Dailyve" loading="lazy">
                        <div>
                            <img src="{{ esc_url($upload('2025/01/download-app-store.png')) }}" alt="Tải trên App Store" loading="lazy">
                            <img src="{{ esc_url($upload('2025/01/download-gg-play.png')) }}" alt="Tải trên Google Play" loading="lazy">
                        </div>
                    </div>
                </div>
                <div class="dailyve-app-band__visual" aria-hidden="true">
                    <img src="{{ esc_url($upload('2025/06/banner-sale.png')) }}" alt="">
                </div>
            </div>
        </section>
    </article>
@endsection

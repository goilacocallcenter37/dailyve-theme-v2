@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php
            the_post();

            $post_id = get_the_ID();
            $operator_result = \App\dailyve_get_operator_detail($post_id);
            $api_error = is_wp_error($operator_result) ? $operator_result : null;
            $operator = $api_error ? [] : $operator_result;

            $decode = fn($value) => html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8');
            $operator_name = $decode($operator['name'] ?? preg_replace('/^Nhà xe\s+/iu', '', get_the_title($post_id)));
            $post_title = $decode(get_the_title($post_id));
            $rating = $operator['rating'] ?? get_post_meta($post_id, 'rating', true);
            $review_count = (int) ($operator['review_count'] ?? get_post_meta($post_id, 'reviews', true));
            $operator_id = trim(
                (string) ($operator['operator_id'] ??
                    ($operator['company_id'] ?? get_post_meta($post_id, 'company_id', true))),
            );
            $display_review_total = $review_count ?: count((array) ($operator['reviews'] ?? []));
            $status = (string) ($operator['status'] ?? '');
            $status_label = $status === 'active' ? 'Đang hoạt động' : ($status ? ucfirst($status) : 'Đang cập nhật');
            $contact_info = is_array($operator['contact_info'] ?? null) ? $operator['contact_info'] : [];
            $contact_cities = is_array($contact_info['contact_cities'] ?? null) ? $contact_info['contact_cities'] : [];
            $all_phones = array_values(array_filter((array) ($contact_info['all_phones'] ?? [])));
            $primary_phone = get_post_meta($post_id, 'company_phone', true) ?? '1900 0155';
            $first_office = null;
            $office_count = 0;

            foreach ($contact_cities as $city_group) {
                $offices = is_array($city_group['offices'] ?? null) ? $city_group['offices'] : [];
                $office_count += count($offices);
                if (!$first_office && !empty($offices[0])) {
                    $first_office = $offices[0] + ['city' => $city_group['city'] ?? ''];
                }
            }

            $primary_address =
                $first_office['address'] ??
                (function_exists('get_field')
                    ? get_field('company_address', $post_id)
                    : get_post_meta($post_id, 'company_address', true));
            $vehicle_types = array_values(array_filter((array) ($operator['vehicle_type_summary'] ?? [])));
            $amenities = array_values(array_filter((array) ($operator['amenities'] ?? [])));
            $rating_details = array_values(array_filter((array) ($operator['rating_details'] ?? [])));
            $reviews_list = array_values(array_filter((array) ($operator['reviews'] ?? [])));
            $routes = array_values(array_filter((array) ($operator['routes'] ?? [])));
            $route_count = (int) (count($routes) ?? 0);
            $media = is_array($operator['media'] ?? null) ? $operator['media'] : [];

            $normalize_image_url = function ($value) {
                if (is_array($value)) {
                    $value = $value['url'] ?? ($value['sizes']['full'] ?? '');
                }

                if (is_numeric($value)) {
                    $value = wp_get_attachment_image_url((int) $value, 'full') ?: '';
                }

                $url = trim((string) $value);
                if ($url === '') {
                    return '';
                }

                if (strpos($url, '//') === 0) {
                    return 'https:' . $url;
                }

                return esc_url_raw($url);
            };

            $gallery = [];
            foreach ((array) ($media['gallery_items'] ?? []) as $item) {
                $url = $normalize_image_url($item);
                if ($url) {
                    $gallery[] = [
                        'url' => $url,
                        'alt' => $decode(is_array($item) ? $item['alt'] ?? $operator_name : $operator_name),
                    ];
                }
            }

            $avatar_url = $normalize_image_url($media['avatar_url'] ?? '');
            if ($avatar_url && !in_array($avatar_url, wp_list_pluck($gallery, 'url'), true)) {
                array_unshift($gallery, ['url' => $avatar_url, 'alt' => $operator_name]);
            }

            if (empty($gallery) && has_post_thumbnail($post_id)) {
                $gallery[] = [
                    'url' => get_the_post_thumbnail_url($post_id, 'large'),
                    'alt' => $post_title,
                ];
            }

            if (empty($gallery)) {
                $gallery[] = [
                    'url' => 'https://object.dailyve.com/dailyve/wp-content/uploads/2026/05/nha-xe-chat-luong-cao.webp',
                    'alt' => $operator_name,
                ];
            }

            $format_price = fn($price) => $price ? number_format((int) $price, 0, ',', '.') . 'đ' : 'Liên hệ';
            $price_from_route = function ($route) {
                $min = null;
                foreach ((array) ($route['prices'] ?? []) as $price_line) {
                    if (preg_match_all('/(\d{4,})/u', (string) $price_line, $matches)) {
                        foreach ($matches[1] as $raw_price) {
                            $value = (int) $raw_price;
                            $min = $min === null ? $value : min($min, $value);
                        }
                    }
                }

                return $min;
            };

            $route_filter_key = fn($route) => sanitize_title($route['from_province_name'] ?? ($route['from'] ?? ''));
            $route_filter_label = fn($route) => $decode($route['from_province_name'] ?? ($route['from'] ?? ''));
            $route_filters = [];
            foreach ($routes as $route) {
                $key = trim((string) $route_filter_key($route));
                $label = trim((string) $route_filter_label($route));
                if ($key !== '' && $label !== '') {
                    $route_filters[$key] = $label;
                }
            }

            $booking_url = function ($route = []) use ($operator) {
                $depart_date = date_i18n('Y-m-d', strtotime('+1 day', current_time('timestamp')));
                $params = [
                    'from' => $route['from_id'] ?? '',
                    'to' => $route['to_id'] ?? '',
                    'date' => $depart_date,
                    'service' => 'bus',
                    'nameFrom' => $route['from'] ?? '',
                    'nameTo' => $route['to'] ?? '',
                ];

                if (!empty($operator['operator_id'])) {
                    $params['operator_id'] = $operator['operator_id'];
                }

                return add_query_arg(array_filter($params), home_url('/dat-ve-truc-tuyen/'));
            };

            $offers = [
                ['icon' => 'fa-percent', 'title' => 'Giảm 10%', 'meta' => 'Cho khách mới', 'code' => 'DAILYVE10'],
                ['icon' => 'fa-ticket-alt', 'title' => 'Giảm 50K', 'meta' => 'Vé xe khách', 'code' => 'XEKH50K'],
                ['icon' => 'fa-wallet', 'title' => 'Cashback 10%', 'meta' => 'Qua ví Dailyve', 'code' => 'CASHBACK10'],
                [
                    'icon' => 'fa-graduation-cap',
                    'title' => 'Ưu đãi sinh viên',
                    'meta' => 'Cuối tuần',
                    'code' => 'SVWEEKEND',
                ],
            ];

            $trust_items = [
                ['icon' => 'fa-shield-alt', 'label' => 'Chắc chắn có chỗ'],
                ['icon' => 'fa-headset', 'label' => 'Hỗ trợ 24/7'],
                ['icon' => 'fa-credit-card', 'label' => 'Không cần thanh toán trước'],
                ['icon' => 'fa-user-check', 'label' => 'Được chọn chỗ ngồi'],
                ['icon' => 'fa-shuttle-van', 'label' => 'Xe trung chuyển'],
            ];

            $appStore = home_url('/wp-content/themes/dailyve-theme/resources/images/download-app-store.png');
            $googlePlay = home_url('/wp-content/themes/dailyve-theme/resources/images/download-gg-play.png');
            $qrCode = 'https://object.dailyve.com/dailyve/wp-content/uploads/2025/08/QR-CODE-APP-DLV.png';
            $tetBanner = home_url('/wp-content/themes/dailyve-theme/resources/images/operator-tet-ticket-banner.webp');

            // FAQ Items
            $faq_items = [
                'Giá vé trung bình của ' .
                $operator_name .
                ' là bao nhiêu?' => 'Giá vé thay đổi theo tuyến, ngày đi và dòng xe. Bạn có thể xem giá từ trong từng tuyến phía trên.',
                'Địa chỉ văn phòng ' .
                $operator_name .
                ' gần nhất?' => 'Xem tab Địa chỉ văn phòng & SĐT để chọn điểm liên hệ theo tỉnh/thành.',
                'Có thể chọn ghế khi đặt vé không?' =>
                    'Các chuyến hỗ trợ sơ đồ ghế sẽ cho phép chọn chỗ trước khi thanh toán.',
                'Hành lý được mang theo như thế nào?' =>
                    'Quy định hành lý phụ thuộc từng chuyến. Dailyve sẽ hỗ trợ kiểm tra trước giờ khởi hành.',
            ];

            // Structured Schema Markup JSON-LD (SEO Best Practice)
            $schema_data = [
                '@context' => 'https://schema.org',
                '@type' => 'LocalBusiness',
                'name' => $operator_name,
                'image' =>
                    $gallery[0]['url'] ??
                    'https://object.dailyve.com/dailyve/wp-content/uploads/2026/05/nha-xe-chat-luong-cao.webp',
                'telephone' => $primary_phone ?? '',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $primary_address ?: '',
                    'addressLocality' => !empty($contact_cities[0]['city'])
                        ? $decode($contact_cities[0]['city'])
                        : 'Việt Nam',
                    'addressCountry' => 'VN',
                ],
                'url' => get_permalink($post_id),
            ];

            if ($rating) {
                $schema_data['aggregateRating'] = [
                    '@type' => 'AggregateRating',
                    'ratingValue' => $rating,
                    'reviewCount' => $review_count ?: 1,
                    'bestRating' => '5',
                    'worstRating' => '1',
                ];
            }

            $faq_schema_data = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(
                    function ($q, $a) {
                        return [
                            '@type' => 'Question',
                            'name' => $q,
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => $a,
                            ],
                        ];
                    },
                    array_keys($faq_items),
                    array_values($faq_items),
                ),
            ];
        @endphp

        <script type="application/ld+json">
            {!! wp_json_encode($schema_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
        <script type="application/ld+json">
            {!! wp_json_encode($faq_schema_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>

        <div class="dailyve-operator-detail bg-slate-50 text-slate-700" data-operator-detail>

            <x-breadcrumb :items="[
                ['title' => 'Dailyve', 'url' => home_url('/')],
                ['title' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
                ['title' => 'Nhà xe', 'url' => home_url('/ve-xe-khach/nha-xe/')],
                ['title' => $operator_name, 'url' => ''],
            ]" preset="directory" />

            <section class="border-b border-slate-200 bg-white relative z-20">
                <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                    <div
                        class="dailyve-bus-search-card relative overflow-visible rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div id="react-search-form" class="min-h-[120px]" data-initial-service="bus"></div>
                    </div>
                </div>
            </section>

            @if ($api_error)
                <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
                        Không lấy được dữ liệu API chi tiết nhà xe: {{ $api_error->get_error_message() }} Nội dung bài viết
                        vẫn được hiển thị bên dưới.
                    </div>
                </section>
            @endif

            <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,0.95fr)_minmax(0,1.25fr)]">
                    <article class="operator-hero-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm md:p-6"
                        data-operator-reveal>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                <i class="fas fa-check-circle" aria-hidden="true"></i>
                                Đối tác uy tín
                            </span>
                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                {{ $status_label }}
                            </span>
                        </div>

                        <h1
                            class="dailyve-operator-detail__display mt-4 text-3xl font-semibold leading-tight text-slate-950 md:text-4xl">
                            {{ $operator_name }}
                        </h1>

                        <div class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm">
                            @if ($rating)
                                <strong class="text-slate-950">{{ $rating }}</strong>
                                <span class="inline-flex text-amber-400" aria-label="{{ $rating }} sao">
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                    @endfor
                                </span>
                            @endif
                            @if ($review_count)
                                <span class="font-medium text-slate-500">({{ number_format($review_count, 0, ',', '.') }}
                                    đánh giá)</span>
                            @endif
                        </div>

                        <div class="mt-5 grid gap-3 text-sm text-slate-600">
                            @if ($primary_phone)
                                <a class="inline-flex items-center gap-3 font-semibold text-slate-900 hover:text-blue-600"
                                    href="tel:{{ preg_replace('/\D+/', '', '19000155') }}">
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                                    </span>
                                    {{ $primary_phone }}
                                </a>
                            @endif

                            @if ($primary_address)
                                <p class="m-0 flex items-center gap-3">
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                    </span>
                                    <span>{{ $primary_address }}</span>
                                </p>
                            @endif
                        </div>

                        <div class="operator-trust-grid mt-6">
                            @foreach ($trust_items as $item)
                                <div class="operator-trust-item">
                                    <span class="operator-trust-item__icon">
                                        <i class="fas {{ $item['icon'] }}" aria-hidden="true"></i>
                                    </span>
                                    <span class="operator-trust-item__label">{{ $item['label'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <a href="#operator-routes"
                                class="inline-flex h-12 items-center justify-center gap-2 rounded-lg bg-amber-400 px-5 text-sm font-semibold text-slate-950 transition hover:bg-amber-500">
                                Xem giá & lịch chạy
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                            <button type="button" data-save-operator="{{ esc_attr($post_id) }}"
                                class="inline-flex h-12 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:text-blue-600">
                                Lưu nhà xe
                                <i class="far fa-heart" aria-hidden="true"></i>
                            </button>
                        </div>

                        {{-- <div class="operator-stat-grid mt-6">
                            <div class="operator-stat-card">
                                <span>Trạng thái</span>
                                <strong>{{ $status_label }}</strong>
                            </div>
                            <div class="operator-stat-card">
                                <span>Số tuyến đường</span>
                                <strong>{{ $route_count ? number_format($route_count, 0, ',', '.') : count($routes) }}</strong>
                            </div>
                            <div class="operator-stat-card">
                                <span>Dòng xe</span>
                                <strong>{{ $vehicle_types[0]['name'] ?? 'Đang cập nhật' }}</strong>
                            </div>
                        </div> --}}
                    </article>

                    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm" data-operator-reveal>
                        <div class="operator-gallery" data-operator-gallery>
                            <div class="operator-gallery__viewport">
                                <div class="operator-gallery__track" data-gallery-track>
                                    @foreach ($gallery as $index => $image)
                                        <div class="operator-gallery__slide {{ $index === 0 ? 'is-active' : '' }}"
                                            data-gallery-slide>
                                            <img src="{{ esc_url($image['url']) }}" alt="{{ esc_attr($image['alt']) }}"
                                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}" decoding="async"
                                                @if ($index === 0) fetchpriority="high" @endif>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if (count($gallery) > 1)
                                <button type="button" class="operator-gallery__nav operator-gallery__nav--prev"
                                    data-gallery-prev aria-label="Ảnh trước">
                                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="operator-gallery__nav operator-gallery__nav--next"
                                    data-gallery-next aria-label="Ảnh tiếp theo">
                                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                </button>
                                <div class="operator-gallery__dots" aria-label="Chọn ảnh">
                                    @foreach ($gallery as $index => $image)
                                        <button type="button"
                                            class="operator-gallery__dot {{ $index === 0 ? 'is-active' : '' }}"
                                            data-gallery-dot="{{ $index }}"
                                            aria-label="Xem ảnh {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if (count($gallery) > 1)
                            <div class="operator-gallery__thumbs mt-3 hidden" style="display: none !important;"
                                data-gallery-thumbs>
                                @foreach ($gallery as $index => $image)
                                    <button type="button"
                                        class="operator-gallery__thumb {{ $index === 0 ? 'is-active' : '' }}"
                                        data-gallery-thumb="{{ $index }}" aria-label="Xem ảnh {{ $index + 1 }}">
                                        <img class="h-full w-full object-cover" src="{{ esc_url($image['url']) }}"
                                            alt="" loading="lazy" decoding="async">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="operator-offers-slider mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8" data-offers-slider>
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h2 class="text-base font-semibold text-slate-950">Ưu đãi khi đặt xe {{ $operator_name }} tại Dailyve
                    </h2>
                    @if (count($offers) > 1)
                        <div class="hidden shrink-0 items-center gap-2 sm:flex">
                            <button type="button" class="operator-offers-slider__nav" data-offers-prev
                                aria-label="Ưu đãi trước">
                                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="operator-offers-slider__nav" data-offers-next
                                aria-label="Ưu đãi tiếp theo">
                                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="operator-offers-slider__viewport" data-offers-viewport>
                    <div class="operator-offers-slider__track">
                        @foreach ($offers as $offer)
                            <article class="operator-offer-card rounded-xl border border-slate-200 bg-white p-4 shadow-sm"
                                data-offer-slide>
                                <div class="flex items-start gap-4">
                                    <span
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-xl text-blue-600">
                                        <i class="fas {{ $offer['icon'] }}" aria-hidden="true"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <h3 class="text-sm font-semibold text-blue-600">{{ $offer['title'] }}</h3>
                                        <p class="mt-1 text-xs font-medium text-slate-600">{{ $offer['meta'] }}</p>
                                        <p class="mt-1 text-[11px] text-slate-400">HSD: 31/12/{{ date('Y') }}</p>
                                    </div>
                                </div>
                                <button type="button"
                                    class="mt-4 h-9 w-full rounded-lg bg-blue-600 text-xs font-semibold text-white transition hover:bg-blue-700"
                                    data-copy-code="{{ esc_attr($offer['code']) }}">
                                    {{ $offer['code'] }}
                                </button>
                            </article>
                        @endforeach
                    </div>
                </div>

                @if (count($offers) > 1)
                    <div class="operator-offers-slider__footer mt-4 flex items-center justify-between gap-3">
                        <div class="operator-offers-slider__dots" aria-label="Chọn ưu đãi">
                            @foreach ($offers as $index => $offer)
                                <button type="button"
                                    class="operator-offers-slider__dot {{ $index === 0 ? 'is-active' : '' }}"
                                    data-offer-dot="{{ $index }}"
                                    aria-label="Xem ưu đãi {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="flex shrink-0 items-center gap-2 sm:hidden">
                            <button type="button" class="operator-offers-slider__nav" data-offers-prev
                                aria-label="Ưu đãi trước">
                                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="operator-offers-slider__nav" data-offers-next
                                aria-label="Ưu đãi tiếp theo">
                                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </section>

            @php
                $tet_ticket_url = !empty($routes[0]) ? $booking_url($routes[0]) : '#operator-routes';
            @endphp
            <section class="operator-tet-ticket mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                <article class="operator-tet-ticket__card" style="background-image: url('{{ esc_url($tetBanner) }}');">
                    <div class="operator-tet-ticket__content">
                        <h2>Vé xe Tết cùng {{ $operator_name }}</h2>
                        <p>Đặt vé sớm - Giá tốt - Chọn chỗ ưng ý</p>
                        <p>Hỗ trợ đổi trả linh hoạt, an tâm về quê đón Tết!</p>
                        <a href="{{ esc_url($tet_ticket_url) }}" class="operator-tet-ticket__button">
                            <span>Xem vé Tết ngay</span>
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            </section>

            <section id="operator-routes" class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    {{-- <div>
                        <h2 class="dailyve-operator-detail__display text-2xl font-semibold text-slate-950">Các tuyến đường nhà xe cung cấp</h2>
                        <p class="mt-2 text-sm text-slate-500">
                            @if ($routes)
                                Hiển thị {{ count($routes) }} tuyến tỉnh/thành tiêu biểu từ dữ liệu API.
                            @else
                                Dailyve đang cập nhật dữ liệu tuyến đường cho nhà xe này.
                            @endif
                        </p>
                    </div> --}}

                    @if ($route_filters)
                        <div class="operator-filter-scroll flex max-w-full gap-2 overflow-x-auto pb-2">
                            <button type="button"
                                class="operator-filter-btn is-active h-10 shrink-0 rounded-lg bg-blue-600 px-4 text-sm font-semibold text-white"
                                data-operator-filter="all">Tất cả</button>
                            @foreach ($route_filters as $key => $label)
                                <button type="button"
                                    class="operator-filter-btn h-10 shrink-0 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 transition hover:border-blue-300 hover:text-blue-600"
                                    data-operator-filter="{{ esc_attr($key) }}">{{ $label }}</button>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if ($routes)
                    <div class="grid gap-5 lg:grid-cols-2 items-start">
                        @foreach ($routes as $route)
                            @php
                                $route_price = $price_from_route($route);
                                $from = $decode($route['from_province_name'] ?? ($route['from'] ?? ''));
                                $to = $decode($route['to_province_name'] ?? ($route['to'] ?? ''));
                                $times = array_slice(
                                    (array) ($route['scheduled_departure_times'] ?? ($route['departure_times'] ?? [])),
                                    0,
                                    10,
                                );
                                $pickup_points = array_slice((array) ($route['pickup_points'] ?? []), 0, 4);
                                $dropoff_points = array_slice((array) ($route['dropoff_points'] ?? []), 0, 4);
                                $vehicle_names = array_values(
                                    array_filter(
                                        array_map(
                                            fn($item) => $decode(is_array($item) ? $item['name'] ?? '' : $item),
                                            (array) ($route['vehicle_type_details'] ?? []),
                                        ),
                                    ),
                                );
                                $is_route_open = $loop->index < 2;
                                $should_hide_initially = $loop->index >= 6;
                            @endphp
                            <article
                                class="operator-route-card {{ $is_route_open ? 'is-open' : '' }} {{ $should_hide_initially ? 'is-hidden-by-limit' : '' }} overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
                                data-route-card data-route-open="{{ $is_route_open ? 'true' : 'false' }}"
                                data-route-from="{{ esc_attr($route_filter_key($route)) }}" data-operator-reveal>
                                <button type="button"
                                    class="operator-route-toggle flex w-full items-center justify-between gap-4 bg-blue-600 px-5 py-3 text-left text-white"
                                    data-route-toggle aria-expanded="{{ $is_route_open ? 'true' : 'false' }}">
                                    <span class="min-w-0">
                                        <span class="block truncate text-base font-semibold">{{ $from }} →
                                            {{ $to }}</span>
                                        <span class="mt-0.5 block text-xs font-medium text-blue-100">
                                            {{ $route['travel_duration'] ?? 'Đang cập nhật' }}
                                            @if (!empty($route['distance']))
                                                · {{ $route['distance'] }}
                                            @endif
                                        </span>
                                    </span>
                                    <span
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-blue-600 shadow-sm">
                                        <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                                    </span>
                                </button>

                                <div class="operator-route-body" data-route-body>
                                    <div class="p-5">
                                        @if ($times)
                                            <div>
                                                <p class="mb-2 text-xs font-semibold text-slate-500">Khung giờ đi</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($times as $time)
                                                        <span
                                                            class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $time }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-5 grid gap-5 md:grid-cols-[minmax(0,1fr)_150px]">
                                            <div class="grid gap-4">
                                                @foreach ($pickup_points as $point)
                                                    <p class="m-0 flex items-start gap-3 text-sm">
                                                        <i class="fas fa-map-marker-alt mt-1 text-blue-500"
                                                            aria-hidden="true"></i>
                                                        <span>{{ $decode($point) }}</span>
                                                    </p>
                                                @endforeach
                                                @foreach ($dropoff_points as $point)
                                                    <p class="m-0 flex items-start gap-3 text-sm">
                                                        <i class="fas fa-map-marker-alt mt-1 text-red-500"
                                                            aria-hidden="true"></i>
                                                        <span>{{ $decode($point) }}</span>
                                                    </p>
                                                @endforeach
                                            </div>

                                            <div class="flex flex-col items-start justify-end md:items-end">
                                                <span class="text-xs font-semibold text-slate-500">Giá từ</span>
                                                <strong
                                                    class="mt-1 text-2xl font-semibold text-red-500">{{ $format_price($route_price) }}</strong>
                                                <a class="mt-4 inline-flex h-10 items-center justify-center rounded-lg bg-amber-400 px-5 text-sm font-semibold text-slate-950 transition hover:bg-amber-500"
                                                    href="{{ esc_url($booking_url($route)) }}">
                                                    Chọn chuyến
                                                </a>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-5 flex flex-wrap gap-x-4 gap-y-2 border-t border-slate-100 pt-3 text-[11px] font-medium text-slate-500">
                                            @if ($vehicle_names)
                                                <span>{{ implode(' · ', array_slice($vehicle_names, 0, 2)) }}</span>
                                            @endif
                                            @foreach (array_slice($amenities, 0, 3) as $amenity)
                                                <span>{{ $decode($amenity['title'] ?? '') }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if (count($routes) > 6)
                        @php
                            $remaining_routes_count = count($routes) - 6;
                        @endphp
                        <div class="mt-6 text-center" data-routes-toggle-container>
                            <button type="button"
                                class="inline-flex h-12 items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 text-sm font-semibold text-white transition hover:bg-blue-700 shadow-md hover:shadow-lg"
                                data-routes-toggle-btn data-state="collapsed"
                                data-remaining="{{ $remaining_routes_count }}">
                                <span>Xem thêm {{ $remaining_routes_count }} tuyến</span>
                                <i class="fas fa-chevron-down ml-1" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                @else
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center">
                        <h3 class="text-lg font-semibold text-slate-950">Chưa có tuyến đường</h3>
                        <p class="mt-2 text-sm text-slate-500">Dailyve đang cập nhật lịch chạy mới nhất của
                            {{ $operator_name }}.</p>
                    </div>
                @endif
            </section>

            <section
                class="operator-content-layout mx-auto grid max-w-7xl gap-6 px-4 pb-8 sm:px-6 lg:grid-cols-[300px_minmax(0,1fr)] lg:px-8">
                <aside class="min-w-0 space-y-5 order-last lg:order-none">
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-950">Lý do khách đặt vé với Dailyve</h2>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            @foreach (['Giá vé cạnh tranh, nhiều ưu đãi', 'Hỗ trợ 24/7, tư vấn tận tâm', 'Không cần thanh toán trước', 'Chọn chỗ ngồi theo ý muốn', 'Hoàn vé dễ dàng, nhanh chóng'] as $reason)
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check-circle mt-0.5 text-blue-600" aria-hidden="true"></i>
                                    <span>{{ $reason }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-950">Hướng dẫn đặt vé</h2>
                        <ol class="mt-4 space-y-3 text-sm text-slate-600">
                            @foreach (['Chọn tuyến đường, giờ chạy phù hợp', 'Chọn ghế & kiểm tra thông tin', 'Nhập thông tin hành khách', 'Thanh toán và nhận vé điện tử'] as $index => $step)
                                <li class="flex items-start gap-3">
                                    <span
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">{{ $index + 1 }}</span>
                                    <span>{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="rounded-xl bg-blue-600 p-5 text-white shadow-sm">
                        <h2 class="text-base font-semibold">Tải ứng dụng Dailyve</h2>
                        <p class="mt-1 text-xs text-blue-100">Đặt vé nhanh chóng, ưu đãi mỗi ngày</p>
                        <div class="mt-4 grid grid-cols-[88px_minmax(0,1fr)] gap-3">
                            <img class="h-[88px] w-[88px] rounded-lg bg-white p-1" src="{{ esc_url($qrCode) }}"
                                alt="QR tải ứng dụng Dailyve" loading="lazy" decoding="async">
                            <div class="grid content-center gap-2">
                                <img class="h-9 w-auto rounded-md" src="{{ esc_url($appStore) }}" alt="App Store"
                                    loading="lazy" decoding="async">
                                <img class="h-9 w-auto rounded-md" src="{{ esc_url($googlePlay) }}" alt="Google Play"
                                    loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="operator-content-main min-w-0 space-y-5">
                    <div
                        class="operator-content-card min-w-0 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex overflow-x-auto border-b border-slate-200 px-3">
                            @foreach ([['id' => 'intro', 'label' => 'Giới thiệu'], ['id' => 'offices', 'label' => 'Số điện thoại'], ['id' => 'amenities', 'label' => 'Tiện ích']] as $tab)
                                <button type="button"
                                    class="operator-tab-trigger h-14 shrink-0 border-b-2 px-5 text-sm font-semibold transition {{ $loop->first ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-blue-600' }}"
                                    data-operator-tab="{{ $tab['id'] }}">
                                    {{ $tab['label'] }}
                                </button>
                            @endforeach
                        </div>

                        <div class="p-5 md:p-6">
                            <div class="operator-tab-pane" data-operator-tab-pane="intro">
                                <div class="operator-intro-shell">
                                    <div class="operator-intro-collapse e-content max-w-none overflow-hidden"
                                        data-intro-collapse>
                                        @php the_content(); @endphp
                                    </div>
                                    <button type="button" class="operator-intro-toggle mt-3" data-intro-toggle
                                        aria-expanded="false" hidden>
                                        Xem thêm
                                    </button>
                                </div>

                                {{-- @if ($amenities)
                                <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                                    @foreach (array_slice($amenities, 0, 8) as $amenity)
                                        @php $amenity_icon = $normalize_image_url($amenity['image'] ?? ''); @endphp
                                        <div
                                            class="flex items-center gap-3 rounded-lg bg-slate-50 p-3 text-sm font-semibold text-slate-700">
                                            @if ($amenity_icon)
                                                <img class="h-5 w-5 object-contain" src="{{ esc_url($amenity_icon) }}"
                                                    alt="" loading="lazy" decoding="async">
                                            @else
                                                <i class="fas fa-check text-blue-600" aria-hidden="true"></i>
                                            @endif
                                            <span>{{ $decode($amenity['title'] ?? '') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif --}}
                            </div>

                            <div class="operator-tab-pane" data-operator-tab-pane="offices" hidden>
                                @if ($contact_cities)
                                    @php
                                        $valid_cities = [];
                                        foreach ($contact_cities as $city_group) {
                                            $city_name = $decode($city_group['city'] ?? '');
                                            $offices = is_array($city_group['offices'] ?? null)
                                                ? $city_group['offices']
                                                : [];
                                            if (!empty($offices) && $city_name !== '') {
                                                $valid_cities[] = $city_group;
                                            }
                                        }
                                    @endphp

                                    @if (!empty($valid_cities))
                                        <div
                                            class="flex gap-5 overflow-x-auto border-b border-slate-100 pb-3 mb-6 scrollbar-none">
                                            @foreach ($valid_cities as $index => $city_group)
                                                @php
                                                    $city_name = $decode($city_group['city'] ?? '');
                                                    $city_slug = sanitize_title($city_name);
                                                @endphp
                                                <button type="button"
                                                    class="operator-office-tab-btn shrink-0 text-sm font-bold transition {{ $index === 0 ? 'text-slate-950' : 'text-slate-400 hover:text-slate-600' }}"
                                                    data-office-tab="{{ $city_slug }}">
                                                    {{ $city_name }}
                                                </button>
                                            @endforeach
                                        </div>

                                        <div class="operator-office-panes">
                                            @foreach ($valid_cities as $index => $city_group)
                                                @php
                                                    $city_name = $decode($city_group['city'] ?? '');
                                                    $city_slug = sanitize_title($city_name);
                                                    $offices = is_array($city_group['offices'] ?? null)
                                                        ? $city_group['offices']
                                                        : [];
                                                @endphp
                                                <div class="operator-office-pane" data-office-pane="{{ $city_slug }}"
                                                    {{ $index > 0 ? 'hidden' : '' }}>
                                                    <div class="grid gap-4 md:grid-cols-2">
                                                        @foreach ($offices as $office)
                                                            <article
                                                                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300 hover:shadow-md">
                                                                <h4 class="text-base font-semibold text-slate-950">
                                                                    {{ $decode($office['office_name'] ?? 'Văn phòng') }}
                                                                </h4>
                                                                @if (!empty($office['address']))
                                                                    <p
                                                                        class="mt-2.5 flex items-start gap-2.5 text-sm text-slate-600">
                                                                        <i class="fas fa-map-marker-alt mt-0.5 text-blue-500 shrink-0"
                                                                            aria-hidden="true"></i>
                                                                        <span>{{ $decode($office['address']) }}</span>
                                                                    </p>
                                                                @endif
                                                                @if (!empty($office['phones']))
                                                                    <div class="mt-4 flex flex-wrap gap-2">
                                                                        @foreach ((array) $office['phones'] as $phone)
                                                                            <a class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3.5 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-100 hover:text-blue-700"
                                                                                href="tel:{{ preg_replace('/\D+/', '', $phone) }}">
                                                                                <i class="fas fa-phone-alt text-[10px]"
                                                                                    aria-hidden="true"></i>
                                                                                {{ $phone }}
                                                                            </a>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </article>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-500">Dailyve đang cập nhật địa chỉ văn phòng của
                                            {{ $operator_name }}.</p>
                                    @endif
                                @else
                                    <p class="text-sm text-slate-500">Dailyve đang cập nhật địa chỉ văn phòng của
                                        {{ $operator_name }}.</p>
                                @endif
                            </div>

                            <div class="operator-tab-pane" data-operator-tab-pane="amenities" hidden>
                                @if ($amenities)
                                    <div class="grid gap-4 md:grid-cols-2">
                                        @foreach ($amenities as $amenity)
                                            @php $amenity_icon = $normalize_image_url($amenity['image'] ?? ''); @endphp
                                            <article class="flex gap-4 rounded-xl border border-slate-200 p-4">
                                                <span
                                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                                    @if ($amenity_icon)
                                                        <img class="h-6 w-6 object-contain"
                                                            src="{{ esc_url($amenity_icon) }}" alt=""
                                                            rel="nofollow noreferrer" loading="lazy" decoding="async">
                                                    @else
                                                        <i class="fas fa-check" aria-hidden="true"></i>
                                                    @endif
                                                </span>
                                                <span>
                                                    <strong
                                                        class="block text-sm text-slate-950">{{ $decode($amenity['title'] ?? '') }}</strong>
                                                    @if (!empty($amenity['description']))
                                                        <span
                                                            class="mt-1 block text-sm text-slate-500">{{ $decode($amenity['description']) }}</span>
                                                    @endif
                                                </span>
                                            </article>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500">Tiện ích nhà xe đang được cập nhật.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <section id="operator-reviews"
                        class="operator-review-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm md:p-6"
                        data-operator-reveal>
                        <div class="flex items-center justify-between gap-4">
                            <h2 class="text-lg font-semibold text-slate-950">Đánh giá nhà xe</h2>
                            <div class="flex shrink-0 items-center gap-2">
                                <strong
                                    class="dailyve-operator-detail__display text-2xl font-semibold text-slate-950">{{ number_format((float) ($rating ?: 4.8), 1) }}</strong>
                                <i class="fas fa-star text-amber-400" aria-hidden="true"></i>
                            </div>
                        </div>

                        @if ($rating_details)
                            <div class="operator-rating-detail-grid mt-5">
                                @foreach ($rating_details as $detail)
                                    @php
                                        $score = (float) ($detail['score'] ?? 0);
                                        $width = max(0, min(100, ($score / 5) * 100));
                                    @endphp
                                    <div class="operator-rating-detail">
                                        <div class="operator-rating-detail__label">
                                            <span>{{ $decode($detail['label'] ?? '') }}</span>
                                            <strong>{{ number_format($score, 1) }}</strong>
                                        </div>
                                        <span class="operator-rating-detail__bar">
                                            <span style="width: {{ $width }}%"></span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="my-6 border-t border-slate-200"></div>

                        <h3 class="text-lg font-semibold text-slate-950">Chi tiết đánh giá</h3>

                        <div class="operator-review-list mt-4" data-review-list>
                            @if ($reviews_list)
                                @foreach (array_slice($reviews_list, 0, 1) as $review)
                                    @php
                                        $review_name = $decode(
                                            $review['reviewer_name'] ?? ($review['name'] ?? 'Khách hàng'),
                                        );
                                        $review_avatar = $normalize_image_url($review['social_avatar'] ?? '');
                                        $review_rating = max(0, min(5, (int) ($review['rating'] ?? 5)));
                                        $review_comment = trim($decode($review['comment'] ?? ''));
                                        $review_date = !empty($review['created_at'])
                                            ? date_i18n('d/m/Y', strtotime($review['created_at']))
                                            : (!empty($review['trip_date'])
                                                ? date_i18n('d/m/Y', strtotime($review['trip_date']))
                                                : '');
                                        $review_vehicle = $decode(
                                            $review['vehicle_type'] ?? ($review['vehicle_name'] ?? ''),
                                        );
                                        $review_route = $decode(
                                            $review['trip_name'] ?? ($review['route_name'] ?? ($review['route'] ?? '')),
                                        );
                                    @endphp
                                    <article class="operator-review-item" data-review-item>
                                        <div class="flex items-start gap-3">
                                            @if ($review_avatar)
                                                <img class="h-10 w-10 shrink-0 rounded-full object-cover"
                                                    src="{{ esc_url($review_avatar) }}" rel="nofollow noreferrer"
                                                    alt="{{ esc_attr($review_name) }}" loading="lazy" decoding="async">
                                            @else
                                                <span class="operator-review-avatar">
                                                    {{ function_exists('getInitialsNameToAvatar') ? getInitialsNameToAvatar($review_name) : mb_substr($review_name, 0, 2) }}
                                                </span>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                    <strong
                                                        class="text-sm font-semibold text-slate-950">{{ $review_name }}</strong>
                                                    @if ($review_date)
                                                        <span
                                                            class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600">
                                                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                                                            Đã đi · {{ $review_date }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-1 flex text-xs text-amber-400"
                                                    aria-label="{{ $review_rating }} sao">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i class="{{ $i < $review_rating ? 'fas' : 'far' }} fa-star"
                                                            aria-hidden="true"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>

                                        @if ($review_comment)
                                            <p class="mt-3 text-sm leading-6 text-slate-700">
                                                {!! nl2br(esc_html($review_comment)) !!}
                                            </p>
                                        @endif

                                        @if ($review_vehicle || $review_route)
                                            <div class="mt-3 space-y-1 text-xs text-slate-400">
                                                @if ($review_vehicle)
                                                    <p class="m-0">Loại xe: {{ $review_vehicle }}</p>
                                                @endif
                                                @if ($review_route)
                                                    <p class="m-0">Tuyến đường: {{ $review_route }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </article>
                                @endforeach
                            @else
                                <div
                                    class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-500">
                                    Dailyve đang cập nhật nhận xét chi tiết từ hành khách. Điểm đánh giá hiện được
                                    tổng hợp từ dữ liệu đặt vé và phản hồi đã ghi nhận.
                                </div>
                            @endif
                        </div>

                        @if ($display_review_total)
                            <button type="button" class="operator-review-more mt-4" data-review-drawer-open
                                data-total="{{ $display_review_total }}" aria-controls="operator-reviews-drawer">
                                Xem tất cả {{ number_format($display_review_total, 0, ',', '.') }} đánh giá
                            </button>
                        @endif
                    </section>

                    <article class="operator-faq-card">
                        <h2 class="operator-faq-card__title">Câu hỏi thường gặp về {{ $operator_name }}</h2>
                        <div class="operator-faq-card__list">
                            @foreach ($faq_items as $question => $answer)
                                <details class="operator-faq-card__item">
                                    <summary class="operator-faq-card__question">
                                        <span>{{ $question }}</span>
                                        <svg class="operator-faq-card__icon" width="16" height="16"
                                            viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </summary>
                                    <div class="operator-faq-card__answer">
                                        <p>{{ $answer }}</p>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </article>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 pb-6 sm:px-6 lg:px-8">
                <article class="operator-highlights-card">
                    <h2 class="operator-highlights-card__title">Thông tin nổi bật</h2>
                    <div class="operator-highlights-card__grid">
                        <div class="operator-highlights-card__item">
                            <i class="fas fa-route" aria-hidden="true"></i>
                            <span>{{ $operator_name }} hiện có
                                {{ $route_count ? number_format($route_count, 0, ',', '.') : count($routes) }} tuyến đường
                                được Dailyve cập nhật.</span>
                        </div>
                        <div class="operator-highlights-card__item">
                            <i class="fas fa-bus" aria-hidden="true"></i>
                            <span>Dòng xe:
                                {{ $vehicle_types ? implode(', ', array_slice(array_map(fn($item) => $decode($item['name'] ?? ''), $vehicle_types), 0, 3)) : 'đang cập nhật' }}.</span>
                        </div>
                        <div class="operator-highlights-card__item">
                            <i class="fas fa-building" aria-hidden="true"></i>
                            <span>{{ $office_count ?: 'Nhiều' }} văn phòng/điểm liên hệ được ghi nhận trên hệ
                                thống.</span>
                        </div>
                    </div>
                </article>
            </section>



            <div id="operator-reviews-drawer" class="operator-review-drawer" data-operator-reviews-drawer
                data-company-id="{{ esc_attr($operator_id) }}"
                data-ajax-url="{{ esc_url(admin_url('admin-ajax.php')) }}"
                data-review-total="{{ esc_attr($display_review_total) }}"
                data-total-pages="{{ esc_attr(max(1, (int) ceil(max(1, $display_review_total) / 10))) }}"
                aria-hidden="true">
                <div class="operator-review-drawer__backdrop" data-review-drawer-close></div>
                <aside class="operator-review-drawer__panel" role="dialog" aria-modal="true"
                    aria-labelledby="operator-reviews-drawer-title" tabindex="-1">
                    <header class="operator-review-drawer__header">
                        <button type="button" class="operator-review-drawer__back" data-review-drawer-close
                            aria-label="Đóng đánh giá">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        </button>
                        <h2 id="operator-reviews-drawer-title">Đánh giá nhà xe</h2>
                    </header>

                    <div class="operator-review-drawer__body">
                        <section class="operator-review-drawer__summary" aria-label="Tổng quan đánh giá">
                            <div class="operator-review-drawer__summary-heading">
                                <h3>Đánh giá nhà xe</h3>
                                <div class="operator-review-drawer__score">
                                    <strong>{{ number_format((float) ($rating ?: 4.8), 1) }}</strong>
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                </div>
                            </div>

                            @if ($rating_details)
                                <div class="operator-rating-detail-grid mt-5">
                                    @foreach ($rating_details as $detail)
                                        @php
                                            $score = (float) ($detail['score'] ?? 0);
                                            $width = max(0, min(100, ($score / 5) * 100));
                                        @endphp
                                        <div class="operator-rating-detail">
                                            <div class="operator-rating-detail__label">
                                                <span>{{ $decode($detail['label'] ?? '') }}</span>
                                                <strong>{{ number_format($score, 1) }}</strong>
                                            </div>
                                            <span class="operator-rating-detail__bar">
                                                <span style="width: {{ $width }}%"></span>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <div class="operator-review-drawer__divider"></div>

                        <h3 class="operator-review-drawer__section-title">Chi tiết đánh giá</h3>

                        <div class="operator-review-drawer__filters" role="tablist" aria-label="Lọc đánh giá">
                            <button type="button" class="is-active" data-review-filter="all" role="tab"
                                aria-selected="true">
                                Tất cả
                                <span
                                    data-review-filter-count="all">({{ number_format($display_review_total, 0, ',', '.') }})</span>
                            </button>
                            <button type="button" data-review-filter="comment" role="tab" aria-selected="false">
                                Có nhận xét
                                <span data-review-filter-count="comment">(0)</span>
                            </button>
                            <button type="button" data-review-filter="image" role="tab" aria-selected="false">
                                Có hình ảnh
                                <span data-review-filter-count="image">(0)</span>
                            </button>
                        </div>

                        <div class="operator-review-drawer__loading" data-review-loading hidden>
                            <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
                            <span>Đang tải đánh giá...</span>
                        </div>

                        <div class="operator-review-drawer__list" data-review-drawer-list>
                            @if ($reviews_list)
                                @foreach (array_slice($reviews_list, 0, 10) as $review)
                                    @php
                                        $review_name = $decode(
                                            $review['reviewer_name'] ?? ($review['name'] ?? 'Khách hàng'),
                                        );
                                        $review_avatar = $normalize_image_url($review['social_avatar'] ?? '');
                                        $review_rating = max(0, min(5, (int) ($review['rating'] ?? 5)));
                                        $review_comment = trim($decode($review['comment'] ?? ''));
                                        $review_date = !empty($review['created_at'])
                                            ? date_i18n('d/m/Y', strtotime($review['created_at']))
                                            : (!empty($review['trip_date'])
                                                ? date_i18n('d/m/Y', strtotime($review['trip_date']))
                                                : '');
                                        $review_vehicle = $decode(
                                            $review['vehicle_type'] ?? ($review['vehicle_name'] ?? ''),
                                        );
                                        $review_route = $decode(
                                            $review['trip_name'] ?? ($review['route_name'] ?? ($review['route'] ?? '')),
                                        );
                                        $review_images = array_values(array_filter((array) ($review['images'] ?? [])));
                                    @endphp
                                    <article class="operator-review-drawer-comment" data-review-loaded-item
                                        data-has-comment="{{ $review_comment ? 'true' : 'false' }}"
                                        data-has-image="{{ $review_images ? 'true' : 'false' }}">
                                        <div class="operator-review-drawer-comment__head">
                                            @if ($review_avatar)
                                                <img class="operator-review-drawer-comment__avatar"
                                                    src="{{ esc_url($review_avatar) }}"
                                                    alt="{{ esc_attr($review_name) }}" loading="lazy" decoding="async">
                                            @else
                                                <span class="operator-review-avatar">
                                                    {{ function_exists('getInitialsNameToAvatar') ? getInitialsNameToAvatar($review_name) : mb_substr($review_name, 0, 2) }}
                                                </span>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="operator-review-drawer-comment__meta">
                                                    <strong>{{ $review_name }}</strong>
                                                    @if ($review_date)
                                                        <span>
                                                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                                                            Đã đi · {{ $review_date }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="operator-review-drawer-comment__stars"
                                                    aria-label="{{ $review_rating }} sao">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i class="{{ $i < $review_rating ? 'fas' : 'far' }} fa-star"
                                                            aria-hidden="true"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>

                                        @if ($review_comment)
                                            <p class="operator-review-drawer-comment__content">
                                                {!! nl2br(esc_html($review_comment)) !!}
                                            </p>
                                        @endif

                                        @if ($review_images)
                                            <div class="operator-review-drawer-comment__images">
                                                @foreach (array_slice($review_images, 0, 4) as $image)
                                                    @php $review_image_url = $normalize_image_url($image); @endphp
                                                    @if ($review_image_url)
                                                        <img src="{{ esc_url($review_image_url) }}" alt=""
                                                            loading="lazy" decoding="async" rel="nofollow noreferrer">
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                        @if ($review_vehicle || $review_route)
                                            <div class="operator-review-drawer-comment__trip">
                                                @if ($review_vehicle)
                                                    <p>Loại xe: {{ $review_vehicle }}</p>
                                                @endif
                                                @if ($review_route)
                                                    <p>Tuyến đường: {{ $review_route }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </article>
                                @endforeach
                            @else
                                <div class="operator-review-drawer__empty">
                                    Dailyve đang cập nhật nhận xét chi tiết từ hành khách.
                                </div>
                            @endif
                        </div>

                        <div class="operator-review-drawer__empty" data-review-filter-empty hidden>
                            Chưa có đánh giá phù hợp với bộ lọc này.
                        </div>

                        @if ($operator_id && $display_review_total)
                            <div class="operator-review-drawer__load" data-review-load-container hidden>
                                <button type="button" data-review-load-more data-next-page="1"
                                    data-total-pages="{{ max(1, (int) ceil(max(1, $display_review_total) / 10)) }}">
                                    <span>Xem thêm nhận xét</span>
                                    <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>

        <script>
            (function() {
                function initOperatorDetail() {
                    var root = document.querySelector('[data-operator-detail]');
                    if (!root) return;
                    if (root.getAttribute('data-operator-ready') === 'true') return;
                    root.setAttribute('data-operator-ready', 'true');

                    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                    root.querySelectorAll('[data-operator-reveal]').forEach(function(item, index) {
                        window.setTimeout(function() {
                            item.classList.add('is-visible');
                        }, reducedMotion ? 0 : Math.min(index * 45, 360));
                    });

                    root.querySelectorAll('[data-intro-collapse]').forEach(function(panel) {
                        var shell = panel.closest('.operator-intro-shell') || panel.parentElement;
                        var toggle = shell ? shell.querySelector('[data-intro-toggle]') : null;
                        if (!toggle) return;

                        function getCollapsedHeight() {
                            var value = window.getComputedStyle(panel).getPropertyValue(
                                '--operator-intro-collapsed-height');
                            var parsed = parseFloat(value);
                            return Number.isFinite(parsed) ? parsed : 560;
                        }

                        function syncIntroToggle() {
                            var hasOverflow = panel.scrollHeight > getCollapsedHeight() + 8;
                            panel.classList.toggle('has-overflow', hasOverflow);
                            toggle.hidden = !hasOverflow;
                        }

                        toggle.addEventListener('click', function() {
                            var expanded = !panel.classList.contains('is-expanded');
                            panel.classList.toggle('is-expanded', expanded);
                            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                            toggle.textContent = expanded ? 'Thu gọn' : 'Xem thêm';

                            if (!expanded) {
                                panel.scrollIntoView({
                                    behavior: reducedMotion ? 'auto' : 'smooth',
                                    block: 'start'
                                });
                            }
                        });

                        panel.querySelectorAll('img').forEach(function(image) {
                            if (image.complete) return;
                            image.addEventListener('load', syncIntroToggle, {
                                once: true
                            });
                        });

                        window.addEventListener('resize', syncIntroToggle);
                        syncIntroToggle();
                    });

                    var reviewDrawer = root.querySelector('[data-operator-reviews-drawer]');
                    if (reviewDrawer) {
                        var reviewPanel = reviewDrawer.querySelector('.operator-review-drawer__panel');
                        var reviewList = reviewDrawer.querySelector('[data-review-drawer-list]');
                        var reviewLoading = reviewDrawer.querySelector('[data-review-loading]');
                        var reviewLoadContainer = reviewDrawer.querySelector('[data-review-load-container]');
                        var reviewLoadButton = reviewDrawer.querySelector('[data-review-load-more]');
                        var reviewFilterEmpty = reviewDrawer.querySelector('[data-review-filter-empty]');
                        var reviewAjaxUrl = reviewDrawer.getAttribute('data-ajax-url') || '';
                        var reviewCompanyId = reviewDrawer.getAttribute('data-company-id') || '';
                        var reviewTotal = parseInt(reviewDrawer.getAttribute('data-review-total'), 10) || 0;
                        var activeReviewFilter = 'all';
                        var lastReviewFocus = null;

                        function formatReviewNumber(value) {
                            return (parseInt(value, 10) || 0).toLocaleString('vi-VN');
                        }

                        function getReviewItems() {
                            if (!reviewList) return [];
                            return Array.from(reviewList.querySelectorAll(
                                '.operator-review-drawer-comment, .rating-tab__comments-list__item'
                            ));
                        }

                        function reviewItemHasComment(item) {
                            var declared = item.getAttribute('data-has-comment');
                            if (declared === 'true' || declared === 'false') {
                                return declared === 'true';
                            }

                            var content = item.querySelector(
                                '.operator-review-drawer-comment__content, .rating-tab__comments-list__item-content'
                            );

                            return !!(content && content.textContent.trim().length);
                        }

                        function reviewItemHasImage(item) {
                            var declared = item.getAttribute('data-has-image');
                            if (declared === 'true' || declared === 'false') {
                                return declared === 'true';
                            }

                            return !!item.querySelector(
                                '.operator-review-drawer-comment__images img, .rating-tab__comments-list__item-gallery img'
                            );
                        }

                        function updateReviewFilterCounts() {
                            var items = getReviewItems();
                            var counts = {
                                all: reviewTotal || items.length,
                                comment: items.filter(reviewItemHasComment).length,
                                image: items.filter(reviewItemHasImage).length
                            };

                            Object.keys(counts).forEach(function(key) {
                                var counter = reviewDrawer.querySelector('[data-review-filter-count="' + key +
                                    '"]');
                                if (counter) counter.textContent = '(' + formatReviewNumber(counts[key]) + ')';
                            });
                        }

                        function applyReviewFilter() {
                            var visibleCount = 0;
                            getReviewItems().forEach(function(item) {
                                var visible = activeReviewFilter === 'all' ||
                                    (activeReviewFilter === 'comment' && reviewItemHasComment(item)) ||
                                    (activeReviewFilter === 'image' && reviewItemHasImage(item));

                                item.hidden = !visible;
                                if (visible) visibleCount += 1;
                            });

                            if (reviewFilterEmpty) {
                                var hasBaseEmpty = reviewList && reviewList.querySelector('.operator-review-drawer__empty');
                                reviewFilterEmpty.hidden = visibleCount > 0 || !!hasBaseEmpty || (reviewLoading && !
                                    reviewLoading.hidden);
                            }
                        }

                        function setReviewLoading(isLoading) {
                            if (reviewLoading) reviewLoading.hidden = !isLoading;
                            if (reviewLoadButton) reviewLoadButton.disabled = isLoading;
                        }

                        function syncReviewLoadButton(nextPage, totalPages, hasHtml) {
                            if (!reviewLoadButton || !reviewLoadContainer) return;

                            reviewLoadButton.setAttribute('data-next-page', String(nextPage));
                            reviewLoadButton.setAttribute('data-total-pages', String(totalPages));
                            reviewLoadContainer.hidden = !hasHtml || nextPage > totalPages;

                            var text = reviewLoadButton.querySelector('span');
                            var icon = reviewLoadButton.querySelector('i');
                            if (text) text.textContent = 'Xem thêm nhận xét';
                            if (icon) icon.className = 'fas fa-chevron-down';
                            reviewLoadButton.disabled = false;
                        }

                        function loadOperatorReviews(page, replace) {
                            if (!reviewAjaxUrl || !reviewCompanyId || !reviewList) {
                                updateReviewFilterCounts();
                                applyReviewFilter();
                                return Promise.resolve();
                            }

                            setReviewLoading(true);
                            if (reviewLoadButton) {
                                var text = reviewLoadButton.querySelector('span');
                                var icon = reviewLoadButton.querySelector('i');
                                if (text) text.textContent = 'Đang tải...';
                                if (icon) icon.className = 'fas fa-spinner fa-spin';
                            }

                            var url = new URL(reviewAjaxUrl, window.location.origin);
                            url.searchParams.set('action', 'get_review_ajax_company');
                            url.searchParams.set('companyId', reviewCompanyId);
                            url.searchParams.set('partnerName', 'vexere');
                            url.searchParams.set('page', page);

                            return fetch(url.toString(), {
                                    credentials: 'same-origin'
                                })
                                .then(function(response) {
                                    return response.json();
                                })
                                .then(function(response) {
                                    var html = response && response.html ? response.html : '';
                                    var totalPages = parseInt(response && response.total, 10) ||
                                        parseInt(reviewDrawer.getAttribute('data-total-pages'), 10) || 1;

                                    if (replace) {
                                        reviewList.innerHTML = html ||
                                            '<div class="operator-review-drawer__empty">Chưa có nhận xét nào.</div>';
                                    } else if (html) {
                                        reviewList.insertAdjacentHTML('beforeend', html);
                                    }

                                    reviewDrawer.setAttribute('data-loaded', 'true');
                                    syncReviewLoadButton(page + 1, totalPages, !!html);
                                    updateReviewFilterCounts();
                                    applyReviewFilter();
                                })
                                .catch(function(error) {
                                    console.error('Error loading operator reviews:', error);
                                    if (reviewLoadContainer) reviewLoadContainer.hidden = true;
                                    updateReviewFilterCounts();
                                    applyReviewFilter();
                                })
                                .finally(function() {
                                    setReviewLoading(false);
                                });
                        }

                        function openReviewDrawer() {
                            lastReviewFocus = document.activeElement;
                            reviewDrawer.classList.add('is-open');
                            reviewDrawer.setAttribute('aria-hidden', 'false');
                            document.body.classList.add('operator-review-drawer-lock');
                            if (reviewPanel) reviewPanel.focus({
                                preventScroll: true
                            });

                            if (reviewDrawer.getAttribute('data-loaded') !== 'true') {
                                loadOperatorReviews(1, true);
                            } else {
                                updateReviewFilterCounts();
                                applyReviewFilter();
                            }
                        }

                        function closeReviewDrawer() {
                            reviewDrawer.classList.remove('is-open');
                            reviewDrawer.setAttribute('aria-hidden', 'true');
                            document.body.classList.remove('operator-review-drawer-lock');
                            if (lastReviewFocus && typeof lastReviewFocus.focus === 'function') {
                                lastReviewFocus.focus({
                                    preventScroll: true
                                });
                            }
                        }

                        root.querySelectorAll('[data-review-drawer-open]').forEach(function(button) {
                            button.addEventListener('click', openReviewDrawer);
                        });

                        reviewDrawer.querySelectorAll('[data-review-drawer-close]').forEach(function(button) {
                            button.addEventListener('click', closeReviewDrawer);
                        });

                        reviewDrawer.querySelectorAll('[data-review-filter]').forEach(function(button) {
                            button.addEventListener('click', function() {
                                activeReviewFilter = button.getAttribute('data-review-filter') || 'all';
                                reviewDrawer.querySelectorAll('[data-review-filter]').forEach(function(
                                    item) {
                                    var active = item === button;
                                    item.classList.toggle('is-active', active);
                                    item.setAttribute('aria-selected', active ? 'true' : 'false');
                                });
                                applyReviewFilter();
                            });
                        });

                        if (reviewLoadButton) {
                            reviewLoadButton.addEventListener('click', function() {
                                var nextPage = parseInt(reviewLoadButton.getAttribute('data-next-page'), 10) || 1;
                                loadOperatorReviews(nextPage, false);
                            });
                        }

                        document.addEventListener('keydown', function(event) {
                            if (event.key === 'Escape' && reviewDrawer.classList.contains('is-open')) {
                                closeReviewDrawer();
                            }
                        });

                        updateReviewFilterCounts();
                        applyReviewFilter();
                    }

                    root.querySelectorAll('[data-operator-gallery]').forEach(function(gallery) {
                        var track = gallery.querySelector('[data-gallery-track]');
                        var slides = Array.from(gallery.querySelectorAll('[data-gallery-slide]'));
                        var dots = Array.from(gallery.querySelectorAll('[data-gallery-dot]'));
                        var prev = gallery.querySelector('[data-gallery-prev]');
                        var next = gallery.querySelector('[data-gallery-next]');
                        var galleryShell = gallery.closest('[data-operator-reveal]') || root;
                        var thumbs = Array.from(galleryShell.querySelectorAll('[data-gallery-thumb]'));
                        var thumbViewport = galleryShell.querySelector('[data-gallery-thumbs]');
                        var current = 0;
                        var autoplay = null;
                        var pointerStartX = null;

                        if (!track || !slides.length) return;

                        function setActive(index, userAction) {
                            current = (index + slides.length) % slides.length;
                            track.style.transform = 'translate3d(-' + (current * 100) + '%, 0, 0)';

                            slides.forEach(function(slide, slideIndex) {
                                slide.classList.toggle('is-active', slideIndex === current);
                            });
                            dots.forEach(function(dot, dotIndex) {
                                dot.classList.toggle('is-active', dotIndex === current);
                            });
                            thumbs.forEach(function(thumb, thumbIndex) {
                                thumb.classList.toggle('is-active', thumbIndex === current);
                            });

                            if (thumbViewport && thumbs[current]) {
                                var activeThumb = thumbs[current];
                                var thumbLeft = activeThumb.offsetLeft;
                                var thumbRight = thumbLeft + activeThumb.offsetWidth;
                                var viewLeft = thumbViewport.scrollLeft;
                                var viewRight = viewLeft + thumbViewport.clientWidth;
                                var nextScrollLeft = null;

                                if (thumbLeft < viewLeft) {
                                    nextScrollLeft = thumbLeft;
                                } else if (thumbRight > viewRight) {
                                    nextScrollLeft = thumbRight - thumbViewport.clientWidth;
                                }

                                if (nextScrollLeft !== null) {
                                    if (thumbViewport.scrollTo) {
                                        thumbViewport.scrollTo({
                                            left: nextScrollLeft,
                                            behavior: userAction && !reducedMotion ? 'smooth' : 'auto'
                                        });
                                    } else {
                                        thumbViewport.scrollLeft = nextScrollLeft;
                                    }
                                }
                            }

                            if (userAction) {
                                restartAutoplay();
                            }
                        }

                        function stopAutoplay() {
                            if (autoplay) {
                                window.clearInterval(autoplay);
                                autoplay = null;
                            }
                        }

                        function startAutoplay() {
                            if (reducedMotion || slides.length < 2 || autoplay) return;
                            autoplay = window.setInterval(function() {
                                setActive(current + 1, false);
                            }, 5200);
                        }

                        function restartAutoplay() {
                            stopAutoplay();
                            startAutoplay();
                        }

                        if (prev) {
                            prev.addEventListener('click', function() {
                                setActive(current - 1, true);
                            });
                        }

                        if (next) {
                            next.addEventListener('click', function() {
                                setActive(current + 1, true);
                            });
                        }

                        dots.forEach(function(dot) {
                            dot.addEventListener('click', function() {
                                setActive(parseInt(dot.getAttribute('data-gallery-dot'), 10) || 0,
                                    true);
                            });
                        });

                        thumbs.forEach(function(thumb) {
                            thumb.addEventListener('click', function() {
                                setActive(parseInt(thumb.getAttribute('data-gallery-thumb'), 10) ||
                                    0, true);
                            });
                        });

                        gallery.addEventListener('pointerdown', function(event) {
                            pointerStartX = event.clientX;
                        });

                        gallery.addEventListener('pointerup', function(event) {
                            if (pointerStartX === null) return;
                            var diff = event.clientX - pointerStartX;
                            pointerStartX = null;

                            if (Math.abs(diff) < 40) return;
                            setActive(diff > 0 ? current - 1 : current + 1, true);
                        });

                        gallery.addEventListener('mouseenter', stopAutoplay);
                        gallery.addEventListener('mouseleave', startAutoplay);
                        gallery.addEventListener('focusin', stopAutoplay);
                        gallery.addEventListener('focusout', startAutoplay);

                        setActive(0, false);
                        startAutoplay();
                    });

                    root.querySelectorAll('[data-offers-slider]').forEach(function(slider) {
                        var viewport = slider.querySelector('[data-offers-viewport]');
                        var slides = Array.from(slider.querySelectorAll('[data-offer-slide]'));
                        var dots = Array.from(slider.querySelectorAll('[data-offer-dot]'));
                        var prevButtons = Array.from(slider.querySelectorAll('[data-offers-prev]'));
                        var nextButtons = Array.from(slider.querySelectorAll('[data-offers-next]'));
                        var current = 0;
                        var ticking = false;

                        if (!viewport || !slides.length) return;

                        function getMaxIndex() {
                            var maxScrollLeft = Math.max(0, viewport.scrollWidth - viewport.clientWidth);
                            var maxIndex = 0;

                            slides.forEach(function(slide, index) {
                                if (slide.offsetLeft <= maxScrollLeft + 1) {
                                    maxIndex = index;
                                }
                            });

                            return Math.max(0, Math.min(maxIndex, slides.length - 1));
                        }

                        function updateState(index) {
                            var maxIndex = getMaxIndex();
                            current = Math.max(0, Math.min(index, maxIndex));

                            dots.forEach(function(dot, dotIndex) {
                                dot.hidden = dotIndex > maxIndex;
                                dot.classList.toggle('is-active', dotIndex === current);
                            });

                            prevButtons.forEach(function(button) {
                                button.disabled = current === 0;
                            });
                            nextButtons.forEach(function(button) {
                                button.disabled = current === maxIndex;
                            });
                        }

                        function scrollToSlide(index) {
                            var nextIndex = Math.max(0, Math.min(index, getMaxIndex()));
                            if (!slides[nextIndex]) return;

                            slides[nextIndex].scrollIntoView({
                                behavior: reducedMotion ? 'auto' : 'smooth',
                                block: 'nearest',
                                inline: 'start'
                            });
                            updateState(nextIndex);
                        }

                        function syncFromScroll() {
                            var viewportLeft = viewport.getBoundingClientRect().left;
                            var closestIndex = 0;
                            var closestDistance = Infinity;

                            slides.forEach(function(slide, index) {
                                var distance = Math.abs(slide.getBoundingClientRect().left - viewportLeft);
                                if (distance < closestDistance) {
                                    closestDistance = distance;
                                    closestIndex = index;
                                }
                            });

                            updateState(closestIndex);
                            ticking = false;
                        }

                        prevButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                scrollToSlide(current - 1);
                            });
                        });

                        nextButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                scrollToSlide(current + 1);
                            });
                        });

                        dots.forEach(function(dot) {
                            dot.addEventListener('click', function() {
                                scrollToSlide(parseInt(dot.getAttribute('data-offer-dot'), 10) ||
                                    0);
                            });
                        });

                        viewport.addEventListener('scroll', function() {
                            if (ticking) return;
                            ticking = true;
                            window.requestAnimationFrame(syncFromScroll);
                        }, {
                            passive: true
                        });

                        window.addEventListener('resize', function() {
                            updateState(current);
                        });

                        updateState(0);
                    });

                    function setRouteOpen(card, open, instant) {
                        var body = card.querySelector('[data-route-body]');
                        var toggle = card.querySelector('[data-route-toggle]');
                        if (!body) return;

                        card.classList.toggle('is-open', open);
                        card.setAttribute('data-route-open', open ? 'true' : 'false');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                        }

                        // Clear any active transitions for this card to prevent animation overlapping
                        if (body.dvTimeout) {
                            window.clearTimeout(body.dvTimeout);
                            body.dvTimeout = null;
                        }

                        if (instant) {
                            body.style.height = open ? 'auto' : '0px';
                            return;
                        }

                        if (open) {
                            // Open logic: start from 0px, trigger layout reflow, then set to scrollHeight
                            body.style.height = '0px';
                            body.offsetHeight;
                            body.style.height = body.scrollHeight + 'px';

                            body.dvTimeout = window.setTimeout(function() {
                                if (card.classList.contains('is-open')) {
                                    body.style.height = 'auto';
                                }
                            }, 360);
                        } else {
                            // Close logic: start from current scrollHeight, trigger reflow, then transition to 0px
                            if (body.style.height === 'auto' || !body.style.height) {
                                body.style.height = body.scrollHeight + 'px';
                            }
                            body.offsetHeight;
                            body.style.height = '0px';
                        }
                    }

                    // Toggle Show More / Show Less routes & Filtering logic (centralized & state-aware)
                    var routesToggleBtn = root.querySelector('[data-routes-toggle-btn]');
                    var routesToggleContainer = root.querySelector('[data-routes-toggle-container]');
                    var routeCards = Array.from(root.querySelectorAll('[data-route-card]'));

                    function updateRouteVisibility() {
                        var activeFilterBtn = root.querySelector('[data-operator-filter].is-active');
                        var filter = activeFilterBtn ? activeFilterBtn.getAttribute('data-operator-filter') : 'all';
                        var isExpanded = routesToggleBtn ? routesToggleBtn.getAttribute('data-state') === 'expanded' :
                            false;

                        var matchingCount = 0;
                        var totalMatching = 0;

                        routeCards.forEach(function(card) {
                            var cardFilter = card.getAttribute('data-route-from');
                            var matchesFilter = (filter === 'all' || cardFilter === filter);
                            if (matchesFilter) {
                                totalMatching++;
                            }
                        });

                        routeCards.forEach(function(card) {
                            var cardFilter = card.getAttribute('data-route-from');
                            var matchesFilter = (filter === 'all' || cardFilter === filter);

                            if (!matchesFilter) {
                                card.classList.add('is-hidden-by-limit');
                                card.classList.remove('is-expanded-by-btn');
                                return;
                            }

                            // Matches filter
                            if (filter === 'all') {
                                if (matchingCount >= 6 && !isExpanded) {
                                    card.classList.add('is-hidden-by-limit');
                                    card.classList.remove('is-expanded-by-btn');
                                } else {
                                    card.classList.remove('is-hidden-by-limit');
                                    if (matchingCount >= 6) {
                                        card.classList.add('is-expanded-by-btn');
                                    } else {
                                        card.classList.remove('is-expanded-by-btn');
                                    }
                                }
                            } else {
                                // Specific province filter: always show matching routes
                                card.classList.remove('is-hidden-by-limit');
                                card.classList.remove('is-expanded-by-btn');
                            }

                            if (card.classList.contains('is-open')) {
                                var body = card.querySelector('[data-route-body]');
                                if (body) {
                                    body.style.height = 'auto';
                                }
                            }

                            matchingCount++;
                        });

                        if (routesToggleContainer && routesToggleBtn) {
                            if (filter === 'all' && totalMatching > 6) {
                                routesToggleContainer.style.display = 'block';
                                var remainingCount = totalMatching - 6;
                                var span = routesToggleBtn.querySelector('span');
                                var icon = routesToggleBtn.querySelector('i');
                                if (isExpanded) {
                                    if (span) span.textContent = 'Ẩn bớt tuyến';
                                    if (icon) icon.className = 'fas fa-chevron-up ml-1';
                                } else {
                                    if (span) span.textContent = 'Xem thêm ' + remainingCount + ' tuyến';
                                    if (icon) icon.className = 'fas fa-chevron-down ml-1';
                                }
                            } else {
                                routesToggleContainer.style.display = 'none';
                            }
                        }
                    }

                    if (routesToggleBtn) {
                        routesToggleBtn.addEventListener('click', function() {
                            var state = routesToggleBtn.getAttribute('data-state') || 'collapsed';
                            var isCollapsed = (state === 'collapsed');

                            if (isCollapsed) {
                                routesToggleBtn.setAttribute('data-state', 'expanded');
                                updateRouteVisibility();
                            } else {
                                routesToggleBtn.setAttribute('data-state', 'collapsed');
                                updateRouteVisibility();
                                // Scroll back up to the route section smoothly
                                var routeSection = document.getElementById('operator-routes');
                                if (routeSection) {
                                    routeSection.scrollIntoView({
                                        behavior: 'smooth'
                                    });
                                }
                            }
                        });
                    }

                    root.querySelectorAll('[data-route-card]').forEach(function(card) {
                        setRouteOpen(card, card.getAttribute('data-route-open') === 'true', true);

                        var toggle = card.querySelector('[data-route-toggle]');
                        if (!toggle) return;

                        toggle.addEventListener('click', function() {
                            setRouteOpen(card, !card.classList.contains('is-open'), false);
                        });
                    });

                    root.querySelectorAll('[data-operator-filter]').forEach(function(button) {
                        button.addEventListener('click', function() {
                            root.querySelectorAll('[data-operator-filter]').forEach(function(item) {
                                item.classList.remove('is-active', 'bg-blue-600', 'text-white',
                                    'hover:text-white');
                                item.classList.add('border', 'border-slate-200', 'bg-white',
                                    'text-slate-600');
                            });
                            button.classList.add('is-active', 'bg-blue-600', 'text-white',
                                'hover:text-white');
                            button.classList.remove('border', 'border-slate-200', 'bg-white',
                                'text-slate-600');

                            updateRouteVisibility();
                        });
                    });

                    // Initialize the correct visibility on load
                    updateRouteVisibility();

                    root.querySelectorAll('[data-operator-tab]').forEach(function(button) {
                        button.addEventListener('click', function() {
                            var tab = button.getAttribute('data-operator-tab');

                            root.querySelectorAll('[data-operator-tab]').forEach(function(item) {
                                item.classList.remove('border-blue-600', 'text-blue-600');
                                item.classList.add('border-transparent', 'text-slate-500');
                            });
                            button.classList.add('border-blue-600', 'text-blue-600');
                            button.classList.remove('border-transparent', 'text-slate-500');

                            root.querySelectorAll('[data-operator-tab-pane]').forEach(function(pane) {
                                pane.hidden = pane.getAttribute('data-operator-tab-pane') !== tab;
                            });
                        });
                    });

                    root.querySelectorAll('[data-office-tab]').forEach(function(button) {
                        button.addEventListener('click', function() {
                            var targetTab = button.getAttribute('data-office-tab');

                            root.querySelectorAll('[data-office-tab]').forEach(function(btn) {
                                btn.classList.remove('text-slate-950');
                                btn.classList.add('text-slate-400', 'hover:text-slate-600');
                            });
                            button.classList.add('text-slate-950');
                            button.classList.remove('text-slate-400', 'hover:text-slate-600');

                            root.querySelectorAll('[data-office-pane]').forEach(function(pane) {
                                pane.hidden = pane.getAttribute('data-office-pane') !== targetTab;
                            });
                        });
                    });

                    root.querySelectorAll('[data-copy-code]').forEach(function(button) {
                        button.addEventListener('click', function() {
                            var code = button.getAttribute('data-copy-code');
                            if (navigator.clipboard && code) {
                                navigator.clipboard.writeText(code);
                            }
                            button.textContent = 'Đã lưu mã';
                        });
                    });

                    var saveButton = root.querySelector('[data-save-operator]');
                    if (saveButton) {
                        saveButton.addEventListener('click', function() {
                            var postId = saveButton.getAttribute('data-save-operator');
                            try {
                                var saved = JSON.parse(localStorage.getItem('dailyve_saved_operators') || '[]');
                                if (saved.indexOf(postId) === -1) saved.push(postId);
                                localStorage.setItem('dailyve_saved_operators', JSON.stringify(saved));
                            } catch (e) {}
                            saveButton.innerHTML = 'Đã lưu <i class="fas fa-heart" aria-hidden="true"></i>';
                        });
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initOperatorDetail, {
                        once: true
                    });
                } else {
                    initOperatorDetail();
                }
            })();
        </script>
    @endwhile
@endsection

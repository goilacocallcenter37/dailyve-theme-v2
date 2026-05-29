@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php
            the_post();
            $post_id = get_the_ID();
            $station_name = html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8');
            $post_content = get_the_content(null, false, $post_id);

            $address = function_exists('get_field')
                ? get_field('bus_station_address', $post_id)
                : get_post_meta($post_id, 'bus_station_address', true);
            $address =
                $address ?:
                (function_exists('get_field')
                    ? get_field('company_address', $post_id)
                    : get_post_meta($post_id, 'company_address', true));
            $address = $address ?: 'Chưa cập nhật địa chỉ';

            $hotline = function_exists('get_field')
                ? get_field('bus_station_phone', $post_id)
                : get_post_meta($post_id, 'bus_station_phone', true);
            $hotline =
                $hotline ?:
                (function_exists('get_field')
                    ? get_field('company_phone', $post_id)
                    : get_post_meta($post_id, 'company_phone', true));
            $hotline = $hotline ?: '1900 888 684';

            $hours = function_exists('get_field')
                ? get_field('operating_hours', $post_id)
                : get_post_meta($post_id, 'operating_hours', true);
            $hours = $hours ?: '05:00 - 22:00';

            $website = function_exists('get_field')
                ? get_field('website_url', $post_id)
                : get_post_meta($post_id, 'website_url', true);
            $website = $website ?: 'https://dailyve.com.vn';

            $location_field = function_exists('get_field')
                ? get_field('station_point', $post_id)
                : get_post_meta($post_id, 'station_point', true);
            if (empty($location_field)) {
                $location_field = function_exists('get_field')
                    ? get_field('schedule_departure_point', $post_id)
                    : get_post_meta($post_id, 'schedule_departure_point', true);
            }
            if (empty($location_field)) {
                $location_field = function_exists('get_field')
                    ? get_field('location_id', $post_id)
                    : get_post_meta($post_id, 'location_id', true);
            }

            $location_id = '';
            if (is_array($location_field)) {
                $location_id = $location_field['value'] ?? ($location_field[0] ?? '');
            } else {
                $location_id = (string) $location_field;
            }
            if (empty($location_id)) {
                $location_id = '69e71ed15139c113eb3d3b89';
            }

            $direction = isset($_GET['direction']) && $_GET['direction'] === 'to' ? 'to' : 'from';
            $paged = isset($_GET['page_num']) ? max(1, (int) $_GET['page_num']) : 1;
            $page_size = 10;

            $routes_result = \App\dailyve_get_station_routes($location_id, $paged, $page_size);
            $api_error = is_wp_error($routes_result) ? $routes_result->get_error_message() : null;
            $routes_data = $api_error ? [] : $routes_result;

            $items_from = $routes_data['departing']['items'] ?? [];
            $items_to = $routes_data['arriving']['items'] ?? [];
            $items = $direction === 'from' ? $items_from : $items_to;
            $total_items = $routes_data[$direction === 'from' ? 'departing' : 'arriving']['total'] ?? count($items);
            $total_pages =
                $routes_data[$direction === 'from' ? 'departing' : 'arriving']['totalPages'] ??
                (int) ceil($total_items / $page_size);

            $provinces = [];
            foreach ($items as $item) {
                $opp = $direction === 'from' ? $item['to'] ?? [] : $item['from'] ?? [];
                $prov_name = $opp['province_name'] ?? '';
                if ($prov_name && !in_array($prov_name, $provinces, true)) {
                    $provinces[] = $prov_name;
                }
            }

            $directions_data = [
                'from' => [
                    'items' => $items_from,
                    'total' => $routes_data['departing']['total'] ?? count($items_from),
                    'totalPages' =>
                        $routes_data['departing']['totalPages'] ??
                        (int) ceil(($routes_data['departing']['total'] ?? count($items_from)) / $page_size),
                ],
                'to' => [
                    'items' => $items_to,
                    'total' => $routes_data['arriving']['total'] ?? count($items_to),
                    'totalPages' =>
                        $routes_data['arriving']['totalPages'] ??
                        (int) ceil(($routes_data['arriving']['total'] ?? count($items_to)) / $page_size),
                ],
            ];

            $gallery = [];
            $gallery_field = function_exists('get_field')
                ? get_field('bus_station_gallery', $post_id)
                : get_post_meta($post_id, 'bus_station_gallery', true);
            if (is_array($gallery_field)) {
                foreach ($gallery_field as $img) {
                    $url = is_array($img)
                        ? $img['url'] ?? ($img['sizes']['large'] ?? '')
                        : wp_get_attachment_image_url((int) $img, 'large');
                    if ($url) {
                        $gallery[] = $url;
                    }
                }
            }
            if (empty($gallery)) {
                $thumb_id = get_post_thumbnail_id($post_id);
                $gallery[] = $thumb_id
                    ? wp_get_attachment_image_url($thumb_id, 'large')
                    : 'https://object.dailyve.com/dailyve/wp-content/uploads/2026/05/banner_web.webp';
            }

            $format_price = fn($p) => function_exists('formatVND')
                ? formatVND($p) . 'đ'
                : number_format($p, 0, ',', '.') . 'đ';
            $get_initials = fn($n) => function_exists('getInitialsNameToAvatar') ? getInitialsNameToAvatar($n) : 'DLV';

            $map_query = urlencode($station_name . ' ' . $address);
            $map_embed_url = "https://maps.google.com/maps?q={$map_query}&t=&z=15&ie=UTF8&iwloc=&output=embed";

            $tabs = [
                ['id' => 'intro', 'label' => 'Giới thiệu', 'icon' => 'fas fa-info-circle'],
                ['id' => 'map', 'label' => 'Sơ đồ bến xe', 'icon' => 'fas fa-map'],
                ['id' => 'amenities', 'label' => 'Tiện ích', 'icon' => 'fas fa-concierge-bell'],
                ['id' => 'rules', 'label' => 'Quy định', 'icon' => 'fas fa-clipboard-list'],
                ['id' => 'shipping', 'label' => 'Gửi hàng', 'icon' => 'fas fa-box-open'],
                ['id' => 'transit', 'label' => 'Hướng dẫn di chuyển', 'icon' => 'fas fa-route'],
            ];

            $highlights = [
                [
                    'icon' => 'fas fa-parking',
                    'label' => 'Bãi đỗ xe rộng rãi',
                    'desc' => 'Bãi đỗ xe ô tô, xe máy rộng rãi, an ninh 24/24.',
                ],
                [
                    'icon' => 'fas fa-wind',
                    'label' => 'Khu vực chờ máy lạnh',
                    'desc' => 'Khu vực ngồi chờ thoáng mát trang bị điều hòa nhiệt độ.',
                ],
                [
                    'icon' => 'fas fa-wifi',
                    'label' => 'Wifi miễn phí',
                    'desc' => 'Hệ thống mạng không dây tốc độ cao phủ sóng toàn bộ khuôn viên.',
                ],
                [
                    'icon' => 'fas fa-utensils',
                    'label' => 'Nhà hàng, quán ăn',
                    'desc' => 'Chuỗi cửa hàng tiện lợi, quán ăn đa dạng đảm bảo vệ sinh.',
                ],
                [
                    'icon' => 'fas fa-ticket-alt',
                    'label' => 'Quầy vé tự động',
                    'desc' => 'Hệ thống ki-ốt tra cứu lịch trình và xuất vé điện tử tức thì.',
                ],
                [
                    'icon' => 'fas fa-pump-soap',
                    'label' => 'Nhà vệ sinh sạch sẽ',
                    'desc' => 'Khu vực vệ sinh công cộng đạt tiêu chuẩn sạch sẽ, tiện nghi.',
                ],
            ];

            $canonical_url = get_permalink($post_id);
            $get_switcher_url = function ($dir) use ($canonical_url) {
                return add_query_arg(['direction' => $dir], $canonical_url);
            };
            $get_page_url = function ($p) use ($canonical_url, $direction) {
                return add_query_arg(['direction' => $direction, 'page_num' => $p], $canonical_url);
            };
        @endphp

        <script>
            window.route_data = {
                to_id: @json($location_id),
                to_name: @json($station_name),
                from_id: '',
                from_name: ''
            };
            window.stationRoutesSummary = @json($routes_data);
            window.stationDirection = @json($direction);
            window.stationCurrentPage = @json($paged);
        </script>

        <div class="bx-detail bg-[#f4f6fb] text-slate-700 font-sans pb-20 overflow-x-hidden">
            <style>
                /* ── Typography ── */
                .bx-display {
                    font-family: 'Be Vietnam Pro', 'Segoe UI', sans-serif;
                    font-weight: 700;
                    letter-spacing: -0.02em;
                }

                /* ── Surface tokens ── */
                .bx-card {
                    background: #fff;
                    border: 1px solid #e8ecf4;
                    border-radius: 20px;
                    box-shadow: 0 2px 16px rgba(30, 60, 120, .05);
                }

                .bx-card-lg {
                    background: #fff;
                    border: 1px solid #e8ecf4;
                    border-radius: 24px;
                    box-shadow: 0 4px 24px rgba(30, 60, 120, .07);
                }

                /* ── Direction switcher ── */
                .dir-tab {
                    transition: all .2s;
                }

                .dir-tab-active {
                    background: linear-gradient(135deg, #1a6fef, #2196f3) !important;
                    color: #fff !important;
                    box-shadow: 0 4px 14px rgba(33, 150, 243, .30);
                }

                /* ── Province pills ── */
                .prov-pill {
                    transition: all .2s;
                    white-space: nowrap;
                }

                .prov-pill-active {
                    background: linear-gradient(135deg, #1a6fef, #2196f3) !important;
                    color: #fff !important;
                    box-shadow: 0 4px 12px rgba(33, 150, 243, .25);
                }

                .pills-scroll {
                    display: flex;
                    gap: 8px;
                    overflow-x: auto;
                    padding-bottom: 4px;
                    scrollbar-width: thin;
                    scrollbar-color: #cbd5e1 transparent;
                    /* Fix: ensure pills don't wrap and scroll properly */
                    flex-wrap: nowrap;
                }

                .pills-scroll::-webkit-scrollbar {
                    height: 4px;
                }

                .pills-scroll::-webkit-scrollbar-track {
                    background: transparent;
                }

                .pills-scroll::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 99px;
                }

                /* ── Route cards ── */
                .route-card {
                    background: #fff;
                    border: 1.5px solid #e8ecf4;
                    border-radius: 20px;
                    transition: all .25s cubic-bezier(.4, 0, .2, 1);
                    display: flex;
                    flex-direction: column;
                }

                .route-card:hover {
                    border-color: #93c5fd;
                    box-shadow: 0 8px 28px rgba(33, 150, 243, .13);
                    transform: translateY(-2px);
                }

                /* ── Tab triggers (left sidebar) ── */
                .tab-trg {
                    transition: all .2s;
                    border-left: 3px solid transparent;
                }

                .tab-trg:hover {
                    background: #f0f7ff;
                    color: #1a6fef;
                }

                .tab-trg-active {
                    background: #e8f1fd !important;
                    color: #1a6fef !important;
                    border-left-color: #1a6fef !important;
                    font-weight: 700;
                }

                .tab-pane {
                    display: none;
                }

                .tab-pane.active {
                    display: block;
                }

                /* ── Operators scroll ── */
                .ops-scroll {
                    max-height: 300px;
                    overflow-y: auto;
                    scrollbar-width: thin;
                    scrollbar-color: #e2e8f0 transparent;
                }

                .ops-scroll::-webkit-scrollbar {
                    width: 4px;
                }

                .ops-scroll::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 99px;
                }

                /* ── Carousel ── */
                #station-slides-track {
                    display: flex;
                    transition: transform .45s cubic-bezier(.4, 0, .2, 1);
                }

                /* ── Skeleton pulse ── */
                @keyframes bx-pulse {

                    0%,
                    100% {
                        opacity: 1
                    }

                    50% {
                        opacity: .45
                    }
                }

                .bx-skeleton {
                    background: #e8ecf4;
                    border-radius: 8px;
                    animation: bx-pulse 1.6s infinite;
                }

                /* ── Action buttons ── */
                .action-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    height: 42px;
                    padding: 0 16px;
                    border-radius: 12px;
                    border: 1.5px solid #e8ecf4;
                    background: #fff;
                    font-size: 12px;
                    font-weight: 700;
                    color: #475569;
                    transition: all .2s;
                    text-decoration: none !important;
                    white-space: nowrap;
                }

                .action-btn:hover {
                    border-color: #93c5fd;
                    color: #1a6fef;
                    background: #f0f7ff;
                }

                /* ── Stat chips on hero ── */
                .stat-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: #f4f6fb;
                    border: 1px solid #e8ecf4;
                    border-radius: 10px;
                    padding: 6px 12px;
                    font-size: 12px;
                    font-weight: 600;
                    color: #475569;
                }

                /* ── Route Card Accordion ── */
                .route-card {
                    overflow: hidden;
                }

                .route-card.is-open .route-chevron {
                    transform: rotate(180deg);
                    background-color: #e8f1fd;
                    color: #1a6fef;
                }

                .route-card-body {
                    transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }

                .is-hidden-by-limit {
                    display: none !important;
                }
            </style>

            {{-- Breadcrumbs --}}
            <x-breadcrumb :items="[
                ['title' => 'Dailyve', 'url' => home_url('/')],
                ['title' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
                ['title' => 'Bến xe khách', 'url' => home_url('/ve-xe-khach/ben-xe/')],
                ['title' => $station_name, 'url' => ''],
            ]" preset="directory" />

            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="bx-card-lg p-4 mb-6">
                    <div id="react-search-form" class="min-h-[120px]" data-initial-service="bus"></div>
                </div>

                <div class="bx-card-lg overflow-hidden mb-6">
                    <div class="grid lg:grid-cols-[1.15fr_1fr]">

                        {{-- Image Carousel --}}
                        <div class="relative overflow-hidden bg-slate-900" style="min-height:280px;max-height:420px;">
                            <div id="station-slides-track" class="h-full" style="width:{{ count($gallery) * 100 }}%;">
                                @foreach ($gallery as $img_url)
                                    <div class="shrink-0 h-full relative" style="width:{{ 100 / count($gallery) }}%;">
                                        <img class="w-full h-full object-cover" src="{{ esc_url($img_url) }}"
                                            alt="{{ esc_attr($station_name) }}"
                                            loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async"
                                            style="display:block;">
                                    </div>
                                @endforeach
                            </div>

                            {{-- Gradient overlay --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none">
                            </div>

                            @if (count($gallery) > 1)
                                <button type="button" onclick="moveStationSlide(-1)"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-white/85 hover:bg-white text-slate-800 shadow-lg transition-all hover:scale-105 active:scale-95 backdrop-blur-sm">
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </button>
                                <button type="button" onclick="moveStationSlide(1)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-white/85 hover:bg-white text-slate-800 shadow-lg transition-all hover:scale-105 active:scale-95 backdrop-blur-sm">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>

                                {{-- Dot indicators --}}
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-1.5">
                                    @foreach ($gallery as $i => $_)
                                        <span
                                            class="slide-dot w-1.5 h-1.5 rounded-full transition-all {{ $i === 0 ? 'bg-white w-4' : 'bg-white/50' }}"></span>
                                    @endforeach
                                </div>
                            @endif

                            <span
                                class="absolute bottom-4 right-4 z-20 flex items-center gap-1.5 bg-black/50 text-white text-xs font-semibold px-3 py-1.5 rounded-lg backdrop-blur-sm"
                                id="station-slide-counter">
                                <i class="fas fa-camera"></i> <span id="slide-num">1</span>/{{ count($gallery) }}
                            </span>
                        </div>

                        {{-- Station Info --}}
                        <div class="flex flex-col p-6 lg:p-8">
                            {{-- Badges --}}
                            <div class="flex items-center gap-2 flex-wrap mb-4">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 border border-blue-100 px-3 py-1 text-[12px] font-bold text-[#2196f3]">
                                    <i class="fas fa-shield-alt"></i> Bến xe đối tác
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-100 px-3 py-1 text-[12px] font-bold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Đã xác thực
                                </span>
                            </div>

                            <h1 class="bx-display text-2xl lg:text-3xl text-slate-950 leading-tight mb-5">
                                {{ $station_name }}
                            </h1>

                            {{-- Info rows --}}
                            <div class="space-y-3 text-sm text-slate-600">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
                                        <i class="fas fa-map-marker-alt text-xs"></i>
                                    </span>
                                    <span class="pt-1.5 leading-relaxed text-xs text-slate-600">{{ $address }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <a href="tel:{{ preg_replace('/\D+/', '', $hotline) }}"
                                        class="flex items-center gap-2.5 no-underline! text-slate-700 hover:text-[#2196f3] transition-colors group">
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-500 group-hover:bg-blue-100 transition-colors">
                                            <i class="fas fa-phone-alt text-xs"></i>
                                        </span>
                                        <div>
                                            <span
                                                class="block text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Hotline</span>
                                            <strong class="text-xs font-bold text-slate-900">{{ $hotline }}</strong>
                                        </div>
                                    </a>

                                    <div class="flex items-center gap-2.5">
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                            <i class="fas fa-clock text-xs"></i>
                                        </span>
                                        <div>
                                            <span
                                                class="block text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Giờ
                                                mở cửa</span>
                                            <strong class="text-xs font-bold text-slate-900">{{ $hours }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Stats row --}}
                            <div class="flex flex-wrap gap-2 mt-5">
                                <span class="stat-chip"><i class="fas fa-bus text-blue-500"></i>
                                    {{ number_format($total_items, 0, ',', '.') }} tuyến xe</span>
                                <span class="stat-chip"><i class="fas fa-star text-amber-400"></i> 3.9 đánh giá</span>
                                <span class="stat-chip"><i class="fas fa-ticket-alt text-rose-500"></i> Đặt vé online</span>
                            </div>

                            {{-- Action buttons --}}
                            <div class="pt-5 border-t border-slate-100 mt-5 grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $map_query }}"
                                    target="_blank" rel="noopener noreferrer" class="action-btn">
                                    <i class="fas fa-directions text-blue-500"></i> Chỉ đường
                                </a>
                                <a href="tel:{{ preg_replace('/\D+/', '', $hotline) }}" class="action-btn">
                                    <i class="fas fa-phone text-emerald-500"></i> Gọi điện
                                </a>
                                <a href="{{ esc_url($website) }}" target="_blank" rel="noopener noreferrer"
                                    class="action-btn">
                                    <i class="fas fa-globe text-indigo-500"></i> Website
                                </a>
                                <button type="button"
                                    onclick="alert('Đã lưu bến xe {{ $station_name }} vào mục yêu thích!')"
                                    class="action-btn">
                                    <i class="far fa-bookmark text-amber-500"></i> Lưu bến xe
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════
                 ROUTES SECTION
            ═══════════════════════════════════════════════════════════════ --}}
            <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <section class="bx-card-lg p-5 sm:p-7" aria-labelledby="route-list-title">

                    {{-- Section header --}}
                    <div
                        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-5 mb-5">
                        <div>
                            <h2 id="route-list-title" class="bx-display text-xl sm:text-2xl text-slate-950">
                                Các tuyến đường đến & đi từ bến xe
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Tìm kiếm vé xe khách tiện lợi từ 100+ nhà xe hoạt động tại đây.
                            </p>
                        </div>

                        {{-- Direction switcher --}}
                        <div class="flex items-center bg-slate-100 p-1 rounded-2xl shrink-0 self-start sm:self-auto"
                            role="tablist">
                            <button type="button" onclick="switchDirection('from')" data-dir="from"
                                class="dir-tab-btn {{ $direction === 'from' ? 'dir-tab-active' : 'text-slate-600' }} px-4 py-2 text-sm font-bold rounded-xl transition-all">
                                Từ bến xe
                            </button>
                            <button type="button" onclick="switchDirection('to')" data-dir="to"
                                class="dir-tab-btn {{ $direction === 'to' ? 'dir-tab-active' : 'text-slate-600' }} px-4 py-2 text-sm font-bold rounded-xl transition-all">
                                Đến bến xe
                            </button>
                        </div>
                    </div>

                    @foreach (['from', 'to'] as $dir_key)
                        @php
                            $dir_items = $directions_data[$dir_key]['items'] ?? [];
                            $dir_total = $directions_data[$dir_key]['total'] ?? count($dir_items);
                            $dir_total_pages =
                                $directions_data[$dir_key]['totalPages'] ?? (int) ceil($dir_total / $page_size);

                            $dir_provinces = [];
                            foreach ($dir_items as $item) {
                                $opp = $dir_key === 'from' ? $item['to'] ?? [] : $item['from'] ?? [];
                                $prov_name = $opp['province_name'] ?? '';
                                if ($prov_name && !in_array($prov_name, $dir_provinces, true)) {
                                    $dir_provinces[] = $prov_name;
                                }
                            }
                        @endphp

                        <div id="route-dir-{{ $dir_key }}"
                            class="route-dir-wrapper {{ $direction === $dir_key ? '' : 'hidden' }}">
                            @if (empty($dir_items) && !$api_error)
                                <div
                                    class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 py-16 text-center">
                                    <i class="fas fa-route text-5xl text-slate-200 mb-4 block"></i>
                                    <h3 class="text-base font-bold text-slate-900">Không tìm thấy chuyến xe nào</h3>
                                    <p class="mt-2 text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                                        Hiện tại không có dữ liệu hành trình nào hoạt động tại bến xe trong hệ thống.
                                    </p>
                                </div>
                            @elseif (!empty($dir_items))
                                {{-- Province filter bar --}}
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="pills-scroll flex-1 min-w-0"
                                        id="provinces-filter-container-{{ $dir_key }}" role="tablist">
                                        <button type="button"
                                            class="prov-pill prov-pill-active shrink-0 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700"
                                            onclick="filterByProvince('all')">
                                            Tất cả
                                        </button>
                                        @foreach ($dir_provinces as $prov)
                                            <button type="button"
                                                class="prov-pill shrink-0 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700"
                                                onclick="filterByProvince('{{ esc_attr($prov) }}')">
                                                {{ $prov }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <span id="total-routes-count-{{ $dir_key }}"
                                        class="hidden sm:inline-flex shrink-0 items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-2 rounded-xl border border-blue-100">
                                        <i class="fas fa-route text-blue-400"></i>
                                        {{ number_format($dir_total, 0, ',', '.') }} tuyến
                                    </span>
                                </div>

                                {{-- Cards grid --}}
                                <div id="routes-grid-{{ $dir_key }}" class="grid gap-4 md:grid-cols-2 items-start">
                                    @foreach ($dir_items as $item)
                                        @php
                                            $from_name = $item['from']['name'] ?? '';
                                            $to_name = $item['to']['name'] ?? '';
                                            $prov_name =
                                                $dir_key === 'from'
                                                    ? $item['to']['province_name'] ?? ''
                                                    : $item['from']['province_name'] ?? '';
                                            $raw_operators = $item['operators'] ?? [];
                                            $operators = [];
                                            $seen_operator_ids = [];
                                            foreach ($raw_operators as $op) {
                                                $op_id = trim((string) ($op['operator_id'] ?? ($op['id'] ?? '')));

                                                if ($op_id) {
                                                    if (!isset($seen_operator_ids[$op_id])) {
                                                        $seen_operator_ids[$op_id] = true;
                                                        $operators[] = $op;
                                                    }
                                                } else {
                                                    $operators[] = $op;
                                                }
                                            }

                                            $op_count = $item['operator_count'] ?? 0;
                                            $trip_count = $item['trip_count'] ?? 0;
                                            $min_price = $item['min_price'] ?? 0;

                                            $totalOperators = intval($op_count ?: count($operators));
                                            $remainingOperators = max($totalOperators - 8, 0);

                                            $fromName = trim($item['from']['name'] ?? $station_name ?? '');
                                            $toName = trim($item['to']['name'] ?? $item['to']['province_name'] ?? '');

                                            $from_prov_id = $item['from_province_id'] ?? ($item['from']['province_id'] ?? ($item['from']['id'] ?? ''));
                                            $to_prov_id = $item['to_province_id'] ?? ($item['to']['province_id'] ?? ($item['to']['id'] ?? ''));

                                            $routeSeoUrl = function_exists('\App\dailyve_get_route_seo_url') ? \App\dailyve_get_route_seo_url($fromName, $toName, $station_name ?? '')
                                                : '';

                                            $bookingUrl = add_query_arg([
                                                'from' => $from_prov_id,
                                                'to' => $to_prov_id,
                                                'nameFrom' => $fromName,
                                                'nameTo' => $toName,
                                            ], home_url('/dat-ve-truc-tuyen/'));

                                            $moreOperatorsUrl = !empty($routeSeoUrl)
                                                ? $routeSeoUrl
                                                : $bookingUrl;

                                            $hasRouteSeoUrl = !empty($routeSeoUrl);

                                            $search_query_url = add_query_arg(
                                                [
                                                    'from' => $item['from']['province_id'] ?? '',
                                                    'to' => $item['to']['province_id'] ?? '',
                                                    'nameFrom' => $from_name,
                                                    'nameTo' => $to_name,
                                                    'operator_id' => $op_id ?? '',
                                                    'date' => date(
                                                        'Y-m-d',
                                                        strtotime('+1 day', current_time('timestamp')),
                                                    ),
                                                ],
                                                home_url('/dat-ve-truc-tuyen/'),
                                            );
                                            $is_route_open = $loop->index < 2;
                                        @endphp

                                        <article class="route-card overflow-hidden" data-route-card
                                            data-route-open="{{ $is_route_open ? 'true' : 'false' }}"
                                            data-province="{{ esc_attr($prov_name) }}">

                                            {{-- Card header acts as toggle --}}
                                            <div class="flex items-start justify-between gap-3 p-4 pb-3 border-b border-slate-100 cursor-pointer select-none"
                                                data-route-toggle>
                                                <div class="min-w-0 flex-1">
                                                    <h3
                                                        class="text-sm font-extrabold text-slate-900 leading-tight line-clamp-2 group-hover:text-[#2196f3]">
                                                        {{ $from_name }}
                                                        <span
                                                            class="inline-flex items-center justify-center w-5 h-5 mx-0.5 rounded-full bg-blue-50 text-blue-400 text-[9px] align-middle shrink-0">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </span>
                                                        {{ $to_name }}
                                                    </h3>
                                                    <div
                                                        class="flex items-center gap-2 mt-1.5 text-[12px] text-slate-500 font-medium flex-wrap">
                                                        <span class="flex items-center gap-1">
                                                            <i
                                                                class="fas fa-bus-alt text-blue-400"></i>{{ $op_count }}
                                                            nhà xe
                                                        </span>
                                                        <span class="w-1 h-1 rounded-full bg-slate-200 shrink-0"></span>
                                                        <span class="flex items-center gap-1">
                                                            <i
                                                                class="fas fa-route text-emerald-400"></i>{{ $trip_count }}
                                                            chuyến/ngày
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="shrink-0 flex items-center gap-3">
                                                    <div class="text-right">
                                                        <span
                                                            class="block text-[10px] text-slate-400 font-semibold mb-0.5">Giá
                                                            từ</span>
                                                        <span
                                                            class="text-sm font-black text-rose-500 bg-rose-50 border border-rose-100 px-2.5 py-1 rounded-lg block">
                                                            {{ $format_price($min_price) }}
                                                        </span>
                                                    </div>
                                                    <span
                                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-transform duration-200 route-chevron">
                                                        <i class="fas fa-chevron-down text-xs"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Collapsible Body --}}
                            <div class="route-card-body overflow-hidden transition-all duration-300"
                                                data-route-body style="height: {{ $is_route_open ? 'auto' : '0px' }};">
                                                {{-- Operators list --}}
                                                @if (!empty($operators))
                                                    <div class="px-4 pt-3 pb-4 flex-1">
                                                        <p
                                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                                            Nhà xe nổi bật
                                                            <span class="h-px bg-slate-100 flex-1"></span>
                                                        </p>
                                                        <div class="ops-container space-y-1.5">
                                                            @foreach ($operators as $idx => $op)
                                                                @php
                                                                    if ($idx >= 8) {
                                                                        continue;
                                                                    }
                                                                    $op_avatar =
                                                                        $op['image_url'] ??
                                                                        'https://object.dailyve.com/dailyve/wp-content/uploads/2026/05/nha-xe-chat-luong-cao.webp';
                                                                    $op_name = $op['name'] ?? '';
                                                                    $op_rating =
                                                                        $op['display_rating'] ??
                                                                        ($op['rating'] ?? '4.8');
                                                                    $op_price = $op['min_price'] ?? 0;
                                                                    $op_post_url = $op['media']['wp_url'] ?? '';
                                                                    $op_reviews = $op['review_count'] ?? 0;
                                                                    $badge_colors = [
                                                                        'bg-amber-100 text-amber-700',
                                                                        'bg-slate-100 text-slate-600',
                                                                        'bg-orange-50 text-orange-600',
                                                                    ];
                                                                    $badge_color = $badge_colors[min($idx, 2)];
                                                                    $is_hidden = $idx >= 8;
                                                                @endphp
                                                                <div
                                                                    class="op-item flex items-center gap-2.5 rounded-xl p-2 hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 {{ $is_hidden ? 'js-hidden-op hidden' : '' }}">
                                                                    {{-- Avatar --}}
                                                                    <div class="relative shrink-0">
                                                                        @if ($op_avatar)
                                                                            <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow"
                                                                                src="{{ esc_url($op_avatar) }}"
                                                                                alt="{{ esc_attr($op_name) }}"
                                                                                loading="lazy">
                                                                        @else
                                                                            <span
                                                                                class="flex h-9 w-9 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 text-[10px] font-black text-blue-700 items-center justify-center ring-2 ring-white shadow">
                                                                                {{ $get_initials($op_name) }}
                                                                            </span>
                                                                        @endif
                                                                        <span
                                                                            class="absolute -bottom-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full {{ $badge_color }} text-[8px] font-black ring-2 ring-white">
                                                                            {{ $idx + 1 }}
                                                                        </span>
                                                                    </div>

                                                                    {{-- Info --}}
                                                                    <div class="flex-1 min-w-0">
                                                                        <div
                                                                            class="flex justify-between items-center gap-1">
                                                                            @if ($op_post_url)
                                                                                <a href="{{ esc_url($op_post_url) }}"
                                                                                    class="text-[12px] font-bold text-slate-800 hover:text-[#2196f3] truncate no-underline! leading-tight">{{ $op_name }}</a>
                                                                            @else
                                                                                <span
                                                                                    class="text-[12px] font-bold text-slate-800 truncate leading-tight">{{ $op_name }}</span>
                                                                            @endif
                                                                            <span
                                                                                class="text-[12px] font-bold text-slate-800 shrink-0">{{ $format_price($op_price) }}</span>
                                                                        </div>
                                                                        <div
                                                                            class="flex items-center justify-between mt-1 gap-2">
                                                                            <div class="flex items-center gap-1.5">
                                                                                <span
                                                                                    class="flex items-center gap-0.5 bg-amber-50 text-amber-600 border border-amber-100 px-1.5 py-0.5 rounded-md text-[9px] font-bold">
                                                                                    <i class="fas fa-star text-[8px]"></i>
                                                                                    {{ $op_rating }}
                                                                                </span>
                                                                                @if ($op_reviews > 0)
                                                                                    <span
                                                                                        class="text-[10px] text-slate-400 font-medium">{{ $op_reviews }}
                                                                                        đánh giá</span>
                                                                                @endif
                                                                            </div>
                                                                            @php
                                                                                $op_trip_count = $op['trip_count'] ?? 0;
                                                                                if ($op_trip_count > 10) {
                                                                                    $btn_text = 'Xem 10+ chuyến';
                                                                                } elseif ($op_trip_count > 0) {
                                                                                    $btn_text = "Xem {$op_trip_count} chuyến";
                                                                                } else {
                                                                                    $btn_text = 'Xem chuyến';
                                                                                }

                                                                                $from_prov_id =
                                                                                    $item['from_province_id'] ??
                                                                                    ($item['from']['province_id'] ??
                                                                                        ($item['from']['id'] ?? ''));
                                                                                $to_prov_id =
                                                                                    $item['to_province_id'] ??
                                                                                    ($item['to']['province_id'] ??
                                                                                        ($item['to']['id'] ?? ''));

                                                                                $card_booking_url = add_query_arg(
                                                                                    [
                                                                                        'from' => $from_prov_id,
                                                                                        'to' => $to_prov_id,
                                                                                        'nameFrom' => $from_name,
                                                                                        'nameTo' => $to_name,
                                                                                        'operator_id' =>
                                                                                            $op['operator_id'] ??
                                                                                            ($op['id'] ?? ''),
                                                                                    ],
                                                                                    home_url('/dat-ve-truc-tuyen/'),
                                                                                );
                                                                            @endphp
                                                                            <a href="{!! esc_url($card_booking_url) !!}"
                                                                                data-dailyve-date-range-trigger
                                                                                data-date-range-url="{!! esc_url($card_booking_url) !!}"
                                                                                data-date-range-from-name="{{ esc_attr($from_name) }}"
                                                                                data-date-range-to-name="{{ esc_attr($to_name) }}"
                                                                                data-date-range-service="bus"
                                                                                data-date-range-min="today"
                                                                                class="shrink-0 inline-flex items-center justify-center bg-blue-50 hover:bg-[#2196f3] text-[#2196f3] hover:text-white transition-all text-[12px] font-semibold px-2.5 py-1 rounded-lg no-underline! border border-blue-100 hover:border-[#2196f3]">
                                                                                {{ $btn_text }}
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        @if ($totalOperators > 10)
                                                            <a href="{{ esc_url($moreOperatorsUrl) }}"
                                                                class="mt-2 inline-flex w-full items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100 no-underline!">
                                                                @if ($hasRouteSeoUrl)
                                                                    Xem tất cả {{ $totalOperators }} nhà xe
                                                                @else
                                                                    Xem và đặt vé {{ $fromName }} đi {{ $toName }}
                                                                @endif
                                                            </a>
                                                        @elseif ($remainingOperators > 0)
                                                            <button type="button" onclick="toggleOperators(this)"
                                                                class="mt-2 inline-flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                                                <span class="toggle-label">
                                                                    Xem thêm {{ $remainingOperators }} nhà xe
                                                                </span>
                                                                <i
                                                                    class="fas fa-chevron-down text-[10px] toggle-icon transition-transform"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @else
                                                    {{-- No operators fallback --}}
                                                    <div class="px-4 pb-4 pt-3 flex-1 flex items-center justify-between">
                                                        <span class="text-xs text-slate-400 font-medium">Chưa có thông tin
                                                            nhà
                                                            xe</span>
                                                        <a href="{{ esc_url($search_query_url) }}"
                                                            class="inline-flex items-center gap-1.5 bg-[#2196f3] hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl no-underline! transition-colors">
                                                            <i class="fas fa-search text-[10px]"></i> Tìm chuyến
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </article>
                                    @endforeach
                                </div>

                                {{-- Pagination --}}
                                <div id="routes-pagination-{{ $dir_key }}">
                                    @if ($dir_total_pages > 1)
                                        <nav class="mt-8 flex flex-wrap items-center justify-center gap-2"
                                            aria-label="Phân trang">
                                            @php
                                                // Default paged logic when first load
                                                $current_paged = $direction === $dir_key ? $paged : 1;
                                            @endphp
                                            @if ($current_paged > 1)
                                                <button type="button"
                                                    onclick="fetchRoutesPage({{ $current_paged - 1 }})"
                                                    class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:border-blue-400 hover:text-[#2196f3] transition-colors px-3">
                                                    <i class="fas fa-chevron-left text-xs"></i>
                                                </button>
                                            @endif
                                            @for ($i = 1; $i <= $dir_total_pages; $i++)
                                                @if ($i == 1 || $i == $dir_total_pages || ($i >= $current_paged - 1 && $i <= $current_paged + 1))
                                                    <button type="button" onclick="fetchRoutesPage({{ $i }})"
                                                        class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border text-sm font-bold transition-all {{ $i === $current_paged ? 'bg-[#2196f3] border-[#2196f3] text-white shadow-md shadow-blue-200' : 'bg-white border-slate-200 text-slate-700 hover:border-blue-400 hover:text-[#2196f3]' }}">
                                                        {{ $i }}
                                                    </button>
                                                @elseif ($i == 2 || $i == $dir_total_pages - 1)
                                                    <span class="text-slate-400 font-bold px-1">…</span>
                                                @endif
                                            @endfor
                                            @if ($current_paged < $dir_total_pages)
                                                <button type="button"
                                                    onclick="fetchRoutesPage({{ $current_paged + 1 }})"
                                                    class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:border-blue-400 hover:text-[#2196f3] transition-colors px-3">
                                                    <i class="fas fa-chevron-right text-xs"></i>
                                                </button>
                                            @endif
                                        </nav>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </section>
            </main>

            {{-- ═══════════════════════════════════════════════════════════════
                 INFO SECTION — horizontal tabs + left sidebar + main content
            ═══════════════════════════════════════════════════════════════ --}}
            <section class="mx-auto max-w-7xl px-4 mt-6 sm:px-6 lg:px-8" id="station-info-section">

                {{-- Horizontal Tab Bar --}}
                <div class="bx-card mb-5 overflow-x-auto">
                    <div class="flex items-center min-w-max border-b border-slate-100">
                        @php
                            $info_tabs = [
                                ['id' => 'intro', 'label' => 'Giới thiệu', 'icon' => 'fas fa-info-circle'],
                                ['id' => 'phone', 'label' => 'Số điện thoại', 'icon' => 'fas fa-phone-alt'],
                                ['id' => 'amenities', 'label' => 'Tiện ích', 'icon' => 'fas fa-concierge-bell'],
                                ['id' => 'address', 'label' => 'Địa chỉ', 'icon' => 'fas fa-map-marker-alt'],
                                ['id' => 'hours', 'label' => 'Giờ hoạt động', 'icon' => 'fas fa-clock'],
                                ['id' => 'transit', 'label' => 'Hướng dẫn đi bến', 'icon' => 'fas fa-route'],
                            ];
                        @endphp
                        @foreach ($info_tabs as $idx => $tab)
                            <button type="button" onclick="switchInfoTab('{{ $tab['id'] }}')"
                                data-tab-id="{{ $tab['id'] }}"
                                class="info-htab shrink-0 flex items-center gap-2 px-5 py-4 text-xs font-bold transition-all border-b-2 whitespace-nowrap {{ $idx === 0 ? 'border-[#2196f3] text-[#2196f3] bg-blue-50/60' : 'border-transparent text-slate-500 hover:text-[#2196f3] hover:bg-slate-50' }}">
                                <i class="{{ $tab['icon'] }} text-[11px]"></i>
                                {{ $tab['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Body: sidebar + main --}}
                <div class="grid gap-5 lg:grid-cols-[260px_1fr] items-start">

                    {{-- Left Sidebar --}}
                    <aside class="space-y-4">

                        {{-- Quick Info Card --}}
                        <div class="bx-card p-5">
                            <h3 class="bx-display text-base text-slate-900 pb-3 border-b border-slate-100 mb-4">Thông tin
                                nhanh</h3>
                            <div class="space-y-3 text-xs">
                                <div class="flex items-start gap-2.5 text-slate-600">
                                    <i class="fas fa-map-marker-alt mt-0.5 text-blue-500 shrink-0"></i>
                                    <span class="leading-relaxed">{{ $address }}</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-slate-600">
                                    <i class="fas fa-phone-alt text-blue-500 shrink-0"></i>
                                    <a href="tel:{{ preg_replace('/\D+/', '', $hotline) }}"
                                        class="font-bold text-slate-800 hover:text-[#2196f3] transition-colors no-underline!">{{ $hotline }}</a>
                                </div>
                                <div class="flex items-center gap-2.5 text-slate-600">
                                    <i class="fas fa-clock text-blue-500 shrink-0"></i>
                                    <span>Giờ mở cửa: <strong class="text-slate-800">{{ $hours }}</strong></span>
                                </div>
                                <div class="border-t border-slate-100 pt-3 space-y-2">
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $map_query }}"
                                        target="_blank" rel="noopener noreferrer"
                                        class="flex items-center gap-2 text-slate-600 hover:text-[#2196f3] transition-colors no-underline!">
                                        <i class="fas fa-directions text-blue-500 w-4 text-center shrink-0"></i>
                                        <span class="font-semibold">Chỉ đường</span>
                                    </a>
                                    <a href="tel:{{ preg_replace('/\D+/', '', $hotline) }}"
                                        class="flex items-center gap-2 text-slate-600 hover:text-[#2196f3] transition-colors no-underline!">
                                        <i class="fas fa-phone text-emerald-500 w-4 text-center shrink-0"></i>
                                        <span class="font-semibold">Gọi điện</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- App Download Card --}}
                        @php
                            $appStore = home_url(
                                '/wp-content/themes/dailyve-theme/resources/images/download-app-store.png',
                            );
                            $googlePlay = home_url(
                                '/wp-content/themes/dailyve-theme/resources/images/download-gg-play.png',
                            );
                            $qrCode =
                                'https://object.dailyve.com/dailyve/wp-content/uploads/2025/08/QR-CODE-APP-DLV.png';
                        @endphp
                        <div class="rounded-xl bg-[#2196f3] p-5 text-white shadow-sm">
                            <h2 class="text-base font-semibold">Tải ứng dụng Dailyve</h2>
                            <p class="mt-1 text-xs text-blue-100">Đặt vé nhanh chóng, ưu đãi mỗi ngày</p>
                            <div class="mt-4 grid grid-cols-[88px_minmax(0,1fr)] gap-3">
                                <img class="h-[88px] w-[88px] rounded-lg bg-white p-1" src="{{ esc_url($qrCode) }}"
                                    alt="QR tải ứng dụng Dailyve" loading="lazy" decoding="async">
                                <div class="grid content-center gap-2">
                                    <a href="https://apps.apple.com" target="_blank" rel="noopener noreferrer">
                                        <img class="h-9 w-auto rounded-md transition-transform hover:scale-105"
                                            src="{{ esc_url($appStore) }}" alt="App Store" loading="lazy"
                                            decoding="async">
                                    </a>
                                    <a href="https://play.google.com" target="_blank" rel="noopener noreferrer">
                                        <img class="h-9 w-auto rounded-md transition-transform hover:scale-105"
                                            src="{{ esc_url($googlePlay) }}" alt="Google Play" loading="lazy"
                                            decoding="async">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </aside>

                    {{-- Main Content --}}
                    <div class="space-y-5">

                        {{-- ── Giới thiệu pane ── --}}
                        <div class="info-tab-pane" id="pane-intro">

                            {{-- Intro card --}}
                            <div class="bx-card p-6 mb-5">
                                <h2 class="bx-display text-xl text-slate-950 mb-4">Giới thiệu bến xe {{ $station_name }}
                                </h2>

                                <div class="text-sm text-slate-600 leading-relaxed space-y-3">
                                    @if ($post_content)
                                        {!! apply_filters('the_content', $post_content) !!}
                                    @else
                                        <p>{{ $station_name }} là một trong những bến xe lớn và quan trọng nhất tại TP.
                                            Hồ Chí Minh, đóng vai trò kết nối khu vực Tây Bắc với trung tâm thành phố và
                                            các tỉnh thành trên khắp cả nước.</p>
                                        <p>Với quy mô hiện đại, hạ tầng thông dịch vụ đa dạng và vị trí thuận tiện ngay
                                            trên Quốc lộ 22, bến xe mang đến trải nghiệm đi lại an toàn, nhanh chóng và
                                            thoải mái cho hành khách.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ── Số điện thoại pane ── --}}
                        <div class="info-tab-pane hidden" id="pane-phone">
                            <div class="bx-card p-6">
                                <h2 class="bx-display text-xl text-slate-950 mb-5">Số điện thoại liên hệ</h2>
                                <div class="space-y-4">
                                    <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                                        <span
                                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#2196f3] text-white shrink-0">
                                            <i class="fas fa-phone-alt text-lg"></i>
                                        </span>
                                        <div>
                                            <span
                                                class="block text-[10px] font-bold text-blue-500 uppercase tracking-wide">Hotline
                                                bến xe</span>
                                            <a href="tel:{{ preg_replace('/\D+/', '', $hotline) }}"
                                                class="text-xl font-black text-slate-900 hover:text-[#2196f3] transition-colors no-underline!">{{ $hotline }}</a>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <span
                                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 shrink-0">
                                            <i class="fas fa-headset text-lg"></i>
                                        </span>
                                        <div>
                                            <span
                                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Tổng
                                                đài Dailyve</span>
                                            <a href="tel:19000155"
                                                class="text-xl font-black text-slate-900 hover:text-[#2196f3] transition-colors no-underline!">1900
                                                0155</a>
                                            <span class="block text-[11px] text-slate-400 mt-0.5">07:00 – 24:00 (7
                                                ngày/tuần)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Tiện ích pane ── --}}
                        <div class="info-tab-pane hidden" id="pane-amenities">
                            <div class="bx-card p-6">
                                <h2 class="bx-display text-xl text-slate-950 mb-5">Tiện ích tại bến xe</h2>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    @foreach ($highlights as $hl)
                                        <div
                                            class="flex items-start gap-3 p-4 bg-slate-50 hover:bg-blue-50 rounded-2xl transition-colors border border-slate-100 hover:border-blue-100">
                                            <span
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-[#2196f3]">
                                                <i class="{{ $hl['icon'] }} text-base"></i>
                                            </span>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-900">{{ $hl['label'] }}</h4>
                                                <p class="text-[12px] text-slate-500 mt-0.5 leading-relaxed">
                                                    {{ $hl['desc'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ── Địa chỉ pane ── --}}
                        <div class="info-tab-pane hidden" id="pane-address">
                            <div class="bx-card p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="bx-display text-xl text-slate-950">Địa chỉ bến xe</h2>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $map_query }}"
                                        target="_blank" rel="noopener noreferrer" class="action-btn text-xs">
                                        <i class="fas fa-directions text-blue-500"></i> Chỉ đường
                                    </a>
                                </div>
                                <p class="text-sm text-slate-700 font-semibold mb-5">{{ $address }}</p>
                                <div class="overflow-hidden rounded-2xl border border-slate-200 aspect-video relative">
                                    <iframe class="absolute inset-0 w-full h-full border-0"
                                        src="{{ esc_url($map_embed_url) }}" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>

                        {{-- ── Giờ hoạt động pane ── --}}
                        <div class="info-tab-pane hidden" id="pane-hours">
                            <div class="bx-card p-6">
                                <h2 class="bx-display text-xl text-slate-950 mb-5">Giờ hoạt động</h2>
                                <div
                                    class="flex items-center gap-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 mb-5">
                                    <span
                                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-600 text-white shrink-0">
                                        <i class="fas fa-clock text-lg"></i>
                                    </span>
                                    <div>
                                        <span
                                            class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wide">Giờ
                                            mở cửa mỗi ngày</span>
                                        <span class="text-2xl font-black text-slate-900">{{ $hours }}</span>
                                        <span class="block text-[11px] text-slate-500 mt-0.5">Kể cả ngày lễ và cuối
                                            tuần</span>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    @foreach (['Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy', 'Chủ Nhật'] as $day)
                                        <div
                                            class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                                            <span class="text-slate-600 font-medium">{{ $day }}</span>
                                            <span class="font-bold text-slate-900">{{ $hours }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ── Hướng dẫn đi bến pane ── --}}
                        <div class="info-tab-pane hidden" id="pane-transit">
                            <div class="bx-card p-6">
                                <h2 class="bx-display text-xl text-slate-950 mb-5">Hướng dẫn di chuyển đến bến xe</h2>
                                <div class="grid sm:grid-cols-3 gap-4">
                                    <div
                                        class="flex flex-col items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-[#2196f3]">
                                            <i class="fas fa-motorcycle text-base"></i>
                                        </span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900 mb-1">Đi bằng xe máy</h4>
                                            <p class="text-[12px] text-slate-500 leading-relaxed">Từ trung tâm TPHCM đi
                                                theo Quốc lộ 22 hướng Tây Ninh, bến xe nằm bên phải sau khoảng 20km.</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-col items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                            <i class="fas fa-car text-base"></i>
                                        </span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900 mb-1">Đi bằng ô tô</h4>
                                            <p class="text-[12px] text-slate-500 leading-relaxed">Di chuyển trên Quốc lộ
                                                22, đường rộng rãi, thuận tiện cho ô tô. Có bãi đỗ xe rộng rãi.</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-col items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                                            <i class="fas fa-bus text-base"></i>
                                        </span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900 mb-1">Đi bằng xe buýt</h4>
                                            <p class="text-[12px] text-slate-500 leading-relaxed">Có nhiều tuyến xe buýt đi
                                                qua hoặc có trạm dừng gần bến xe (Tuyến 04, 13, 27, 33...)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- FAQ card --}}
                        <div class="bx-card p-6">
                            <h2 class="bx-display text-xl text-slate-950 mb-5">Câu hỏi thường gặp</h2>
                            @php
                                $faqs = [
                                    [
                                        'q' => $station_name . ' hoạt động vào khung giờ nào?',
                                        'a' =>
                                            'Bến xe hoạt động từ ' . $hours . ' hàng ngày kể cả ngày lễ và cuối tuần.',
                                    ],
                                    [
                                        'q' => 'Có giữ xe qua đêm tại bến xe không?',
                                        'a' =>
                                            'Bến xe có bãi giữ xe ô tô và xe máy phục vụ 24/7. Hành khách có thể gửi xe qua đêm với mức phí theo quy định của ban quản lý bến xe.',
                                    ],
                                    [
                                        'q' => 'Có thể mua vé trực tuyến cho các tuyến đi từ bến xe không?',
                                        'a' =>
                                            'Có. Bạn có thể đặt vé trực tuyến qua Dailyve.com hoặc ứng dụng di động Dailyve. Vé sẽ được xác nhận ngay sau khi thanh toán thành công.',
                                    ],
                                    [
                                        'q' => $station_name . ' có dịch vụ gửi hàng không?',
                                        'a' =>
                                            'Có. Bến xe cung cấp dịch vụ gửi nhận hàng hóa qua các nhà xe hoạt động tại bến. Hành khách liên hệ trực tiếp quầy vé hoặc hotline để biết thêm chi tiết.',
                                    ],
                                ];
                            @endphp
                            <div class="space-y-2" id="faq-list">
                                @foreach ($faqs as $faq_idx => $faq)
                                    <div class="faq-item border border-slate-100 rounded-2xl overflow-hidden">
                                        <button type="button"
                                            class="faq-toggle w-full flex items-center justify-between gap-3 px-5 py-4 text-left text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors"
                                            onclick="toggleFaq(this)">
                                            <span>{{ $faq['q'] }}</span>
                                            <i
                                                class="fas fa-chevron-down text-xs text-slate-400 shrink-0 transition-transform duration-200 faq-icon"></i>
                                        </button>
                                        <div class="faq-body overflow-hidden" style="height:0;">
                                            <p class="px-5 pb-5 pt-1 text-sm text-slate-600 leading-relaxed">
                                                {{ $faq['a'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </main>

            <script>
                (function() {
                    /* ── Carousel Slider ── */
                    window.stationCurrentSlide = 0;
                    window.moveStationSlide = function(direction) {
                        const track = document.getElementById('station-slides-track');
                        if (!track) return;

                        // Count slides based on the number of image children
                        const totalSlides = track.children.length;
                        if (totalSlides <= 1) return;

                        window.stationCurrentSlide += direction;
                        if (window.stationCurrentSlide < 0) window.stationCurrentSlide = totalSlides - 1;
                        if (window.stationCurrentSlide >= totalSlides) window.stationCurrentSlide = 0;

                        track.style.display = 'flex';
                        track.style.transition = 'transform 0.4s ease-in-out';
                        track.style.transform = `translateX(-${window.stationCurrentSlide * (100 / totalSlides)}%)`;

                        const num = document.getElementById('slide-num');
                        if (num) num.textContent = window.stationCurrentSlide + 1;

                        const dots = document.querySelectorAll('.slide-dot');
                        dots.forEach((dot, idx) => {
                            if (idx === window.stationCurrentSlide) {
                                dot.className = 'slide-dot w-4 h-1.5 rounded-full transition-all bg-white';
                            } else {
                                dot.className = 'slide-dot w-1.5 h-1.5 rounded-full transition-all bg-white/50';
                            }
                        });
                    };

                    /* ── Direction switcher ── */
                    window.stationCurrentPages = {
                        from: window.stationDirection === 'from' ? window.stationCurrentPage : 1,
                        to: window.stationDirection === 'to' ? window.stationCurrentPage : 1
                    };

                    /* ── Province filter ── */
                    window.filterByProvince = function(prov, btnEl) {
                        const activeWrapper = document.getElementById('route-dir-' + window.stationDirection);
                        if (!activeWrapper) return;
                        activeWrapper.querySelectorAll('.prov-pill').forEach(btn => {
                            btn.classList.remove('prov-pill-active');
                            btn.classList.add('bg-slate-100', 'text-slate-700');
                        });
                        const clicked = btnEl || window.event?.currentTarget;
                        if (clicked) {
                            clicked.classList.add('prov-pill-active');
                            clicked.classList.remove('bg-slate-100', 'text-slate-700');
                        } else {
                            const pills = activeWrapper.querySelectorAll('.prov-pill');
                            pills.forEach(btn => {
                                const attr = btn.getAttribute('onclick') || '';
                                if ((prov === 'all' && attr.includes("'all'")) || (prov !== 'all' && attr.includes(
                                        "'" + prov + "'"))) {
                                    btn.classList.add('prov-pill-active');
                                    btn.classList.remove('bg-slate-100', 'text-slate-700');
                                }
                            });
                        }
                        const routeCards = activeWrapper.querySelectorAll('[data-route-card]');
                        let visibleCount = 0;
                        routeCards.forEach(card => {
                            const matchesFilter = (prov === 'all' || card.dataset.province === prov);
                            if (!matchesFilter) {
                                card.style.display = 'none';
                                setRouteOpen(card, false, true);
                                return;
                            }
                            card.style.display = 'flex';
                            const shouldBeOpen = visibleCount < 2;
                            setRouteOpen(card, shouldBeOpen, true);
                            visibleCount++;
                        });
                    };

                    /* ── Info tab switcher ── */
                    window.switchInfoTab = function(tabId) {
                        document.querySelectorAll('.info-htab').forEach(btn => {
                            const isActive = btn.dataset.tabId === tabId;
                            btn.classList.toggle('border-[#2196f3]', isActive);
                            btn.classList.toggle('text-[#2196f3]', isActive);
                            btn.classList.toggle('border-transparent', !isActive);
                            btn.classList.toggle('text-slate-500', !isActive);
                        });
                        document.querySelectorAll('.info-tab-pane').forEach(pane => {
                            pane.classList.toggle('hidden', pane.id !== 'pane-' + tabId);
                        });
                    };

                    /* ── FAQ accordion ── */
                    window.toggleFaq = function(btn) {
                        const body = btn.nextElementSibling;
                        const icon = btn.querySelector('.faq-icon');
                        const isOpen = body.style.height !== '0px' && body.style.height !== '';
                        if (isOpen) {
                            body.style.height = body.scrollHeight + 'px';
                            body.offsetHeight;
                            body.style.transition = 'height 0.25s ease';
                            body.style.height = '0px';
                            if (icon) icon.style.transform = '';
                        } else {
                            body.style.height = '0px';
                            body.style.transition = 'height 0.25s ease';
                            body.offsetHeight;
                            body.style.height = body.scrollHeight + 'px';
                            if (icon) icon.style.transform = 'rotate(180deg)';
                            setTimeout(function() {
                                body.style.height = 'auto';
                            }, 260);
                        }
                    };

                    window.switchDirection = function(dir) {
                        if (window.stationDirection === dir) return;
                        window.stationDirection = dir;
                        document.querySelectorAll('.dir-tab-btn').forEach(btn => {
                            btn.classList.toggle('dir-tab-active', btn.dataset.dir === dir);
                            if (btn.dataset.dir !== dir) {
                                btn.classList.remove('dir-tab-active');
                                btn.classList.add('text-slate-600');
                            }
                        });
                        document.querySelectorAll('.route-dir-wrapper').forEach(wrapper => {
                            if (wrapper.id === 'route-dir-' + dir) {
                                wrapper.classList.remove('hidden');
                            } else {
                                wrapper.classList.add('hidden');
                            }
                        });
                        const titleEl = document.getElementById('route-list-title');
                        if (titleEl) {
                            const yOffset = -120;
                            const rect = titleEl.getBoundingClientRect();
                            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                            const y = rect.top + scrollTop + yOffset;
                            window.scrollTo({
                                top: y,
                                behavior: 'auto'
                            });
                        }
                        const url = new URL(window.location);
                        url.searchParams.set('direction', dir);
                        url.searchParams.set('page_num', window.stationCurrentPages[dir] || 1);
                        window.history.replaceState({}, '', url);
                    };

                    /* ── Toggle operators ── */
                    window.toggleOperators = function(btn) {
                        const container = btn.closest('.px-4, [class*="px-4"]')?.querySelector('.ops-container');
                        if (!container) return;
                        const hiddenOps = container.querySelectorAll('.js-hidden-op');
                        const isExpanded = btn.dataset.expanded === 'true';
                        hiddenOps.forEach(op => op.classList.toggle('hidden', isExpanded));
                        btn.dataset.expanded = isExpanded ? '' : 'true';
                        const label = btn.querySelector('.toggle-label');
                        const icon = btn.querySelector('.toggle-icon');
                        if (label) label.textContent = isExpanded ? `Xem thêm ${hiddenOps.length} nhà xe` : 'Thu gọn';
                        if (icon) icon.style.transform = isExpanded ? '' : 'rotate(180deg)';
                    };

                    /* ── Fetch routes (AJAX pagination) ── */
                    window.fetchRoutesPage = function(page, force) {
                        const currentDir = window.stationDirection;
                        if (!force && page === (window.stationCurrentPages[currentDir] || 1)) return;
                        const grid = document.getElementById('routes-grid-' + currentDir);
                        if (!grid) return;
                        const titleEl = document.getElementById('route-list-title');
                        if (titleEl) {
                            const yOffset = -120;
                            const rect = titleEl.getBoundingClientRect();
                            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                            const y = rect.top + scrollTop + yOffset;
                            window.scrollTo({
                                top: y,
                                behavior: 'auto'
                            });
                        }
                        grid.innerHTML = Array(4).fill('').map(() =>
                            `<div class="route-card p-4 space-y-3">
                            <div class="bx-skeleton h-4 w-3/4 rounded-lg"></div>
                            <div class="bx-skeleton h-3 w-1/2 rounded-lg"></div>
                            <div class="bx-skeleton h-20 rounded-xl"></div>
                            <div class="bx-skeleton h-9 rounded-xl mt-2"></div>
                        </div>`
                        ).join('');
                        const locationId = '{!! esc_js($location_id) !!}';
                        const stationName = '{!! esc_js($station_name) !!}';
                        if (!locationId) return;
                        fetch('{!! esc_url(admin_url('admin-ajax.php')) !!}?action=dailyve_get_station_routes&location_id=' + locationId +
                                '&page=' + page + '&page_size=10&station_name=' + encodeURIComponent(stationName))
                            .then(res => res.json())
                            .then(res => {
                                if (res.success) {
                                    window.stationRoutesSummary = res.data;
                                    window.stationCurrentPages[currentDir] = page;
                                    renderRoutesAndPagination(window.stationRoutesSummary, page, currentDir);
                                    const url = new URL(window.location);
                                    url.searchParams.set('direction', currentDir);
                                    url.searchParams.set('page_num', page);
                                    window.history.replaceState({}, '', url);
                                } else {
                                    alert(res.data?.message || 'Có lỗi xảy ra khi tải dữ liệu.');
                                }
                            })
                            .catch(e => {
                                console.error(e);
                                alert('Lỗi kết nối mạng.');
                            });
                    };

                    function formatPrice(p) {
                        if (!p) return '—';
                        return new Intl.NumberFormat('vi-VN').format(p) + 'đ';
                    }

                    function getInitials(name) {
                        if (!name) return 'DLV';
                        const parts = name.trim().split(/\s+/);
                        return parts.length >= 2 ? (parts[0][0] + parts[parts.length - 1][0]).toUpperCase() : name.slice(0, 2)
                            .toUpperCase();
                    }

                    function setRouteOpen(card, open, instant) {
                        const body = card.querySelector('[data-route-body]');
                        if (!body) return;
                        card.classList.toggle('is-open', open);
                        card.setAttribute('data-route-open', open ? 'true' : 'false');
                        const toggle = card.querySelector('[data-route-toggle]');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                        }
                        if (body.dvTimeout) {
                            window.clearTimeout(body.dvTimeout);
                            body.dvTimeout = null;
                        }
                        if (instant) {
                            body.style.height = open ? 'auto' : '0px';
                            return;
                        }
                        if (open) {
                            body.style.height = '0px';
                            body.offsetHeight;
                            body.style.height = body.scrollHeight + 'px';
                            body.dvTimeout = window.setTimeout(function() {
                                if (card.classList.contains('is-open')) {
                                    body.style.height = 'auto';
                                }
                            }, 300);
                        } else {
                            if (body.style.height === 'auto' || !body.style.height) {
                                body.style.height = body.scrollHeight + 'px';
                            }
                            body.offsetHeight;
                            body.style.height = '0px';
                        }
                    }

                    function initRouteCardAccordions() {
                        document.querySelectorAll('[data-route-card]').forEach(function(card) {
                            if (card.dataset.accordionInit) return;
                            card.dataset.accordionInit = 'true';
                            const isOpen = card.getAttribute('data-route-open') === 'true';
                            setRouteOpen(card, isOpen, true);
                            const toggle = card.querySelector('[data-route-toggle]');
                            if (toggle) {
                                toggle.addEventListener('click', function() {
                                    const currentlyOpen = card.classList.contains('is-open');
                                    setRouteOpen(card, !currentlyOpen, false);
                                });
                            }
                        });
                    }

                    function renderRoutesAndPagination(data, page, currentDir) {
                        page = parseInt(page, 10) || 1;
                        const dirKey = currentDir === 'from' ? 'departing' : 'arriving';
                        const dirData = data[dirKey] || {};
                        const items = dirData.items || [];
                        const totalItems = dirData.total || items.length;
                        const totalPages = dirData.totalPages || 1;
                        const totalCountEl = document.getElementById('total-routes-count-' + currentDir);
                        if (totalCountEl) {
                            totalCountEl.innerHTML =
                                `<i class="fas fa-route text-blue-400"></i> ${new Intl.NumberFormat('vi-VN').format(totalItems)} tuyến`;
                        }
                        const provincesList = [];
                        items.forEach(item => {
                            const opp = currentDir === 'from' ? (item.to || {}) : (item.from || {});
                            const provName = opp.province_name || '';
                            if (provName && !provincesList.includes(provName)) {
                                provincesList.push(provName);
                            }
                        });
                        const provincesContainer = document.getElementById('provinces-filter-container-' + currentDir);
                        if (provincesContainer) {
                            let html =
                                `<button type="button" class="prov-pill prov-pill-active shrink-0 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700" onclick="filterByProvince('all', this)">Tất cả</button>`;
                            provincesList.forEach(prov => {
                                html +=
                                    `<button type="button" class="prov-pill shrink-0 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700" onclick="filterByProvince('${prov}', this)">${prov}</button>`;
                            });
                            provincesContainer.innerHTML = html;
                        }
                        const grid = document.getElementById('routes-grid-' + currentDir);
                        if (grid) {
                            if (items.length === 0) {
                                grid.innerHTML = `<div class="col-span-full rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 py-16 text-center">
                                                <i class="fas fa-route text-5xl text-slate-200 mb-4 block"></i>
                                                <h3 class="text-base font-bold text-slate-900">Không tìm thấy chuyến xe nào</h3>
                                            </div>`;
                            } else {
                                let html = '';
                                items.forEach((item, idx) => {
                                    const fromName = item.from?.name || '';
                                    const toName = item.to?.name || '';
                                    const provName = currentDir === 'from' ? (item.to?.province_name || '') : (item.from
                                        ?.province_name || '');
                                    const operators = item.operators || [];
                                    const opCount = item.operator_count || 0;
                                    const tripCount = item.trip_count || 0;
                                    const minPrice = item.min_price || 0;
                                    const tomorrow = new Date();
                                    tomorrow.setDate(tomorrow.getDate() + 1);
                                    const dateStr = tomorrow.toISOString().split('T')[0];
                                    const searchQueryUrl = window.location.origin + '/dat-ve-truc-tuyen/?from=' + (item
                                            .from?.id || '') + '&to=' + (item.to?.id || '') + '&nameFrom=' +
                                        encodeURIComponent(fromName) + '&nameTo=' + encodeURIComponent(toName) +
                                        '&date=' + dateStr;
                                    let opsHtml = '';
                                    if (operators.length > 0) {
                                        const totalOperators = parseInt(item.operator_count || operators.length, 10);
                                        const remainingOperators = Math.max(totalOperators - 8, 0);

                                        opsHtml += `<div class="px-4 pt-3 pb-4 flex-1">
                                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">Nhà xe nổi bật<span class="h-px bg-slate-100 flex-1"></span></p>
                                                    <div class="ops-container space-y-1.5">`;
                                        operators.forEach((op, opIdx) => {
                                            if (opIdx >= 8) return;

                                            const opAvatar = op.image_url ||
                                                'https://object.dailyve.com/dailyve/wp-content/uploads/2026/05/nha-xe-chat-luong-cao.webp';
                                            const opName = op.name || '';
                                            const opRating = op.display_rating || op.rating || '4.8';
                                            const opPrice = op.min_price || 0;
                                            const opPostUrl = op.media?.wp_url || '';
                                            const opReviews = op.review_count || 0;
                                            const badgeColors = ['bg-amber-100 text-amber-700',
                                                'bg-slate-100 text-slate-600',
                                                'bg-orange-50 text-orange-600'
                                            ];
                                            const badgeColor = badgeColors[Math.min(opIdx, 2)];
                                            const isHidden = opIdx >= 8;
                                            const hiddenClass = isHidden ? 'js-hidden-op hidden' : '';
                                            const opTripCount = op.trip_count || 0;
                                            let btnText = 'Xem chuyến';
                                            if (opTripCount > 10) {
                                                btnText = 'Xem 10+ chuyến';
                                            } else if (opTripCount > 0) {
                                                btnText = `Xem ${opTripCount} chuyến`;
                                            }
                                            const fromProvId = item.from_province_id || item.from
                                                ?.province_id || item.from?.id || '';
                                            const toProvId = item.to_province_id || item.to?.province_id || item
                                                .to?.id || '';
                                            const opId = op.operator_id || op.id || '';
                                            const cardBookingUrl = window.location.origin +
                                                '/dat-ve-truc-tuyen/?from=' + fromProvId + '&to=' + toProvId +
                                                '&nameFrom=' + encodeURIComponent(fromName) + '&nameTo=' +
                                                encodeURIComponent(toName) + '&operator_id=' + opId + '&date=' +
                                                dateStr;
                                            opsHtml += `<div class="op-item flex items-center gap-2.5 rounded-xl p-2 hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 ${hiddenClass}">
                                                        <div class="relative shrink-0">
                                                            ${opAvatar ? `<img class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow" src="${opAvatar}" alt="${opName}" loading="lazy">` : `<span class="flex h-9 w-9 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 text-[10px] font-black text-blue-700 items-center justify-center ring-2 ring-white shadow">${getInitials(opName)}</span>`}
                                                            <span class="absolute -bottom-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full ${badgeColor} text-[8px] font-black ring-2 ring-white">${opIdx + 1}</span>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex justify-between items-center gap-1">
                                                                ${opPostUrl ? `<a href="${opPostUrl}" class="text-[12px] font-bold text-slate-800 hover:text-[#2196f3] truncate no-underline! leading-tight">${opName}</a>` : `<span class="text-[12px] font-bold text-slate-800 truncate leading-tight">${opName}</span>`}
                                                                <span class="text-[12px] font-bold text-slate-800 shrink-0">${formatPrice(opPrice)}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between mt-1 gap-2">
                                                                <div class="flex items-center gap-1.5">
                                                                    <span class="flex items-center gap-0.5 bg-amber-50 text-amber-600 border border-amber-100 px-1.5 py-0.5 rounded-md text-[9px] font-bold"><i class="fas fa-star text-[8px]"></i> ${opRating}</span>
                                                                    ${opReviews > 0 ? `<span class="text-[10px] text-slate-400 font-medium hidden sm:block">${opReviews} đánh giá</span>` : ''}
                                                                </div>
                                                                <a href="${cardBookingUrl}" data-dailyve-date-range-trigger data-date-range-url="${cardBookingUrl}" data-date-range-from-name="${fromName}" data-date-range-to-name="${toName}" data-date-range-service="bus" data-date-range-min="today" class="shrink-0 inline-flex items-center justify-center bg-blue-50 hover:bg-[#2196f3] text-[#2196f3] hover:text-white transition-all text-[12px] font-semibold px-2.5 py-1 rounded-lg no-underline! border border-blue-100 hover:border-[#2196f3]">${btnText}</a>
                                                            </div>
                                                        </div>
                                                    </div>`;
                                        });
                                        opsHtml += `</div>`;

                                        const seoUrl = item.seo_url || '';
                                        const bookingUrl = window.location.origin + '/dat-ve-truc-tuyen/?from=' + fromProvId + '&to=' + toProvId + '&nameFrom=' + encodeURIComponent(fromName) + '&nameTo=' + encodeURIComponent(toName);
                                        const moreOperatorsUrl = seoUrl || bookingUrl;
                                        const hasSeoUrl = !!seoUrl;

                                        if (totalOperators > 10) {
                                            opsHtml += `<a href="${moreOperatorsUrl}" class="mt-2 inline-flex w-full items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100 no-underline!">
                                                ${hasSeoUrl ? `Xem tất cả ${totalOperators} nhà xe` : `Xem và đặt vé ${fromName} đi ${toName}`}
                                            </a>`;
                                        } else if (remainingOperators > 0) {
                                            opsHtml += `<button type="button" onclick="toggleOperators(this)" class="mt-2 inline-flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                                <span class="toggle-label">Xem thêm ${remainingOperators} nhà xe</span>
                                                <i class="fas fa-chevron-down text-[10px] toggle-icon transition-transform ml-1.5"></i>
                                            </button>`;
                                        }
                                        opsHtml += `</div>`;
                                    } else {
                                        opsHtml += `<div class="px-4 pb-4 pt-3 flex-1 flex items-center justify-between">
                                                    <span class="text-xs text-slate-400 font-medium">Chưa có thông tin nhà xe</span>
                                                    <a href="${searchQueryUrl}" class="inline-flex items-center gap-1.5 bg-[#2196f3] hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl no-underline! transition-colors">
                                                        <i class="fas fa-search text-[10px]"></i> Tìm chuyến
                                                    </a>
                                                </div>`;
                                    }
                                    const isRouteOpen = idx < 2;
                                    html += `<article class="route-card overflow-hidden" data-route-card data-route-open="${isRouteOpen ? 'true' : 'false'}" data-province="${provName}">
                                            <div class="flex items-start justify-between gap-3 p-4 pb-3 border-b border-slate-100 cursor-pointer select-none" data-route-toggle>
                                                <div class="min-w-0 flex-1">
                                                    <h3 class="text-sm font-extrabold text-slate-900 leading-tight line-clamp-2 group-hover:text-[#2196f3]">
                                                        ${fromName} <span class="inline-flex items-center justify-center w-5 h-5 mx-0.5 rounded-full bg-blue-50 text-blue-400 text-[9px] align-middle shrink-0"><i class="fas fa-arrow-right"></i></span> ${toName}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-1.5 text-[12px] text-slate-500 font-medium flex-wrap">
                                                        <span class="flex items-center gap-1"><i class="fas fa-bus-alt text-blue-400"></i>${opCount} nhà xe</span>
                                                        <span class="w-1 h-1 rounded-full bg-slate-200 shrink-0"></span>
                                                        <span class="flex items-center gap-1"><i class="fas fa-route text-emerald-400"></i>${tripCount} chuyến/ngày</span>
                                                    </div>
                                                </div>
                                                <div class="shrink-0 flex items-center gap-3">
                                                    <div class="text-right">
                                                        <span class="block text-[10px] text-slate-400 font-semibold mb-0.5">Giá từ</span>
                                                        <span class="text-sm font-black text-rose-500 bg-rose-50 border border-rose-100 px-2.5 py-1 rounded-lg block">${formatPrice(minPrice)}</span>
                                                    </div>
                                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-transform duration-200 route-chevron">
                                                        <i class="fas fa-chevron-down text-xs"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="route-card-body overflow-hidden transition-all duration-300" data-route-body style="height: ${isRouteOpen ? 'auto' : '0px'};">
                                                ${opsHtml}
                                            </div>
                                        </article>`;
                                });
                                grid.innerHTML = html;
                            }
                        }

                        const paginationContainer = document.getElementById('routes-pagination-' + currentDir);
                        if (paginationContainer) {
                            if (totalPages > 1) {
                                let html =
                                    `<nav class="mt-8 flex flex-wrap items-center justify-center gap-2" aria-label="Phân trang">`;
                                if (page > 1) {
                                    html +=
                                        `<button type="button" onclick="fetchRoutesPage(${page - 1})" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:border-blue-400 hover:text-[#2196f3] transition-colors px-3"><i class="fas fa-chevron-left text-xs"></i></button>`;
                                }
                                for (let i = 1; i <= totalPages; i++) {
                                    if (i === 1 || i === totalPages || (i >= page - 1 && i <= page + 1)) {
                                        html +=
                                            `<button type="button" onclick="fetchRoutesPage(${i})" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border text-sm font-bold transition-all ${i === page ? 'bg-[#2196f3] border-[#2196f3] text-white shadow-md shadow-blue-200' : 'bg-white border-slate-200 text-slate-700 hover:border-blue-400 hover:text-[#2196f3]'}">${i}</button>`;
                                    } else if (i === 2 || i === totalPages - 1) {
                                        html += `<span class="text-slate-400 font-bold px-1">…</span>`;
                                    }
                                }
                                if (page < totalPages) {
                                    html +=
                                        `<button type="button" onclick="fetchRoutesPage(${page + 1})" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:border-blue-400 hover:text-[#2196f3] transition-colors px-3"><i class="fas fa-chevron-right text-xs"></i></button>`;
                                }
                                html += `</nav>`;
                                paginationContainer.innerHTML = html;
                            } else {
                                paginationContainer.innerHTML = '';
                            }
                        }
                        initRouteCardAccordions();
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        initRouteCardAccordions();
                    });
                })();
            </script>
    @endwhile
@endsection

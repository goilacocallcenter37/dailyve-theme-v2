@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php
            the_post();
            $post_id = get_the_ID();
            $station_name = html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8');
            $post_content = get_the_content(null, false, $post_id);

            // Fetch metadata based on exact user ACF configuration
            $address = function_exists('get_field')
                ? get_field('bus_station_address', $post_id)
                : get_post_meta($post_id, 'bus_station_address', true);
            $address = $address ?: (function_exists('get_field') ? get_field('company_address', $post_id) : get_post_meta($post_id, 'company_address', true));
            $address = $address ?: 'Chưa cập nhật địa chỉ';

            $hotline = function_exists('get_field')
                ? get_field('bus_station_phone', $post_id)
                : get_post_meta($post_id, 'bus_station_phone', true);
            $hotline = $hotline ?: (function_exists('get_field') ? get_field('company_phone', $post_id) : get_post_meta($post_id, 'company_phone', true));
            $hotline = $hotline ?: '1900 888 684';

            $hours = function_exists('get_field')
                ? get_field('operating_hours', $post_id)
                : get_post_meta($post_id, 'operating_hours', true);
            $hours = $hours ?: '05:00 - 22:00';

            $website = function_exists('get_field')
                ? get_field('website_url', $post_id)
                : get_post_meta($post_id, 'website_url', true);
            $website = $website ?: 'https://dailyve.com.vn';

            // Query Location ID using user's station_point select field
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
                $location_id = $location_field['value'] ?? $location_field[0] ?? '';
            } else {
                $location_id = (string) $location_field;
            }
            
            // Fallback for location ID if not set
            if (empty($location_id)) {
                if (stripos($station_name, 'Miền Đông') !== false) {
                    $location_id = '69e71ed15139c113eb3d3b89';
                } else {
                    $location_id = '69e71ed15139c113eb3d3b89'; // Default to Mien Dong
                }
            }

            // Direction parameter for switcher
            $direction = isset($_GET['direction']) && $_GET['direction'] === 'to' ? 'to' : 'from';

            // Page parameter for API query
            $paged = max(1, isset($_GET['page_num']) ? (int) $_GET['page_num'] : 1);
            $page_size = 6;

            // Fetch Route Groups from Production API (Unified calling method)
            $routes_result = \App\dailyve_get_station_routes($location_id, $direction, $paged);
            $api_error = is_wp_error($routes_result) ? $routes_result->get_error_message() : null;
            $routes_data = $api_error ? [] : $routes_result;

            $items = $routes_data['items'] ?? [];
            $total_items = $routes_data['total'] ?? count($items);
            $total_pages = $routes_data['totalPages'] ?? 1;

            // Extract dynamic provinces from items for filter buttons
            $provinces = [];
            foreach ($items as $item) {
                $opp = $direction === 'from' ? ($item['to'] ?? []) : ($item['from'] ?? []);
                $prov_name = $opp['province_name'] ?? '';
                if ($prov_name && !in_array($prov_name, $provinces, true)) {
                    $provinces[] = $prov_name;
                }
            }

            // Gallery image logic using user's bus_station_gallery gallery field
            $gallery = [];
            $gallery_field = function_exists('get_field')
                ? get_field('bus_station_gallery', $post_id)
                : get_post_meta($post_id, 'bus_station_gallery', true);

            if (is_array($gallery_field)) {
                foreach ($gallery_field as $img) {
                    $url = is_array($img) ? ($img['url'] ?? $img['sizes']['large'] ?? '') : wp_get_attachment_image_url((int)$img, 'large');
                    if ($url) {
                        $gallery[] = $url;
                    }
                }
            }

            // Fallback to thumbnail if gallery is empty
            if (empty($gallery)) {
                $thumb_id = get_post_thumbnail_id($post_id);
                $gallery[] = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : 'https://object.dailyve.com/dailyve/wp-content/uploads/2026/05/banner_web.webp';
            }

            // Helpers from dailyve core
            $format_price = fn($p) => function_exists('formatVND') ? formatVND($p) . 'đ' : number_format($p, 0, ',', '.') . 'đ';
            $get_initials = fn($n) => function_exists('getInitialsNameToAvatar') ? getInitialsNameToAvatar($n) : 'DLV';

            // Map location coordinates for Embed Map
            $map_query = urlencode($station_name . ' ' . $address);
            $map_embed_url = "https://maps.google.com/maps?q={$map_query}&t=&z=15&ie=UTF8&iwloc=&output=embed";

            // Static Tabs Configuration
            $tabs = [
                ['id' => 'intro', 'label' => 'Giới thiệu', 'icon' => 'fas fa-info-circle'],
                ['id' => 'map', 'label' => 'Sơ đồ bến xe', 'icon' => 'fas fa-map'],
                ['id' => 'amenities', 'label' => 'Tiện ích', 'icon' => 'fas fa-concierge-bell'],
                ['id' => 'rules', 'label' => 'Quy định', 'icon' => 'fas fa-clipboard-list'],
                ['id' => 'shipping', 'label' => 'Gửi hàng', 'icon' => 'fas fa-box-open'],
                ['id' => 'transit', 'label' => 'Hướng dẫn di chuyển', 'icon' => 'fas fa-route'],
            ];

            // Highlights
            $highlights = [
                ['icon' => 'fas fa-parking', 'label' => 'Bãi đỗ xe rộng rãi', 'desc' => 'Bãi đỗ xe ô tô, xe máy rộng rãi, an ninh 24/24.'],
                ['icon' => 'fas fa-wind', 'label' => 'Khu vực chờ máy lạnh', 'desc' => 'Khu vực ngồi chờ thoáng mát trang bị điều hòa nhiệt độ.'],
                ['icon' => 'fas fa-wifi', 'label' => 'Wifi miễn phí', 'desc' => 'Hệ thống mạng không dây tốc độ cao phủ sóng toàn bộ khuôn viên.'],
                ['icon' => 'fas fa-utensils', 'label' => 'Nhà hàng, quán ăn', 'desc' => 'Chuỗi cửa hàng tiện lợi, quán ăn đa dạng đảm bảo vệ sinh.'],
                ['icon' => 'fas fa-ticket-alt', 'label' => 'Quầy vé tự động', 'desc' => 'Hệ thống ki-ốt tra cứu lịch trình và xuất vé điện tử tức thì.'],
                ['icon' => 'fas fa-pump-soap', 'label' => 'Nhà vệ sinh sạch sẽ', 'desc' => 'Khu vực vệ sinh công cộng đạt tiêu chuẩn sạch sẽ, tiện nghi.'],
            ];

            // SEO Canonical & Query Builder for Switcher/Pagination
            $canonical_url = get_permalink($post_id);
            $get_switcher_url = function($dir) use ($canonical_url) {
                return add_query_arg(['direction' => $dir], $canonical_url);
            };
            $get_page_url = function($p) use ($canonical_url, $direction) {
                return add_query_arg(['direction' => $direction, 'page_num' => $p], $canonical_url);
            };
        @endphp

        {{-- Prefill destination combobox in React SearchForm --}}
        <script>
            window.route_data = {
                to_id: @json($location_id),
                to_name: @json($station_name),
                from_id: '',
                from_name: ''
            };
        </script>

        <div class="ben-xe-detail overflow-x-hidden bg-slate-50 text-slate-700 font-sans pb-16">
            <style>
                .display-grotesk {
                    font-family: 'Space Grotesk', sans-serif;
                    letter-spacing: -0.03em;
                }
                .be-vietnam {
                    font-family: 'Be Vietnam Pro', sans-serif;
                }
                .ben-xe-detail-shadow {
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.04);
                }
                .province-pill-active {
                    background-color: #2196F3 !important;
                    color: #FFFFFF !important;
                    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
                }
                .tab-trigger-active {
                    background-color: #FFFFFF !important;
                    color: #2196F3 !important;
                    border-left: 4px solid #2196F3 !important;
                    font-weight: 600;
                }
                .direction-tab-active {
                    background-color: #2196F3 !important;
                    color: #FFFFFF !important;
                    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.25);
                }
                .custom-scrollbar::-webkit-scrollbar {
                    height: 5px;
                }
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: #f1f5f9;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 99px;
                }
            </style>

            {{-- Breadcrumbs Section --}}
            <x-breadcrumb :items="[
                ['title' => 'Dailyve', 'url' => home_url('/')],
                ['title' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
                ['title' => 'Bến xe khách', 'url' => home_url('/ve-xe-khach/ben-xe/')],
                ['title' => $station_name, 'url' => '']
            ]" preset="directory" />

            {{-- Main Station Detail Card --}}
            <header class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                {{-- Dynamic Search Widget Section --}}
                <div class="relative overflow-visible rounded-3xl border border-slate-200 bg-white p-4 shadow-sm mb-8">
                    <div id="react-search-form" class="min-h-[120px]" data-initial-service="bus"></div>
                </div>

                {{-- Station Hero Panel --}}
                <div class="grid gap-6 lg:grid-cols-[1.1fr_1fr] bg-white rounded-3xl border border-slate-200 p-6 ben-xe-detail-shadow">
                    {{-- Left Image Carousel/Holder --}}
                    <div class="relative overflow-hidden rounded-2xl aspect-[16/10] bg-slate-100 group shadow-inner">
                        <div class="w-full h-full flex transition-transform duration-500" id="station-slides-track" style="width: {{ count($gallery) * 100 }}%;">
                            @foreach ($gallery as $img_url)
                                <div class="w-full h-full shrink-0 relative" style="width: {{ 100 / count($gallery) }}%;">
                                    <img class="w-full h-full object-cover" 
                                         src="{{ esc_url($img_url) }}" 
                                         alt="{{ esc_attr($station_name) }}"
                                         loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                         decoding="async">
                                </div>
                            @endforeach
                        </div>
                        <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/50 to-transparent pointer-events-none"></div>
                        
                        @if (count($gallery) > 1)
                            <button type="button" 
                                    class="absolute left-3 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full bg-white/80 hover:bg-white text-slate-800 shadow transition-all hover:scale-105 active:scale-95 z-20"
                                    onclick="moveStationSlide(-1)">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <button type="button" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full bg-white/80 hover:bg-white text-slate-800 shadow transition-all hover:scale-105 active:scale-95 z-20"
                                    onclick="moveStationSlide(1)">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        @endif

                        <span class="absolute bottom-4 left-4 bg-black/60 text-white text-xs font-semibold px-3 py-1.5 rounded-lg backdrop-blur-sm select-none z-20" id="station-slide-counter">
                            <i class="fas fa-camera mr-1"></i> 1/{{ count($gallery) }} Ảnh
                        </span>
                    </div>

                    {{-- Right Station Info details --}}
                    <div class="flex flex-col justify-between py-2">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-600">
                                    <i class="fas fa-shield-alt"></i> Bến xe đối tác
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Đã xác thực
                                </span>
                            </div>

                            <h1 class="display-grotesk mt-4 text-3xl font-semibold leading-tight text-slate-950 md:text-4xl">
                                {{ $station_name }}
                            </h1>

                            <div class="mt-5 space-y-4 text-sm text-slate-600 be-vietnam">
                                <p class="flex items-start gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-blue-600">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <span class="mt-1.5 leading-relaxed">{{ $address }}</span>
                                </p>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <a href="tel:{{ preg_replace('/\D+/', '', $hotline) }}" 
                                       class="flex items-center gap-3 no-underline! text-slate-700 hover:text-blue-600 transition-colors">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-blue-600">
                                            <i class="fas fa-phone-alt"></i>
                                        </span>
                                        <div>
                                            <span class="block text-xs text-slate-400 font-semibold">Hotline hỗ trợ</span>
                                            <strong class="text-sm font-bold text-slate-900">{{ $hotline }}</strong>
                                        </div>
                                    </a>

                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-blue-600">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <div>
                                            <span class="block text-xs text-slate-400 font-semibold">Giờ hoạt động</span>
                                            <strong class="text-sm font-bold text-slate-900">{{ $hours }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Cluster Buttons --}}
                        <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-3 be-vietnam">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $map_query }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="no-underline! flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-blue-300 hover:text-blue-600">
                                <i class="fas fa-directions text-blue-500"></i> Chỉ đường
                            </a>

                            <a href="tel:{{ preg_replace('/\D+/', '', $hotline) }}" 
                               class="no-underline! flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-blue-300 hover:text-blue-600">
                                <i class="fas fa-phone text-emerald-500"></i> Gọi điện
                            </a>

                            <a href="{{ esc_url($website) }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="no-underline! flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-blue-300 hover:text-blue-600">
                                <i class="fas fa-globe text-indigo-500"></i> Website
                            </a>

                            <button type="button" 
                                    class="flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-blue-300 hover:text-blue-600"
                                    onclick="alert('Đã lưu bến xe {{ $station_name }} vào mục yêu thích!')">
                                <i class="far fa-bookmark text-amber-500"></i> Lưu bến xe
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Inbound / Outbound Routes Section --}}
            <main class="mx-auto max-w-7xl px-4 mt-12 sm:px-6 lg:px-8">
                <section aria-labelledby="route-list-title" class="bg-white rounded-3xl border border-slate-200 p-6 ben-xe-detail-shadow">
                    
                    {{-- Header with Direction Switcher --}}
                    <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 id="route-list-title" class="display-grotesk text-2xl font-semibold text-slate-950">
                                Các tuyến đường đến & đi từ bến xe
                            </h2>
                            <p class="mt-1.5 text-xs text-slate-500 be-vietnam">
                                Tìm kiếm vé xe khách tiện lợi, nhanh chóng của 100+ nhà xe hoạt động trực tiếp tại đây.
                            </p>
                        </div>

                        {{-- Inbound/Outbound Switcher --}}
                        <div class="flex bg-slate-100 p-1 rounded-2xl border border-slate-200 shrink-0 select-none be-vietnam" role="tablist">
                            <a href="{{ $get_switcher_url('from') }}" 
                               class="no-underline! rounded-xl px-4 py-2 text-xs font-bold transition-all {{ $direction === 'from' ? 'direction-tab-active' : 'text-slate-600 hover:text-blue-600' }}">
                                <i class="fas fa-sign-out-alt mr-1"></i> Từ bến xe
                            </a>
                            <a href="{{ $get_switcher_url('to') }}" 
                               class="no-underline! rounded-xl px-4 py-2 text-xs font-bold transition-all {{ $direction === 'to' ? 'direction-tab-active' : 'text-slate-600 hover:text-blue-600' }}">
                                <i class="fas fa-sign-in-alt mr-1"></i> Đến bến xe
                            </a>
                        </div>
                    </div>

                    @if ($api_error)
                        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800 be-vietnam flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle mt-0.5 shrink-0 text-amber-600"></i>
                            <div>
                                <strong class="block font-semibold mb-1">Không thể tải danh sách chuyến xe từ API</strong>
                                <p>{{ $api_error }}</p>
                            </div>
                        </div>
                    @endif

                    @if (empty($items) && !$api_error)
                        <div class="mt-8 rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 py-12 text-center be-vietnam">
                            <div class="flex justify-center text-5xl text-slate-300 mb-4">
                                <i class="fas fa-route"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Không tìm thấy chuyến xe nào</h3>
                            <p class="mt-2 text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                                Hiện tại không có dữ liệu hành trình nào hoạt động tại bến xe trong hệ thống. Lịch bến xe đang được chúng tôi cập nhật liên tục.
                            </p>
                        </div>
                    @elseif (!empty($items))
                        {{-- Province Filters badge pills list --}}
                        <div class="mt-6 flex items-center justify-between gap-3">
                            <div class="flex max-w-full gap-2 overflow-x-auto pb-2 custom-scrollbar be-vietnam" role="tablist">
                                <button type="button" 
                                        class="province-pill shrink-0 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 transition-all duration-200 province-pill-active"
                                        onclick="filterByProvince('all')">
                                    Tất cả tỉnh thành
                                </button>
                                @foreach ($provinces as $prov)
                                    <button type="button" 
                                            class="province-pill shrink-0 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 transition-all duration-200"
                                            onclick="filterByProvince('{{ esc_attr($prov) }}')">
                                        {{ $prov }}
                                    </button>
                                @endforeach
                            </div>

                            <span class="hidden md:inline-flex bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1.5 rounded-lg shrink-0 be-vietnam">
                                Tổng cộng: {{ number_format($total_items, 0, ',', '.') }} tuyến
                            </span>
                        </div>

                        {{-- Route Group Cards Grid --}}
                        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3 be-vietnam">
                            @foreach ($items as $item)
                                @php
                                    $from_name = $item['from']['name'] ?? '';
                                    $to_name = $item['to']['name'] ?? '';
                                    $prov_name = $direction === 'from' ? ($item['to']['province_name'] ?? '') : ($item['from']['province_name'] ?? '');
                                    
                                    $opp_name = $direction === 'from' ? $to_name : $from_name;
                                    $top_operators = array_slice($item['operators'] ?? [], 0, 3);
                                    
                                    $search_query_url = add_query_arg([
                                        'from' => $item['from']['id'] ?? '',
                                        'to' => $item['to']['id'] ?? '',
                                        'nameFrom' => $from_name,
                                        'nameTo' => $to_name,
                                        'date' => date('Y-m-d', strtotime('+1 day', current_time('timestamp')))
                                    ], home_url('/dat-ve-truc-tuyen/'));
                                @endphp

                                <article class="route-group-card group relative flex flex-col justify-between rounded-2xl bg-white p-5 shadow-[0_2px_12px_rgba(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgba(33,150,243,0.12)] border border-slate-100 hover:border-blue-200 transition-all duration-300"
                                         data-province="{{ esc_attr($prov_name) }}">
                                    <div>
                                        {{-- Header --}}
                                        <div class="flex items-start justify-between border-b border-slate-100/80 pb-4">
                                            <div class="pr-2">
                                                <h3 class="flex flex-col gap-1.5">
                                                    <span class="text-base font-extrabold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1" title="{{ $from_name }} - {{ $to_name }}">
                                                        {{ $from_name }} 
                                                        <i class="fas fa-arrow-right text-[11px] text-slate-400 mx-1 align-middle"></i> 
                                                        {{ $to_name }}
                                                    </span>
                                                    <div class="flex items-center gap-3 text-[11px] text-slate-500 font-medium">
                                                        <span class="flex items-center gap-1.5"><i class="fas fa-bus-alt text-blue-500"></i>{{ $item['operator_count'] ?? 0 }} nhà xe</span>
                                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                        <span class="flex items-center gap-1.5"><i class="fas fa-route text-emerald-500"></i>{{ $item['trip_count'] ?? 0 }} chuyến/ngày</span>
                                                    </div>
                                                </h3>
                                            </div>
                                            <div class="flex flex-col items-end shrink-0">
                                                <span class="text-[10px] text-slate-500 mb-0.5 font-medium">Giá vé từ</span>
                                                <span class="text-sm font-black text-rose-500 bg-rose-50 px-2.5 py-1 rounded-lg">
                                                    {{ $format_price($item['min_price'] ?? 0) }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Top Operators List --}}
                                        @if (!empty($top_operators))
                                            <div class="mt-4">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                                                    Nhà xe nổi bật
                                                    <span class="h-px bg-slate-100 flex-1"></span>
                                                </p>
                                                <div class="space-y-2">
                                                    @foreach ($top_operators as $idx => $op)
                                                        @php
                                                            $op_avatar = $op['media']['avatar_url'] ?? ($op['image_url'] ?? '');
                                                            $op_name = $op['name'] ?? '';
                                                            $op_rating = $op['display_rating'] ?? ($op['rating'] ?? '4.8');
                                                            $op_price = $op['min_price'] ?? 0;
                                                            $op_post_url = $op['media']['wp_url'] ?? '';
                                                            $badge_color = $idx === 0 ? 'bg-amber-100 text-amber-600' : ($idx === 1 ? 'bg-slate-100 text-slate-600' : 'bg-orange-50 text-orange-600');
                                                        @endphp
                                                        <div class="flex items-center gap-3 bg-slate-50/50 hover:bg-slate-50 p-2.5 rounded-xl transition-colors border border-transparent hover:border-slate-100">
                                                            <div class="relative shrink-0">
                                                                @if ($op_avatar)
                                                                    <img class="h-9 w-9 rounded-full object-cover border-2 border-white shadow-sm" 
                                                                         src="{{ esc_url($op_avatar) }}" 
                                                                         alt="{{ esc_attr($op_name) }}"
                                                                         loading="lazy">
                                                                @else
                                                                    <span class="flex h-9 w-9 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 text-[10px] font-bold text-blue-700 items-center justify-center border-2 border-white shadow-sm">
                                                                        {{ $get_initials($op_name) }}
                                                                    </span>
                                                                @endif
                                                                <span class="absolute -bottom-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full {{ $badge_color }} text-[8px] font-black border-2 border-white shadow-sm z-10">
                                                                    {{ $idx + 1 }}
                                                                </span>
                                                            </div>
                                                            
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex justify-between items-start mb-0.5">
                                                                    @if ($op_post_url)
                                                                        <a href="{{ esc_url($op_post_url) }}" class="text-xs font-bold text-slate-800 hover:text-blue-600 truncate max-w-[120px] no-underline!">
                                                                            {{ $op_name }}
                                                                        </a>
                                                                    @else
                                                                        <span class="text-xs font-bold text-slate-800 truncate max-w-[120px]">
                                                                            {{ $op_name }}
                                                                        </span>
                                                                    @endif
                                                                    <span class="text-[11px] font-bold text-slate-700">{{ $format_price($op_price) }}</span>
                                                                </div>
                                                                <div class="flex items-center gap-2 text-[10px] text-slate-500 font-medium">
                                                                    <span class="flex items-center gap-1 bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded text-[9px] font-bold">
                                                                        <i class="fas fa-star"></i> {{ $op_rating }}
                                                                    </span>
                                                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                                                    <span class="truncate">Ghế ngồi, Giường nằm</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Bottom Action --}}
                                    <div class="mt-5 pt-4 border-t border-slate-100/80 mt-auto">
                                        <a href="{{ esc_url($search_query_url) }}" 
                                           class="no-underline! relative overflow-hidden flex w-full items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm font-bold text-blue-600 transition-all hover:bg-blue-600 hover:text-white group/btn">
                                            <span>Xem lịch trình & đặt vé</span>
                                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-blue-600 group-hover/btn:bg-white group-hover/btn:text-blue-600 transition-colors shadow-sm">
                                                <i class="fas fa-chevron-right text-[10px]"></i>
                                            </div>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- Pagination Row --}}
                        @if ($total_pages > 1)
                            <nav class="mt-10 flex flex-wrap items-center justify-center gap-2 be-vietnam" aria-label="Phân trang tuyến đường bến xe">
                                {{-- Prev Page Button --}}
                                @if ($paged > 1)
                                    <a href="{{ $get_page_url($paged - 1) }}" 
                                       class="no-underline! inline-flex min-w-[40px] h-40 items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:border-blue-500 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-chevron-left text-xs"></i>
                                    </a>
                                @endif

                                {{-- Main Pages list --}}
                                @for ($i = 1; $i <= $total_pages; $i++)
                                    @if ($i == 1 || $i == $total_pages || ($i >= $paged - 1 && $i <= $paged + 1))
                                        <a href="{{ $get_page_url($i) }}" 
                                           class="no-underline! inline-flex min-w-[40px] h-40 items-center justify-center rounded-xl border text-sm font-bold transition-all duration-200 {{ $i === $paged ? 'bg-blue-600 border-blue-600 text-white shadow-md shadow-blue-500/25' : 'border-slate-200 bg-white text-slate-700 hover:border-blue-500 hover:text-blue-600' }}">
                                            {{ $i }}
                                        </a>
                                    @elseif ($i == 2 || $i == $total_pages - 1)
                                        <span class="text-slate-400 font-semibold px-1">...</span>
                                    @endif
                                @endfor

                                {{-- Next Page Button --}}
                                @if ($paged < $total_pages)
                                    <a href="{{ $get_page_url($paged + 1) }}" 
                                       class="no-underline! inline-flex min-w-[40px] h-40 items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:border-blue-500 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </a>
                                @endif
                            </nav>
                        @endif
                    @endif
                </section>
            </main>

            {{-- Dynamic Info Tabs and Highlights Section --}}
            <section class="mx-auto max-w-7xl px-4 mt-8 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[280px_1fr_320px] items-start be-vietnam">
                    
                    {{-- Left tabs menu list selector --}}
                    <aside class="w-full bg-white border border-slate-200 rounded-3xl p-4 ben-xe-detail-shadow space-y-1">
                        <h3 class="display-grotesk px-3 py-2 text-sm font-bold text-slate-950 uppercase border-b border-slate-100 pb-3 mb-2">
                            Thông tin bến xe
                        </h3>
                        @foreach ($tabs as $index => $tab)
                            <button type="button" 
                                    class="tab-trigger w-full flex items-center gap-3 px-4 py-3 text-left text-xs font-semibold text-slate-600 rounded-xl bg-transparent hover:bg-slate-50 transition-all duration-200 {{ $index === 0 ? 'tab-trigger-active' : '' }}"
                                    data-tab-id="{{ $tab['id'] }}"
                                    onclick="switchInfoTab('{{ $tab['id'] }}')">
                                <i class="{{ $tab['icon'] }} shrink-0 text-slate-400"></i>
                                <span>{{ $tab['label'] }}</span>
                            </button>
                        @endforeach
                    </aside>

                    {{-- Center Content Pane panel --}}
                    <div class="w-full bg-white border border-slate-200 rounded-3xl p-6 ben-xe-detail-shadow min-h-[380px] flex flex-col">
                        
                        {{-- Tab Pane: Giới thiệu --}}
                        <article class="tab-pane active" id="pane-intro">
                            <h3 class="display-grotesk text-xl font-bold text-slate-950 mb-4 border-b border-slate-100 pb-2">
                                Giới thiệu bến xe
                            </h3>
                            <div class="text-sm text-slate-600 leading-relaxed space-y-4">
                                @if ($post_content)
                                    {!! apply_filters('the_content', $post_content) !!}
                                @else
                                    <p>
                                        Bến xe này là một trong những đầu mối giao thông đường bộ cực kỳ quan trọng tại địa phương. Bến xe có hạ tầng được quy hoạch đồng bộ, khang trang và hiện đại hàng đầu Việt Nam. Mỗi ngày bến xe phục vụ hàng vạn lượt hành khách trung chuyển tỏa ra khắp các tỉnh thành trên cả nước.
                                    </p>
                                    <p>
                                        Với phương châm đảm bảo an toàn tuyệt đối, trật tự và cung cấp các dịch vụ chất lượng cao tốt nhất, ban quản lý bến xe liên kết chặt chẽ cùng các đơn vị nhà xe uy tín cao để liên tục cải tiến hệ thống bán vé, đón trả khách trực tuyến dễ dàng, thân thiện.
                                    </p>
                                @endif
                            </div>

                            {{-- Google Embed map inside intro --}}
                            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 shadow-sm aspect-[16/9] relative">
                                <iframe class="absolute inset-0 w-full h-full border-0" 
                                        src="{{ esc_url($map_embed_url) }}" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </article>

                        {{-- Tab Pane: Sơ đồ --}}
                        <article class="tab-pane hidden" id="pane-map" style="display: none;">
                            <h3 class="display-grotesk text-xl font-bold text-slate-950 mb-4 border-b border-slate-100 pb-2">
                                Sơ đồ bến xe
                            </h3>
                            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                                Sơ đồ phân khu chức năng bến xe: Nhà chờ ga đi, Sảnh chờ VIP, Nhà xe 2 bánh, Trạm đón taxi và Khu vực đỗ xe khách liên tỉnh đón trả khách.
                            </p>
                            <div class="overflow-hidden rounded-2xl border border-slate-200 aspect-[16/10] bg-slate-50 flex items-center justify-center text-slate-400">
                                <div class="text-center">
                                    <i class="fas fa-map text-5xl text-slate-300 mb-3 block"></i>
                                    <span class="text-xs font-semibold">Sơ đồ phân khu đang được ban quản lý cập nhật</span>
                                </div>
                            </div>
                        </article>

                        {{-- Tab Pane: Tiện ích --}}
                        <article class="tab-pane hidden" id="pane-amenities" style="display: none;">
                            <h3 class="display-grotesk text-xl font-bold text-slate-950 mb-4 border-b border-slate-100 pb-2">
                                Tiện ích bến xe cung cấp
                            </h3>
                            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                                Khuôn viên bến xe được cung cấp đầy đủ các tiện ích gia tăng hiện đại chuẩn quốc tế nhằm đem lại trải nghiệm di chuyển tối ưu nhất cho hành khách đón xe:
                            </p>
                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach ($highlights as $hl)
                                    <div class="flex items-start gap-3 p-3 bg-slate-50/50 hover:bg-slate-50 rounded-xl transition-colors">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 shrink-0">
                                            <i class="{{ $hl['icon'] }}"></i>
                                        </span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">{{ $hl['label'] }}</h4>
                                            <p class="text-[11px] text-slate-400 mt-0.5 font-medium leading-relaxed">{{ $hl['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </article>

                        {{-- Tab Pane: Quy định --}}
                        <article class="tab-pane hidden" id="pane-rules" style="display: none;">
                            <h3 class="display-grotesk text-xl font-bold text-slate-950 mb-4 border-b border-slate-100 pb-2">
                                Quy định bến xe khách
                            </h3>
                            <div class="text-sm text-slate-600 leading-relaxed space-y-4">
                                <p>Hành khách mua vé đón xe tại bến xe vui lòng tuyệt đối tuân thủ theo các quy định dưới đây để đảm bảo an ninh trật tự chung:</p>
                                <ul class="list-disc pl-5 space-y-2.5">
                                    <li>Đón xe đúng khu vực quy định (Nhà ga chính, đúng ô vị trí bến đổ của hãng xe).</li>
                                    <li>Có mặt trước giờ khởi hành tối thiểu 30-45 phút để làm thủ tục ký gửi hàng lý và check-in vé.</li>
                                    <li>Nghiêm cấm mang theo các vật dụng dễ cháy nổ, vũ khí nguy hiểm, các chất độc hại nằm trong danh mục cấm lên xe.</li>
                                    <li>Giữ vệ sinh chung tại khu vực ngồi chờ, khu ăn uống công cộng bến xe.</li>
                                </ul>
                            </div>
                        </article>

                        {{-- Tab Pane: Gửi hàng --}}
                        <article class="tab-pane hidden" id="pane-shipping" style="display: none;">
                            <h3 class="display-grotesk text-xl font-bold text-slate-950 mb-4 border-b border-slate-100 pb-2">
                                Hướng dẫn gửi nhận hàng hóa
                            </h3>
                            <div class="text-sm text-slate-600 leading-relaxed space-y-4">
                                <p>Bên cạnh dịch vụ vận tải hành khách, bến xe cung cấp các khu vực ga tập kết hàng hóa ký gửi (Nhận hàng từ các tỉnh chuyển về và Gửi hàng đi tỉnh miền khác):</p>
                                <ol class="list-decimal pl-5 space-y-2.5">
                                    <li><strong>Đóng gói hàng hóa</strong>: Đảm bảo bọc kín thùng các-tông hoặc bao bì ni-lông tránh va đập mạnh.</li>
                                    <li><strong>Địa điểm giao nhận</strong>: Mang hàng đến trực tiếp khu văn phòng nhận hàng của hãng xe hoặc bãi đổ ga hàng hóa.</li>
                                    <li><strong>Ghi thông tin liên lạc</strong>: Đảm bảo ghi chính xác họ tên, SĐT liên hệ của cả người gửi và người nhận.</li>
                                    <li><strong>Nhận hàng về</strong>: Người nhận đến bến xe trình chứng minh nhân dân/căn cước công dân hoặc mã vận đơn SMS của hãng xe để được kiểm kho lấy hàng.</li>
                                </ol>
                            </div>
                        </article>

                        {{-- Tab Pane: Di chuyển --}}
                        <article class="tab-pane hidden" id="pane-transit" style="display: none;">
                            <h3 class="display-grotesk text-xl font-bold text-slate-950 mb-4 border-b border-slate-100 pb-2">
                                Hướng dẫn di chuyển đến bến xe
                            </h3>
                            <div class="text-sm text-slate-600 leading-relaxed space-y-4">
                                <p>Hành khách có thể dễ dàng di chuyển nhanh tới khuôn viên bến xe bằng đa dạng loại phương tiện:</p>
                                <ul class="list-disc pl-5 space-y-2.5">
                                    <li><strong>Xe buýt nội thành</strong>: Có hệ thống ga xe buýt lớn trung chuyển khách ngay sảnh mặt tiền bến xe với tần suất 10-15 phút/chuyến.</li>
                                    <li><strong>Taxi công nghệ / Taxi truyền thống</strong>: Trạm trung chuyển đón khách taxi nằm sát ga đi và ga đến, dễ bắt xe 24/7.</li>
                                    <li><strong>Xe trung chuyển nhà xe</strong>: Rất nhiều nhà xe cung cấp miễn phí dịch vụ xe trung chuyển đón trả khách tận nơi trong phạm vi bán kính 5-10km nội thành đưa trực tiếp vào bến.</li>
                                    <li><strong>Phương tiện cá nhân</strong>: Có bãi gửi xe nhiều tầng/bãi đỗ rộng rãi gửi xe qua đêm an toàn tuyệt đối.</li>
                                </ul>
                            </div>
                        </article>

                    </div>

                    {{-- Right Amenities sidebar card --}}
                    <aside class="w-full bg-white border border-slate-200 rounded-3xl p-5 ben-xe-detail-shadow">
                        <h3 class="display-grotesk text-sm font-bold text-slate-950 uppercase border-b border-slate-100 pb-3 mb-4">
                            Tiện ích nổi bật
                        </h3>
                        <ul class="space-y-3.5 text-xs text-slate-600 font-semibold">
                            @foreach ($highlights as $hl)
                                <li class="flex items-start gap-2.5">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-50 text-blue-600 shrink-0">
                                        <i class="{{ $hl['icon'] }}"></i>
                                    </span>
                                    <div class="mt-0.5">
                                        <span class="block text-slate-900 font-bold">{{ $hl['label'] }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </aside>

                </div>
            </section>
        </div>

        {{-- Interactive Javascript handlers for tabs & local province filter --}}
        <script>
            // Client-side quick filter logic for Province Pills
            function filterByProvince(provinceName) {
                // Handle active styles for buttons
                document.querySelectorAll('.province-pill').forEach(btn => {
                    btn.classList.remove('province-pill-active', 'bg-blue-600', 'text-white');
                    btn.classList.add('bg-slate-100', 'text-slate-700');
                });
                
                const activeBtn = window.event ? window.event.currentTarget : null;
                if (activeBtn) {
                    activeBtn.classList.remove('bg-slate-100', 'text-slate-700');
                    activeBtn.classList.add('province-pill-active', 'bg-blue-600', 'text-white');
                }

                // Hide/show cards
                document.querySelectorAll('.route-group-card').forEach(card => {
                    if (provinceName === 'all' || card.getAttribute('data-province') === provinceName) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Client-side tab switching handler
            function switchInfoTab(tabId) {
                // Switch triggers
                document.querySelectorAll('.tab-trigger').forEach(btn => {
                    btn.classList.remove('tab-trigger-active');
                });
                const activeTrigger = document.querySelector('[data-tab-id="' + tabId + '"]');
                if (activeTrigger) {
                    activeTrigger.classList.add('tab-trigger-active');
                }

                // Switch panes
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.style.display = 'none';
                    pane.classList.remove('active');
                });
                const activePane = document.getElementById('pane-' + tabId);
                if (activePane) {
                    activePane.style.display = 'block';
                    activePane.classList.add('active');
                }
            }

            // Client-side quick sliding gallery
            let currentStationSlide = 0;
            const totalStationSlides = {{ count($gallery) }};
            function moveStationSlide(direction) {
                currentStationSlide = (currentStationSlide + direction + totalStationSlides) % totalStationSlides;
                const track = document.getElementById('station-slides-track');
                if (track) {
                    track.style.transform = `translateX(-${(currentStationSlide * 100) / totalStationSlides}%)`;
                }
                const counter = document.getElementById('station-slide-counter');
                if (counter) {
                    counter.innerHTML = `<i class="fas fa-camera mr-1"></i> ${currentStationSlide + 1}/${totalStationSlides} Ảnh`;
                }
            }
        </script>
    @endwhile
@endsection

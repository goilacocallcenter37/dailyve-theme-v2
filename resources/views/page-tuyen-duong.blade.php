@extends('layouts.app')

@section('content')
    @php
        $page_id = get_queried_object_id();
        $paged = max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 1));
        $per_page = 9;

        $routes_query = new \WP_Query([
            'post_type' => 'page',
            'post_parent' => 15738,
            'posts_per_page' => $per_page,
            'paged' => $paged,
            'orderby' => 'ID',
            'order' => 'DESC',
            'post_status' => 'publish',
            'no_found_rows' => false,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => false,
        ]);

        $total_routes = (int) $routes_query->found_posts;
        $total_pages = (int) $routes_query->max_num_pages;
        $from_index = $total_routes > 0 ? ($paged - 1) * $per_page + 1 : 0;
        $to_index = min($paged * $per_page, $total_routes);
        $canonical_url = get_permalink($page_id);

        $parse_route_title = function ($title) {
            $clean_title = trim(wp_strip_all_tags($title));
            $from = '';
            $to = '';

            if (preg_match('/từ\s+(.+?)\s+đi\s+(.+)/iu', $clean_title, $matches)) {
                $from = trim($matches[1]);
                $to = trim($matches[2]);
            } elseif (preg_match('/(.+?)\s+đi\s+(.+)/iu', $clean_title, $matches)) {
                $from = trim($matches[1]);
                $to = trim($matches[2]);
            }

            return [
                'from' => $from,
                'to' => $to,
            ];
        };

        $format_route_price = function ($price) {
            if ($price === '' || $price === null) {
                return '';
            }

            if (is_numeric($price)) {
                return number_format((float) $price, 0, ',', '.') . 'đ';
            }

            return trim((string) $price);
        };
    @endphp

    <div class="route-directory overflow-x-hidden bg-slate-50 text-slate-700">
        <x-breadcrumb :items="[
            ['title' => 'Dailyve', 'url' => home_url('/')],
            ['title' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
            ['title' => 'Tuyến đường', 'url' => ''],
        ]" preset="directory" />


        <header class="bg-white">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-8 lg:py-14">
                <div>
                    <p class="mb-4 inline-flex rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-blue-600">
                        Danh bạ tuyến xe khách Dailyve
                    </p>
                    <h1
                        class="route-directory__display max-w-4xl text-4xl font-semibold leading-tight text-slate-950 md:text-5xl">
                        Danh sách tuyến đường xe khách
                    </h1>
                    <p class="mt-5 max-w-3xl text-base leading-7 text-slate-600 md:text-lg">
                        Tra cứu nhanh các tuyến xe khách phổ biến, so sánh giá tham khảo, thời gian di chuyển và mở trang
                        chi tiết để đặt vé trực tuyến trên Dailyve.
                    </p>
                </div>

                <aside class="rounded-xl border border-slate-200 bg-slate-50 p-5" aria-label="Thông tin danh sách tuyến">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-white p-4">
                            <span class="block text-sm font-medium text-slate-500">Tuyến đang có</span>
                            <strong
                                class="route-directory__display mt-1 block text-3xl font-semibold text-slate-950">{{ number_format($total_routes, 0, ',', '.') }}</strong>
                        </div>
                        <div class="rounded-lg bg-white p-4">
                            <span class="block text-sm font-medium text-slate-500">Hiển thị</span>
                            <strong
                                class="route-directory__display mt-1 block text-3xl font-semibold text-slate-950">9</strong>
                        </div>
                    </div>
                    <a class="mt-4 inline-flex h-10 w-full items-center justify-center rounded-lg bg-blue-500 px-5 text-sm font-semibold text-white hover:bg-blue-700"
                        href="{{ esc_url(home_url('/dat-ve-truc-tuyen/')) }}">
                        Tìm vé theo hành trình
                    </a>
                </aside>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
            <section aria-labelledby="route-directory-list-title" class="mx-auto max-w-7xl">
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 id="route-directory-list-title"
                            class="route-directory__display text-2xl font-semibold text-slate-950 md:text-3xl">
                            Tất cả tuyến đường
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">
                            @if ($total_routes > 0)
                                Đang xem
                                {{ number_format($from_index, 0, ',', '.') }}-{{ number_format($to_index, 0, ',', '.') }}
                                trong {{ number_format($total_routes, 0, ',', '.') }} tuyến.
                            @else
                                Dailyve đang cập nhật thêm tuyến mới.
                            @endif
                        </p>
                    </div>
                    <p class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-600 ring-1 ring-slate-200">
                        Trang {{ number_format($paged, 0, ',', '.') }}@if ($total_pages > 1)
                            /{{ number_format($total_pages, 0, ',', '.') }}
                        @endif
                    </p>
                </div>

                @if ($routes_query->have_posts())
                    @php $route_index = 0; @endphp
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @while ($routes_query->have_posts())
                            @php
                                $routes_query->the_post();
                                $route_index++;
                                $route_id = get_the_ID();
                                $route_title = html_entity_decode(get_the_title($route_id), ENT_QUOTES, 'UTF-8');
                                $route_url = get_permalink($route_id);
                                $route_meta = $parse_route_title($route_title);
                                $price = function_exists('get_field')
                                    ? get_field('routes_price', $route_id)
                                    : get_post_meta($route_id, 'routes_price', true);
                                $distance = function_exists('get_field')
                                    ? get_field('routes_distance', $route_id)
                                    : get_post_meta($route_id, 'routes_distance', true);
                                $time = function_exists('get_field')
                                    ? get_field('routes_time', $route_id)
                                    : get_post_meta($route_id, 'routes_time', true);
                                $price_label = $format_route_price($price);
                                $thumb_id = get_post_thumbnail_id($route_id);
                                $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : '';
                                $image_loading = $route_index <= 3 ? 'eager' : 'lazy';
                                $image_attrs = [
                                    'class' =>
                                        'h-full w-full object-cover transition-transform duration-300 group-hover:scale-105',
                                    'loading' => $image_loading,
                                    'decoding' => 'async',
                                    'sizes' => '(min-width: 1024px) 380px, (min-width: 768px) 50vw, 100vw',
                                    'onerror' =>
                                        'this.onerror=null; this.style.display="none"; var p=this.parentElement.querySelector(".image-placeholder"); if(p) p.style.display="flex";',
                                ];
                                if ($route_index === 1) {
                                    $image_attrs['fetchpriority'] = 'high';
                                }
                                $viewed_payload = [
                                    'id' => $route_id,
                                    'title' => $route_title,
                                    'url' => $route_url,
                                    'image' => $thumb_url,
                                    'price' => $price_label,
                                    'distance' => $distance ?: '',
                                    'time' => $time ?: '',
                                ];
                            @endphp

                            <article
                                class="group flex min-h-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                                <a href="{{ esc_url($route_url) }}"
                                    class="block aspect-[4/3] overflow-hidden bg-slate-100 relative"
                                    aria-label="Xem {{ esc_attr($route_title) }}"
                                    data-route-record='@json($viewed_payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)'>
                                    @if ($thumb_id)
                                        {!! wp_get_attachment_image($thumb_id, 'medium_large', false, $image_attrs) !!}
                                        <div class="image-placeholder flex h-full w-full items-center justify-center text-4xl text-slate-300 absolute inset-0 bg-slate-100"
                                            style="display: none;">
                                            <i class="fas fa-route" aria-hidden="true"></i>
                                        </div>
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-4xl text-slate-300">
                                            <i class="fas fa-route" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </a>

                                <div class="flex flex-1 flex-col p-5">
                                    @if ($route_meta['from'] || $route_meta['to'])
                                        <p class="mb-3 flex items-center gap-2 text-sm font-semibold text-blue-600">
                                            <i class="fas fa-location-arrow text-xs" aria-hidden="true"></i>
                                            <span class="truncate">{{ $route_meta['from'] ?: 'Điểm đi' }} →
                                                {{ $route_meta['to'] ?: 'Điểm đến' }}</span>
                                        </p>
                                    @endif

                                    <h3 class="text-lg font-semibold leading-snug text-slate-950">
                                        <a class="hover:text-blue-600" href="{{ esc_url($route_url) }}"
                                            data-route-record='@json($viewed_payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)'>
                                            {{ $route_title }}
                                        </a>
                                    </h3>

                                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                        <div class="rounded-lg bg-slate-50 p-3">
                                            <span class="block text-xs font-medium text-slate-500">Giá từ</span>
                                            <strong
                                                class="mt-1 block text-base font-semibold text-slate-950">{{ $price_label ?: 'Liên hệ' }}</strong>
                                        </div>
                                        <div class="rounded-lg bg-slate-50 p-3">
                                            <span class="block text-xs font-medium text-slate-500">Thời gian</span>
                                            <strong
                                                class="mt-1 block text-base font-semibold text-slate-950">{{ $time ?: 'Đang cập nhật' }}</strong>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3.5">
                                        @if ($distance)
                                            <span class="inline-flex items-center gap-1.5 text-xs text-slate-500">
                                                <i class="fas fa-road text-blue-500" aria-hidden="true"></i>
                                                <span>Quãng đường: <strong
                                                        class="font-semibold text-slate-800">{{ $distance }}</strong></span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs text-slate-400">
                                                <i class="fas fa-road text-slate-300" aria-hidden="true"></i>
                                                <span class="font-medium text-slate-400">Đang cập nhật chiều dài</span>
                                            </span>
                                        @endif
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Vé điện tử
                                        </span>
                                    </div>

                                    <a class="mt-5 inline-flex h-10 items-center justify-center rounded-lg bg-blue-500 px-5 text-sm font-semibold text-white hover:bg-blue-700"
                                        href="{{ esc_url($route_url) }}" data-route-record='@json($viewed_payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)'>
                                        Đặt vé
                                    </a>
                                </div>
                            </article>
                        @endwhile
                    </div>

                    @php
                        wp_reset_postdata();
                        $pagination_links =
                            $total_pages > 1
                                ? paginate_links([
                                    'base' => trailingslashit($canonical_url) . '%_%',
                                    'format' => 'page/%#%/',
                                    'current' => $paged,
                                    'total' => $total_pages,
                                    'type' => 'array',
                                    'prev_text' =>
                                        '<i class="fas fa-chevron-left" aria-hidden="true"></i><span class="sr-only">Trang trước</span>',
                                    'next_text' =>
                                        '<span class="sr-only">Trang sau</span><i class="fas fa-chevron-right" aria-hidden="true"></i>',
                                    'mid_size' => 1,
                                    'end_size' => 1,
                                ])
                                : [];
                    @endphp

                    @if (!empty($pagination_links))
                        <nav class="route-directory-pagination mt-10 flex flex-wrap items-center justify-center gap-2"
                            aria-label="Phân trang tuyến đường">
                            @foreach ($pagination_links as $link)
                                {!! $link !!}
                            @endforeach
                        </nav>
                    @endif
                @else
                    @php wp_reset_postdata(); @endphp
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center">
                        <h3 class="text-lg font-semibold text-slate-950">Chưa có tuyến đường</h3>
                        <p class="mt-2 text-sm text-slate-500">Dailyve đang cập nhật thêm các tuyến xe khách mới.</p>
                    </div>
                @endif
            </section>

            <section class="mt-14 border-t border-slate-200 pt-10 mx-auto max-w-7xl" aria-labelledby="route-viewed-title"
                data-viewed-routes-section>
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 id="route-viewed-title"
                            class="route-directory__display text-2xl font-semibold text-slate-950 md:text-3xl">
                            Các bài viết đã xem
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">Những tuyến bạn đã mở gần đây sẽ được lưu trên trình duyệt
                            này.</p>
                    </div>
                    <div class="hidden gap-2 md:flex">
                        <button
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 hover:border-blue-500 hover:text-blue-600"
                            type="button" data-viewed-prev aria-label="Bài đã xem trước">
                            <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                        </button>
                        <button
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 hover:border-blue-500 hover:text-blue-600"
                            type="button" data-viewed-next aria-label="Bài đã xem tiếp theo">
                            <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <div class="route-directory__scroller flex snap-x gap-5 overflow-x-auto scroll-smooth pb-2"
                    data-viewed-track></div>

                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500"
                    data-viewed-empty>
                    Bạn chưa xem tuyến nào. Khi mở một bài tuyến đường, danh sách này sẽ tự cập nhật.
                </div>
            </section>
        </main>

        <script>
            (function() {
                var storageKey = 'dailyve:viewed-routes';
                var maxItems = 12;

                function readViewed() {
                    try {
                        var parsed = JSON.parse(localStorage.getItem(storageKey) || '[]');
                        return Array.isArray(parsed) ? parsed.filter(function(item) {
                            return item && item.url && item.title;
                        }) : [];
                    } catch (error) {
                        return [];
                    }
                }

                function writeViewed(items) {
                    try {
                        localStorage.setItem(storageKey, JSON.stringify(items.slice(0, maxItems)));
                    } catch (error) {}
                }

                function normalizeRecord(record) {
                    if (!record || !record.url || !record.title) return null;
                    return {
                        id: record.id || record.url,
                        title: record.title,
                        url: record.url,
                        image: record.image || '',
                        price: record.price || '',
                        distance: record.distance || '',
                        time: record.time || '',
                        viewedAt: Date.now()
                    };
                }

                function saveRecord(record) {
                    var normalized = normalizeRecord(record);
                    if (!normalized) return;

                    var items = readViewed().filter(function(item) {
                        return String(item.id || item.url) !== String(normalized.id || normalized.url);
                    });
                    items.unshift(normalized);
                    writeViewed(items);
                }

                function escapeHtml(value) {
                    return String(value || '').replace(/[&<>"']/g, function(char) {
                        return {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#039;'
                        } [char];
                    });
                }

                function renderViewed() {
                    var section = document.querySelector('[data-viewed-routes-section]');
                    var track = document.querySelector('[data-viewed-track]');
                    var empty = document.querySelector('[data-viewed-empty]');
                    if (!section || !track || !empty) return;

                    var items = readViewed();
                    empty.hidden = items.length > 0;
                    track.innerHTML = items.map(function(item) {
                        var meta = [item.distance, item.time].filter(Boolean).join(' - ');
                        return [
                            '<article class="w-[280px] shrink-0 snap-start overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm md:w-[340px]">',
                            '<a class="block aspect-[4/3] bg-slate-100" href="' + escapeHtml(item.url) + '">',
                            item.image ? '<img class="h-full w-full object-cover" src="' + escapeHtml(item
                                .image) + '" alt="' + escapeHtml(item.title) +
                            '" loading="lazy" decoding="async">' :
                            '<div class="flex h-full w-full items-center justify-center text-3xl text-slate-300"><i class="fas fa-route" aria-hidden="true"></i></div>',
                            '</a>',
                            '<div class="p-4">',
                            '<h3 class="text-base font-semibold leading-snug text-slate-950"><a class="hover:text-blue-600" href="' +
                            escapeHtml(item.url) + '">' + escapeHtml(item.title) + '</a></h3>',
                            meta ? '<p class="mt-2 text-sm text-slate-500">' + escapeHtml(meta) + '</p>' : '',
                            item.price ? '<p class="mt-3 text-sm font-semibold text-blue-600">Từ ' + escapeHtml(
                                item.price) + '</p>' : '',
                            '</div>',
                            '</article>'
                        ].join('');
                    }).join('');
                }

                document.addEventListener('click', function(event) {
                    var link = event.target.closest('[data-route-record]');
                    if (!link) return;

                    try {
                        saveRecord(JSON.parse(link.getAttribute('data-route-record') || '{}'));
                    } catch (error) {}
                }, {
                    capture: true
                });

                document.addEventListener('DOMContentLoaded', function() {
                    renderViewed();

                    var track = document.querySelector('[data-viewed-track]');
                    var prev = document.querySelector('[data-viewed-prev]');
                    var next = document.querySelector('[data-viewed-next]');
                    if (!track) return;

                    if (prev) {
                        prev.addEventListener('click', function() {
                            track.scrollBy({
                                left: -track.clientWidth * 0.85,
                                behavior: 'smooth'
                            });
                        });
                    }

                    if (next) {
                        next.addEventListener('click', function() {
                            track.scrollBy({
                                left: track.clientWidth * 0.85,
                                behavior: 'smooth'
                            });
                        });
                    }
                });
            })();
        </script>
    </div>
@endsection

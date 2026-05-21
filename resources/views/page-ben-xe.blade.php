@extends('layouts.app')

@section('content')
    @php
        $page_id = get_queried_object_id();
        $paged = max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 1));
        $per_page = 9;

        $routes_query = new \WP_Query([
            'post_type' => 'page',
            'post_parent' => 15896, // Ben xe parent ID
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
    @endphp

    <div class="route-directory overflow-x-hidden bg-slate-50 text-slate-700">
        <style>
            .route-directory__display {
                font-family: "Space Grotesk", "Be Vietnam Pro", sans-serif;
                letter-spacing: -0.03em;
            }

            .route-directory,
            .route-directory * {
                min-width: 0;
            }

            .route-directory {
                width: 100%;
                max-width: 100%;
            }

            .route-directory nav,
            .route-directory header,
            .route-directory main {
                max-width: 100%;
                overflow-x: hidden;
            }

            .route-directory h1,
            .route-directory h2,
            .route-directory h3,
            .route-directory p {
                overflow-wrap: anywhere;
            }

            @media (max-width: 767px) {
                .route-directory>nav>ol,
                .route-directory>header>div,
                .route-directory>main {
                    box-sizing: border-box;
                    width: 100% !important;
                    max-width: 100% !important;
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                    padding-left: 16px !important;
                    padding-right: 16px !important;
                }

                .route-directory>header>div {
                    grid-template-columns: minmax(0, 1fr) !important;
                }
            }

            .route-directory__scroller {
                scrollbar-width: none;
            }

            .route-directory__scroller::-webkit-scrollbar {
                display: none;
            }

            .route-directory-pagination .page-numbers {
                display: inline-flex;
                min-width: 40px;
                height: 40px;
                align-items: center;
                justify-content: center;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #ffffff;
                color: #334155;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
            }

            .route-directory-pagination .page-numbers.current {
                border-color: #2196f3;
                background: #2196f3;
                color: #ffffff;
            }

            .route-directory-pagination .page-numbers:hover {
                border-color: #2196f3;
                color: #2196f3;
            }

            .route-directory-pagination .page-numbers.current:hover {
                color: #ffffff;
            }
        </style>

        <nav class="border-b border-slate-200 bg-white" aria-label="Breadcrumb">
            <ol
                class="mx-auto flex max-w-7xl flex-wrap items-center gap-2 px-4 py-4 text-sm font-medium text-slate-500 sm:px-6 lg:px-8">
                <li><a class="text-slate-500 hover:text-blue-600" href="{{ esc_url(home_url('/')) }}">Dailyve</a></li>
                <li aria-hidden="true">/</li>
                <li><a class="text-slate-500 hover:text-blue-600" href="{{ esc_url(home_url('/ve-xe-khach/')) }}">Vé xe khách</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-semibold text-slate-900" aria-current="page">Bến xe</li>
            </ol>
        </nav>

        <header class="bg-white">
            <div
                class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-8 lg:py-14">
                <div>
                    <p class="mb-4 inline-flex rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-blue-600">
                        Danh bạ bến xe khách Dailyve
                    </p>
                    <h1
                        class="route-directory__display max-w-4xl text-4xl font-semibold leading-tight text-slate-950 md:text-5xl">
                        Danh sách bến xe khách uy tín
                    </h1>
                    <p class="mt-5 max-w-3xl text-base leading-7 text-slate-600 md:text-lg">
                        Tra cứu nhanh thông tin các bến xe khách chất lượng cao trên toàn quốc, địa chỉ, số điện thoại liên hệ, bản đồ di chuyển và danh sách các nhà xe đang hoạt động.
                    </p>
                </div>

                <aside class="rounded-xl border border-slate-200 bg-slate-50 p-5" aria-label="Thông tin danh sách bến xe">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-white p-4">
                            <span class="block text-sm font-medium text-slate-500">Bến xe đang có</span>
                            <strong
                                class="route-directory__display mt-1 block text-3xl font-semibold text-slate-950">{{ number_format($total_routes, 0, ',', '.') }}</strong>
                        </div>
                        <div class="rounded-lg bg-white p-4">
                            <span class="block text-sm font-medium text-slate-500">Hiển thị</span>
                            <strong
                                class="route-directory__display mt-1 block text-3xl font-semibold text-slate-950">{{ $per_page }}</strong>
                        </div>
                    </div>
                    <a class="mt-4 inline-flex h-10 w-full items-center justify-center rounded-lg bg-blue-500 px-5 text-sm font-semibold text-white hover:bg-blue-700"
                        href="{{ esc_url(home_url('/dat-ve-truc-tuyen/')) }}">
                        Tìm chuyến xe đặt vé
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
                            Tất cả bến xe khách
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">
                            @if ($total_routes > 0)
                                Đang xem
                                {{ number_format($from_index, 0, ',', '.') }}-{{ number_format($to_index, 0, ',', '.') }}
                                trong {{ number_format($total_routes, 0, ',', '.') }} bến xe.
                            @else
                                Dailyve đang cập nhật thêm bến xe mới.
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
                                $route_title = get_the_title($route_id);
                                $route_url = get_permalink($route_id);
                                $address = function_exists('get_field')
                                     ? get_field('company_address', $route_id)
                                     : get_post_meta($route_id, 'company_address', true);
                                $thumb_id = get_post_thumbnail_id($route_id);
                                $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : '';
                                $image_loading = $route_index <= 3 ? 'eager' : 'lazy';
                                $image_attrs = [
                                    'class' =>
                                        'h-full w-full object-cover transition-transform duration-300 group-hover:scale-105',
                                    'loading' => $image_loading,
                                    'decoding' => 'async',
                                    'sizes' => '(min-width: 1024px) 380px, (min-width: 768px) 50vw, 100vw',
                                ];
                                if ($route_index === 1) {
                                    $image_attrs['fetchpriority'] = 'high';
                                }
                            @endphp

                            <article
                                class="group flex min-h-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                                <a href="{{ esc_url($route_url) }}" class="block aspect-[4/3] overflow-hidden bg-slate-100"
                                    aria-label="Xem {{ esc_attr($route_title) }}">
                                    @if ($thumb_id)
                                        {!! wp_get_attachment_image($thumb_id, 'medium_large', false, $image_attrs) !!}
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-4xl text-slate-300">
                                            <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </a>

                                <div class="flex flex-1 flex-col p-5">
                                    <h3 class="text-base md:text-lg font-semibold leading-snug text-slate-950">
                                        <a class="hover:text-blue-600 transition-colors" href="{{ esc_url($route_url) }}">
                                            {{ $route_title }}
                                        </a>
                                    </h3>

                                    @if ($address)
                                        <p class="mt-3 flex items-start gap-2 text-xs md:text-sm text-slate-600 leading-relaxed">
                                            <i class="fas fa-map-marker-alt text-blue-500 mt-0.5 shrink-0" aria-hidden="true"></i>
                                            <span>{{ $address }}</span>
                                        </p>
                                    @endif

                                    <div class="mt-4 flex-1 text-xs md:text-sm text-slate-500 leading-relaxed line-clamp-2">
                                        @php
                                            $excerpt = get_the_excerpt($route_id);
                                            if (!$excerpt) {
                                                $excerpt = wp_trim_words(get_post_field('post_content', $route_id), 18);
                                            }
                                        @endphp
                                        {{ $excerpt }}
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3.5">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                            <i class="fas fa-shield-alt text-blue-500" aria-hidden="true"></i>
                                            <span>Đối tác chính thức</span>
                                        </span>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Đã xác thực
                                        </span>
                                    </div>

                                    <a class="mt-5 inline-flex h-10 items-center justify-center rounded-lg bg-blue-500 px-5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors"
                                        href="{{ esc_url($route_url) }}">
                                        Xem thông tin
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
                            aria-label="Phân trang bến xe">
                            @foreach ($pagination_links as $link)
                                {!! $link !!}
                            @endforeach
                        </nav>
                    @endif
                @else
                    @php wp_reset_postdata(); @endphp
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center">
                        <h3 class="text-lg font-semibold text-slate-950">Chưa có bến xe</h3>
                        <p class="mt-2 text-sm text-slate-500">Dailyve đang cập nhật thêm các bến xe khách mới.</p>
                    </div>
                @endif
            </section>
        </main>
    </div>
@endsection

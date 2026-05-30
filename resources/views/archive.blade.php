@extends('layouts.app')

@section('content')
    @php
        $current_cat = get_queried_object();
        $is_category = $current_cat instanceof \WP_Term && $current_cat->taxonomy === 'category';
        $term_id = $is_category ? $current_cat->term_id : 0;

        // Get all categories for the horizontal filter menu
        $list_categories = get_terms([
            'taxonomy' => 'category',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);
        $total_categories = count($list_categories);

        // Get child categories of current category
        $child_cats = [];
        if ($is_category) {
            $child_cats = get_terms([
                'taxonomy' => 'category',
                'child_of' => $term_id,
                'hide_empty' => true,
            ]);
        }

        // Query 5 most recent posts
        $popular_posts = null;
        if ($is_category) {
            $popular_posts = new \WP_Query([
                'posts_per_page' => 5,
                'cat' => $term_id,
                'orderby' => 'date',
                'order' => 'DESC',
            ]);
        }
        $popular_count = 0;

        $breadcrumbs = [['title' => 'Trang chủ', 'url' => home_url('/')]];
        if ($is_category) {
            $parent_id = $current_cat->category_parent;
            if ($parent_id) {
                $parent = get_category($parent_id);
                $breadcrumbs[] = [
                    'title' => html_entity_decode($parent->name, ENT_QUOTES, 'UTF-8'),
                    'url' => get_category_link($parent_id),
                ];
            }
            $breadcrumbs[] = [
                'title' => html_entity_decode(single_cat_title('', false), ENT_QUOTES, 'UTF-8'),
                'url' => '',
            ];
        } else {
            $breadcrumbs[] = [
                'title' => html_entity_decode(strip_tags(get_the_archive_title()), ENT_QUOTES, 'UTF-8'),
                'url' => '',
            ];
        }
    @endphp

    <style>
        .breadcrumb-archive>div {
            padding: 0 !important;
        }
    </style>
    <!-- Categories Filter Carousel Header -->
    <div
        class="dailyve-archive-filter border-b border-slate-100 shadow-sm py-4 sticky z-40 backdrop-blur-md bg-white/95 dailyve-sticky-filter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-2 overflow-x-auto scrollbar-none py-1">
                <!-- Home Button -->
                <a href="{{ home_url('/') }}"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-primary hover:bg-slate-50 transition duration-200 border border-slate-100 shadow-sm">
                    <i class="fas fa-home text-sm"></i>
                </a>

                <!-- Category Items -->
                @if (!empty($list_categories) && !is_wp_error($list_categories))
                    @foreach ($list_categories as $index => $cat)
                        @php
                            $is_active = $is_category && $term_id === $cat->term_id;
                        @endphp
                        <a href="{{ esc_url(get_category_link($cat->term_id)) }}"
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition duration-200 border shadow-sm
                      {{ $is_active
                          ? 'bg-primary text-white border-primary shadow-primary/20'
                          : 'bg-white text-slate-600 border-slate-100 hover:border-slate-300 hover:bg-slate-50' }}">
                            <span>{{ $cat->name }}</span>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="dailyve-archive max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Render Dynamic Breadcrumbs --}}
        <x-breadcrumb :items="$breadcrumbs" preset="default" class="breadcrumb-archive" />

        <!-- Archive Title Header -->
        <div class="mb-8 mt-6 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                {!! $is_category ? single_cat_title('', false) : get_the_archive_title() !!}
            </h1>
            @if ($is_category && category_description())
                <div class="mt-2 text-base text-slate-500 max-w-3xl">
                    {!! category_description() !!}
                </div>
            @endif
        </div>

        <!-- Featured popular posts (2x4 Grid) -->
        @if ($popular_posts && $popular_posts->have_posts())
            <div class="mb-12">
                <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                    <span class="w-1.5 h-6 bg-primary rounded-full mr-3"></span>
                    Bài viết nổi bật
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 md:grid-rows-2 gap-6 h-auto md:h-[480px]">
                    @while ($popular_posts->have_posts())
                        @php
                            $popular_posts->the_post();
                            $popular_count++;
                            $is_large = $popular_count === 1;
                            $grid_class = $is_large
                                ? 'md:col-span-2 md:row-span-2 min-h-[300px] md:h-full'
                                : 'md:col-span-1 md:row-span-1 min-h-[180px] md:h-full';
                        @endphp

                        <a href="{{ get_permalink() }}"
                            class="dailyve-featured-post group relative flex flex-col justify-end overflow-hidden rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 {{ $grid_class }}">
                            <!-- Thumbnail -->
                            <div class="absolute inset-0 z-0">
                                @if (has_post_thumbnail())
                                    {!! get_the_post_thumbnail(null, 'large', [
                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out',
                                    ]) !!}
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                        <i class="far fa-image text-4xl text-slate-400"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Gradient Overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10 transition-opacity duration-300 group-hover:opacity-95">
                            </div>

                            <!-- Content -->
                            <div class="relative z-20 p-6">
                                <!-- Date -->
                                <span class="inline-flex items-center text-xs font-semibold text-slate-300 mb-2">
                                    <i class="far fa-calendar-alt mr-1.5"></i>
                                    {{ get_the_date('d M, Y') }}
                                </span>
                                <!-- Title -->
                                <h3
                                    class="font-bold text-white leading-snug group-hover:text-primary-light transition-colors duration-200
                           {{ $is_large ? 'text-xl md:text-2xl line-clamp-2' : 'text-base line-clamp-2' }}">
                                    {!! get_the_title() !!}
                                </h3>

                                @if ($is_large)
                                    <p class="text-sm text-slate-300 mt-2 line-clamp-2 font-medium opacity-90">
                                        {!! wp_strip_all_tags(get_the_excerpt()) !!}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endwhile
                    @php
                        wp_reset_postdata();
                    @endphp
                </div>
            </div>
        @endif

        <!-- Main Categories & Child Categories loop -->
        @if (!empty($child_cats) && !is_wp_error($child_cats))
            @foreach ($child_cats as $childCat)
                @php
                    $child_posts = new \WP_Query([
                        'posts_per_page' => 8,
                        'cat' => $childCat->term_id,
                        'order' => 'DESC',
                    ]);
                @endphp

                @if ($child_posts->have_posts())
                    <div class="mb-12">
                        <!-- Header bar for category -->
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-slate-100">
                            <h2 class="text-xl font-extrabold text-slate-900 flex items-center">
                                <span class="w-1.5 h-6 bg-emerald-500 rounded-full mr-3"></span>
                                {{ $childCat->name }}
                            </h2>
                            <a href="{{ esc_url(get_category_link($childCat->term_id)) }}"
                                class="text-sm font-semibold text-primary hover:text-primary-dark inline-flex items-center transition duration-150">
                                Xem tất cả
                                <i class="fas fa-chevron-right ml-1.5 text-xs"></i>
                            </a>
                        </div>

                        <!-- Post Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                            @while ($child_posts->have_posts())
                                @php
                                    $child_posts->the_post();
                                @endphp
                                @include('partials.content-blog-card')
                            @endwhile
                            @php
                                wp_reset_postdata();
                            @endphp
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <!-- If no child categories, display a standard grid of all posts with pagination -->
            <div class="mb-12">
                <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                    <span class="w-1.5 h-6 bg-primary rounded-full mr-3"></span>
                    Danh sách bài viết
                </h2>

                @if (have_posts())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        @while (have_posts())
                            @php
                                the_post();
                            @endphp
                            @include('partials.content-blog-card')
                        @endwhile
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12 flex justify-center">
                        @php
                            $pag = paginate_links([
                                'type' => 'array',
                                'prev_next' => true,
                                'prev_text' => '<i class="fas fa-chevron-left"></i>',
                                'next_text' => '<i class="fas fa-chevron-right"></i>',
                            ]);
                        @endphp
                        @if ($pag)
                            <nav class="flex items-center space-x-1" aria-label="Pagination">
                                @foreach ($pag as $page_link)
                                    @php
                                        $is_curr = str_contains($page_link, 'current');
                                        $url = '#';
                                        if (preg_match('/href="([^"]*)"/', $page_link, $matches)) {
                                            $url = $matches[1];
                                        }
                                    @endphp

                                    @if ($is_curr)
                                        <span
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20">
                                            {!! strip_tags($page_link) !!}
                                        </span>
                                    @elseif (str_contains($page_link, 'dots'))
                                        <span
                                            class="inline-flex items-center justify-center w-10 h-10 text-slate-400">...</span>
                                    @else
                                        <a href="{{ esc_url($url) }}"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white text-slate-600 hover:text-primary hover:bg-slate-50 border border-slate-100 text-sm font-bold shadow-sm transition duration-200">
                                            {!! strip_tags($page_link, '<i>') !!}
                                        </a>
                                    @endif
                                @endforeach
                            </nav>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <i class="far fa-frown text-4xl text-slate-400 mb-3"></i>
                        <p class="text-slate-600 font-semibold">Xin lỗi, chưa có bài viết nào trong danh mục này.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection

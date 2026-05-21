@extends('layouts.app')

@section('content')
  @php
    $post_id = get_the_ID();
    $current_title = html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8');
    $parent_id = wp_get_post_parent_id($post_id);
    $parent_title = html_entity_decode(get_the_title($parent_id), ENT_QUOTES, 'UTF-8');
    $parent_url = get_permalink($parent_id);

    // Custom breadcrumbs
    $breadcrumbs = [
        ['title' => 'Trang chủ', 'url' => home_url('/')],
        ['title' => $parent_title, 'url' => $parent_url],
        ['title' => $current_title, 'url' => ''],
    ];

    // Detect post parent to show custom flight or train helper visuals
    $is_flight = (strpos(strtolower($parent_title), 'máy bay') !== false || (int) $parent_id === 16844);
    $transport_label = $is_flight ? 'Vé máy bay' : 'Vé tàu hỏa';
    $icon_svg = $is_flight 
      ? '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>'
      : '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9a1 1 0 01-1-1v-1h5v1zm4-7h2m-2 4h2m-4-10a3 3 0 013 3v12a3 3 0 01-3 3H9a3 3 0 01-3-3V5a3 3 0 013-3h6z"></path></svg>';
  @endphp

    <style>
    .prose *, .e-content *, .dailyve-single * {
      max-width: 100% !important;
      box-sizing: border-box !important;
    }
    .prose img, .e-content img, .dailyve-single img, .prose iframe, .e-content iframe {
      max-width: 100% !important;
      height: auto !important;
      width: auto !important;
      display: block !important;
    }
    .prose figure, .e-content figure, .dailyve-single figure, .prose .wp-block-image, .e-content .wp-block-image, .dailyve-single .wp-block-image {
      max-width: 100% !important;
      width: 100% !important;
      margin-left: 0 !important;
      margin-right: 0 !important;
      display: block !important;
    }
    /* Hide scrollbars for slick slider aesthetics */
    .scrollbar-none::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-none {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>

  <div class="bg-slate-50 min-h-screen pb-20 font-sans antialiased text-slate-800">
    <x-breadcrumb :items="$breadcrumbs" preset="default" />

    <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 pt-4 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-8 items-start">
        
        {{-- Left Column: Main Content --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm shadow-slate-100/50 max-w-full overflow-hidden">
          
          {{-- Header Section --}}
          <header class="mb-8 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-3 mb-4">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                {!! $icon_svg !!}
                {{ $transport_label }}
              </span>
              <span class="text-xs font-semibold text-slate-400">Cập nhật hôm nay</span>
            </div>
            
            <h1 class="font-display font-semibold text-2xl md:text-3xl text-slate-900 leading-tight tracking-tight mb-4">
              {!! $current_title !!}
            </h1>
            
            <p class="text-sm md:text-base text-slate-500 font-medium leading-relaxed">
              Thông tin chi tiết về lịch trình di chuyển, các hạng vé xe, bảng giá vé ưu đãi tốt nhất và hướng dẫn đặt vé trực tuyến nhanh chóng cùng Dailyve.
            </p>
          </header>

          {{-- Premium visual mockup card (Cal.com & Design.md inspired product chrome) --}}
          <section class="mb-10 bg-slate-50 rounded-2xl p-6 border border-slate-200/60 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-xl"></div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
              <div>
                <h3 class="font-display font-semibold text-base text-slate-800 tracking-tight">
                  Tra cứu & Đặt vé trực tuyến
                </h3>
                <p class="text-xs text-slate-500 mt-1">Lịch trình luôn được cập nhật chính xác nhất từ hệ thống</p>
              </div>
              <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full flex items-center gap-1.5 self-start sm:self-auto">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span>
                Hệ thống hoạt động ổn định
              </span>
            </div>

            {{-- Mockup Schedule/Ticket layout --}}
            <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm space-y-4">
              <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100">
                    {!! $icon_svg !!}
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-slate-800">
                      Tuyến hành trình phổ biến
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Khởi hành hàng ngày</p>
                  </div>
                </div>
                <div class="text-right">
                  <span class="text-xs font-semibold text-slate-400">Giá tham khảo từ</span>
                  <p class="text-base font-display font-semibold text-blue-600">450.000đ <span class="text-[10px] text-slate-400 font-sans">/ vé</span></p>
                </div>
              </div>

              {{-- Timeline mockup --}}
              <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-4 text-center sm:text-left">
                <div>
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Điểm khởi hành</span>
                  <p class="text-sm font-semibold text-slate-800">Điểm Ga / Sân bay đi</p>
                  <span class="text-xs text-slate-500">Thành phố xuất phát</span>
                </div>
                
                {{-- Travel Line --}}
                <div class="flex flex-col items-center justify-center py-2 sm:py-0">
                  <span class="text-[10px] text-slate-400 font-semibold mb-1">Thời gian di chuyển</span>
                  <div class="w-full max-w-[120px] flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full border-2 border-blue-500 bg-white"></span>
                    <span class="flex-grow border-t border-dashed border-slate-300"></span>
                    <span class="w-2 h-2 rounded-full border-2 border-blue-500 bg-white"></span>
                  </div>
                  <span class="text-xs font-semibold text-slate-500 mt-1">Linh hoạt</span>
                </div>

                <div class="sm:text-right">
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Điểm kết thúc</span>
                  <p class="text-sm font-semibold text-slate-800">Điểm Ga / Sân bay đến</p>
                  <span class="text-xs text-slate-500">Thành phố đích</span>
                </div>
              </div>

              {{-- CTA Book --}}
              <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-xs text-slate-500 font-medium">Bảo mật thông tin thanh toán 100%</span>
                <a href="{{ home_url('/') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all duration-300 shadow-sm text-center">
                  Đặt vé ngay bây giờ
                </a>
              </div>
            </div>
          </section>

          {{-- Page Body Content --}}
          <article class="prose max-w-none text-slate-600 leading-relaxed">
            @while(have_posts())
              @php
                the_post();
                the_content();
              @endphp
            @endwhile
          </article>
          
        </div>
        
        {{-- Right Column: Sidebar --}}
        <div>
          <x-sidebar />
        </div>

      </div>

      {{-- Related Posts Slider Section --}}
      @php
        $related_posts = new \WP_Query([
            'post_type'           => 'post',
            'posts_per_page'      => 6,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1
        ]);
      @endphp
      @if ($related_posts->have_posts())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pb-6">
          <h3 class="font-display font-semibold text-xl text-slate-900 mb-8 flex items-center">
            <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-3"></span>
            Bài viết liên quan
          </h3>

          <div class="relative group">
            <div id="transit-related-slider" class="flex overflow-x-auto gap-6 scrollbar-none pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 scroll-smooth">
              @while ($related_posts->have_posts())
                @php
                  $related_posts->the_post();
                  $post_title = get_the_title();
                  $post_url = get_permalink();
                  $has_thumb = has_post_thumbnail();
                  
                  // Calculate reading time
                  $content = get_post_field('post_content', get_the_ID());
                  $word_count = str_word_count(strip_tags($content));
                  $reading_time = max(1, ceil($word_count / 200));
                @endphp
                
                <a href="{{ $post_url }}" class="group flex-shrink-0 w-80 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-100/50 transition-all duration-300 overflow-hidden flex flex-col">
                  <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                    @if ($has_thumb)
                      {!! get_the_post_thumbnail(null, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']) !!}
                    @else
                      <div class="w-full h-full flex items-center justify-center text-blue-500 bg-blue-50">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                      </div>
                    @endif
                  </div>
                  <div class="p-5 flex-grow flex flex-col justify-between bg-white">
                    <h4 class="text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                      {!! $post_title !!}
                    </h4>
                    <div class="flex items-center gap-3 text-[10px] font-semibold text-slate-400 mt-4 pt-3 border-t border-slate-50">
                      <span>{{ get_the_date('d/m/Y') }}</span>
                      <span>•</span>
                      <span>{{ $reading_time }} phút đọc</span>
                    </div>
                  </div>
                </a>
              @endwhile
              @php
                wp_reset_postdata();
              @endphp
            </div>

            <!-- Slider Left Arrow Button -->
            <button onclick="document.getElementById('transit-related-slider').scrollBy({ left: -320, behavior: 'smooth' })" 
                    class="absolute -left-5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:scale-105 transition-all duration-200 cursor-pointer hidden md:flex z-10"
                    aria-label="Slide Left">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            
            <!-- Slider Right Arrow Button -->
            <button onclick="document.getElementById('transit-related-slider').scrollBy({ left: 320, behavior: 'smooth' })" 
                    class="absolute -right-5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:scale-105 transition-all duration-200 cursor-pointer hidden md:flex z-10"
                    aria-label="Slide Right">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection

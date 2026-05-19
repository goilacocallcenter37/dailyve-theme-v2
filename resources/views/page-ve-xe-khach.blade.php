@extends('layouts.app')

@section('content')
<div class="dailyve-bus-page bg-slate-50 min-h-screen font-sans antialiased text-slate-800 pb-20">
  
  {{-- Breadcrumbs --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
    <nav class="flex text-xs md:text-sm text-slate-500 font-medium items-center gap-2">
      <a href="/" class="hover:text-blue-600 transition-colors flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Trang chủ
      </a>
      <span class="text-slate-400">/</span>
      <span class="text-slate-800 font-semibold">Vé xe khách</span>
    </nav>
  </div>

  {{-- Search Widget Block --}}
  <div class="dailyve-bus-search-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
    <div class="dailyve-bus-search-card bg-white rounded-3xl shadow-xl shadow-slate-100 p-6 md:p-8 border border-slate-100 relative overflow-visible">
      <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-sky-500 to-indigo-500"></div>
      <h2 class="text-xl md:text-2xl font-bold text-slate-900 mb-6 text-center tracking-tight flex items-center justify-center gap-2">
        <svg class="w-6 h-6 text-blue-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        TÌM VÉ XE KHÁCH TRỰC TUYẾN
      </h2>
      <div id="react-search-form" class="min-h-[120px]">
        {{-- React search widget mounts here --}}
      </div>
    </div>
  </div>

  {{-- Hero Section (2-Column Grid) --}}
  <div class="dailyve-bus-intro-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
      
      {{-- Left Side: Introduce & Features (7/12) --}}
      <div class="lg:col-span-7 flex flex-col justify-between">
        <div>
          <h1 class="text-2xl md:text-3.5xl font-black text-slate-950 mb-4 tracking-tight leading-tight uppercase bg-gradient-to-r from-slate-950 via-blue-900 to-indigo-950 bg-clip-text text-transparent">
            Đặt vé xe khách trực tuyến tại Dailyve
          </h1>
          <p class="text-slate-600 leading-relaxed mb-8 text-sm md:text-base">
            Đặt vé xe khách trực tuyến với hàng trăm tuyến đường trên Việt Nam tại Dailyve.com.vn. Chúng tôi là đối tác tin cậy của hơn 100 nhà xe uy tín cung cấp đa dạng dòng xe đáp ứng nhu cầu đi lại của hàng khách. Bạn có thể lựa chọn tuyến đường, ghế ngồi, khung giờ phù hợp và so sánh giá giữa các nhà xe một cách dễ dàng trên hệ thống của chúng tôi.
          </p>
        </div>

        {{-- Benefit Cards (2x2 Grid) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          
          {{-- Benefit 1 --}}
          <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex items-start gap-4 group">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-slate-900 mb-1 text-sm md:text-base">Tìm kiếm nhanh chóng</h4>
              <p class="text-xs text-slate-500 leading-relaxed">Tra cứu hành trình, dòng xe, điểm đón trả chỉ trong vài giây.</p>
            </div>
          </div>

          {{-- Benefit 2 --}}
          <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex items-start gap-4 group">
            <div class="p-3 bg-sky-50 text-sky-600 rounded-xl group-hover:bg-sky-600 group-hover:text-white transition-colors duration-300">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-slate-900 mb-1 text-sm md:text-base">Tối ưu hóa chi phí</h4>
              <p class="text-xs text-slate-500 leading-relaxed">Săn nhiều ưu đãi đặc quyền và mức giá cạnh tranh nhất.</p>
            </div>
          </div>

          {{-- Benefit 3 --}}
          <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex items-start gap-4 group">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-slate-900 mb-1 text-sm md:text-base">Nhà xe uy tín cao</h4>
              <p class="text-xs text-slate-500 leading-relaxed">Hợp tác cùng 100+ đối tác vận tải chất lượng vượt trội.</p>
            </div>
          </div>

          {{-- Benefit 4 --}}
          <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex items-start gap-4 group">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-slate-900 mb-1 text-sm md:text-base">Thanh toán bảo mật</h4>
              <p class="text-xs text-slate-500 leading-relaxed">Đa dạng hình thức giao dịch qua cổng bảo mật 100%.</p>
            </div>
          </div>

        </div>
      </div>

      {{-- Right Side: Callout Support Banner (5/12) --}}
      <div class="lg:col-span-5">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-800 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-blue-100 flex flex-col justify-between h-full min-h-[380px] group border border-blue-500/20">
          <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/10 rounded-full blur-xl group-hover:scale-110 transition-transform duration-500"></div>
          <div class="absolute -left-12 -bottom-12 w-36 h-36 bg-blue-500/20 rounded-full blur-lg"></div>
          
          <div>
            <span class="bg-white/20 text-white text-[10px] md:text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full inline-block mb-6 backdrop-blur-sm">Hỗ trợ hotline 24/7</span>
            <h3 class="text-2xl md:text-3xl text-white font-black mb-3 leading-snug tracking-tight">Tổng đài đặt vé & CSKH trực tuyến</h3>
            <p class="text-blue-100 text-xs md:text-sm leading-relaxed mb-6">
              Kết nối trực tiếp tới tổng đài hỗ trợ viên để đặt giữ chỗ nhanh nhất, tư vấn mọi lịch trình và xử lý nhanh chóng các yêu cầu hoàn hủy đổi vé.
            </p>
          </div>
          
          <div class="relative z-10">
            <a href="tel:19000155" class="bg-white text-blue-900 hover:bg-slate-50 transition-all font-black text-xl md:text-2xl py-4 px-6 rounded-2xl flex items-center justify-center gap-3 shadow-lg hover:shadow-xl active:scale-[0.98] duration-200 group/btn">
              <svg class="w-6 h-6 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
              <span>1900 0155</span>
            </a>
            <p class="text-center text-[10px] md:text-xs text-blue-200 mt-3 font-medium">Cước phí theo quy định nhà mạng</p>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- Offers Section ("Ưu đãi Dailyve.com.vn") --}}
  @php
    $offers_query = new \WP_Query([
        'cat' => 32,
        'posts_per_page' => 4,
        'post_status' => 'publish'
    ]);
  @endphp
  @if($offers_query->have_posts())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight uppercase flex items-center gap-2">
          <span class="w-2.5 h-6 bg-blue-600 rounded-full"></span>
          Ưu đãi độc quyền từ Dailyve.com.vn
        </h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @while($offers_query->have_posts())
          @php $offers_query->the_post(); @endphp
          <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md shadow-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
            <div class="relative overflow-hidden aspect-video">
              @if(has_post_thumbnail())
                {!! get_the_post_thumbnail(get_the_ID(), 'medium', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']) !!}
              @else
                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">Không có ảnh</div>
              @endif
              <span class="absolute top-3 left-3 bg-red-500 text-white font-bold text-[10px] uppercase tracking-wider py-1 px-2.5 rounded-full shadow-sm">Ưu Đãi</span>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between">
              <h3 class="font-bold text-slate-900 text-sm md:text-base leading-snug mb-3 line-clamp-2 hover:text-blue-600 transition-colors">
                <a href="{{ get_permalink() }}">{{ get_the_title() }}</a>
              </h3>
              <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                <span class="text-slate-400 text-xs">{{ get_the_date('d/m/Y') }}</span>
                <a href="{{ get_permalink() }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                  Xem chi tiết
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        @endwhile
        @php wp_reset_postdata(); @endphp
      </div>
    </div>
  @endif

  {{-- Custom Styles for Sliders --}}
  <style>
    .scrollbar-none::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-none {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>

  {{-- Section 1: Tuyến đường phổ biến --}}
  @php
    $routes_query = new \WP_Query([
        'post_type' => 'page',
        'post_parent' => 15738,
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => 'outstanding',
                'value' => true,
                'compare' => '='
            ]
        ]
    ]);
  @endphp
  @if($routes_query->have_posts())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 relative">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight uppercase flex items-center gap-2">
          <span class="w-2.5 h-6 bg-blue-600 rounded-full animate-pulse"></span>
          Tuyến đường phổ biến
        </h2>
      </div>
      
      <div class="relative group">
        {{-- Left Arrow --}}
        <button onclick="slideLeft('routes-slider')" id="routes-slider-prev" class="absolute -left-4 md:-left-6 top-1/2 -translate-y-1/2 bg-white text-slate-800 p-3 rounded-full shadow-xl border border-slate-100 hover:bg-slate-50 transition-all hover:scale-110 active:scale-95 z-20 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 duration-300 pointer-events-none">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>

        {{-- Container --}}
        <div id="routes-slider" onscroll="updateSliderButtons('routes-slider')" class="flex overflow-x-auto scroll-smooth gap-6 pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x scrollbar-none">
          @while($routes_query->have_posts())
            @php
              $routes_query->the_post();
              $post_id = get_the_ID();
              $price = get_field('routes_price', $post_id);
              $distance = get_field('routes_distance', $post_id);
              $time = get_field('routes_time', $post_id);
            @endphp
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md shadow-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shrink-0 w-[290px] snap-start group/card">
              <div class="relative overflow-hidden aspect-[4/3]">
                @if(has_post_thumbnail())
                  {!! get_the_post_thumbnail($post_id, 'medium', ['class' => 'w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500']) !!}
                @else
                  <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">Không có ảnh</div>
                @endif
                @if($price)
                  <span class="absolute bottom-3 right-3 bg-red-500 text-white font-black text-xs py-1.5 px-3.5 rounded-xl shadow-md z-10">
                    {{ number_format($price, 0, ',', '.') }}đ
                  </span>
                @endif
              </div>
              <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                  <h3 class="font-black text-slate-900 text-sm md:text-base leading-snug mb-2 line-clamp-1 hover:text-blue-600 transition-colors">
                    <a href="{{ get_permalink() }}">{{ get_the_title() }}</a>
                  </h3>
                  @if($distance && $time)
                    <p class="text-slate-400 text-[11px] font-semibold flex items-center gap-1 mb-4">
                      <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      {{ $distance }} - {{ $time }}
                    </p>
                  @endif
                </div>
                <a href="{{ get_permalink() }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-3 px-4 rounded-xl text-center shadow-md active:scale-[0.98] transition-all block">
                  Đặt vé ngay
                </a>
              </div>
            </div>
          @endwhile
        </div>

        {{-- Right Arrow --}}
        <button onclick="slideRight('routes-slider')" id="routes-slider-next" class="absolute -right-4 md:-right-6 top-1/2 -translate-y-1/2 bg-white text-slate-800 p-3 rounded-full shadow-xl border border-slate-100 hover:bg-slate-50 transition-all hover:scale-110 active:scale-95 z-20 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 duration-300">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
    </div>
    @php wp_reset_postdata(); @endphp
  @endif

  {{-- Section 2: Nhà xe phổ biến --}}
  @php
    $operators_query = new \WP_Query([
        'post_type' => 'page',
        'post_parent' => 15764,
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => 'outstanding',
                'value' => true,
                'compare' => '='
            ]
        ]
    ]);
  @endphp
  @if($operators_query->have_posts())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 relative">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight uppercase flex items-center gap-2">
          <span class="w-2.5 h-6 bg-indigo-600 rounded-full animate-pulse"></span>
          Nhà xe phổ biến
        </h2>
      </div>
      
      <div class="relative group">
        {{-- Left Arrow --}}
        <button onclick="slideLeft('operators-slider')" id="operators-slider-prev" class="absolute -left-4 md:-left-6 top-1/2 -translate-y-1/2 bg-white text-slate-800 p-3 rounded-full shadow-xl border border-slate-100 hover:bg-slate-50 transition-all hover:scale-110 active:scale-95 z-20 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 duration-300 pointer-events-none">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>

        {{-- Container --}}
        <div id="operators-slider" onscroll="updateSliderButtons('operators-slider')" class="flex overflow-x-auto scroll-smooth gap-6 pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x scrollbar-none">
          @while($operators_query->have_posts())
            @php $operators_query->the_post(); @endphp
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md shadow-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shrink-0 w-[290px] snap-start group/card">
              <div class="relative overflow-hidden aspect-[4/3]">
                @if(has_post_thumbnail())
                  {!! get_the_post_thumbnail(get_the_ID(), 'medium', ['class' => 'w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500']) !!}
                @else
                  <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">Không có ảnh</div>
                @endif
              </div>
              <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                  <h3 class="font-black text-slate-900 text-sm md:text-base leading-snug mb-3 line-clamp-1 hover:text-blue-600 transition-colors">
                    <a href="{{ get_permalink() }}">{{ get_the_title() }}</a>
                  </h3>
                  <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-4">
                    {{ get_the_excerpt() ?: wp_trim_words(get_post_field('post_content', get_the_ID()), 15) }}
                  </p>
                </div>
                <a href="{{ get_permalink() }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-3 px-4 rounded-xl text-center shadow-md active:scale-[0.98] transition-all block">
                  Chi tiết nhà xe
                </a>
              </div>
            </div>
          @endwhile
        </div>

        {{-- Right Arrow --}}
        <button onclick="slideRight('operators-slider')" id="operators-slider-next" class="absolute -right-4 md:-right-6 top-1/2 -translate-y-1/2 bg-white text-slate-800 p-3 rounded-full shadow-xl border border-slate-100 hover:bg-slate-50 transition-all hover:scale-110 active:scale-95 z-20 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 duration-300">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
    </div>
    @php wp_reset_postdata(); @endphp
  @endif

  {{-- Section 3: Bến xe phổ biến --}}
  @php
    $stations_query = new \WP_Query([
        'post_type' => 'page',
        'post_parent' => 15896,
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => 'outstanding',
                'value' => true,
                'compare' => '='
            ]
        ]
    ]);
  @endphp
  @if($stations_query->have_posts())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 relative">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight uppercase flex items-center gap-2">
          <span class="w-2.5 h-6 bg-emerald-600 rounded-full animate-pulse"></span>
          Bến xe phổ biến
        </h2>
      </div>
      
      <div class="relative group">
        {{-- Left Arrow --}}
        <button onclick="slideLeft('stations-slider')" id="stations-slider-prev" class="absolute -left-4 md:-left-6 top-1/2 -translate-y-1/2 bg-white text-slate-800 p-3 rounded-full shadow-xl border border-slate-100 hover:bg-slate-50 transition-all hover:scale-110 active:scale-95 z-20 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 duration-300 pointer-events-none">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>

        {{-- Container --}}
        <div id="stations-slider" onscroll="updateSliderButtons('stations-slider')" class="flex overflow-x-auto scroll-smooth gap-6 pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x scrollbar-none">
          @while($stations_query->have_posts())
            @php
              $stations_query->the_post();
              $post_id = get_the_ID();
              $address = get_field('company_address', $post_id);
            @endphp
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md shadow-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shrink-0 w-[290px] snap-start group/card">
              <div class="relative overflow-hidden aspect-[4/3]">
                @if(has_post_thumbnail())
                  {!! get_the_post_thumbnail($post_id, 'medium', ['class' => 'w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500']) !!}
                @else
                  <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">Không có ảnh</div>
                @endif
              </div>
              <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                  <h3 class="font-black text-slate-900 text-sm md:text-base leading-snug mb-2 line-clamp-1 hover:text-blue-600 transition-colors">
                    <a href="{{ get_permalink() }}">{{ get_the_title() }}</a>
                  </h3>
                  @if($address)
                    <p class="text-xs text-slate-500 flex items-start gap-1.5 leading-relaxed line-clamp-2 mb-4">
                      <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                      {{ $address }}
                    </p>
                  @endif
                </div>
                <a href="{{ get_permalink() }}" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-3 px-4 rounded-xl text-center shadow-md active:scale-[0.98] transition-all block">
                  Chi tiết bến xe
                </a>
              </div>
            </div>
          @endwhile
        </div>

        {{-- Right Arrow --}}
        <button onclick="slideRight('stations-slider')" id="stations-slider-next" class="absolute -right-4 md:-right-6 top-1/2 -translate-y-1/2 bg-white text-slate-800 p-3 rounded-full shadow-xl border border-slate-100 hover:bg-slate-50 transition-all hover:scale-110 active:scale-95 z-20 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 duration-300">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
    </div>
    @php wp_reset_postdata(); @endphp
  @endif

  {{-- FAQ / Accordion Section --}}
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    <div class="text-center mb-10">
      <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 uppercase tracking-tight mb-3">
        Câu hỏi thường gặp (FAQs)
      </h2>
      <p class="text-xs md:text-sm text-slate-500">
        Tìm kiếm nhanh câu trả lời cho các thắc mắc phổ biến về quy trình đặt vé xe khách tại Dailyve.
      </p>
    </div>

    <div class="space-y-4">
      
      {{-- Accordion 1 --}}
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm shadow-slate-100/80 transition-all duration-300">
        <button onclick="toggleAccordion(this)" class="w-full py-5 px-6 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors text-sm md:text-base group">
          <span>Làm thế nào để đặt vé xe trực tuyến trên Dailyve?</span>
          <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="p-6 pt-0 border-t border-slate-50 text-slate-600 text-xs md:text-sm leading-relaxed">
            Bạn chỉ cần điền Điểm xuất phát, Điểm đến và Ngày đi tại form tìm kiếm ở đầu trang, sau đó nhấn "Tìm vé". Hệ thống sẽ lọc ra các chuyến xe tốt nhất để bạn lựa chọn, điền thông tin và tiến hành thanh toán cực kì nhanh chóng.
          </div>
        </div>
      </div>

      {{-- Accordion 2 --}}
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm shadow-slate-100/80 transition-all duration-300">
        <button onclick="toggleAccordion(this)" class="w-full py-5 px-6 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors text-sm md:text-base group">
          <span>Tôi có thể chọn vị trí ghế ngồi trước không?</span>
          <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="p-6 pt-0 border-t border-slate-50 text-slate-600 text-xs md:text-sm leading-relaxed">
            Hoàn toàn được. Với hầu hết các nhà xe đối tác lớn trên hệ thống Dailyve, bạn sẽ được xem sơ đồ xe trực quan và tự tay lựa chọn giường nằm hoặc ghế ngồi trống ưng ý trước khi thanh toán.
          </div>
        </div>
      </div>

      {{-- Accordion 3 --}}
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm shadow-slate-100/80 transition-all duration-300">
        <button onclick="toggleAccordion(this)" class="w-full py-5 px-6 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors text-sm md:text-base group">
          <span>Chính sách hoàn hủy vé trên hệ thống thế nào?</span>
          <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="p-6 pt-0 border-t border-slate-50 text-slate-600 text-xs md:text-sm leading-relaxed">
            Quy định hoàn hủy vé phụ thuộc vào chính sách riêng của từng nhà xe đối tác. Bạn có thể xem chi tiết điều khoản hủy vé của từng chuyến xe trong phần thông tin chi tiết hoặc liên hệ trực tiếp Hotline 1900 0155 để được hỗ trợ thủ tục nhanh nhất.
          </div>
        </div>
      </div>

      {{-- Accordion 4 --}}
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm shadow-slate-100/80 transition-all duration-300">
        <button onclick="toggleAccordion(this)" class="w-full py-5 px-6 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors text-sm md:text-base group">
          <span>Thanh toán vé xe khách bằng những phương thức nào?</span>
          <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="p-6 pt-0 border-t border-slate-50 text-slate-600 text-xs md:text-sm leading-relaxed">
            Dailyve hỗ trợ thanh toán an toàn, bảo mật 100% qua QR Code ứng dụng ngân hàng, cổng thanh toán VNPAY, thẻ Visa/Mastercard hoặc chuyển khoản ngân hàng trực tiếp.
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

{{-- Inline vanilla JS for dynamic tab transitions and accordions --}}
<script>
  function slideLeft(id) {
    const container = document.getElementById(id);
    if (container) {
      container.scrollBy({ left: -container.clientWidth * 0.8, behavior: 'smooth' });
    }
  }

  function slideRight(id) {
    const container = document.getElementById(id);
    if (container) {
      container.scrollBy({ left: container.clientWidth * 0.8, behavior: 'smooth' });
    }
  }

  function updateSliderButtons(id) {
    const container = document.getElementById(id);
    const prevBtn = document.getElementById(id + '-prev');
    const nextBtn = document.getElementById(id + '-next');
    if (container && prevBtn && nextBtn) {
      const scrollLeft = container.scrollLeft;
      const maxScroll = container.scrollWidth - container.clientWidth;
      
      if (scrollLeft <= 5) {
        prevBtn.classList.add('opacity-0', 'pointer-events-none');
        prevBtn.classList.remove('opacity-100');
      } else {
        prevBtn.classList.remove('opacity-0', 'pointer-events-none');
        prevBtn.classList.add('opacity-100');
      }
      
      if (scrollLeft >= maxScroll - 5) {
        nextBtn.classList.add('opacity-0', 'pointer-events-none');
        nextBtn.classList.remove('opacity-100');
      } else {
        nextBtn.classList.remove('opacity-0', 'pointer-events-none');
        nextBtn.classList.add('opacity-100');
      }
    }
  }

  function toggleAccordion(button) {
    const parent = button.parentNode;
    const content = button.nextElementSibling;
    const icon = button.querySelector('svg');
    const isExpanded = content.style.maxHeight && content.style.maxHeight !== '0px';

    // Collapse all other accordions first
    document.querySelectorAll('.accordion-content').forEach(acc => {
      acc.style.maxHeight = '0px';
      acc.parentNode.classList.remove('border-blue-500/30', 'shadow-blue-50/50');
      const otherIcon = acc.previousElementSibling.querySelector('svg');
      if(otherIcon) otherIcon.style.transform = 'rotate(0deg)';
    });

    if (isExpanded) {
      content.style.maxHeight = '0px';
      parent.classList.remove('border-blue-500/30', 'shadow-blue-50/50');
      if (icon) icon.style.transform = 'rotate(0deg)';
    } else {
      content.style.maxHeight = content.scrollHeight + 'px';
      parent.classList.add('border-blue-500/30', 'shadow-blue-50/50');
      if (icon) icon.style.transform = 'rotate(180deg)';
    }
  }

  // Initial slider buttons checks
  document.addEventListener('DOMContentLoaded', () => {
    ['routes-slider', 'operators-slider', 'stations-slider'].forEach(id => {
      setTimeout(() => updateSliderButtons(id), 500);
    });
  });
</script>
@endsection

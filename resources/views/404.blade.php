@extends('layouts.app')

@section('content')
<section class="min-h-[calc(100vh-200px)] flex items-center justify-center bg-canvas px-4 py-16 md:py-24">
  <div class="w-full max-w-3xl mx-auto text-center">

    {{-- Animated travel illustration --}}
    <div class="relative mb-8 flex justify-center items-end h-40 md:h-52">
      {{-- Road line --}}
      <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-72 md:w-96 h-px bg-hairline"></div>
      <div class="absolute bottom-[22px] left-1/2 -translate-x-1/2 w-72 md:w-96 border-b-2 border-dashed border-hairline"></div>

      {{-- Animated bus SVG --}}
      <div class="absolute bottom-7 animate-[busRide_6s_ease-in-out_infinite]">
        <svg width="80" height="56" viewBox="0 0 80 56" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-md">
          {{-- Bus body --}}
          <rect x="4" y="8" width="72" height="36" rx="8" fill="#2196F3"/>
          <rect x="4" y="8" width="72" height="12" rx="8" fill="#1565C0"/>
          {{-- Windshield --}}
          <rect x="60" y="14" width="12" height="14" rx="3" fill="#E0F2FE" opacity="0.9"/>
          {{-- Windows --}}
          <rect x="12" y="14" width="10" height="10" rx="2" fill="#E0F2FE" opacity="0.85"/>
          <rect x="26" y="14" width="10" height="10" rx="2" fill="#E0F2FE" opacity="0.85"/>
          <rect x="40" y="14" width="10" height="10" rx="2" fill="#E0F2FE" opacity="0.85"/>
          {{-- Stripe --}}
          <rect x="4" y="32" width="72" height="4" fill="#1565C0" opacity="0.3"/>
          {{-- Door --}}
          <rect x="54" y="26" width="8" height="16" rx="2" fill="#0F172A" opacity="0.15"/>
          {{-- Wheels --}}
          <circle cx="22" cy="46" r="6" fill="#334155"/>
          <circle cx="22" cy="46" r="3" fill="#94A3B8"/>
          <circle cx="58" cy="46" r="6" fill="#334155"/>
          <circle cx="58" cy="46" r="3" fill="#94A3B8"/>
          {{-- Headlight --}}
          <rect x="73" y="28" width="4" height="6" rx="2" fill="#F59E0B"/>
          {{-- Taillight --}}
          <rect x="3" y="30" width="3" height="4" rx="1.5" fill="#DC2626"/>
        </svg>
      </div>

      {{-- Small clouds --}}
      <div class="absolute top-2 left-[15%] animate-[cloudFloat_8s_ease-in-out_infinite]">
        <svg width="48" height="24" viewBox="0 0 48 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="24" cy="16" rx="20" ry="8" fill="#F1F5F9"/>
          <ellipse cx="16" cy="12" rx="12" ry="8" fill="#F1F5F9"/>
          <ellipse cx="32" cy="12" rx="10" ry="7" fill="#F1F5F9"/>
        </svg>
      </div>
      <div class="absolute top-6 right-[12%] animate-[cloudFloat_10s_ease-in-out_infinite_1s]">
        <svg width="40" height="20" viewBox="0 0 40 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="20" cy="13" rx="16" ry="7" fill="#F1F5F9"/>
          <ellipse cx="14" cy="10" rx="10" ry="6" fill="#F1F5F9"/>
          <ellipse cx="28" cy="10" rx="9" ry="6" fill="#F1F5F9"/>
        </svg>
      </div>
    </div>

    {{-- 404 Number --}}
    <h1 class="font-['Space_Grotesk'] text-[96px] md:text-[140px] font-semibold leading-none tracking-[-4px] bg-gradient-to-r from-primary to-primary-active bg-clip-text text-transparent select-none mb-2">
      404
    </h1>

    {{-- Headline --}}
    <h2 class="font-['Space_Grotesk'] text-2xl md:text-4xl font-semibold leading-tight tracking-[-1px] text-ink mb-4">
      Ôi! Trang này không tồn tại
    </h2>

    {{-- Description --}}
    <p class="text-base text-muted max-w-md mx-auto mb-8 leading-relaxed">
      Có vẻ như trang bạn đang tìm đã bị di chuyển hoặc không còn tồn tại. 
      Đừng lo, hãy quay lại trang chủ hoặc tìm chuyến xe ngay nhé!
    </p>

    {{-- CTA Buttons --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-12">
      <a href="{{ esc_url(home_url('/')) }}" 
         class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-active transition-colors duration-200 shadow-sm hover:shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/>
        </svg>
        Về trang chủ
      </a>
      <a href="{{ esc_url(home_url('/ve-xe-khach/')) }}" 
         class="inline-flex items-center gap-2 px-6 py-3 bg-canvas text-primary text-sm font-semibold rounded-lg border border-primary hover:bg-surface-soft transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
        Đặt vé ngay
      </a>
    </div>

    {{-- Popular Routes --}}
    <div class="border-t border-hairline-soft pt-10">
      <p class="text-sm font-medium text-muted-soft uppercase tracking-wider mb-6">Tuyến đường phổ biến</p>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
        @php
          $popularRoutes = [
            ['from' => 'Sài Gòn', 'to' => 'Đà Lạt', 'icon' => 'fa-mountain-sun', 'url' => home_url('/ve-xe-khach/')],
            ['from' => 'Sài Gòn', 'to' => 'Nha Trang', 'icon' => 'fa-umbrella-beach', 'url' => home_url('/ve-xe-khach/')],
            ['from' => 'Hà Nội', 'to' => 'Sapa', 'icon' => 'fa-cloud-sun', 'url' => home_url('/ve-xe-khach/')],
          ];
        @endphp

        @foreach ($popularRoutes as $route)
          <a href="{{ esc_url($route['url']) }}" 
             class="group flex items-center gap-3 p-4 bg-surface-soft rounded-xl border border-transparent hover:border-hairline hover:bg-canvas hover:shadow-sm transition-all duration-200">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-surface-card flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-200">
              <i class="fas {{ $route['icon'] }} text-sm" aria-hidden="true"></i>
            </div>
            <div class="text-left">
              <span class="block text-sm font-semibold text-ink leading-tight">{{ $route['from'] }} → {{ $route['to'] }}</span>
              <span class="block text-xs text-muted-soft mt-0.5">Xem chuyến xe</span>
            </div>
          </a>
        @endforeach
      </div>
    </div>

  </div>
</section>

{{-- 404 Page Animations --}}
<style>
  @keyframes busRide {
    0%, 100% { transform: translateX(-60px) translateY(0); }
    25% { transform: translateX(-20px) translateY(-2px); }
    50% { transform: translateX(40px) translateY(0); }
    75% { transform: translateX(80px) translateY(-1px); }
  }
  @keyframes cloudFloat {
    0%, 100% { transform: translateX(0) translateY(0); }
    50% { transform: translateX(12px) translateY(-4px); }
  }
</style>
@endsection

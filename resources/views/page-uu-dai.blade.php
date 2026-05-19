@extends('layouts.app')

@section('content')
  <div class="profile-dashboard-layout">
    {{-- Sidebar --}}
    @include('partials.profile-sidebar', ['active' => 'uu-dai'])

    {{-- Main Content --}}
    <div class="profile-dashboard-content">
      <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100/80">
        <div class="border-b border-slate-100 pb-5 mb-6">
          <h2 class="text-xl font-bold text-slate-800">Ưu đãi của tôi</h2>
          <p class="text-sm text-slate-400 mt-1">Các mã khuyến mãi và ưu đãi đặc biệt dành riêng cho bạn.</p>
        </div>
        <div class="flex flex-col items-center justify-center py-12 text-center">
          <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-4 text-2xl">
            <i class="fas fa-gift"></i>
          </div>
          <p class="text-slate-500 text-sm font-medium">Hiện chưa có ưu đãi nào. Hãy quay lại sau nhé!</p>
        </div>
      </div>
    </div>
  </div>
@endsection

@extends('layouts.app')

@section('content')
  <div class="profile-dashboard-layout">
    {{-- Sidebar --}}
    @include('partials.profile-sidebar', ['active' => 'quan-ly-the'])

    {{-- Main Content --}}
    <div class="profile-dashboard-content">
      <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100/80">
        <div class="border-b border-slate-100 pb-5 mb-6">
          <h2 class="text-xl font-bold text-slate-800">Quản lý thẻ</h2>
          <p class="text-sm text-slate-400 mt-1">Thêm và quản lý thẻ thanh toán của bạn.</p>
        </div>
        <div class="flex flex-col items-center justify-center py-12 text-center">
          <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4 text-2xl">
            <i class="far fa-credit-card"></i>
          </div>
          <p class="text-slate-500 text-sm font-medium">Chức năng quản lý thẻ đang được phát triển.</p>
        </div>
      </div>
    </div>
  </div>
@endsection

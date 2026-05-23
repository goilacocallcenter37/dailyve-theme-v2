@extends('layouts.app')

@section('content')
    <section class="dailyve-lookup-page mx-auto max-w-6xl px-4 pt-10">
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="font-display text-3xl font-semibold tracking-tight text-slate-900">Tra cứu vé</h1>
            <p class="mt-2 text-sm text-slate-500">Theo dõi trạng thái thanh toán, lịch trình và yêu cầu hủy/hoàn vé ngay tại
                đây.</p>
        </div>
    </section>

    <div class="profile-dashboard-layout pt-2">
        {{-- Sidebar --}}
        @include('partials.profile-sidebar', ['active' => 'don-hang-cua-toi'])

        {{-- Main Content --}}
        <div class="profile-dashboard-content">
            <div id="react-ticket-lookup"></div>
        </div>
    </div>
@endsection

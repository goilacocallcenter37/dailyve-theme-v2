@extends('layouts.app')

@section('content')
  <div class="profile-dashboard-layout">
    {{-- Sidebar --}}
    @include('partials.profile-sidebar', ['active' => 'don-hang-cua-toi'])

    {{-- Main Content --}}
    <div class="profile-dashboard-content">
      <div id="react-ticket-lookup"></div>
    </div>
  </div>
@endsection

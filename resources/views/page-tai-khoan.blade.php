@extends('layouts.app')

@section('content')
  <div class="profile-dashboard-layout">
    {{-- Sidebar --}}
    @include('partials.profile-sidebar', ['active' => 'tai-khoan'])

    {{-- Main Content --}}
    <div class="profile-dashboard-content">
      <div id="react-profile"></div>
    </div>
  </div>
@endsection

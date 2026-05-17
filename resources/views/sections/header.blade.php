@php
    $navItems = [
        ['label' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
        ['label' => 'Vé tàu hỏa', 'url' => home_url('/ve-tau-hoa/')],
        ['label' => 'Vé máy bay', 'url' => home_url('/ve-may-bay/')],
        ['label' => 'Khách sạn', 'url' => home_url('/khach-san/')],
        ['label' => 'Tin tức', 'url' => home_url('/tin-tuc/')],
        ['label' => 'Hỗ trợ', 'url' => home_url('/ho-tro/')],
    ];
@endphp

<header class="dailyve-site-header">
    <div class="dailyve-container dailyve-site-header__inner">
        <a class="dailyve-site-header__logo" href="{{ esc_url(home_url('/')) }}" aria-label="Dailyve">
            <img src="https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/DailyVe-12-300x104.png" alt="Dailyve">
        </a>

        <nav class="dailyve-site-header__nav" aria-label="Điều hướng chính">
            @foreach ($navItems as $item)
                <a href="{{ esc_url($item['url']) }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="dailyve-site-header__actions">
            <a class="dailyve-site-header__account" href="{{ esc_url(home_url('/tai-khoan/')) }}" aria-label="Tài khoản">
                <i class="far fa-user" aria-hidden="true"></i>
            </a>
            <a class="dailyve-site-header__login" href="{{ esc_url(home_url('/tai-khoan/')) }}">Đăng nhập / Đăng ký</a>
            <button class="dailyve-site-header__menu" type="button" aria-label="Mở menu">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</header>

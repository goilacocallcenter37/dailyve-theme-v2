@php
    $navItems = [
        ['label' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
        ['label' => 'Vé tàu hỏa', 'url' => home_url('/ve-tau-hoa/')],
        ['label' => 'Vé máy bay', 'url' => home_url('/ve-may-bay/')],
        ['label' => 'Tra cứu', 'url' => home_url('/tra-cuu/')],
        ['label' => 'Tin tức', 'url' => home_url('/tin-tuc/')],
        ['label' => 'Hỗ trợ', 'url' => home_url('/ho-tro/')],
    ];

    global $wp;
    $current_url = home_url(add_query_arg([], $wp->request));
    $current_url_clean = trailingslashit(strtok($current_url, '?'));
@endphp

<header class="dailyve-site-header">
    <div class="dailyve-container dailyve-site-header__inner">
        <a class="dailyve-site-header__logo" href="{{ esc_url(home_url('/')) }}" aria-label="Dailyve">
            <img src="https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/DailyVe-12-300x104.png"
                alt="Dailyve">
        </a>

        <nav class="dailyve-site-header__nav" aria-label="Điều hướng chính">
            @foreach ($navItems as $item)
                @php
                    $item_url_clean = trailingslashit(strtok($item['url'], '?'));
                    $isActive = $current_url_clean === $item_url_clean;
                @endphp
                <a href="{{ esc_url($item['url']) }}" class="{{ $isActive ? 'is-active' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="dailyve-site-header__actions">
            <div id="react-auth-menu"></div>
            <button class="dailyve-site-header__menu" type="button" aria-label="Mở menu">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div class="dailyve-mobile-drawer" id="dailyveMobileDrawer" aria-hidden="true">
    <div class="dailyve-mobile-drawer__backdrop" id="dailyveMobileDrawerBackdrop"></div>
    <div class="dailyve-mobile-drawer__content">
        <div class="dailyve-mobile-drawer__header">
            <a class="dailyve-mobile-drawer__logo" href="{{ esc_url(home_url('/')) }}">
                <img src="https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/DailyVe-12-300x104.png"
                    alt="Dailyve">
            </a>
            <button class="dailyve-mobile-drawer__close" id="dailyveMobileDrawerClose" type="button"
                aria-label="Đóng menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="dailyve-mobile-drawer__nav" aria-label="Điều hướng di động">
            @foreach ($navItems as $item)
                @php
                    $iconClass = 'fa-link';
                    if (strpos($item['label'], 'xe khách') !== false) {
                        $iconClass = 'fa-bus';
                    } elseif (strpos($item['label'], 'tàu hỏa') !== false) {
                        $iconClass = 'fa-train';
                    } elseif (strpos($item['label'], 'máy bay') !== false) {
                        $iconClass = 'fa-plane';
                    } elseif (strpos($item['label'], 'Khách sạn') !== false) {
                        $iconClass = 'fa-hotel';
                    } elseif (strpos($item['label'], 'Tin tức') !== false) {
                        $iconClass = 'fa-newspaper';
                    } elseif (strpos($item['label'], 'Hỗ trợ') !== false) {
                        $iconClass = 'fa-headset';
                    }
                    $item_url_clean = trailingslashit(strtok($item['url'], '?'));
                    $isActive = $current_url_clean === $item_url_clean;
                @endphp
                <a class="dailyve-mobile-drawer__link{{ $isActive ? ' is-active' : '' }}"
                    href="{{ esc_url($item['url']) }}">
                    <span class="dailyve-mobile-drawer__icon">
                        <i class="fas {{ $iconClass }}" aria-hidden="true"></i>
                    </span>
                    <span class="dailyve-mobile-drawer__label">{{ $item['label'] }}</span>
                    <i class="fas fa-chevron-right dailyve-mobile-drawer__arrow" aria-hidden="true"></i>
                </a>
            @endforeach
        </nav>

        <div class="dailyve-mobile-drawer__footer">
            <div class="dailyve-mobile-drawer__contact">
                <a href="tel:19000155" class="dailyve-mobile-drawer__contact-item">
                    <i class="fas fa-phone-alt text-emerald-500" aria-hidden="true"></i>
                    <span>Hotline: <strong>1900 0155</strong></span>
                </a>
                <a href="mailto:info.dailyve@gmail.com" class="dailyve-mobile-drawer__contact-item">
                    <i class="fas fa-envelope text-sky-500" aria-hidden="true"></i>
                    <span>info.dailyve@gmail.com</span>
                </a>
            </div>
            <div class="dailyve-mobile-drawer__trust">
                <i class="fas fa-shield-alt text-emerald-500" aria-hidden="true"></i>
                <span>Thanh toán an toàn & bảo mật</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.querySelector('.dailyve-site-header__menu');
        const drawer = document.getElementById('dailyveMobileDrawer');
        const closeButton = document.getElementById('dailyveMobileDrawerClose');
        const backdrop = document.getElementById('dailyveMobileDrawerBackdrop');

        if (!menuButton || !drawer) return;

        function openDrawer() {
            drawer.classList.add('is-open');
            drawer.setAttribute('aria-hidden', 'false');
            document.body.classList.add('dailyve-mobile-menu-active');
        }

        function closeDrawer() {
            drawer.classList.remove('is-open');
            drawer.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('dailyve-mobile-menu-active');
        }

        menuButton.addEventListener('click', openDrawer);
        if (closeButton) closeButton.addEventListener('click', closeDrawer);
        if (backdrop) backdrop.addEventListener('click', closeDrawer);

        // Close drawer on links click
        const drawerLinks = drawer.querySelectorAll('.dailyve-mobile-drawer__link');
        drawerLinks.forEach(link => {
            link.addEventListener('click', closeDrawer);
        });
    });
</script>

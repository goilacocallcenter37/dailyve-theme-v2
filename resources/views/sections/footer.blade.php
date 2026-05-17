@php
    $whiteLogo = home_url('/wp-content/uploads/2025/05/logo-dlv-white.png');
    $appStore = home_url('/wp-content/uploads/2025/01/download-app-store.png');
    $googlePlay = home_url('/wp-content/uploads/2025/01/download-gg-play.png');
@endphp

<footer class="dailyve-footer">
    <div class="dailyve-container dailyve-footer__grid">
        <div class="dailyve-footer__brand">
            <img src="{{ esc_url($whiteLogo) }}" alt="Dailyve">
            <p>Đặt vé nhanh - Hành trình trọn vẹn</p>
            <ul>
                <li><i class="fas fa-phone-alt" aria-hidden="true"></i><a href="tel:19000155">1900 0155</a></li>
                <li><i class="fas fa-envelope" aria-hidden="true"></i><a href="mailto:support@dailyve.com">support@dailyve.com</a></li>
                <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i>Tầng 10, Tòa nhà Viettel, 285 Cách Mạng Tháng 8, TP.HCM</li>
            </ul>
        </div>

        <nav aria-label="Về Dailyve">
            <h2>Về Dailyve</h2>
            <a href="{{ esc_url(home_url('/gioi-thieu/')) }}">Giới thiệu</a>
            <a href="{{ esc_url(home_url('/tuyen-dung/')) }}">Tuyển dụng</a>
            <a href="{{ esc_url(home_url('/dieu-khoan-su-dung/')) }}">Điều khoản sử dụng</a>
            <a href="{{ esc_url(home_url('/chinh-sach-bao-mat/')) }}">Chính sách bảo mật</a>
        </nav>

        <nav aria-label="Hỗ trợ">
            <h2>Hỗ trợ</h2>
            <a href="{{ esc_url(home_url('/trung-tam-ho-tro/')) }}">Trung tâm hỗ trợ</a>
            <a href="{{ esc_url(home_url('/huong-dan-dat-ve/')) }}">Hướng dẫn đặt vé</a>
            <a href="{{ esc_url(home_url('/hinh-thuc-thanh-toan/')) }}">Hình thức thanh toán</a>
            <a href="{{ esc_url(home_url('/chinh-sach-hoan-huy/')) }}">Chính sách hoàn hủy</a>
        </nav>

        <div class="dailyve-footer__social">
            <h2>Kết nối với chúng tôi</h2>
            <div>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                <a href="#" aria-label="Messenger"><i class="fab fa-facebook-messenger" aria-hidden="true"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
                <a href="#" aria-label="TikTok"><i class="fab fa-tiktok" aria-hidden="true"></i></a>
            </div>
        </div>

        <div class="dailyve-footer__apps">
            <h2>Tải ứng dụng</h2>
            <img src="{{ esc_url($appStore) }}" alt="Tải trên App Store">
            <img src="{{ esc_url($googlePlay) }}" alt="Tải trên Google Play">
        </div>
    </div>

    <div class="dailyve-container dailyve-footer__bottom">
        <p>© 2024 Dailyve. All rights reserved.</p>
    </div>
</footer>

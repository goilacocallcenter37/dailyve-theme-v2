@php
    $whiteLogo = home_url('/wp-content/uploads/2025/05/logo-dlv-white.png');
    $appStore = home_url('/wp-content/uploads/2025/01/download-app-store.png');
    $googlePlay = home_url('/wp-content/uploads/2025/01/download-gg-play.png');
@endphp

<footer class="dailyve-footer">
    <div class="dailyve-container dailyve-footer__grid">
        <div class="dailyve-footer__brand">
            <img src="{{ esc_url($whiteLogo) }}" alt="Dailyve">
            <p>Hệ thống đặt vé xe khách, tàu hỏa, máy bay trực tuyến hàng đầu Việt Nam.</p>
            <div class="dailyve-footer__social-inline">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                <a href="#" aria-label="TikTok"><i class="fab fa-tiktok" aria-hidden="true"></i></a>
            </div>
        </div>

        <nav aria-label="Về Dailyve">
            <h2>Về Dailyve</h2>
            <a href="{{ esc_url(home_url('/gioi-thieu/')) }}">Giới thiệu</a>
            <a href="{{ esc_url(home_url('/tuyen-dung/')) }}">Tuyển dụng</a>
            <a href="{{ esc_url(home_url('/tin-tuc/')) }}">Tin tức</a>
            <a href="{{ esc_url(home_url('/lien-he/')) }}">Liên hệ</a>
        </nav>

        <nav aria-label="Hỗ trợ khách hàng">
            <h2>Hỗ trợ khách hàng</h2>
            <a href="{{ esc_url(home_url('/trung-tam-ho-tro/')) }}">Trung tâm hỗ trợ</a>
            <a href="{{ esc_url(home_url('/huong-dan-dat-ve/')) }}">Hướng dẫn đặt vé</a>
            <a href="{{ esc_url(home_url('/chinh-sach-bao-mat/')) }}">Chính sách bảo mật</a>
            <a href="{{ esc_url(home_url('/dieu-khoan-su-dung/')) }}">Điều khoản sử dụng</a>
        </nav>

        <nav aria-label="Thông tin khác">
            <h2>Thông tin khác</h2>
            <a href="{{ esc_url(home_url('/quy-che-hoat-dong/')) }}">Quy chế hoạt động</a>
            <a href="{{ esc_url(home_url('/chinh-sach-hoan-huy/')) }}">Chính sách hoàn hủy</a>
            <a href="{{ esc_url(home_url('/ho-tro/')) }}">Câu hỏi thường gặp</a>
            <a href="{{ esc_url(home_url('/sitemap/')) }}">Sơ đồ trang</a>
        </nav>

        <div class="dailyve-footer__contact">
            <h2>Liên hệ</h2>
            <ul>
                <li><i class="fas fa-phone-alt" aria-hidden="true"></i><a href="tel:19005155">1900 5155</a></li>
                <li><i class="fas fa-envelope" aria-hidden="true"></i><a href="mailto:support@dailyve.com">support@dailyve.com</a></li>
                <li><i class="fas fa-clock" aria-hidden="true"></i>Hỗ trợ 24/7</li>
            </ul>
            <img src="{{ esc_url($appStore) }}" alt="Tải trên App Store" loading="lazy">
            <img src="{{ esc_url($googlePlay) }}" alt="Tải trên Google Play" loading="lazy">
        </div>
    </div>

    <div class="dailyve-container dailyve-footer__bottom">
        <p>© {{ date('Y') }} Dailyve. Bản quyền thuộc về Dailyve.</p>
        <div class="dailyve-footer__payments" aria-label="Phương thức thanh toán">
            <span>Thanh toán an toàn với</span>
            <span class="dailyve-footer__pay dailyve-footer__pay--visa">VISA</span>
            <span class="dailyve-footer__pay dailyve-footer__pay--mc">Mastercard</span>
            <span class="dailyve-footer__pay dailyve-footer__pay--momo">MoMo</span>
            <span class="dailyve-footer__pay dailyve-footer__pay--zalo">ZaloPay</span>
        </div>
    </div>
</footer>

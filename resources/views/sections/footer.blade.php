@php
    $whiteLogo = home_url('/wp-content/uploads/2025/05/logo-dlv-white.png');
    $appStore = home_url('/wp-content/uploads/2025/01/download-app-store.png');
    $googlePlay = home_url('/wp-content/uploads/2025/01/download-gg-play.png');
    $footerGroups = [
        [
            'label' => 'Đặt vé',
            'links' => [
                ['label' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
                ['label' => 'Vé tàu hỏa', 'url' => home_url('/ve-tau-hoa/')],
                ['label' => 'Vé máy bay', 'url' => home_url('/ve-may-bay/')],
                ['label' => 'Tra cứu vé', 'url' => home_url('/don-hang-cua-toi/')],
            ],
        ],
        [
            'label' => 'Tuyến phổ biến',
            'links' => [
                ['label' => 'Sài Gòn - Đà Lạt', 'url' => home_url('/ve-xe-khach/')],
                ['label' => 'Sài Gòn - Nha Trang', 'url' => home_url('/ve-xe-khach/')],
                ['label' => 'Hà Nội - Sapa', 'url' => home_url('/ve-xe-khach/')],
                ['label' => 'Xem tất cả tuyến', 'url' => home_url('/ve-xe-khach/')],
            ],
        ],
        [
            'label' => 'Về Dailyve',
            'links' => [
                ['label' => 'Giới thiệu', 'url' => home_url('/gioi-thieu/')],
                ['label' => 'Tin tức', 'url' => home_url('/tin-tuc/')],
                ['label' => 'Tuyển dụng', 'url' => home_url('/tuyen-dung/')],
                ['label' => 'Liên hệ', 'url' => home_url('/lien-he/')],
            ],
        ],
        [
            'label' => 'Hỗ trợ',
            'links' => [
                ['label' => 'Trung tâm hỗ trợ', 'url' => home_url('/trung-tam-ho-tro/')],
                ['label' => 'Hướng dẫn đặt vé', 'url' => home_url('/huong-dan-dat-ve/')],
                ['label' => 'Chính sách hoàn hủy', 'url' => home_url('/chinh-sach-hoan-huy/')],
                ['label' => 'Điều khoản sử dụng', 'url' => home_url('/dieu-khoan-su-dung/')],
            ],
        ],
    ];
@endphp

<footer class="dailyve-footer">
    <div class="dailyve-container dailyve-footer__top">
        <div class="dailyve-footer__brand">
            <a href="{{ esc_url(home_url('/')) }}" aria-label="Dailyve">
                <img src="{{ esc_url($whiteLogo) }}" alt="Dailyve">
            </a>
            <p>Đặt vé xe khách, tàu hỏa, máy bay nhanh chóng với giá minh bạch và hỗ trợ 24/7.</p>
            <div class="dailyve-footer__trust">
                <span><i class="fas fa-shield-alt" aria-hidden="true"></i> Thanh toán an toàn</span>
                <span><i class="fas fa-headset" aria-hidden="true"></i> Hỗ trợ 24/7</span>
            </div>
            <div class="dailyve-footer__social-inline">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                <a href="#" aria-label="TikTok"><i class="fab fa-tiktok" aria-hidden="true"></i></a>
            </div>
        </div>

        <div class="dailyve-footer__links">
            @foreach ($footerGroups as $group)
                <nav aria-label="{{ esc_attr($group['label']) }}">
                    <h2>{{ $group['label'] }}</h2>
                    @foreach ($group['links'] as $link)
                        <a href="{{ esc_url($link['url']) }}">{{ $link['label'] }}</a>
                    @endforeach
                </nav>
            @endforeach
        </div>
    </div>

    <div class="dailyve-container dailyve-footer__middle">
        <div class="dailyve-footer__contact" aria-label="Thông tin liên hệ">
            <a href="tel:19005155"><i class="fas fa-phone-alt" aria-hidden="true"></i>1900 5155</a>
            <a href="mailto:support@dailyve.com"><i class="fas fa-envelope" aria-hidden="true"></i>support@dailyve.com</a>
            <span><i class="fas fa-clock" aria-hidden="true"></i>Phục vụ khách hàng 24/7</span>
        </div>

        <div class="dailyve-footer__apps" aria-label="Tải ứng dụng Dailyve">
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

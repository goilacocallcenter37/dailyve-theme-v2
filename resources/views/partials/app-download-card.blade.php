<div class="rounded-xl bg-[#2196f3] p-5 text-white shadow-sm">
    <h2 class="text-base font-semibold">Tải ứng dụng Dailyve</h2>
    <p class="mt-1 text-xs text-blue-100">Đặt vé nhanh chóng, ưu đãi mỗi ngày</p>
    <div class="mt-4 grid grid-cols-[88px_minmax(0,1fr)] gap-3">
        <img class="h-[88px] w-[88px] rounded-lg bg-white p-1" src="{{ esc_url($qrCode) }}"
            alt="QR tải ứng dụng Dailyve" loading="lazy" decoding="async">
        <div class="grid content-center gap-2">
            <a href="https://apps.apple.com" target="_blank" rel="noopener noreferrer">
                <img class="h-9 w-auto rounded-md transition-transform hover:scale-105"
                    src="{{ esc_url($appStore) }}" alt="App Store" loading="lazy"
                    decoding="async">
            </a>
            <a href="https://play.google.com" target="_blank" rel="noopener noreferrer">
                <img class="h-9 w-auto rounded-md transition-transform hover:scale-105"
                    src="{{ esc_url($googlePlay) }}" alt="Google Play" loading="lazy"
                    decoding="async">
            </a>
        </div>
    </div>
</div>

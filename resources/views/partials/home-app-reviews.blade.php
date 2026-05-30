<section class="relative overflow-hidden bg-[#03162f] text-white" aria-labelledby="dailyve-reviews-title"
    data-home-testimonials>
    <div
        class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_18%_4%,rgba(33,150,243,0.24),transparent_32%),radial-gradient(circle_at_78%_72%,rgba(0,122,255,0.22),transparent_34%)]">
    </div>

    <div class="dailyve-container relative py-12 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-5xl text-center">
            <p
                class="mx-auto mb-5 inline-flex rounded-full border border-[#1677ff]/70 bg-[#0b3e82]/70 px-6 py-2 text-xs font-bold uppercase tracking-wide text-[#38bdf8] shadow-[0_0_28px_rgba(22,119,255,0.22)]">
                Được hàng nghìn khách hàng tin chọn
            </p>
            <h2 id="dailyve-reviews-title"
                class="m-0 text-[28px] font-bold leading-tight text-white sm:text-4xl lg:text-5xl">
                Khách hàng nói gì về <span class="text-[#3b82f6]">Dailyve</span>?
            </h2>
            <div class="mt-4 flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-slate-300">
                <strong class="text-[32px] font-bold text-[#3b82f6] sm:text-4xl leading-none">4.8<span
                        class="text-white text-xl sm:text-3xl font-medium">/5</span></strong>
                <span class="flex items-center gap-1 text-lg text-[#ffc107] sm:text-2xl" aria-label="5 sao">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fas fa-star" aria-hidden="true"></i>
                    @endfor
                </span>
                <span class="text-[13px] sm:text-base text-gray-400">(2.356 đánh giá)</span>
            </div>
        </div>

        <div class="relative mt-10">
            <button type="button"
                class="dailyve-testimonials__nav--prev absolute -left-5 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#2ea2ff]/50 bg-[#061b38]/90 text-white shadow-xl transition hover:bg-[#1677ff] lg:flex"
                aria-label="Review trước">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>

            <div
                class="dailyve-testimonials__track scrollbar-none flex snap-x gap-5 overflow-x-auto scroll-smooth pb-4">
                @foreach ($customerReviews as $review)
                    <article
                        class="dailyve-testimonial-card min-w-[85%] sm:min-w-[340px] lg:min-w-[calc((100%_-_60px)/4)] snap-start rounded-2xl border border-[#2ea2ff]/30 bg-[#05214a]/75 p-5 shadow-[0_20px_55px_rgba(0,0,0,0.22)] backdrop-blur transition hover:border-[#2ea2ff]/60">
                        <div class="flex items-start gap-4">
                            @if (!empty($review['avatar']))
                                <img src="{{ esc_url($review['avatar']) }}" alt="{{ esc_attr($review['name']) }}"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover shadow-inner border-2 border-white/10">
                            @endif
                            <div class="min-w-0 flex-1 text-left">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="m-0 truncate text-base font-bold text-white">{{ $review['name'] }}</h3>
                                    <i class="fas fa-quote-right shrink-0 text-xl text-[#1677ff]/80"
                                        aria-hidden="true"></i>
                                </div>
                                <div class="mt-1.5 flex gap-0.5 text-xs text-[#ffc107]"
                                    aria-label="{{ (int) $review['rating'] }} sao">
                                    @for ($i = 0; $i < (int) $review['rating']; $i++)
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                    @endfor
                                </div>
                                <p class="m-0 mt-3 text-[13px] leading-6 text-slate-200">{{ $review['quote'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <button type="button"
                class="dailyve-testimonials__nav--next absolute -right-5 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#2ea2ff]/50 bg-[#061b38]/90 text-white shadow-xl transition hover:bg-[#1677ff] lg:flex"
                aria-label="Review tiếp theo">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>

        <div
            class="mt-8 rounded-2xl border border-[#2ea2ff]/30 bg-[#06224c]/75 px-5 py-6 shadow-[0_18px_55px_rgba(0,0,0,0.2)] backdrop-blur sm:px-8 lg:px-14">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 sm:gap-6">
                @foreach ($reviewStats as $stat)
                    <div
                        class="flex items-center justify-start sm:justify-center lg:justify-start gap-4 lg:border-r lg:border-[#2ea2ff]/20 lg:pr-8 last:lg:border-r-0 pl-2 sm:pl-0">
                        <span
                            class="flex h-12 w-12 sm:h-14 sm:w-14 shrink-0 items-center justify-center rounded-full border border-[#2ea2ff]/40 bg-[#0a3b78] text-lg sm:text-xl text-[#3b82f6]">
                            <i class="{{ esc_attr($stat['icon']) }}" aria-hidden="true"></i>
                        </span>
                        <div class="text-left">
                            <strong
                                class="block text-lg font-bold text-white sm:text-2xl leading-none">{{ $stat['value'] }}</strong>
                            <span
                                class="mt-1 block text-[12px] sm:text-[13px] text-slate-300">{{ $stat['label'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-14 grid items-end gap-9 border-t border-[#2ea2ff]/20 pt-12 lg:grid-cols-[1fr_360px_1fr]">
            <div class="order-1 lg:pb-8">
                <h2 class="m-0 text-3xl font-medium leading-tight text-white">
                    Tải app <strong class="text-[#3b82f6] font-bold">Dailyve</strong> dễ dàng
                </h2>
                <p class="mt-4 max-w-sm text-[15px] leading-7 text-slate-300">
                    Đặt vé nhanh hơn, tiện lợi hơn, nhận ưu đãi tốt hơn.
                </p>

                <div class="mt-8 grid max-w-sm gap-4">
                    <a href="https://apps.apple.com/vn/app/dailyve-%C4%91%E1%BA%B7t-v%C3%A9-xe-24-7/id6748101538"
                        class="group flex min-h-[72px] items-center gap-4 rounded-xl border border-[#2ea2ff]/40 bg-[#062a5e]/80 px-4 text-white shadow-[0_18px_45px_rgba(0,0,0,0.22)] transition hover:border-[#55b7ff] hover:bg-[#0a3674]"
                        aria-label="Tải Dailyve trên App Store">
                        <span class="flex h-12 w-12 items-center justify-center rounded-lg text-4xl">
                            <i class="fab fa-apple" aria-hidden="true"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-xs text-slate-300 mb-0.5">Tải trên</span>
                            <strong class="block text-lg font-bold">App Store</strong>
                        </span>
                        <i class="fas fa-chevron-right text-lg text-slate-400 transition group-hover:translate-x-1"
                            aria-hidden="true"></i>
                    </a>

                    <a href="https://play.google.com/store/apps/details?id=com.dailyve"
                        class="group flex min-h-[72px] items-center gap-4 rounded-xl border border-[#2ea2ff]/40 bg-[#062a5e]/80 px-4 text-white shadow-[0_18px_45px_rgba(0,0,0,0.22)] transition hover:border-[#55b7ff] hover:bg-[#0a3674]"
                        aria-label="Tải Dailyve trên Google Play">
                        <span class="flex h-12 w-12 items-center justify-center rounded-lg text-3xl text-emerald-400">
                            <i class="fab fa-google-play" aria-hidden="true"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-xs text-slate-300 mb-0.5">Tải trên</span>
                            <strong class="block text-lg font-bold">Google Play</strong>
                        </span>
                        <i class="fas fa-chevron-right text-lg text-slate-400 transition group-hover:translate-x-1"
                            aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="order-2 flex justify-center relative" aria-hidden="true">
                <div class="absolute top-1/4 -left-4 text-[#3b82f6] text-3xl animate-pulse">✦</div>
                <div class="absolute top-1/3 -right-6 text-[#3b82f6] text-4xl animate-pulse delay-150">✦</div>
                <div
                    class="relative h-[420px] w-[220px] overflow-hidden rounded-[2.2rem] border-[8px] border-slate-950 bg-white shadow-[0_0_0_2px_rgba(255,255,255,0.3),0_28px_80px_rgba(0,122,255,0.3)] sm:h-[480px] sm:w-[250px]">
                    <span
                        class="absolute left-1/2 top-0 z-10 h-5 w-24 -translate-x-1/2 rounded-b-xl bg-slate-950"></span>
                    <img src="{{ esc_url($screenHome) }}" alt="" class="h-full w-full object-cover"
                        loading="lazy" decoding="async">
                </div>
            </div>

            <div class="order-3 lg:pb-8">
                <div
                    class="mx-auto max-w-sm rounded-[1.5rem] border border-[#2ea2ff]/40 bg-[#062a5e]/80 p-6 text-center shadow-[0_0_45px_rgba(0,122,255,0.15)] backdrop-blur">
                    <img src="{{ esc_url($qrCode) }}" alt="QR tải ứng dụng Dailyve" width="140" height="140"
                        class="mx-auto h-32 w-32 rounded-xl bg-white p-2 shadow-[0_0_30px_rgba(255,255,255,0.15)]"
                        loading="lazy" decoding="async">
                    <h3 class="m-0 mt-5 text-lg font-medium text-white leading-relaxed">Hoặc quét mã QR<br>để tải app
                        Dailyve</h3>
                    <div class="mt-4 flex items-center justify-center gap-4 text-sm text-slate-300">
                        <span class="inline-flex items-center gap-1.5"><i class="fab fa-apple text-white"
                                aria-hidden="true"></i> App Store</span>
                        <span class="inline-flex items-center gap-1.5"><i class="fab fa-google-play text-emerald-400"
                                aria-hidden="true"></i> Google Play</span>
                    </div>
                    <div class="mt-6 grid grid-cols-3 gap-3 border-t border-[#2ea2ff]/20 pt-5">
                        @foreach ($appBenefits as $benefit)
                            <div class="text-center">
                                <span
                                    class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-[#0b3d80] text-base text-[#3b82f6]">
                                    <i class="{{ esc_attr($benefit['icon']) }}" aria-hidden="true"></i>
                                </span>
                                <span
                                    class="mt-2 block text-[11px] font-medium leading-4 text-slate-200">{{ $benefit['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@extends('layouts.app')

@section('content')
    <div class="dailyve-bus-page bg-slate-50 min-h-screen font-sans antialiased text-slate-800 pb-20">

        {{-- Breadcrumbs --}}
        <x-breadcrumb :items="[['title' => 'Trang chủ', 'url' => home_url('/')], ['title' => 'Vé tàu hỏa', 'url' => '']]" preset="default" />


        {{-- Search Widget Block --}}
        <div class="dailyve-bus-search-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="dailyve-bus-search-card relative overflow-visible">
                <h2 class="dailyve-bus-search-title">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    TÌM VÉ TÀU HỎA TRỰC TUYẾN
                </h2>
                <div id="react-search-form" class="min-h-[120px]" data-initial-service="train">
                    {{-- React search widget mounts here --}}
                </div>
            </div>
        </div>

        {{-- Hero Section (2-Column Grid) --}}
        <div class="dailyve-bus-intro-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

                {{-- Left Side: Introduce & Features (7/12) --}}
                <div class="lg:col-span-7 flex flex-col justify-between">
                    <div>
                        <h1
                            class="text-2xl md:text-3.5xl font-bold text-slate-950 mb-4 tracking-tight leading-tight uppercase bg-gradient-to-r from-slate-950 via-blue-900 to-indigo-950 bg-clip-text text-transparent">
                            Đặt vé tàu hỏa trực tuyến tại Dailyve
                        </h1>
                        <p class="text-slate-600 leading-relaxed mb-8 text-sm md:text-base">
                            Đặt vé tàu hỏa trực tuyến với hàng trăm tuyến đường trên Việt Nam tại Dailyve.com.vn. Chúng tôi
                            là đối tác tin cậy của hơn 100 hãng tàu uy tín cung cấp đa dạng dòng xe đáp ứng nhu cầu đi lại
                            của
                            hàng khách. Bạn có thể lựa chọn tuyến đường, ghế ngồi, khung giờ phù hợp và so sánh giá giữa các
                            hãng tàu một cách dễ dàng trên hệ thống của chúng tôi.
                        </p>
                    </div>

                    {{-- Benefit Cards (2x2 Grid) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Benefit 1 --}}
                        <div
                            class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex items-start gap-4 group">
                            <div
                                class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1 text-sm md:text-base">Tìm kiếm nhanh chóng</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Tra cứu hành trình, dòng xe, điểm đón trả
                                    chỉ trong vài giây.</p>
                            </div>
                        </div>

                        {{-- Benefit 2 --}}
                        <div
                            class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex items-start gap-4 group">
                            <div
                                class="p-3 bg-sky-50 text-sky-600 rounded-xl group-hover:bg-sky-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1 text-sm md:text-base">Tối ưu hóa chi phí</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Săn nhiều ưu đãi đặc quyền và mức giá cạnh
                                    tranh nhất.</p>
                            </div>
                        </div>

                        {{-- Benefit 3 --}}
                        <div
                            class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex items-start gap-4 group">
                            <div
                                class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1 text-sm md:text-base">Hãng tàu uy tín cao</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Hợp tác cùng 100+ đối tác vận tải chất
                                    lượng vượt trội.</p>
                            </div>
                        </div>

                        {{-- Benefit 4 --}}
                        <div
                            class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-lg hover:shadow-slate-100/50 transition-all duration-300 flex items-start gap-4 group">
                            <div
                                class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1 text-sm md:text-base">Thanh toán bảo mật</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Đa dạng hình thức giao dịch qua cổng bảo
                                    mật 100%.</p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Right Side: Callout Support Banner (5/12) --}}
                <div class="lg:col-span-5">
                    <div
                        class="bg-gradient-to-br from-[#2196f3] to-[#1565c0] rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-blue-100 flex flex-col justify-between h-full min-h-[380px] group border border-blue-500/20">
                        <div
                            class="absolute -right-16 -top-16 w-48 h-48 bg-white/10 rounded-full blur-xl group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="absolute -left-12 -bottom-12 w-36 h-36 bg-blue-500/20 rounded-full blur-lg"></div>

                        <div>
                            <span
                                class="bg-white/20 text-white text-[10px] md:text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full inline-block mb-6 backdrop-blur-sm">Hỗ
                                trợ hotline 24/7</span>
                            <h3 class="text-2xl md:text-3xl text-white font-bold mb-3 leading-snug tracking-tight">Tổng đài
                                đặt vé & CSKH trực tuyến</h3>
                            <p class="text-blue-100 text-xs md:text-sm leading-relaxed mb-6">
                                Kết nối trực tiếp tới tổng đài hỗ trợ viên để đặt giữ chỗ nhanh nhất, tư vấn mọi lịch trình
                                và xử lý nhanh chóng các yêu cầu hoàn hủy đổi vé.
                            </p>
                        </div>

                        <div class="relative z-10">
                            <a href="tel:19000155"
                                class="bg-white no-underline! text-blue-900 hover:bg-slate-50 transition-all font-bold text-xl md:text-2xl py-4 px-6 rounded-2xl flex items-center justify-center gap-3 shadow-lg hover:shadow-xl active:scale-[0.98] duration-200 group/btn">
                                <i
                                    class="fas fa-phone-alt text-blue-600 group-hover/btn:text-blue-800 transition-colors"></i>
                                <span>1900 0155</span>
                            </a>
                            <p class="text-center text-[10px] md:text-xs text-blue-200 mt-3 font-medium">Cước phí theo quy
                                định nhà mạng</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Offers Section ("Ưu đãi Dailyve.com.vn") --}}
        @php
            $train_offers = [
                ['discount' => '10%', 'label' => 'Cho Người Mới', 'code' => 'DLVNEW10'],
                ['discount' => '50K', 'label' => 'Vé tàu hỏa', 'code' => 'DAILY50'],
                ['discount' => '30K', 'label' => 'Vé tàu hỏa', 'code' => 'TAU30'],
                ['discount' => '100K', 'label' => 'Vé máy bay', 'code' => 'MAYBAY100'],
                ['discount' => '15%', 'label' => 'Khách sạn', 'code' => 'KS15'],
                ['discount' => '20K', 'label' => 'Đơn từ 200K', 'code' => 'DLV20'],
            ];
        @endphp
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16" aria-label="Ưu đãi vé tàu hỏa">
            <div class="relative group">
                <button onclick="slideLeft('train-offers-slider')" id="train-offers-slider-prev"
                    class="absolute -left-4 md:-left-6 top-1/2 z-20 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-900 opacity-0 shadow-lg shadow-slate-200/70 transition-all duration-200 hover:scale-105 hover:bg-slate-50 pointer-events-none md:flex"
                    type="button" aria-label="Ưu đãi trước">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>

                <div id="train-offers-slider" onscroll="updateSliderButtons('train-offers-slider')"
                    class="scrollbar-none flex snap-x gap-5 overflow-x-auto scroll-smooth py-2">
                    @foreach ($train_offers as $offer)
                        <article class="dailyve-offer-card min-h-[146px] w-[278px] shrink-0 snap-start">
                            <div class="dailyve-offer-card__body">
                                <p class="dailyve-offer-card__discount">Giảm Giá {{ $offer['discount'] }}</p>
                                <p class="dailyve-offer-card__label">{{ $offer['label'] }}</p>
                                <button type="button" class="dailyve-offer-card__save"
                                    data-code="{{ esc_attr($offer['code']) }}">
                                    Lưu Mã
                                </button>
                            </div>
                            <div class="dailyve-offer-card__badge" aria-hidden="true">
                                <span>%</span>
                            </div>
                        </article>
                    @endforeach
                </div>

                <button onclick="slideRight('train-offers-slider')" id="train-offers-slider-next"
                    class="absolute -right-4 md:-right-6 top-1/2 z-20 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-900 shadow-lg shadow-slate-200/70 transition-all duration-200 hover:scale-105 hover:bg-slate-50 md:flex"
                    type="button" aria-label="Ưu đãi tiếp theo">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </section>

        {{-- Custom Styles for Sliders --}}
        <style>
            .scrollbar-none::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-none {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>

        {{-- Section 1: Tuyến đường phổ biến --}}
        @php
            $routes_query = new \WP_Query([
                'post_type' => 'page',
                'post_parent' => 16846,
                'posts_per_page' => 12,
                'post_status' => 'publish',
                'meta_query' => [
                    [
                        'key' => 'outstanding',
                        'value' => true,
                        'compare' => '=',
                    ],
                ],
            ]);
        @endphp
        @if ($routes_query->have_posts())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 relative">
                <div class="flex items-center justify-between mb-6">
                    <h2
                        class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight uppercase flex items-center gap-2">
                        <span class="w-2.5 h-6 bg-blue-600 rounded-full animate-pulse"></span>
                        Tuyến đường phổ biến
                    </h2>
                </div>

                <div class="relative group">
                    {{-- Left Arrow --}}
                    <button onclick="slideLeft('routes-slider')" id="routes-slider-prev"
                        class="absolute -left-4 md:-left-6 top-1/2 -translate-y-1/2 bg-white text-slate-800 p-3 rounded-full shadow-xl border border-slate-100 hover:bg-slate-50 transition-all hover:scale-110 active:scale-95 z-20 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 duration-300 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>

                    {{-- Container --}}
                    <div id="routes-slider" onscroll="updateSliderButtons('routes-slider')"
                        class="flex overflow-x-auto scroll-smooth gap-5 pb-4 snap-x scrollbar-none">
                        @while ($routes_query->have_posts())
                            @php
                                $routes_query->the_post();
                                $post_id = get_the_ID();
                                $price = get_field('routes_price', $post_id);
                                $distance = get_field('routes_distance', $post_id);
                                $time = get_field('routes_time', $post_id);
                            @endphp
                            <div
                                class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md shadow-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shrink-0 w-[290px] snap-start group/card">
                                <div class="relative overflow-hidden aspect-[4/3]">
                                    @if (has_post_thumbnail())
                                        {!! get_the_post_thumbnail($post_id, 'medium', [
                                            'class' => 'w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500',
                                        ]) !!}
                                    @else
                                        <div
                                            class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                            Không có ảnh</div>
                                    @endif
                                </div>
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3
                                            class="font-bold text-slate-900 text-sm md:text-base leading-snug mb-2 line-clamp-1 hover:text-blue-600 transition-colors">
                                            <a class="no-underline!"
                                                href="{{ get_permalink() }}">{{ get_the_title() }}</a>
                                        </h3>
                                        @if ($distance && $time)
                                            <p
                                                class="text-slate-400 text-[11px] font-semibold flex items-center gap-1 mb-3">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $distance }} - {{ $time }}
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($price)
                                            <div
                                                class="flex items-center justify-between mb-3 border-t border-slate-100 pt-3">
                                                <span class="text-xs text-slate-500 font-medium">Giá vé từ</span>
                                                <span
                                                    class="text-base md:text-lg font-extrabold text-red-500">{{ number_format($price, 0, ',', '.') }}đ</span>
                                            </div>
                                        @endif
                                        <a href="{{ get_permalink() }}"
                                            class="no-underline! w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-3 px-4 rounded-xl text-center shadow-md active:scale-[0.98] transition-all block">
                                            Đặt vé ngay
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endwhile
                    </div>

                    {{-- Right Arrow --}}
                    <button onclick="slideRight('routes-slider')" id="routes-slider-next"
                        class="absolute -right-4 md:-right-6 top-1/2 -translate-y-1/2 bg-white text-slate-800 p-3 rounded-full shadow-xl border border-slate-100 hover:bg-slate-50 transition-all hover:scale-110 active:scale-95 z-20 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
            @php wp_reset_postdata(); @endphp
        @endif

        @php
            $bus_image = home_url('/wp-content/uploads/images/train-icon.png');
            $qr_blocks = [0, 1, 3, 5, 7, 8, 10, 12, 13, 14, 16, 18, 20, 21, 23, 24];
            $booking_guide_slides = [
                [
                    'eyebrow' => 'Ứng dụng Dailyve',
                    'title' => 'Đặt vé xe tại ứng dụng Dailyve',
                    'note' => 'Tiện lợi quá đeee!!',
                    'steps' => [
                        ['title' => 'Bước 1', 'text' => 'Bật ứng dụng Dailyve - bắt chuyến đi!'],
                        ['title' => 'Bước 2', 'text' => 'Nhập điểm đi, điểm đến và ngày khởi hành'],
                        ['title' => 'Bước 3', 'text' => 'Chọn chuyến tàu ưng ý'],
                        ['title' => 'Bước 4', 'text' => 'Giữ ngay chỗ ngồi ưng mắt'],
                        ['title' => 'Bước 5', 'text' => 'Chọn điểm đón/trả thuận tiện'],
                        ['title' => 'Bước 6', 'text' => 'Điền nhanh thông tin hành khách'],
                        ['title' => 'Bước 7', 'text' => 'Kiểm tra thông tin và thanh toán'],
                        ['title' => 'Bước 8', 'text' => 'Vé về tay - vi vu ngay!'],
                    ],
                ],
                [
                    'eyebrow' => 'Website Dailyve',
                    'title' => 'Đặt vé nhanh trên dailyve.com.vn',
                    'note' => 'Không cần tải app',
                    'steps' => [
                        ['title' => 'Bước 1', 'text' => 'Chọn điểm xuất phát, điểm đến và ngày đi'],
                        ['title' => 'Bước 2', 'text' => 'Bấm Tìm vé để xem danh sách chuyến'],
                        ['title' => 'Bước 3', 'text' => 'So sánh giá, giờ chạy và hãng tàu'],
                        ['title' => 'Bước 4', 'text' => 'Chọn ghế hoặc giường còn trống'],
                        ['title' => 'Bước 5', 'text' => 'Xác nhận điểm đón/trả phù hợp'],
                        ['title' => 'Bước 6', 'text' => 'Nhập thông tin liên hệ nhận vé'],
                        ['title' => 'Bước 7', 'text' => 'Thanh toán an toàn qua cổng hỗ trợ'],
                        ['title' => 'Bước 8', 'text' => 'Nhận mã vé điện tử và lên tàu'],
                    ],
                ],
                [
                    'eyebrow' => 'Hỗ trợ 24/7',
                    'title' => 'Cần hỗ trợ, Dailyve xử lý ngay',
                    'note' => 'Có người đồng hành',
                    'steps' => [
                        ['title' => 'Bước 1', 'text' => 'Gọi hotline 1900 0155 khi cần tư vấn'],
                        ['title' => 'Bước 2', 'text' => 'Cung cấp tuyến đi và thời gian mong muốn'],
                        ['title' => 'Bước 3', 'text' => 'Nhân viên kiểm tra chuyến còn chỗ'],
                        ['title' => 'Bước 4', 'text' => 'Chốt thông tin hành khách và điểm đón'],
                        ['title' => 'Bước 5', 'text' => 'Nhận hướng dẫn thanh toán rõ ràng'],
                        ['title' => 'Bước 6', 'text' => 'Xác nhận vé qua SMS hoặc email'],
                        ['title' => 'Bước 7', 'text' => 'Theo dõi chuyến và giờ xuất bến'],
                        ['title' => 'Bước 8', 'text' => 'Liên hệ lại nếu cần đổi/hủy vé'],
                    ],
                ],
            ];

            $faq_tabs = [
                'tong-quan' => 'Tổng quan',
                'dat-cho' => 'Đặt chỗ',
                'thanh-toan' => 'Thanh toán',
                'huy-ve' => 'Hủy vé - hoàn tiền',
                'khuyen-mai' => 'Giảm giá - khuyến mãi',
            ];

            $train_faqs = [
                [
                    'category' => 'tong-quan',
                    'question' => 'Làm thế nào để đặt vé xe trực tuyến trên Dailyve?',
                    'answer' =>
                        'Bạn chỉ cần điền điểm xuất phát, điểm đến và ngày đi tại form tìm kiếm, sau đó nhấn Tìm vé. Hệ thống sẽ hiển thị các chuyến tàu phù hợp để bạn chọn ghế, nhập thông tin và thanh toán.',
                ],
                [
                    'category' => 'tong-quan',
                    'question' => 'Dailyve có những tuyến xe nào?',
                    'answer' =>
                        'Dailyve kết nối nhiều tuyến tàu hỏa phổ biến trên toàn quốc, bao gồm các chặng liên tỉnh, tuyến du lịch và tuyến về quê. Danh sách chuyến sẽ được lọc theo điểm đi, điểm đến và ngày khởi hành bạn chọn.',
                ],
                [
                    'category' => 'dat-cho',
                    'question' => 'Tôi có thể chọn vị trí ghế ngồi trước không?',
                    'answer' =>
                        'Có. Với các hãng tàu hỗ trợ sơ đồ ghế, bạn có thể xem ghế hoặc giường còn trống và chọn vị trí phù hợp trước khi thanh toán.',
                ],
                [
                    'category' => 'dat-cho',
                    'question' => 'Nếu nhập sai thông tin hành khách thì xử lý thế nào?',
                    'answer' =>
                        'Bạn nên liên hệ Dailyve càng sớm càng tốt qua hotline để được kiểm tra điều kiện chỉnh sửa. Một số hãng tàu cho phép cập nhật thông tin trước giờ khởi hành theo chính sách riêng.',
                ],
                [
                    'category' => 'thanh-toan',
                    'question' => 'Thanh toán vé tàu hỏa bằng những phương thức nào?',
                    'answer' =>
                        'Dailyve hỗ trợ nhiều hình thức thanh toán như QR ngân hàng, chuyển khoản, thẻ nội địa hoặc thẻ quốc tế tùy từng thời điểm và từng đơn hàng.',
                ],
                [
                    'category' => 'thanh-toan',
                    'question' => 'Thanh toán xong bao lâu thì nhận vé?',
                    'answer' =>
                        'Thông thường vé điện tử được gửi ngay sau khi hệ thống xác nhận thanh toán thành công. Nếu chưa nhận được, bạn có thể kiểm tra email, SMS hoặc liên hệ hotline để đối soát.',
                ],
                [
                    'category' => 'huy-ve',
                    'question' => 'Chính sách hoàn hủy vé trên hệ thống thế nào?',
                    'answer' =>
                        'Quy định hoàn hủy phụ thuộc vào chính sách của từng hãng tàu và thời điểm yêu cầu hủy. Dailyve sẽ hỗ trợ kiểm tra điều kiện hoàn/hủy cụ thể cho mã vé của bạn.',
                ],
                [
                    'category' => 'huy-ve',
                    'question' => 'Bao lâu tôi nhận được tiền hoàn?',
                    'answer' =>
                        'Thời gian hoàn tiền phụ thuộc vào phương thức thanh toán và ngân hàng xử lý. Sau khi yêu cầu được duyệt, Dailyve sẽ cập nhật trạng thái hoàn tiền cho bạn.',
                ],
                [
                    'category' => 'khuyen-mai',
                    'question' => 'Tôi nhập mã giảm giá ở đâu?',
                    'answer' =>
                        'Bạn có thể lưu mã ưu đãi ở khu vực khuyến mãi, sau đó nhập hoặc áp dụng mã tại bước thanh toán nếu đơn hàng đủ điều kiện.',
                ],
                [
                    'category' => 'khuyen-mai',
                    'question' => 'Mã giảm giá có áp dụng cùng lúc được không?',
                    'answer' =>
                        'Thông thường mỗi đơn hàng chỉ áp dụng một mã giảm giá. Điều kiện chi tiết có thể thay đổi theo từng chương trình khuyến mãi.',
                ],
            ];
        @endphp

        {{-- Booking Guide Section --}}
        <section class="px-4 sm:px-6 lg:px-8 mb-16 bg-sky-50" aria-label="Hướng dẫn đặt vé tàu hỏa"
            id="booking-guide-section">
            <div class="max-w-7xl mx-auto relative overflow-hidden py-6 md:p-10 lg:p-12">
                <div
                    class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-sky-100/50 to-transparent pointer-events-none">
                </div>

                {{-- Section Header & Tabs Switcher --}}
                <div
                    class="flex flex-col md:flex-row items-center justify-between gap-6 mb-10 pb-6 border-b border-sky-100">
                    <div>
                        <h2 class="text-2xl md:text-3.5xl font-bold tracking-tight text-blue-950 text-center md:text-left">
                            Hướng dẫn đặt vé tàu hỏa
                        </h2>
                        <p class="text-xs md:text-sm text-slate-500 mt-1.5 text-center md:text-left">Dễ dàng, nhanh chóng
                            và an toàn qua nhiều kênh tiện lợi</p>
                    </div>

                    {{-- Tabs --}}
                    <div class="flex gap-2 bg-slate-100/80 p-1.5 rounded-2xl border border-slate-200/50" role="tablist">
                        <button type="button"
                            class="guide-tab-btn shrink-0 rounded-xl px-4 py-2 text-xs md:text-sm font-bold transition-all bg-primary text-white shadow-md shadow-primary/20"
                            data-tab="app" aria-selected="true">
                            <i class="fas fa-mobile-alt mr-1.5"></i> Ứng dụng
                        </button>
                        <button type="button"
                            class="guide-tab-btn shrink-0 rounded-xl px-4 py-2 text-xs md:text-sm font-bold transition-all text-slate-600 hover:bg-white hover:text-primary"
                            data-tab="web" aria-selected="false">
                            <i class="fas fa-globe mr-1.5"></i> Website
                        </button>
                        <button type="button"
                            class="guide-tab-btn shrink-0 rounded-xl px-4 py-2 text-xs md:text-sm font-bold transition-all text-slate-600 hover:bg-white hover:text-primary"
                            data-tab="hotline" aria-selected="false">
                            <i class="fas fa-headset mr-1.5"></i> Tổng đài 24/7
                        </button>
                    </div>
                </div>

                {{-- 2-Column Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                    {{-- Left: Phone Mockup (5/12 cols) --}}
                    <div class="lg:col-span-5 flex justify-center py-4">
                        <div
                            class="relative h-[430px] w-[215px] rounded-[38px] border-[6px] border-slate-900 bg-slate-900 shadow-2xl shadow-blue-900/10">
                            <!-- Dynamic Island/Notch -->
                            <div
                                class="absolute left-1/2 top-1 z-20 h-4.5 w-18 -translate-x-1/2 rounded-full bg-slate-900">
                            </div>

                            <div class="h-full overflow-hidden rounded-[32px] bg-white p-2 relative select-none">
                                <!-- Status Bar -->
                                <div
                                    class="flex h-5 items-center justify-between px-2 text-[9px] font-bold text-slate-950 absolute top-1.5 left-2 right-2 z-10">
                                    <span>09:41</span>
                                    <span class="flex gap-1.5 items-center"><i class="fas fa-signal"></i><i
                                            class="fas fa-wifi"></i><i class="fas fa-battery-three-quarters"></i></span>
                                </div>

                                <!-- Screen Area (relative, overflow-hidden) -->
                                <div class="w-full h-full pt-5 relative rounded-[26px] overflow-hidden bg-slate-50">

                                    @foreach ($booking_guide_slides as $index => $slide)
                                        @php
                                            $tab_id = $index === 0 ? 'app' : ($index === 1 ? 'web' : 'hotline');
                                        @endphp

                                        @foreach ($slide['steps'] as $step_idx => $step)
                                            @php
                                                $step_num = $step_idx + 1;
                                                $is_active = $index === 0 && $step_idx === 0;
                                            @endphp

                                            {{-- Guide Screen Step Container --}}
                                            <div data-screen="{{ $tab_id }}-{{ $step_num }}"
                                                class="guide-screen absolute inset-0 w-full h-full transition-all duration-500 ease-out transform {{ $is_active ? 'opacity-100 scale-100 pointer-events-auto' : 'opacity-0 scale-95 pointer-events-none' }}">

                                                {{-- Image mockup specified by user --}}
                                                <img src="{{ esc_url(home_url("/wp-content/themes/dailyve-theme/resources/images/guide/{$tab_id}-{$step_num}.png")) }}"
                                                    alt="Hướng dẫn {{ $slide['eyebrow'] }} bước {{ $step_num }}"
                                                    class="absolute inset-0 w-full h-full object-cover z-10"
                                                    onerror="this.style.opacity='0'; this.style.pointerEvents='none'; this.nextElementSibling.classList.remove('hidden');"
                                                    onload="this.style.opacity='1';">

                                                <!-- Fallback Placeholder (displayed if image is missing) -->
                                                <div
                                                    class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-primary to-primary-active text-white p-4 text-center hidden">
                                                    <div
                                                        class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center mb-3">
                                                        @if ($tab_id === 'app')
                                                            <i class="fas fa-mobile-alt text-xl"></i>
                                                        @elseif ($tab_id === 'web')
                                                            <i class="fas fa-globe text-xl"></i>
                                                        @else
                                                            <i class="fas fa-headset text-xl"></i>
                                                        @endif
                                                    </div>
                                                    <p class="text-[10px] font-bold tracking-wider uppercase opacity-75">
                                                        {{ $slide['eyebrow'] }}</p>
                                                    <p class="text-xs font-black mt-1">Bước {{ $step_num }}</p>
                                                    <p
                                                        class="text-[9px] mt-1.5 opacity-90 px-2 leading-relaxed line-clamp-3">
                                                        {{ $step['text'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Steps list (7/12 cols) --}}
                    <div class="lg:col-span-7 flex flex-col justify-center">

                        @foreach ($booking_guide_slides as $index => $slide)
                            @php
                                $tab_id = $index === 0 ? 'app' : ($index === 1 ? 'web' : 'hotline');
                            @endphp

                            {{-- Steps block for each tab --}}
                            <div id="steps-container-{{ $tab_id }}"
                                class="guide-steps-container space-y-3 {{ $index > 0 ? 'hidden' : '' }}">
                                <div class="mb-4 flex flex-wrap items-center gap-2">
                                    <span
                                        class="rounded-full bg-white px-3 py-1.5 text-xs font-bold text-primary shadow-sm border border-slate-100">
                                        {{ $slide['eyebrow'] }}
                                    </span>
                                    <span
                                        class="rotate-[-3deg] rounded-full bg-brand-accent px-3 py-1.5 text-xs font-extrabold text-slate-900 shadow-sm">
                                        {{ $slide['note'] }}
                                    </span>
                                </div>

                                <h3 class="text-lg md:text-2xl font-bold text-slate-900 mb-5 leading-snug">
                                    {{ $slide['title'] }}
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($slide['steps'] as $step_idx => $step)
                                        @php
                                            $step_num = $step_idx + 1;
                                            $is_first = $step_idx === 0;
                                        @endphp
                                        <button type="button"
                                            class="guide-step-card text-left p-4 rounded-xl border transition-all duration-300 hover:shadow-md cursor-pointer flex items-start gap-3 w-full focus:outline-none {{ $is_first ? 'bg-primary text-white border-primary shadow-md shadow-primary/20 ring-2 ring-primary/30' : 'bg-white text-slate-800 border-slate-100 hover:border-primary/30 hover:shadow-md' }}"
                                            data-step="{{ $step_num }}" data-tab-type="{{ $tab_id }}">

                                            {{-- Badge number --}}
                                            <span
                                                class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 {{ $is_first ? 'bg-brand-accent text-slate-900' : 'bg-slate-100 text-slate-500' }}">
                                                {{ $step_num }}
                                            </span>

                                            <div>
                                                <h4
                                                    class="font-extrabold text-[13px] tracking-wide mb-0.5 {{ $is_first ? 'text-brand-accent' : 'text-slate-500' }}">
                                                    {{ $step['title'] }}
                                                </h4>
                                                <p
                                                    class="text-xs font-medium leading-relaxed {{ $is_first ? 'text-white' : 'text-slate-700' }}">
                                                    {{ $step['text'] }}
                                                </p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>

            </div>
        </section>

        {{-- FAQ / Accordion Section --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-labelledby="train-faq-title" data-faq-section>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="bg-[#2196f3] px-5 py-5 md:px-8">
                    <h2 id="train-faq-title" class="text-2xl font-semibold tracking-[-0.5px] text-white md:text-3xl">
                        Một số câu hỏi thường gặp
                    </h2>
                </div>

                <div class="bg-white p-4 md:p-6">
                    <div class="scrollbar-none flex gap-2 overflow-x-auto rounded-full bg-slate-100 p-1" role="tablist"
                        aria-label="Nhóm câu hỏi">
                        @foreach ($faq_tabs as $key => $label)
                            <button type="button"
                                class="dailyve-faq-tab shrink-0 rounded-full px-4 py-2 text-sm font-semibold transition-all {{ $loop->first ? 'bg-[#2196f3] text-white shadow-sm' : 'text-blue-500 hover:bg-white' }}"
                                data-faq-tab="{{ esc_attr($key) }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach ($train_faqs as $faq)
                            <div class="dailyve-faq-item overflow-hidden rounded-xl border border-slate-200 bg-white transition-all duration-200 {{ $faq['category'] !== 'tong-quan' ? 'hidden' : '' }}"
                                data-faq-item data-faq-category="{{ esc_attr($faq['category']) }}">
                                <button onclick="toggleAccordion(this)"
                                    class="group flex w-full items-center justify-between gap-4 px-4 py-4 text-left md:px-5"
                                    type="button">
                                    <span class="flex items-start gap-4">
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-blue-500 group-hover:bg-blue-50">
                                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span
                                            class="pt-1 text-base font-bold leading-snug text-slate-900 md:text-lg">{{ $faq['question'] }}</span>
                                    </span>
                                    <svg class="h-5 w-5 shrink-0 text-blue-500 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div
                                    class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                                    <div
                                        class="border-t border-slate-100 px-4 py-4 pl-16 text-sm leading-relaxed text-slate-600 md:px-5 md:pl-[68px]">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

    </div>

    {{-- Inline vanilla JS for dynamic tab transitions and accordions --}}
    <script>
        function slideLeft(id) {
            const container = document.getElementById(id);
            if (container) {
                container.scrollBy({
                    left: -container.clientWidth * 0.8,
                    behavior: 'smooth'
                });
            }
        }

        function slideRight(id) {
            const container = document.getElementById(id);
            if (container) {
                container.scrollBy({
                    left: container.clientWidth * 0.8,
                    behavior: 'smooth'
                });
            }
        }

        function updateSliderButtons(id) {
            const container = document.getElementById(id);
            const prevBtn = document.getElementById(id + '-prev');
            const nextBtn = document.getElementById(id + '-next');
            if (container && prevBtn && nextBtn) {
                const scrollLeft = container.scrollLeft;
                const maxScroll = container.scrollWidth - container.clientWidth;

                if (scrollLeft <= 5) {
                    prevBtn.classList.add('opacity-0', 'pointer-events-none');
                    prevBtn.classList.remove('opacity-100');
                } else {
                    prevBtn.classList.remove('opacity-0', 'pointer-events-none');
                    prevBtn.classList.add('opacity-100');
                }

                if (scrollLeft >= maxScroll - 5) {
                    nextBtn.classList.add('opacity-0', 'pointer-events-none');
                    nextBtn.classList.remove('opacity-100');
                } else {
                    nextBtn.classList.remove('opacity-0', 'pointer-events-none');
                    nextBtn.classList.add('opacity-100');
                }
            }
        }

        function toggleAccordion(button) {
            const parent = button.parentNode;
            const content = button.nextElementSibling;
            const icon = button.querySelector('svg');
            const isExpanded = content.style.maxHeight && content.style.maxHeight !== '0px';

            // Collapse all other accordions first
            document.querySelectorAll('.accordion-content').forEach(acc => {
                acc.style.maxHeight = '0px';
                acc.parentNode.classList.remove('border-blue-500/30', 'border-blue-200', 'shadow-blue-50/50',
                    'shadow-md', 'shadow-blue-100/60');
                const otherIcon = acc.previousElementSibling.querySelector('svg');
                if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
            });

            if (isExpanded) {
                content.style.maxHeight = '0px';
                parent.classList.remove('border-blue-500/30', 'border-blue-200', 'shadow-blue-50/50', 'shadow-md',
                    'shadow-blue-100/60');
                if (icon) icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                parent.classList.add('border-blue-200', 'shadow-md', 'shadow-blue-100/60');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        }

        function initFaqTabs() {
            const root = document.querySelector('[data-faq-section]');
            if (!root) return;

            const tabs = [...root.querySelectorAll('[data-faq-tab]')];
            const items = [...root.querySelectorAll('[data-faq-item]')];

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const category = tab.dataset.faqTab;

                    tabs.forEach(item => {
                        const active = item === tab;
                        item.classList.toggle('bg-blue-500', active);
                        item.classList.toggle('text-white', active);
                        item.classList.toggle('shadow-sm', active);
                        item.classList.toggle('text-blue-500', !active);
                        item.classList.toggle('hover:bg-white', !active);
                        item.setAttribute('aria-selected', active ? 'true' : 'false');
                    });

                    items.forEach(item => {
                        const active = item.dataset.faqCategory === category;
                        item.classList.toggle('hidden', !active);

                        if (!active) {
                            const content = item.querySelector('.accordion-content');
                            const icon = item.querySelector('button svg');
                            if (content) content.style.maxHeight = '0px';
                            if (icon) icon.style.transform = 'rotate(0deg)';
                            item.classList.remove('border-blue-200', 'shadow-md',
                                'shadow-blue-100/60');
                        }
                    });
                });
            });
        }

        function initBookingGuide() {
            const section = document.getElementById('booking-guide-section');
            if (!section) return;

            const tabButtons = [...section.querySelectorAll('.guide-tab-btn')];
            const stepContainers = [...section.querySelectorAll('.guide-steps-container')];
            const stepCards = [...section.querySelectorAll('.guide-step-card')];
            const screens = [...section.querySelectorAll('.guide-screen')];

            // Function to set active step within a tab type
            function setActiveStep(tabType, stepNum) {
                // 1. Update step cards styling
                stepCards.forEach(card => {
                    if (card.dataset.tabType === tabType) {
                        const isCurrent = parseInt(card.dataset.step) === stepNum;
                        const badge = card.querySelector('span');
                        const title = card.querySelector('h4');
                        const desc = card.querySelector('p');

                        card.classList.toggle('bg-primary', isCurrent);
                        card.classList.toggle('text-white', isCurrent);
                        card.classList.toggle('border-primary', isCurrent);
                        card.classList.toggle('shadow-md', isCurrent);
                        card.classList.toggle('shadow-primary/20', isCurrent);
                        card.classList.toggle('ring-2', isCurrent);
                        card.classList.toggle('ring-primary/30', isCurrent);

                        card.classList.toggle('bg-white', !isCurrent);
                        card.classList.toggle('text-slate-800', !isCurrent);
                        card.classList.toggle('border-slate-100', !isCurrent);
                        card.classList.toggle('hover:border-primary/30', !isCurrent);
                        card.classList.toggle('hover:shadow-md', !isCurrent);

                        if (badge) {
                            badge.classList.toggle('bg-brand-accent', isCurrent);
                            badge.classList.toggle('text-slate-900', isCurrent);
                            badge.classList.toggle('bg-slate-100', !isCurrent);
                            badge.classList.toggle('text-slate-500', !isCurrent);
                        }

                        if (title) {
                            title.classList.toggle('text-brand-accent', isCurrent);
                            title.classList.toggle('text-slate-500', !isCurrent);
                        }

                        if (desc) {
                            desc.classList.toggle('text-white', isCurrent);
                            desc.classList.toggle('text-slate-700', !isCurrent);
                        }
                    }
                });

                // 2. Update screen display with fade & scale transitions
                screens.forEach(screen => {
                    const isCurrent = screen.dataset.screen === `${tabType}-${stepNum}`;

                    screen.classList.toggle('opacity-100', isCurrent);
                    screen.classList.toggle('scale-100', isCurrent);
                    screen.classList.toggle('pointer-events-auto', isCurrent);

                    screen.classList.toggle('opacity-0', !isCurrent);
                    screen.classList.toggle('scale-95', !isCurrent);
                    screen.classList.toggle('pointer-events-none', !isCurrent);
                });
            }

            // Tab buttons event listeners
            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const activeTab = btn.dataset.tab;

                    // Update tab buttons styles using DESIGN.md tokens
                    tabButtons.forEach(tBtn => {
                        const isActive = tBtn === btn;
                        tBtn.classList.toggle('bg-primary', isActive);
                        tBtn.classList.toggle('text-white', isActive);
                        tBtn.classList.toggle('shadow-md', isActive);
                        tBtn.classList.toggle('shadow-primary/20', isActive);
                        tBtn.classList.toggle('text-slate-600', !isActive);
                        tBtn.classList.toggle('hover:bg-white', !isActive);
                        tBtn.classList.toggle('hover:text-primary', !isActive);
                        tBtn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    });

                    // Show corresponding step container
                    stepContainers.forEach(container => {
                        const isCurrent = container.id === `steps-container-${activeTab}`;
                        container.classList.toggle('hidden', !isCurrent);
                    });

                    // Reset to step 1 for that tab
                    setActiveStep(activeTab, 1);
                });
            });

            // Step cards click listeners
            stepCards.forEach(card => {
                card.addEventListener('click', () => {
                    const tabType = card.dataset.tabType;
                    const stepNum = parseInt(card.dataset.step);
                    setActiveStep(tabType, stepNum);
                });
            });
        }

        // Initial slider buttons checks
        document.addEventListener('DOMContentLoaded', () => {
            ['train-offers-slider', 'routes-slider', 'operators-slider', 'stations-slider'].forEach(id => {
                setTimeout(() => updateSliderButtons(id), 500);
            });
            initFaqTabs();
            initBookingGuide();
        });
    </script>
@endsection

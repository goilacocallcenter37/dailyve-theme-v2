@extends('layouts.app')

@section('content')
    <article class="relative bg-blue-900 py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/banner-bus.jpg" class="w-full h-full object-cover" alt="Dailyve Banner - Đặt vé xe khách chất lượng cao">
        </div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 drop-shadow-lg">
                Hành Trình Vạn Dặm, Bắt Đầu Từ Một Click
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto mb-10">
                Đặt vé xe khách chất lượng cao, giá rẻ nhất thị trường. Cam kết giữ chỗ 100% tại Dailyve.
            </p>
        </div>
    </article>

    <div class="container mx-auto px-4">
        {!! do_shortcode('[react_search_form]') !!}
    </div>

    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">Tại sao chọn Dailyve?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-ticket-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Giá Vé Rẻ Nhất</h3>
                    <p class="text-gray-600">Luôn cam kết giá vé cạnh tranh nhất, nhiều ưu đãi hấp dẫn mỗi ngày.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">An Toàn Tuyệt Đối</h3>
                    <p class="text-gray-600">Hợp tác với các nhà xe uy tín, lái xe kinh nghiệm, xe đời mới sạch sẽ.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Hỗ Trợ 24/7</h3>
                    <p class="text-gray-600">Đội ngũ chăm sóc khách hàng tận tâm, giải quyết mọi thắc mắc của bạn.</p>
                </div>
            </div>
        </div>
    </section>
@endsection


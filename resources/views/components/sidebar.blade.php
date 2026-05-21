@php
// Dynamic fetch of popular/recent posts for the sidebar
$sidebar_posts = new \WP_Query([
    'post_type'           => 'post',
    'posts_per_page'      => 4,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
]);

// Curated prominent services list mapped to respective WordPress pages
$services = [
    [
        'title' => 'Vé xe khách',
        'desc' => 'Đặt vé xe giường nằm, limousine giá rẻ chất lượng cao.',
        'url' => home_url('/ve-xe-khach/'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
        'color' => 'text-amber-500 bg-amber-50 border-amber-100',
    ],
    [
        'title' => 'Vé máy bay',
        'desc' => 'Săn vé máy bay giá rẻ, cập nhật hành trình nhanh nhất.',
        'url' => home_url('/ve-may-bay/'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>',
        'color' => 'text-blue-500 bg-blue-50 border-blue-100',
    ],
    [
        'title' => 'Vé tàu hỏa',
        'desc' => 'Mua vé tàu trực tuyến nhanh chóng, chọn ghế dễ dàng.',
        'url' => home_url('/ve-tau-hoa/'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9a1 1 0 01-1-1v-1h5v1zm4-7h2m-2 4h2m-4-10a3 3 0 013 3v12a3 3 0 01-3 3H9a3 3 0 01-3-3V5a3 3 0 013-3h6z"></path></svg>',
        'color' => 'text-emerald-500 bg-emerald-50 border-emerald-100',
    ]
];
@endphp

<aside class="space-y-8 lg:sticky lg:top-24">
  {{-- Prominent Services Widget --}}
  <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm shadow-slate-100/50">
    <h3 class="font-display font-semibold text-lg text-slate-900 tracking-tight mb-5 flex items-center gap-2">
      <span class="w-1.5 h-5 bg-blue-600 rounded-full"></span>
      Dịch vụ nổi bật
    </h3>

    <div class="space-y-4">
      @foreach ($services as $service)
        <a href="{{ $service['url'] }}" class="group flex items-start gap-4 p-3 rounded-2xl border border-slate-50 hover:border-blue-100/50 hover:bg-blue-50/10 transition-all duration-300">
          <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center border transition-all duration-300 group-hover:scale-105 {{ $service['color'] }}">
            {!! $service['icon'] !!}
          </div>
          <div class="flex-grow">
            <h4 class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
              {{ $service['title'] }}
            </h4>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
              {{ $service['desc'] }}
            </p>
          </div>
        </a>
      @endforeach
    </div>
  </div>

  {{-- Related Articles / Guide Widget --}}
  @if ($sidebar_posts->have_posts())
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm shadow-slate-100/50">
      <h3 class="font-display font-semibold text-lg text-slate-900 tracking-tight mb-5 flex items-center gap-2">
        <span class="w-1.5 h-5 bg-blue-600 rounded-full"></span>
        Cẩm nang du lịch
      </h3>

      <div class="space-y-4">
        @while ($sidebar_posts->have_posts())
          @php
            $sidebar_posts->the_post();
            $post_title = get_the_title();
            $post_url = get_permalink();
            $has_thumb = has_post_thumbnail();
            
            // Calculate reading time
            $content = get_post_field('post_content', get_the_ID());
            $word_count = str_word_count(strip_tags($content));
            $reading_time = max(1, ceil($word_count / 200));
          @endphp
          <a href="{{ $post_url }}" class="group flex gap-4 p-2 rounded-2xl hover:bg-slate-50 transition-all duration-300">
            @if ($has_thumb)
              <div class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-100">
                {!! get_the_post_thumbnail(null, 'thumbnail', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-300']) !!}
              </div>
            @else
              <div class="flex-shrink-0 w-16 h-16 rounded-xl bg-blue-50 border border-blue-50/50 flex items-center justify-center text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
              </div>
            @endif
            <div class="flex-grow min-w-0 flex flex-col justify-center">
              <h4 class="text-xs font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                {!! $post_title !!}
              </h4>
              <div class="flex items-center gap-3 text-[10px] font-medium text-slate-400 mt-2">
                <span>{{ get_the_date('d/m/Y') }}</span>
                <span>•</span>
                <span>{{ $reading_time }} phút đọc</span>
              </div>
            </div>
          </a>
        @endwhile
        @php
          wp_reset_postdata();
        @endphp
      </div>
    </div>
  @endif
</aside>

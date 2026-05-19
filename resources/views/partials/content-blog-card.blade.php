@php
  $address = function_exists('get_field') ? get_field('company_address') : null;
@endphp

<article class="dailyve-blog-card group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex flex-col h-full overflow-hidden">
  <!-- Post Image -->
  <div class="relative aspect-[16/10] w-full overflow-hidden bg-slate-100">
    <a href="{{ get_permalink() }}" title="{{ the_title_attribute(['echo' => false]) }}" class="absolute inset-0">
      @if (has_post_thumbnail())
        {!! get_the_post_thumbnail(null, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out']) !!}
      @else
        <div class="w-full h-full flex items-center justify-center bg-slate-50">
          <i class="far fa-image text-3xl text-slate-300"></i>
        </div>
      @endif
    </a>
  </div>

  <!-- Post Body -->
  <div class="flex-1 p-5 flex flex-col justify-between">
    <div>
      <!-- Meta (Date and Author) -->
      <div class="flex flex-wrap items-center text-xs font-semibold text-slate-400 mb-2.5 gap-x-3 gap-y-1">
        <span class="inline-flex items-center whitespace-nowrap">
          <i class="far fa-calendar-alt mr-1.5"></i>
          {{ get_the_date('d/m/Y') }}
        </span>
        <span class="inline-flex items-center">
          <i class="far fa-user mr-1.5"></i>
          <span class="truncate max-w-[120px]" title="{{ get_the_author() }}">{{ get_the_author() }}</span>
        </span>
      </div>

      <!-- Title -->
      <h3 class="text-base font-extrabold text-slate-800 leading-snug mb-3 group-hover:text-primary transition-colors duration-150 line-clamp-2">
        <a href="{{ get_permalink() }}">
          {!! get_the_title() !!}
        </a>
      </h3>

      <!-- Excerpt or Company Address -->
      <div class="text-slate-500 text-sm leading-relaxed mb-4">
        @if ($address)
          <p class="inline-flex items-start text-primary font-medium text-xs">
            <i class="fas fa-map-marker-alt mr-1.5 mt-0.5 text-xs"></i>
            <span class="line-clamp-2">{!! esc_html($address) !!}</span>
          </p>
        @else
          <div class="line-clamp-3">
            {!! wp_strip_all_tags(get_the_excerpt()) !!}
          </div>
        @endif
      </div>
    </div>

    <!-- Read More Button Link -->
    <div class="pt-3 border-t border-slate-50 flex items-center justify-between">
      <a href="{{ get_permalink() }}" class="text-xs font-bold text-slate-700 group-hover:text-primary transition-colors duration-150 inline-flex items-center">
        Xem chi tiết
        <i class="fas fa-arrow-right ml-1 text-[10px] transform group-hover:translate-x-0.5 transition-transform"></i>
      </a>
    </div>
  </div>
</article>

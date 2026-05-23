@php
  $categories = get_the_category();
  $primary_cat = !empty($categories) ? $categories[0] : null;

  // Define breadcrumbs for the blog detail page with HTML entity decoding
  $breadcrumbs = [
      ['title' => 'Trang chủ', 'url' => home_url('/')],
  ];
  if ($primary_cat) {
      $breadcrumbs[] = [
          'title' => html_entity_decode($primary_cat->name, ENT_QUOTES, 'UTF-8'),
          'url' => get_category_link($primary_cat->term_id)
      ];
  }
  $breadcrumbs[] = [
      'title' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
      'url' => ''
  ];
@endphp

<div class="bg-slate-50 min-h-screen pb-20 font-sans antialiased text-slate-800 pt-6">
  <style>
    /* Prevent any Gutenberg block or content image overflow on mobile */
    .prose *, .e-content *, .dailyve-single * {
      max-width: 100% !important;
      box-sizing: border-box !important;
    }
    .prose img, .e-content img, .dailyve-single img, .prose iframe, .e-content iframe {
      max-width: 100% !important;
      height: auto !important;
      width: auto !important;
      display: block !important;
    }
    .prose figure, .e-content figure, .dailyve-single figure, .prose .wp-block-image, .e-content .wp-block-image, .dailyve-single .wp-block-image {
      max-width: 100% !important;
      width: 100% !important;
      margin-left: 0 !important;
      margin-right: 0 !important;
      display: block !important;
    }
    /* Hide scrollbars for slick slider aesthetics */
    .scrollbar-none::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-none {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>

  {{-- Render Dynamic Breadcrumbs --}}
  <x-breadcrumb :items="$breadcrumbs" preset="default" />

  <div class="mx-auto grid max-w-7xl gap-8 px-4 pt-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-8 items-start">
      
      {{-- Left Column: Main Content --}}
      <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm shadow-slate-100/50 max-w-full overflow-hidden">
        <article class="{{ implode(' ', get_post_class('dailyve-single')) }}">
          
          <!-- Header -->
          <header class="mb-8 pb-6 border-b border-slate-100">
            <!-- Category Badge -->
            @if ($primary_cat)
              <a href="{{ esc_url(get_category_link($primary_cat->term_id)) }}" 
                 class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 transition-colors mb-4 uppercase tracking-wider">
                {{ $primary_cat->name }}
              </a>
            @endif

            <!-- Post Title -->
            <h1 class="font-display font-semibold text-2xl md:text-4xl text-slate-900 tracking-tight leading-tight mb-6">
              {!! html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8') !!}
            </h1>

            <!-- Entry Meta Info -->
            <div class="flex flex-wrap items-center text-xs md:text-sm font-semibold text-slate-400 gap-4 md:gap-6">
              <span class="inline-flex items-center">
                <i class="far fa-calendar-alt text-blue-500 mr-2"></i>
                {{ get_the_date('d F, Y') }}
              </span>
              <span class="inline-flex items-center">
                <i class="far fa-user text-blue-500 mr-2"></i>
                Viết bởi: {{ get_the_author() }}
              </span>
              <span class="inline-flex items-center">
                <i class="far fa-clock text-blue-500 mr-2"></i>
                @php
                  $content = get_post_field('post_content', get_the_ID());
                  $word_count = str_word_count(strip_tags($content));
                  $reading_time = ceil($word_count / 200); // Average reading speed
                @endphp
                {{ $reading_time > 0 ? $reading_time : 1 }} phút đọc
              </span>
            </div>
          </header>

          <!-- Featured Image -->
          @if (has_post_thumbnail())
            <div class="relative aspect-[16/9] w-full overflow-hidden rounded-2xl border border-slate-100 shadow-sm mb-8 bg-slate-50">
              {!! get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full object-cover']) !!}
            </div>
          @endif

          <!-- Post Body Content -->
          <div class="e-content prose max-w-none text-slate-600 leading-relaxed">
            @php
              the_content();
            @endphp
          </div>

          @if (function_exists('wp_link_pages'))
            <div class="my-6">
              {!! wp_link_pages(['echo' => 0]) !!}
            </div>
          @endif

          <!-- Social Sharing Bar & Tag list -->
          <div class="mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Post Tags -->
            <div class="flex flex-wrap gap-2">
              @php
                $tags = get_the_tags();
              @endphp
              @if ($tags)
                @foreach ($tags as $tag)
                  <a href="{{ esc_url(get_tag_link($tag->term_id)) }}" class="text-xs font-semibold text-slate-500 bg-slate-100 hover:bg-slate-200 px-3 py-1 rounded-xl transition duration-150">
                    #{{ $tag->name }}
                  </a>
                @endforeach
              @endif
            </div>

            <!-- Share actions -->
            <div class="flex items-center space-x-3">
              <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Chia sẻ:</span>
              <!-- FB Share -->
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(get_permalink()) }}" 
                 target="_blank" rel="noopener noreferrer"
                 class="w-9 h-9 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center shadow-md shadow-blue-500/20 hover:scale-105 transition-all duration-200">
                <i class="fab fa-facebook-f text-sm"></i>
              </a>
              <!-- Twitter/X Share -->
              <a href="https://twitter.com/intent/tweet?url={{ urlencode(get_permalink()) }}&text={{ urlencode(get_the_title()) }}" 
                 target="_blank" rel="noopener noreferrer"
                 class="w-9 h-9 rounded-full bg-slate-900 hover:bg-slate-950 text-white flex items-center justify-center shadow-md shadow-slate-900/20 hover:scale-105 transition-all duration-200">
                <i class="fab fa-twitter text-sm"></i>
              </a>
              <!-- Copy Link -->
              <button onclick="navigator.clipboard.writeText('{{ get_permalink() }}'); alert('Đã sao chép liên kết vào bộ nhớ tạm!');"
                      class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center shadow-sm hover:scale-105 transition-all duration-200 cursor-pointer"
                      title="Copy Link">
                <i class="fas fa-link text-sm"></i>
              </button>
            </div>
          </div>

          <!-- Author Bio Box -->
          @php
            $author_desc = get_the_author_meta('description');
          @endphp
          <div class="mt-12 bg-slate-50 border border-slate-100 rounded-2xl p-6 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6 shadow-sm">
            <div class="flex-shrink-0 w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-md">
              {!! get_avatar(get_the_author_meta('ID'), 80, '', '', ['class' => 'w-full h-full object-cover']) !!}
            </div>
            <div class="flex-grow">
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Tác giả bài viết</span>
              <h4 class="text-base font-semibold text-slate-800 mt-1 mb-2">
                {{ get_the_author_meta('display_name') }}
              </h4>
              <p class="text-xs text-slate-500 leading-relaxed font-medium">
                {!! $author_desc ?: 'Chuyên viên biên tập nội dung cẩm nang xe khách, tin tức ưu đãi và chia sẻ trải nghiệm du lịch tại Dailyve.' !!}
              </p>
            </div>
          </div>

          <!-- Comments template -->
          @if (comments_open() || get_comments_number())
            <div class="mt-16 pt-10 border-t border-slate-100">
              @php
                comments_template();
              @endphp
            </div>
          @endif
          
        </article>
      </div>
      
      {{-- Right Column: Shared Sidebar --}}
      <div>
        <x-sidebar />
      </div>

    </div>

    {{-- Related Posts Slider Section --}}
    @php
      $category_ids = [];
      if ($categories) {
          foreach($categories as $individual_category) {
              $category_ids[] = $individual_category->term_id;
          }
      }
      
      $related_posts = new \WP_Query([
          'category__in'        => $category_ids,
          'post__not_in'        => [get_the_ID()],
          'posts_per_page'      => 6,
          'post_status'         => 'publish',
          'ignore_sticky_posts' => 1
      ]);
    @endphp

    @if ($related_posts->have_posts())
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pb-6">
        <h3 class="font-display font-semibold text-xl text-slate-900 mb-8 flex items-center">
          <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-3"></span>
          Bài viết liên quan
        </h3>

        <div class="relative group">
          <div id="single-related-slider" class="flex overflow-x-auto gap-6 scrollbar-none pb-4 scroll-smooth">
            @while ($related_posts->have_posts())
              @php
                $related_posts->the_post();
                $post_title = get_the_title();
                $post_url = get_permalink();
                $has_thumb = has_post_thumbnail();
                
                // Calculate reading time
                $content = get_post_field('post_content', get_the_ID());
                $word_count = str_word_count(strip_tags($content));
                $reading_time = max(1, ceil($word_count / 200));
              @endphp
              
              <a href="{{ $post_url }}" class="group flex-shrink-0 w-80 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-100/50 transition-all duration-300 overflow-hidden flex flex-col">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                  @if ($has_thumb)
                    {!! get_the_post_thumbnail(null, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']) !!}
                  @else
                    <div class="w-full h-full flex items-center justify-center text-blue-500 bg-blue-50">
                      <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                  @endif
                </div>
                <div class="p-5 flex-grow flex flex-col justify-between bg-white">
                  <h4 class="text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                    {!! $post_title !!}
                  </h4>
                  <div class="flex items-center gap-3 text-[10px] font-semibold text-slate-400 mt-4 pt-3 border-t border-slate-50">
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

          <!-- Slider Left Arrow Button -->
          <button onclick="document.getElementById('single-related-slider').scrollBy({ left: -320, behavior: 'smooth' })" 
                  class="absolute -left-5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:scale-105 transition-all duration-200 cursor-pointer hidden md:flex z-10"
                  aria-label="Slide Left">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
          </button>
          
          <!-- Slider Right Arrow Button -->
          <button onclick="document.getElementById('single-related-slider').scrollBy({ left: 320, behavior: 'smooth' })" 
                  class="absolute -right-5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:scale-105 transition-all duration-200 cursor-pointer hidden md:flex z-10"
                  aria-label="Slide Right">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>
        </div>
      </div>
    @endif

  </div>
</div>

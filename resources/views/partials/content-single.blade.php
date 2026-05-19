@php
  $categories = get_the_category();
  $primary_cat = !empty($categories) ? $categories[0] : null;
@endphp

<article class="{{ implode(' ', get_post_class('dailyve-single max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10')) }}">
  <!-- Header -->
  <header class="text-center mb-8">
    <!-- Category Badge -->
    @if ($primary_cat)
      <a href="{{ esc_url(get_category_link($primary_cat->term_id)) }}" 
         class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary/10 text-primary uppercase tracking-wider mb-4 hover:bg-primary/20 transition duration-150">
        {{ $primary_cat->name }}
      </a>
    @endif

    <!-- Post Title -->
    <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-6">
      {!! get_the_title() !!}
    </h1>

    <!-- Entry Meta Info -->
    <div class="flex flex-wrap items-center justify-center text-sm font-semibold text-slate-500 gap-4 md:gap-6 pb-6 border-b border-slate-100">
      <span class="inline-flex items-center">
        <i class="far fa-calendar-alt text-primary mr-2"></i>
        {{ get_the_date('d F, Y') }}
      </span>
      <span class="inline-flex items-center">
        <i class="far fa-user text-primary mr-2"></i>
        Viết bởi: {{ get_the_author() }}
      </span>
      <span class="inline-flex items-center">
        <i class="far fa-clock text-primary mr-2"></i>
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
    <div class="relative aspect-[16/9] w-full overflow-hidden rounded-3xl shadow-md mb-10 bg-slate-100">
      {!! get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full object-cover']) !!}
    </div>
  @endif

  <!-- Post Body Content -->
  <div class="e-content">
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
          <a href="{{ esc_url(get_tag_link($tag->term_id)) }}" class="text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 px-3 py-1 rounded-xl transition duration-150">
            #{{ $tag->name }}
          </a>
        @endforeach
      @endif
    </div>

    <!-- Share actions -->
    <div class="flex items-center space-x-3">
      <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Chia sẻ:</span>
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
  <div class="mt-12 bg-slate-50 border border-slate-100 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6 shadow-sm">
    <div class="flex-shrink-0 w-20 h-20 rounded-full overflow-hidden border-2 border-white shadow-md">
      {!! get_avatar(get_the_author_meta('ID'), 80, '', '', ['class' => 'w-full h-full object-cover']) !!}
    </div>
    <div class="flex-grow">
      <span class="text-xs font-bold text-primary uppercase tracking-wider">Tác giả bài viết</span>
      <h4 class="text-lg font-extrabold text-slate-800 mt-1 mb-2">
        {{ get_the_author_meta('display_name') }}
      </h4>
      <p class="text-sm text-slate-500 leading-relaxed font-medium">
        {!! $author_desc ?: 'Chuyên viên biên tập nội dung cẩm nang xe khách, tin tức ưu đãi và chia sẻ trải nghiệm du lịch tại Dailyve.' !!}
      </p>
    </div>
  </div>

  <!-- Related Posts Grid Section -->
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
        'posts_per_page'      => 4,
        'ignore_sticky_posts' => 1
    ]);
  @endphp

  @if ($related_posts->have_posts())
    <div class="mt-16 pt-10 border-t border-slate-100">
      <h3 class="text-2xl font-extrabold text-slate-900 mb-8 flex items-center">
        <span class="w-1.5 h-6 bg-primary rounded-full mr-3"></span>
        Bài viết liên quan
      </h3>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @while ($related_posts->have_posts())
          @php
            $related_posts->the_post();
          @endphp
          @include('partials.content-blog-card')
        @endwhile
        @php
          wp_reset_postdata();
        @endphp
      </div>
    </div>
  @endif

  <!-- Comments template -->
  @if (comments_open() || get_comments_number())
    <div class="mt-16 pt-10 border-t border-slate-100">
      @php
        comments_template();
      @endphp
    </div>
  @endif
</article>

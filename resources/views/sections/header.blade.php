<header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
  <div class="container mx-auto px-4 py-4 flex items-center justify-between">
    <a class="flex items-center gap-2" href="{{ home_url('/') }}">
      <img src="https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/DailyVe-12-300x104.png" class="h-10 w-auto" alt="Dailyve Logo">
    </a>

    @if (has_nav_menu('primary_navigation'))
      <nav class="hidden md:flex items-center gap-8" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
        {!! wp_nav_menu([
            'theme_location' => 'primary_navigation', 
            'menu_class' => 'flex gap-6 font-semibold text-gray-700 hover:text-blue-600 transition-colors', 
            'echo' => false,
            'container' => false
        ]) !!}
      </nav>
    @endif

    <div class="flex items-center gap-4">
        <a href="/tai-khoan" class="bg-blue-50 text-blue-700 px-5 py-2 rounded-full font-bold hover:bg-blue-100 transition-all">
            Đăng Nhập
        </a>
        <button class="md:hidden text-gray-600">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
  </div>
</header>

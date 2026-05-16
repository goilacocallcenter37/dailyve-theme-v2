<div class="bg-slate-900 text-white/80 py-2 hidden lg:block">
  <div class="container mx-auto px-4 flex justify-between items-center text-[11px] font-bold tracking-wider uppercase">
    <div class="flex gap-6">
      <a href="tel:19001234" class="flex items-center gap-2 hover:text-primary transition-colors">
        <i class="fas fa-phone-alt text-primary"></i>
        HOTLINE: 1900 1234
      </a>
      <span class="flex items-center gap-2">
        <i class="fas fa-envelope text-primary"></i>
        EMAIL: LIENHE@DAILYVE.COM
      </span>
    </div>
    <div class="flex gap-4">
      <a href="#" class="hover:text-primary transition-colors">Về chúng tôi</a>
      <a href="#" class="hover:text-primary transition-colors">Hướng dẫn đặt vé</a>
      <a href="#" class="hover:text-primary transition-colors">Tin tức</a>
    </div>
  </div>
</div>

<header class="glass-effect sticky top-0 z-50 border-b border-white/20 shadow-sm transition-all duration-300">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <a class="flex items-center gap-2 transition-transform hover:scale-105" href="{{ home_url('/') }}">
      <img src="https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/DailyVe-12-300x104.png" class="h-10 w-auto" alt="Dailyve Logo">
    </a>

    @if (has_nav_menu('primary_navigation'))
      <nav class="hidden lg:flex items-center gap-8" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
        {!! wp_nav_menu([
            'theme_location' => 'primary_navigation', 
            'menu_class' => 'flex gap-8 font-sans font-bold text-slate-700 [&_a]:transition-all [&_a]:hover:text-primary [&_a]:relative [&_a]:after:content-[\'\'] [&_a]:after:absolute [&_a]:after:bottom-[-4px] [&_a]:after:left-0 [&_a]:after:w-0 [&_a]:after:h-[2px] [&_a]:after:bg-primary [&_a]:after:transition-all [&_a:hover]:after:w-full', 
            'echo' => false,
            'container' => false
        ]) !!}
      </nav>
    @endif

    <div class="flex items-center gap-4">
        <div class="hidden sm:flex flex-col items-end mr-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase leading-none">Bạn đã có tài khoản?</span>
            <a href="/tai-khoan" class="text-xs font-black text-primary hover:text-primary-dark transition-colors">ĐĂNG NHẬP</a>
        </div>
        <a href="/dat-ve" class="font-display bg-primary text-white px-7 py-2.5 rounded-full font-black text-sm shadow-lg shadow-primary/20 hover:bg-primary-dark hover:shadow-primary/30 transition-all active:scale-95">
            ĐẶT VÉ NGAY
        </a>
        <button class="lg:hidden text-slate-600 h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
  </div>
</header>

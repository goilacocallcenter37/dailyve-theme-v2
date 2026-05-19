@php
  $currentActive = $active ?? '';
  $isLoggedIn = is_customer_logged_in() || is_user_logged_in();
  $customerData = isset($_SESSION['customer_data']) ? $_SESSION['customer_data'] : null;
  $avatar = !empty($customerData['avatar']) ? $customerData['avatar'] : '/wp-content/uploads/images/user.png';
  $phone = $customerData['phone'] ?? '';
  $name = $customerData['name'] ?? '';

  $menuItems = [
    ['slug' => 'tai-khoan', 'label' => 'Thông tin tài khoản', 'icon' => 'far fa-user', 'url' => home_url('/tai-khoan/')],
    ['slug' => 'don-hang-cua-toi', 'label' => 'Đơn hàng của tôi', 'icon' => 'fas fa-ticket-alt', 'url' => home_url('/don-hang-cua-toi/')],
    ['slug' => 'uu-dai', 'label' => 'Ưu đãi', 'icon' => 'fas fa-gift', 'url' => home_url('/uu-dai/')],
    ['slug' => 'quan-ly-the', 'label' => 'Quản lý thẻ', 'icon' => 'far fa-credit-card', 'url' => home_url('/quan-ly-the/')],
  ];
@endphp

@if ($isLoggedIn)
  <aside class="profile-sidebar">
    {{-- User Avatar & Info --}}
    <div class="profile-header">
      <div class="profile-avatar">
        <img src="{{ esc_url($avatar) }}" alt="Avatar">
      </div>
      <div class="profile-name">{{ esc_html($name ?: $phone) }}</div>
    </div>

    {{-- Navigation Menu --}}
    <ul class="profile-menu">
      @foreach ($menuItems as $item)
        <li class="{{ $currentActive === $item['slug'] ? 'active' : '' }}">
          <a href="{{ esc_url($item['url']) }}">
            <i class="{{ $item['icon'] }}"></i>
            <span>{{ $item['label'] }}</span>
          </a>
        </li>
      @endforeach

      <li class="logout-item">
        <a href="{{ esc_url(add_query_arg('action', 'customer_logout', home_url())) }}">
          <i class="fas fa-sign-out-alt"></i>
          <span>Đăng xuất</span>
        </a>
      </li>
    </ul>
  </aside>
@endif

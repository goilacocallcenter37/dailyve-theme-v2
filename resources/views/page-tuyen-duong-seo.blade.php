@extends('layouts.app')

@section('content')
  @php
    $post_id = get_the_ID();
    $current_title = get_the_title();

    // Breadcrumb data
    $breadcrumbs = [
      ['title' => 'Dailyve', 'url' => home_url('/')],
      ['title' => 'Vé xe khách', 'url' => home_url('/ve-xe-khach/')],
      ['title' => 'Tuyến đường', 'url' => home_url('/ve-xe-khach/tuyen-duong/')],
      ['title' => $current_title, 'url' => ''],
    ];

    // Fetch departure / destination location data
    $from_field = get_field('routes_departure_point', $post_id);
    $to_field   = get_field('routes_destination_point', $post_id);

    $from_id   = is_array($from_field) ? ($from_field['value'] ?? '') : $from_field;
    $to_id     = is_array($to_field) ? ($to_field['value'] ?? '') : $to_field;

    // 1. Try to extract names from the post title first (matches SEO terms like "Sài Gòn" instead of "Hồ Chí Minh")
    $from_name = '';
    $to_name   = '';
    $title     = get_the_title($post_id);
    if (preg_match('/(?:từ\s+)(.+?)\s+đi\s+(.+)/iu', $title, $m)) {
        $from_name = trim($m[1]);
        $to_name   = trim($m[2]);
    } elseif (preg_match('/(.+?)\s+đi\s+(.+)/iu', $title, $m)) {
        $from_name = trim($m[1]);
        $to_name   = trim($m[2]);
    }

    // Clean up title suffixes if any (e.g. "|Top 07 nhà xe tốt nhất")
    if (!empty($to_name)) {
        if (strpos($to_name, '|') !== false) {
            $parts = explode('|', $to_name);
            $to_name = trim($parts[0]);
        }
    }

    // 2. If title extraction did not yield names, fallback to ACF labels
    if (empty($from_name)) {
        $from_name = is_array($from_field) ? ($from_field['label'] ?? '') : '';
    }
    if (empty($to_name)) {
        $to_name = is_array($to_field) ? ($to_field['label'] ?? '') : '';
    }

    // 3. Map Hồ Chí Minh and city aliases to canonical API terms (e.g. Sài Gòn)
    $city_normalization = [
        'Hồ Chí Minh' => 'Sài Gòn',
        'TP.HCM'      => 'Sài Gòn',
        'TP HCM'      => 'Sài Gòn',
        'HCM'         => 'Sài Gòn',
    ];
    if (isset($city_normalization[$from_name])) {
        $from_name = $city_normalization[$from_name];
    }
    if (isset($city_normalization[$to_name])) {
        $to_name = $city_normalization[$to_name];
    }

    // Fetch operators data from v2 API (Cached for 1 hour via transients)
    $operators_data = [];
    if (!empty($from_name) && !empty($to_name) && function_exists('dailyve_get_operators_by_route')) {
        $result = dailyve_get_operators_by_route($from_name, $to_name, $from_id, $to_id);
        if (!is_wp_error($result)) {
            $operators_data = $result;
        }
    }

    $operators = $operators_data['items'] ?? [];
    $total = $operators_data['total'] ?? 0;
    $totalRoutes = $operators_data['totalRoutes'] ?? 0;

    $operator_media_map = [];
    if (!empty($operators) && function_exists('ams_get_bulk_company_data')) {
        $company_lookup_items = [];
        foreach ($operators as $lookup_operator) {
            $lookup_company_id = trim((string) ($lookup_operator['operator_id'] ?? ''));
            if ($lookup_company_id !== '' && $lookup_company_id !== '0') {
                $company_lookup_items[] = ['company_id' => $lookup_company_id];
            }
        }

        if (!empty($company_lookup_items)) {
            $operator_media_map = ams_get_bulk_company_data($company_lookup_items);
        }
    }
  @endphp

  {{-- Breadcrumb --}}
  <x-breadcrumb :items="$breadcrumbs" preset="seo" />


  {{-- Route Header --}}
  <section class="route-seo-header">
    <div class="dailyve-container">
      <h1 class="route-seo-header__title">{!! $current_title !!}</h1>
    </div>
  </section>

  {{-- Search Form (React Interactive) --}}
  <section class="route-seo-search">
    <div class="dailyve-container">
      <div id="react-search-form"></div>
    </div>
  </section>

  {{-- Operator List (100% SSR to maximize SEO indexing and page load performance) --}}
  <section class="route-seo-operators">
    <div class="dailyve-container">
      @if (empty($operators))
        <div class="ol-empty">
          <div class="ol-empty__icon"><i class="fas fa-bus"></i></div>
          <h3>Chưa có nhà xe nào</h3>
          <p>Hiện tại chưa tìm thấy nhà xe nào chạy tuyến {{ $from_name }} → {{ $to_name }}. Vui lòng thử lại sau.</p>
        </div>
      @else
        <div class="ol-wrapper">
          <div class="ol-stats">
            <div class="ol-stats__badge">
              <i class="fas fa-check-circle"></i>
              <span>Dailyve Team</span>
            </div>
            <span class="ol-stats__text">
              Đặt mua vé xe <strong>{{ $total }} nhà xe</strong> từ {{ $from_name }} đi {{ $to_name }} chất lượng cao và giá vé ưu đãi nhất: <strong>{{ number_format($totalRoutes, 0, ',', '.') }} chuyến</strong> mỗi ngày
            </span>
          </div>

          <div class="ol-list">
            @foreach ($operators as $operator_index => $op)
              @php
                $route = $op['routes'][0] ?? [];
                $rating = (float) ($op['rating'] ?? 0);
                $review_count = (int) ($op['review_count'] ?? 0);
                $vehicles = $route['vehicle_type_details'] ?? [];
                $short_content = $op['short_content'] ?? '';
                $pickups = $route['pickup_points'] ?? [];
                $dropoffs = $route['dropoff_points'] ?? [];
                $duration = $route['travel_duration'] ?? '';
                $prices = $route['prices'] ?? [];
                $amenities = $route['amenities'] ?? [];
                $times = $route['scheduled_departure_times'] ?? [];
                $operator_id = trim((string) ($op['operator_id'] ?? ''));
                $operator_media = $operator_media_map[$operator_id] ?? [];
                $operator_thumb = $operator_media['thumbnail'] ?? '';
                $operator_gallery = $operator_media['gallery'] ?? [];
                $operator_images = [];

                $company_url = $operator_media['url'] ?? '';

                $normalize_company_image = function ($image) use ($op) {
                    $url = '';
                    $thumb = '';
                    $alt = $op['name'] ?? 'Nhà xe';
                    $caption = '';

                    if (is_array($image)) {
                        $url = $image['sizes']['large'] ?? $image['url'] ?? '';
                        $thumb = $image['sizes']['medium'] ?? $image['sizes']['thumbnail'] ?? $url;
                        $alt = $image['alt'] ?? $image['title'] ?? $alt;
                        $caption = $image['caption'] ?? '';
                    } elseif (is_numeric($image)) {
                        $attachment_id = (int) $image;
                        $url = wp_get_attachment_image_url($attachment_id, 'large') ?: wp_get_attachment_image_url($attachment_id, 'full');
                        $thumb = wp_get_attachment_image_url($attachment_id, 'medium') ?: $url;
                        $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?: get_the_title($attachment_id) ?: $alt;
                        $caption = wp_get_attachment_caption($attachment_id) ?: '';
                    } elseif (is_string($image)) {
                        $url = trim($image);
                        $thumb = $url;
                    }

                    if (is_string($url) && str_starts_with($url, '//')) {
                        $url = 'https:' . $url;
                    }
                    if (is_string($thumb) && str_starts_with($thumb, '//')) {
                        $thumb = 'https:' . $thumb;
                    }

                    if (empty($url)) {
                        return null;
                    }

                    return [
                        'url' => $url,
                        'thumb' => $thumb ?: $url,
                        'alt' => $alt,
                        'caption' => $caption,
                    ];
                };

                if (empty($operator_thumb) && !empty($operator_gallery)) {
                    $first_gallery_image = $operator_gallery[0] ?? '';
                    if (is_array($first_gallery_image)) {
                        $operator_thumb = $first_gallery_image['sizes']['medium'] ?? $first_gallery_image['url'] ?? '';
                    } elseif (is_numeric($first_gallery_image)) {
                        $operator_thumb = wp_get_attachment_image_url((int) $first_gallery_image, 'medium');
                    } elseif (is_string($first_gallery_image)) {
                        $operator_thumb = $first_gallery_image;
                    }
                }

                if (is_string($operator_thumb) && str_starts_with($operator_thumb, '//')) {
                    $operator_thumb = 'https:' . $operator_thumb;
                }
                $operator_thumb_is_placeholder = empty($operator_thumb);
                $operator_thumb = $operator_thumb ?: home_url('/wp-content/uploads/assets/images/logo-icon-f2.png');

                if (!empty($operator_gallery)) {
                    foreach ($operator_gallery as $gallery_image) {
                        $normalized_image = $normalize_company_image($gallery_image);
                        if ($normalized_image) {
                            $operator_images[] = $normalized_image;
                        }
                    }
                }

                if (empty($operator_images) && !$operator_thumb_is_placeholder) {
                    $operator_images[] = [
                        'url' => $operator_thumb,
                        'thumb' => $operator_thumb,
                        'alt' => $op['name'] ?? 'Nhà xe',
                        'caption' => '',
                    ];
                }

                $has_rating = $rating > 0;
                $visible_vehicles = $vehicles;
                $hidden_vehicle_count = 0;
                
                // Extract lowest price from prices string array
                $lowest_price = null;
                if (!empty($prices)) {
                    $prices_numeric = [];
                    foreach ($prices as $p) {
                        if (preg_match('/(\d[\d.]*)\s*(?:VN)?Đ/iu', $p, $m)) {
                            $prices_numeric[] = (int) str_replace('.', '', $m[1]);
                        }
                    }
                    if (!empty($prices_numeric)) {
                        $lowest_price = min($prices_numeric);
                    }
                }
                
                $booking_url = home_url('/dat-ve-truc-tuyen/') . '?from=' . urlencode($from_id ?: ($route['from_id'] ?? '')) . '&to=' . urlencode($to_id ?: ($route['to_id'] ?? '')) . '&nameFrom=' . urlencode($from_name) . '&nameTo=' . urlencode($to_name);
                
                $full_stars = floor($rating);
                $half_star = ($rating - $full_stars) >= 0.3;
                
                $rating_color = '#dc2626';
                if ($rating >= 4.5) $rating_color = '#16a34a';
                elseif ($rating >= 3.5) $rating_color = '#2196F3';
                elseif ($rating >= 2.5) $rating_color = '#d97706';
              @endphp
              
              <div class="ol-card">
                <div class="ol-card__main">
                  <div class="ol-card__media{{ $operator_thumb_is_placeholder ? ' ol-card__media--placeholder' : '' }}">
                    <img src="{{ esc_url($operator_thumb) }}" alt="{{ esc_attr($op['name']) }}" loading="lazy" decoding="async" />
                    <div class="ol-card__instant">
                      <i class="fas fa-check"></i>
                      <span>Xác nhận tức thì</span>
                    </div>
                  </div>

                  <div class="ol-card__info">
                    <div class="ol-card__header">
                      <h3 class="ol-card__name">
                        @if (!empty($company_url))
                          <a href="{{ esc_url($company_url) }}" target="_blank" rel="noopener noreferrer" class="ol-card__name-link">
                            {{ $op['name'] }}
                          </a>
                        @else
                          {{ $op['name'] }}
                        @endif
                      </h3>
                      @if ($has_rating)
                        <div class="ol-card__rating" style="color: {{ $rating_color }}">
                          <span class="ol-card__rating-value">{{ number_format($rating, 1) }}</span>
                          <div class="ol-card__stars">
                            @for ($i = 0; $i < 5; $i++)
                              @if ($i < $full_stars)
                                <i class="fas fa-star"></i>
                              @elseif ($i == $full_stars && $half_star)
                                <i class="fas fa-star-half-alt"></i>
                              @else
                                <i class="far fa-star"></i>
                              @endif
                            @endfor
                          </div>
                          <span class="ol-card__reviews">({{ number_format($review_count, 0, ',', '.') }} đánh giá)</span>
                        </div>
                      @else
                        <div class="ol-card__rating ol-card__rating--empty">
                          <i class="far fa-star"></i>
                          <span>Chưa có đánh giá</span>
                        </div>
                      @endif
                    </div>

                    <!-- Vehicle type tags -->
                    @if (!empty($vehicles))
                      <div class="ol-card__vehicles">
                        @foreach ($visible_vehicles as $v)
                          <span class="ol-card__vehicle-badge">{{ $v['name'] ?? $v }}</span>
                        @endforeach
                        @if ($hidden_vehicle_count > 0)
                          <span class="ol-card__vehicle-more">+{{ $hidden_vehicle_count }} loại xe khác</span>
                        @endif
                      </div>
                    @endif

                    <!-- Terminal point visual timeline -->
                    @if (!empty($pickups) || !empty($dropoffs))
                      <div class="ol-card__stations">
                        @if (!empty($pickups))
                          <div class="ol-card__station">
                            <span class="ol-card__station-dot ol-card__station-dot--pickup"></span>
                            <span>{{ $pickups[0] }}</span>
                          </div>
                        @endif
                        @if (!empty($duration))
                          <div class="ol-card__duration">
                            <span>{{ $duration }}</span>
                          </div>
                        @endif
                        @if (!empty($dropoffs))
                          <div class="ol-card__station">
                            <span class="ol-card__station-dot ol-card__station-dot--dropoff"></span>
                            <span>{{ $dropoffs[0] }}</span>
                          </div>
                        @endif
                      </div>
                    @endif

                    <!-- Departure times list summary -->
                    @if (!empty($times))
                      <div class="ol-card__times">
                        <i class="far fa-clock"></i>
                        <span>Giờ chạy: {{ implode(', ', array_slice($times, 0, 5)) }}{{ count($times) > 5 ? ' (+' . (count($times) - 5) . ')' : '' }}</span>
                      </div>
                    @endif
                  </div>

                  <div class="ol-card__right">
                    @if (!empty($short_content))
                      @php
                        $truncated = mb_strlen($short_content) > 180 ? mb_substr($short_content, 0, 180) . '...' : $short_content;
                      @endphp
                      <div class="ol-card__review">
                        <span class="ol-card__review-quote">"</span>
                        <p>{{ $truncated }}</p>
                      </div>
                    @else
                      <div class="ol-card__review ol-card__review--empty">
                        <span class="ol-card__review-quote">"</span>
                        <p>Dailyve đang cập nhật thêm đánh giá thực tế cho nhà xe này.</p>
                      </div>
                    @endif

                    <div class="ol-card__cta">
                      @if ($lowest_price)
                        <div class="ol-card__price">
                          <span>Giá chỉ từ</span>
                          <strong>{{ number_format($lowest_price, 0, ',', '.') }}đ</strong>
                        </div>
                      @endif
                      @php
                        $card_booking_url = $booking_url;
                        if (!empty($operator_id)) {
                            $card_booking_url .= '&operator_id=' . urlencode($operator_id);
                        }
                      @endphp
                      <a
                        href="{!! esc_url($card_booking_url) !!}"
                        class="ol-card__btn"
                        target="_blank"
                        rel="noopener noreferrer"
                        data-dailyve-date-range-trigger
                        data-date-range-url="{!! esc_url($card_booking_url) !!}"
                        data-date-range-from-name="{{ esc_attr($from_name) }}"
                        data-date-range-to-name="{{ esc_attr($to_name) }}"
                        data-date-range-service="bus"
                        data-date-range-min="today"
                      >
                        Xem giá
                      </a>
                    </div>
                  </div>
                </div>

                <details class="ol-card__accordion">
                  <summary class="ol-card__toggle">
                    <span>Thông tin chi tiết</span>
                    <i class="fas fa-chevron-down"></i>
                  </summary>
                  
                  <div class="ol-card__detail">
                    <!-- Tabbed Navigation -->
                    <ul class="ol-card__tabs-nav">
                      <li class="ol-card__tabs-nav-item active" data-tab="images-{{ $operator_index }}">
                        <i class="far fa-images"></i> Hình ảnh
                      </li>
                      <li class="ol-card__tabs-nav-item" data-tab="amenities-{{ $operator_index }}">
                        <i class="fas fa-wifi"></i> Tiện ích
                      </li>
                      <li class="ol-card__tabs-nav-item" data-tab="stations-{{ $operator_index }}">
                        <i class="fas fa-map-marker-alt"></i> Đón/trả
                      </li>
                      <li class="ol-card__tabs-nav-item" data-tab="policy-{{ $operator_index }}">
                        <i class="fas fa-info-circle"></i> Chính sách
                      </li>
                      <li class="ol-card__tabs-nav-item" data-tab="reviews-{{ $operator_index }}">
                        <i class="fas fa-star"></i> Đánh giá
                      </li>
                    </ul>

                    <div class="ol-card__tabs-content">
                      <!-- Tab Pane: Hình ảnh -->
                      <div class="ol-card__tab-pane active" id="images-{{ $operator_index }}">
                        @if (!empty($operator_images))
                          <div class="ol-card__slider">
                            <div class="ol-card__slider-track" id="slider-track-{{ $operator_index }}" onscroll="handleSliderScroll({{ $operator_index }})">
                              @foreach ($operator_images as $img_idx => $image)
                                <div class="ol-card__slider-slide" data-index="{{ $img_idx }}">
                                  <img src="{{ esc_url($image['url']) }}" alt="{{ esc_attr($image['alt']) }}" loading="lazy" decoding="async" />
                                  @if (!empty($image['caption']))
                                    <div class="ol-card__slider-caption">{{ $image['caption'] }}</div>
                                  @endif
                                </div>
                              @endforeach
                            </div>
                            @if (count($operator_images) > 1)
                              <button type="button" class="ol-card__slider-btn ol-card__slider-btn--prev" onclick="slidePrev({{ $operator_index }})" aria-label="Slide trước">
                                <i class="fas fa-chevron-left"></i>
                              </button>
                              <button type="button" class="ol-card__slider-btn ol-card__slider-btn--next" onclick="slideNext({{ $operator_index }})" aria-label="Slide tiếp theo">
                                <i class="fas fa-chevron-right"></i>
                              </button>
                              <div class="ol-card__slider-counter" id="slider-counter-{{ $operator_index }}">
                                <span class="current">1</span>/<span class="total">{{ count($operator_images) }}</span>
                              </div>
                            @endif
                          </div>

                          @if (count($operator_images) > 1)
                            <div class="ol-card__thumbnails" id="slider-thumbnails-{{ $operator_index }}">
                              @foreach ($operator_images as $img_idx => $image)
                                <button type="button" class="ol-card__thumbnail {{ $img_idx === 0 ? 'active' : '' }}" onclick="goToSlide({{ $operator_index }}, {{ $img_idx }})" aria-label="Xem ảnh {{ $img_idx + 1 }}">
                                  <img src="{{ esc_url($image['thumb'] ?? $image['url']) }}" alt="{{ esc_attr($image['alt']) }}" loading="lazy" />
                                </button>
                              @endforeach
                            </div>
                          @endif
                        @else
                          <div class="ol-card__gallery-empty">
                            <p>Hình ảnh nhà xe đang được cập nhật.</p>
                          </div>
                        @endif
                      </div>

                      <!-- Tab Pane: Tiện ích -->
                      <div class="ol-card__tab-pane" id="amenities-{{ $operator_index }}">
                        @if (!empty($amenities))
                          @php
                            $amenities_with_desc = [];
                            $amenities_no_desc = [];
                            foreach ($amenities as $a) {
                                if (!empty($a['description'])) {
                                    $amenities_with_desc[] = $a;
                                } else {
                                    $amenities_no_desc[] = $a;
                                }
                            }
                          @endphp

                          <div class="ol-card__amenities">
                            {{-- Utilities with descriptions: detailed vertical list --}}
                            @if (!empty($amenities_with_desc))
                              <div class="ol-card__amenities-detailed">
                                @foreach ($amenities_with_desc as $a)
                                  @php
                                    $img = $a['image'] ?? '';
                                    if (str_starts_with($img, '//')) {
                                        $img = 'https:' . $img;
                                    }
                                  @endphp
                                  <div class="ol-card__amenity-detailed-item">
                                    <div class="ol-card__amenity-detailed-header">
                                      @if ($img)
                                        <img class="ol-card__amenity-detailed-icon" rel="nofollow noreferrer" src="{{ esc_url($img) }}" alt="{{ esc_attr($a['title'] ?? '') }}" loading="lazy" />
                                      @endif
                                      <span class="ol-card__amenity-detailed-title">{{ $a['title'] ?? '' }}</span>
                                    </div>
                                    <div class="ol-card__amenity-detailed-desc">
                                      {{ $a['description'] }}
                                    </div>
                                  </div>
                                @endforeach
                              </div>
                            @endif

                            {{-- Utilities without descriptions: responsive grid --}}
                            @if (!empty($amenities_no_desc))
                              <div class="ol-card__amenities-simple-grid">
                                @foreach ($amenities_no_desc as $a)
                                  @php
                                    $img = $a['image'] ?? '';
                                    if (str_starts_with($img, '//')) {
                                        $img = 'https:' . $img;
                                    }
                                  @endphp
                                  <div class="ol-card__amenity-simple" title="{{ $a['title'] ?? '' }}">
                                    @if ($img)
                                      <img class="ol-card__amenity-simple-icon" src="{{ esc_url($img) }}" alt="{{ esc_attr($a['title'] ?? '') }}" loading="lazy" />
                                    @endif
                                    <span class="ol-card__amenity-simple-title">{{ $a['title'] ?? '' }}</span>
                                  </div>
                                @endforeach
                              </div>
                            @endif
                          </div>
                        @else
                          <div class="ol-card__amenities-empty">
                            <p>Tiện ích nhà xe đang được cập nhật.</p>
                          </div>
                        @endif
                      </div>

                      <!-- Tab Pane: Điểm đón, trả -->
                      <div class="ol-card__tab-pane" id="stations-{{ $operator_index }}">
                        <div class="ol-card__all-stations">
                          @if (!empty($pickups))
                            <div>
                              <h4><i class="fas fa-map-marker-alt" style="color: #2196F3;"></i> Điểm đón ({{ count($pickups) }})</h4>
                              <ul>
                                @foreach ($pickups as $p)
                                  <li>{{ $p }}</li>
                                @endforeach
                              </ul>
                            </div>
                          @endif
                          @if (!empty($dropoffs))
                            <div>
                              <h4><i class="fas fa-map-marker-alt" style="color: #ef4444;"></i> Điểm trả ({{ count($dropoffs) }})</h4>
                              <ul>
                                @foreach ($dropoffs as $d)
                                  <li>{{ $d }}</li>
                                @endforeach
                              </ul>
                            </div>
                          @endif
                        </div>
                        
                        @if (!empty($prices))
                          <div class="ol-card__prices" style="margin-top: 24px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
                            <h4 style="margin-bottom: 12px; font-weight: 600;"><i class="fas fa-tags" style="color: #d97706; margin-right: 8px;"></i> Bảng giá vé tham khảo</h4>
                            <div class="ol-card__price-list">
                              @foreach ($prices as $pr)
                                <span class="ol-card__price-item">{{ $pr }}</span>
                              @endforeach
                            </div>
                          </div>
                        @endif
                      </div>

                      <!-- Tab Pane: Chính sách -->
                      <div class="ol-card__tab-pane" id="policy-{{ $operator_index }}">
                        @if (!empty($op['policy']))
                          <div class="ol-card__policy">
                            {!! nl2br(esc_html($op['policy'])) !!}
                          </div>
                        @else
                          <div class="ol-card__policy-empty">
                            <p>Chính sách nhà xe đang được cập nhật.</p>
                          </div>
                        @endif
                      </div>

                      <!-- Tab Pane: Đánh giá -->
                      <div class="ol-card__tab-pane" id="reviews-{{ $operator_index }}">
                        <div class="ol-card__rating-summary">
                          <div class="ol-card__rating-big">
                            <span class="ol-card__rating-score">{{ number_format($rating, 1) }}</span>
                            <span class="ol-card__rating-max">/5</span>
                          </div>
                          <div class="ol-card__rating-meta">
                            <div class="ol-card__rating-stars" style="color: {{ $rating_color }}; font-size: 1.1rem; margin-bottom: 4px;">
                              @for ($i = 0; $i < 5; $i++)
                                @if ($i < $full_stars)
                                  <i class="fas fa-star"></i>
                                @elseif ($i == $full_stars && $half_star)
                                  <i class="fas fa-star-half-alt"></i>
                                @else
                                  <i class="far fa-star"></i>
                                @endif
                              @endfor
                            </div>
                            <p class="ol-card__rating-count" style="color: #6b7280; font-size: 0.9rem; margin: 0;">
                              ({{ number_format($review_count, 0, ',', '.') }} đánh giá thực tế từ khách hàng)
                            </p>
                          </div>
                        </div>
                        
                        <div class="ol-card__rating-notes" style="margin-top: 20px; background: #f9fafb; padding: 16px; border-radius: 8px; border-left: 4px solid #10b981;">
                          <h4 style="margin: 0 0 6px 0; font-size: 0.95rem; font-weight: 600; color: #111827;">Cam kết từ Dailyve</h4>
                          <p style="margin: 0; font-size: 0.85rem; color: #4b5563; line-height: 1.5;">Toàn bộ đánh giá được tổng hợp tự động từ trải nghiệm thực tế của khách hàng đã đi xe, đảm bảo khách quan và chân thực nhất.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </details>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </section>

  {{-- Article Content (from WP editor) --}}
  @php
    $content = apply_filters('the_content', get_the_content(null, false, $post_id));
  @endphp
  @if (!empty(trim(strip_tags($content))))
    <section class="route-seo-content">
      <div class="dailyve-container">
        <div class="route-seo-article">
          {!! $content !!}
        </div>
      </div>
    </section>
  @endif

  {{-- Q&A Section --}}
  @php
    $qa_items = function_exists('get_field') ? get_field('list_question_answer', $post_id) : null;
  @endphp
  @if (!empty($qa_items))
    <section class="route-seo-qa">
      <div class="dailyve-container">
        <h2 class="route-seo-qa__title">Câu hỏi thường gặp về {{ $current_title }}</h2>
        <div class="route-qa-accordion">
          @foreach ($qa_items as $index => $item)
            <details class="route-qa-item" {{ $index === 0 ? 'open' : '' }}>
              <summary>
                <span>{{ $item['route_question'] }}</span>
                <i class="fas fa-chevron-down"></i>
              </summary>
              <div class="route-qa-answer">
                {!! $item['route_answer'] !!}
              </div>
            </details>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <script>
    function goToSlide(opIndex, slideIdx) {
      var track = document.getElementById('slider-track-' + opIndex);
      if (!track) return;
      var firstSlide = track.querySelector('.ol-card__slider-slide');
      if (!firstSlide) return;
      var slideWidth = firstSlide.offsetWidth;
      track.scrollTo({
        left: slideIdx * slideWidth,
        behavior: 'smooth'
      });
      updateSliderUI(opIndex, slideIdx);
    }

    function slidePrev(opIndex) {
      var track = document.getElementById('slider-track-' + opIndex);
      if (!track) return;
      var firstSlide = track.querySelector('.ol-card__slider-slide');
      if (!firstSlide) return;
      var slideWidth = firstSlide.offsetWidth;
      var currentIndex = Math.round(track.scrollLeft / slideWidth);
      var nextIndex = Math.max(0, currentIndex - 1);
      goToSlide(opIndex, nextIndex);
    }

    function slideNext(opIndex) {
      var track = document.getElementById('slider-track-' + opIndex);
      if (!track) return;
      var firstSlide = track.querySelector('.ol-card__slider-slide');
      if (!firstSlide) return;
      var slideWidth = firstSlide.offsetWidth;
      var totalSlides = track.querySelectorAll('.ol-card__slider-slide').length;
      var currentIndex = Math.round(track.scrollLeft / slideWidth);
      var nextIndex = Math.min(totalSlides - 1, currentIndex + 1);
      goToSlide(opIndex, nextIndex);
    }

    function updateSliderUI(opIndex, activeIndex) {
      // Update page counter text
      var counterVal = document.querySelector('#slider-counter-' + opIndex + ' .current');
      if (counterVal) {
        counterVal.textContent = activeIndex + 1;
      }
      
      // Highlight active thumbnail and scroll it into view if overflowed
      var thumbsContainer = document.getElementById('slider-thumbnails-' + opIndex);
      if (thumbsContainer) {
        var thumbs = thumbsContainer.querySelectorAll('.ol-card__thumbnail');
        thumbs.forEach(function(thumb, idx) {
          if (idx === activeIndex) {
            thumb.classList.add('active');
            var containerWidth = thumbsContainer.offsetWidth;
            var thumbLeft = thumb.offsetLeft;
            var thumbWidth = thumb.offsetWidth;
            thumbsContainer.scrollTo({
              left: thumbLeft - (containerWidth / 2) + (thumbWidth / 2),
              behavior: 'smooth'
            });
          } else {
            thumb.classList.remove('active');
          }
        });
      }
    }

    var scrollTimeouts = {};
    function handleSliderScroll(opIndex) {
      clearTimeout(scrollTimeouts[opIndex]);
      scrollTimeouts[opIndex] = setTimeout(function() {
        var track = document.getElementById('slider-track-' + opIndex);
        if (!track) return;
        var firstSlide = track.querySelector('.ol-card__slider-slide');
        if (!firstSlide) return;
        var slideWidth = firstSlide.offsetWidth;
        if (slideWidth > 0) {
          var activeIndex = Math.round(track.scrollLeft / slideWidth);
          updateSliderUI(opIndex, activeIndex);
        }
      }, 100);
    }

    document.addEventListener('DOMContentLoaded', function() {
      try {
        var currentRoute = {!! wp_json_encode([
          'id' => $post_id,
          'title' => get_the_title($post_id),
          'url' => get_permalink($post_id),
          'image' => get_post_thumbnail_id($post_id) ? wp_get_attachment_image_url(get_post_thumbnail_id($post_id), 'medium') : '',
          'price' => function_exists('get_field') && get_field('routes_price', $post_id) ? number_format((float) get_field('routes_price', $post_id), 0, ',', '.') . 'đ' : '',
          'distance' => function_exists('get_field') ? (get_field('routes_distance', $post_id) ?: '') : '',
          'time' => function_exists('get_field') ? (get_field('routes_time', $post_id) ?: '') : '',
          'viewedAt' => 0,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        currentRoute.viewedAt = Date.now();
        var storageKey = 'dailyve:viewed-routes';
        var viewedRoutes = JSON.parse(localStorage.getItem(storageKey) || '[]');
        if (!Array.isArray(viewedRoutes)) viewedRoutes = [];
        viewedRoutes = viewedRoutes.filter(function(item) {
          return String(item.id || item.url) !== String(currentRoute.id || currentRoute.url);
        });
        viewedRoutes.unshift(currentRoute);
        localStorage.setItem(storageKey, JSON.stringify(viewedRoutes.slice(0, 12)));
      } catch (error) {}

      // Global click handler for tabs inside .ol-card
      document.addEventListener('click', function(e) {
        var tabItem = e.target.closest('.ol-card__tabs-nav-item');
        if (!tabItem) return;

        var tabId = tabItem.getAttribute('data-tab');
        var card = tabItem.closest('.ol-card');
        if (!card) return;

        // Deactivate all tab nav items in this card
        card.querySelectorAll('.ol-card__tabs-nav-item').forEach(function(el) {
          el.classList.remove('active');
        });
        // Deactivate all tab panes in this card
        card.querySelectorAll('.ol-card__tab-pane').forEach(function(el) {
          el.classList.remove('active');
        });

        // Activate selected tab nav item
        tabItem.classList.add('active');
        // Activate selected tab pane
        var targetPane = card.querySelector('#' + tabId);
        if (targetPane) {
          targetPane.classList.add('active');
        }
      });
    });
  </script>
@endsection


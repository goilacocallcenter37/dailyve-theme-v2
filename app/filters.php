<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

const DAILYVE_HOME_HERO_BACKGROUND_FALLBACK = 'https://object.dailyve.com/dailyve/wp-content/uploads/2026/05/banner_web.webp';

function dailyve_image_url_from_acf_value($value): string
{
    if (is_array($value)) {
        $url = $value['url'] ?? $value['sizes']['full'] ?? '';
        return is_string($url) ? esc_url_raw($url) : '';
    }

    if (is_numeric($value)) {
        return esc_url_raw(wp_get_attachment_image_url((int) $value, 'full') ?: '');
    }

    return is_string($value) ? esc_url_raw($value) : '';
}

function dailyve_home_hero_background_url(): string
{
    static $background_url = null;

    if ($background_url !== null) {
        return $background_url;
    }

    $front_page_id = (int) get_option('page_on_front');
    $post_id = $front_page_id ?: (int) get_queried_object_id();
    $field_names = [
        'home_hero_background',
        'home_hero_background_image',
        'homepage_hero_background',
        'hero_background_image',
        'hero_background',
        'banner_web',
    ];

    foreach ($field_names as $field_name) {
        $field_value = function_exists('get_field')
            ? get_field($field_name, $post_id)
            : get_post_meta($post_id, $field_name, true);

        $url = dailyve_image_url_from_acf_value($field_value);
        if ($url !== '') {
            $background_url = $url;
            return $background_url;
        }
    }

    $background_url = DAILYVE_HOME_HERO_BACKGROUND_FALLBACK;
    return $background_url;
}

add_action('wp_head', function () {
    if (! is_front_page()) {
        return;
    }

    $background_url = dailyve_home_hero_background_url();
    if ($background_url === '') {
        return;
    }

    $scheme = wp_parse_url($background_url, PHP_URL_SCHEME);
    $host = wp_parse_url($background_url, PHP_URL_HOST);
    $origin = ($scheme && $host) ? $scheme . '://' . $host : '';

    if ($origin && $host !== wp_parse_url(home_url(), PHP_URL_HOST)) {
        echo '<link rel="preconnect" href="' . esc_url($origin) . '" crossorigin>' . "\n";
    }

    $file_type = wp_check_filetype(wp_parse_url($background_url, PHP_URL_PATH) ?: '');
    $type_attr = ! empty($file_type['type']) ? ' type="' . esc_attr($file_type['type']) . '"' : '';

    echo '<link rel="preload" as="image" href="' . esc_url($background_url) . '"' . $type_attr . ' fetchpriority="high">' . "\n";
}, 1);

/**
 * Add JSON-LD Schema to head
 */
add_action('wp_head', function () {
    if (is_single()) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author(),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => get_site_icon_url(),
                ],
            ],
        ];
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
});

/**
 * Remove Gutenberg default styles to improve performance (LCP)
 * (Commented out to prevent breaking block layouts like columns/galleries in single posts)
 */
add_action('wp_enqueue_scripts', function () {
    // wp_dequeue_style('wp-block-library');
    // wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style');
}, 100);

/**
 * Auto-select page-tuyen-duong-seo.blade.php template for child pages of "Vé xe khách > Tuyến đường".
 * Parent page ID = 15738.
 * Auto-select page-transit-route.blade.php template for child pages of "Vé máy bay" (16844) and "Vé tàu hỏa" (16846).
 */
add_filter('page_template_hierarchy', function ($templates) {
    $post_id = get_the_ID();
    if ($post_id) {
        $parent_id = wp_get_post_parent_id($post_id);
        if ((int) $parent_id === 15738) {
            array_unshift($templates, 'page-tuyen-duong-seo.php');
        } elseif (dailyve_is_operator_detail_post($post_id)) {
            array_unshift($templates, 'page-operator-detail.php');
        } elseif ((int) $parent_id === 16844 || (int) $parent_id === 16846) {
            array_unshift($templates, 'page-transit-route.php');
        } elseif ((int) $parent_id === 15896) {
            array_unshift($templates, 'page-ben-xe-detail.php');
        }
    }
    return $templates;
}, 5);

/**
 * Get centralized mock data for discount codes / coupons.
 *
 * @return array
 */
function dailyve_get_mock_coupons(): array
{
    return [
        [
            'code'     => 'DLVNEW10',
            'discount' => '10%',
            'title'    => 'Giảm 10%',
            'label'    => 'Cho Người Mới',
            'meta'     => 'Cho khách mới',
            'icon'     => 'fa-percent'
        ],
        [
            'code'     => 'DAILY50',
            'discount' => '50K',
            'title'    => 'Giảm 50K',
            'label'    => 'Vé xe khách',
            'meta'     => 'Vé xe khách',
            'icon'     => 'fa-ticket-alt'
        ],
        [
            'code'     => 'CASHBACK10',
            'discount' => '10%',
            'title'    => 'Cashback 10%',
            'label'    => 'Qua ví Dailyve',
            'meta'     => 'Qua ví Dailyve',
            'icon'     => 'fa-wallet'
        ],
        [
            'code'     => 'TAU30',
            'discount' => '30K',
            'title'    => 'Giảm 30K',
            'label'    => 'Vé tàu hỏa',
            'meta'     => 'Vé tàu hỏa',
            'icon'     => 'fa-train'
        ],
        [
            'code'     => 'MAYBAY100',
            'discount' => '100K',
            'title'    => 'Giảm 100K',
            'label'    => 'Vé máy bay',
            'meta'     => 'Vé máy bay',
            'icon'     => 'fa-plane'
        ],
        [
            'code'     => 'KS15',
            'discount' => '15%',
            'title'    => 'Giảm 15%',
            'label'    => 'Khách sạn',
            'meta'     => 'Khách sạn',
            'icon'     => 'fa-hotel'
        ],
        [
            'code'     => 'DLV20',
            'discount' => '20K',
            'title'    => 'Giảm 20K',
            'label'    => 'Đơn từ 200K',
            'meta'     => 'Đơn từ 200K',
            'icon'     => 'fa-money-bill-wave'
        ],
        [
            'code'     => 'SVWEEKEND',
            'discount' => '15K',
            'title'    => 'Ưu đãi sinh viên',
            'label'    => 'Cuối tuần',
            'meta'     => 'Cuối tuần',
            'icon'     => 'fa-graduation-cap'
        ],
    ];
}

function dailyve_is_operator_detail_post($post_id = null): bool
{
    $post_id = $post_id ? absint($post_id) : absint(get_the_ID());
    if (!$post_id || get_post_type($post_id) !== 'page') {
        return false;
    }

    $operator_parent_id = 15764;
    if ($post_id === $operator_parent_id) {
        return false;
    }

    $parent_id = absint(wp_get_post_parent_id($post_id));
    if ($parent_id === $operator_parent_id) {
        return true;
    }

    return in_array($operator_parent_id, array_map('absint', get_post_ancestors($post_id)), true);
}

function dailyve_get_operator_detail(int $post_id)
{
    $post_id = absint($post_id);
    if (!$post_id) {
        return new \WP_Error('dailyve_operator_invalid_post', 'Post ID không hợp lệ.');
    }

    $cache_key = 'dv_operator_detail_v2_' . $post_id . '_' . md5((string) get_post_modified_time('U', true, $post_id));
    $cached = get_transient($cache_key);
    if ($cached !== false) {
        return $cached;
    }

    if (!function_exists('call_api_v2')) {
        return new \WP_Error('dailyve_operator_api_missing', 'Thiếu hàm call_api_v2.');
    }

    $response = \call_api_v2('/operators/' . $post_id, 'GET', [
        'siteKey' => 'dailyve',
        'includeRoutes' => 'true',
        'includeReviews' => 'true',
        'minimal' => 'true',
        'provinceRoutesOnly' => 'true',
        'use_wp_post_id' => 'true',
    ], [], 30);

    if (is_wp_error($response)) {
        return $response;
    }

    $status_code = (int) wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($status_code >= 400) {
        return new \WP_Error('dailyve_operator_api_http', 'API chi tiết nhà xe trả lỗi HTTP ' . $status_code . '.');
    }

    if (isset($data['data']) && is_array($data['data'])) {
        $data = $data['data'];
    }

    if (!is_array($data) || empty($data['name'])) {
        return new \WP_Error('dailyve_operator_api_parse', 'Không thể phân tích dữ liệu chi tiết nhà xe.');
    }

    set_transient($cache_key, $data, DAY_IN_SECONDS);

    return $data;
}


/**
 * Inject route_data into window for React OperatorList component on route SEO pages.
 */
add_action('wp_head', function () {
    if (!is_page()) {
        return;
    }

    $post_id = get_the_ID();
    $parent_id = wp_get_post_parent_id($post_id);

    if ((int) $parent_id !== 15738) {
        return;
    }

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
    
    $from_name = preg_replace('/^vé\s+xe\s+khách\s+/iu', '', $from_name);

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

    // Fetch operators from API v2
    $operators_data = [];
    if (!empty($from_name) && !empty($to_name) && function_exists('dailyve_get_operators_by_route')) {
        $result = dailyve_get_operators_by_route($from_name, $to_name, $from_id, $to_id);
        if (!is_wp_error($result)) {
            $operators_data = $result;
        }
    }

    $route_data = [
        'from_name'  => $from_name,
        'to_name'    => $to_name,
        'from_id'    => $from_id,
        'to_id'      => $to_id,
        'title'      => get_the_title($post_id),
        'operators'  => $operators_data['items'] ?? [],
        'total'      => $operators_data['total'] ?? 0,
        'totalRoutes' => $operators_data['totalRoutes'] ?? 0,
    ];

    echo '<script>window.route_data = ' . wp_json_encode($route_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ';</script>' . "\n";
}, 5);

/**
 * SEO metadata and structured data for the route directory page.
 */
function dailyve_is_route_directory_page(): bool
{
    return is_page(15738);
}

function dailyve_route_directory_paged(): int
{
    return max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 1));
}

function dailyve_route_directory_title(): string
{
    $paged = dailyve_route_directory_paged();
    $title = 'Danh sách tuyến đường xe khách | Dailyve';

    return $paged > 1 ? $title . ' - Trang ' . $paged : $title;
}

function dailyve_route_directory_description(): string
{
    $paged = dailyve_route_directory_paged();
    $description = 'Tổng hợp các tuyến xe khách phổ biến tại Dailyve: giá vé tham khảo, thời gian di chuyển, quãng đường và trang chi tiết để đặt vé trực tuyến nhanh chóng.';

    return $paged > 1 ? $description . ' Trang ' . $paged . ' của danh sách tuyến đường.' : $description;
}

function dailyve_route_directory_canonical(): string
{
    $base = trailingslashit(get_permalink(15738));
    $paged = dailyve_route_directory_paged();

    return $paged > 1 ? $base . 'page/' . $paged . '/' : $base;
}

add_filter('rank_math/frontend/title', function ($title) {
    return dailyve_is_route_directory_page() ? dailyve_route_directory_title() : $title;
});

add_filter('rank_math/frontend/description', function ($description) {
    return dailyve_is_route_directory_page() ? dailyve_route_directory_description() : $description;
});

add_filter('rank_math/frontend/canonical', function ($canonical) {
    return dailyve_is_route_directory_page() ? dailyve_route_directory_canonical() : $canonical;
});

add_filter('rank_math/opengraph/facebook/title', function ($title) {
    return dailyve_is_route_directory_page() ? dailyve_route_directory_title() : $title;
});

add_filter('rank_math/opengraph/facebook/description', function ($description) {
    return dailyve_is_route_directory_page() ? dailyve_route_directory_description() : $description;
});

add_filter('rank_math/opengraph/twitter/title', function ($title) {
    return dailyve_is_route_directory_page() ? dailyve_route_directory_title() : $title;
});

add_filter('rank_math/opengraph/twitter/description', function ($description) {
    return dailyve_is_route_directory_page() ? dailyve_route_directory_description() : $description;
});

add_filter('document_title_parts', function ($parts) {
    if (dailyve_is_route_directory_page()) {
        $parts['title'] = dailyve_route_directory_title();
        unset($parts['site']);
    }

    return $parts;
});

add_action('wp_head', function () {
    if (!dailyve_is_route_directory_page()) {
        return;
    }

    $paged = dailyve_route_directory_paged();
    $routes = new \WP_Query([
        'post_type' => 'page',
        'post_parent' => 15738,
        'posts_per_page' => 9,
        'paged' => $paged,
        'orderby' => 'ID',
        'order' => 'DESC',
        'post_status' => 'publish',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    $items = [];
    $position = (($paged - 1) * 9) + 1;
    foreach ($routes->posts as $route_post) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'url' => get_permalink($route_post),
            'name' => get_the_title($route_post),
        ];
    }
    wp_reset_postdata();

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        '@id' => dailyve_route_directory_canonical() . '#webpage',
        'url' => dailyve_route_directory_canonical(),
        'name' => dailyve_route_directory_title(),
        'description' => dailyve_route_directory_description(),
        'isPartOf' => [
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
        ],
        'mainEntity' => [
            '@type' => 'ItemList',
            'name' => 'Danh sách tuyến đường xe khách Dailyve',
            'itemListElement' => $items,
        ],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}, 20);

/**
 * Fetch route groups for a specific station from API v2.
 * Uses transient caching to improve loading speeds and optimize performance.
 */
function dailyve_get_station_routes(string $location_id, int $paged = 1, int $page_size = 6)
{
    $location_id = sanitize_text_field($location_id);
    $paged = max(1, (int) $paged);
    $page_size = max(1, (int) $page_size);

    if (empty($location_id)) {
        return new \WP_Error('dailyve_station_invalid_id', 'Thiếu Location ID của bến xe.');
    }

    $cache_key = 'dv_station_routes_sum_' . md5($location_id . '_' . $paged . '_' . $page_size);
    $cached = get_transient($cache_key);
    if ($cached !== false) {
        return $cached;
    }

    if (!function_exists('call_api_v2')) {
        return new \WP_Error('dailyve_station_api_missing', 'Thiếu hàm call_api_v2 từ hệ thống.');
    }

    $params = [
        'siteKey' => 'dailyve',
        'includeSubLocations' => 'true',
        'page' => $paged,
        'pageSize' => $page_size,
        'operatorLimit' => 10,
        'includeRoutes' => 'false',
        'groupByProvince' => 'true',
        'location_id' => $location_id,
    ];

    $response = \call_api_v2('/station-routes/summary', 'GET', $params, [], 30);

    if (is_wp_error($response)) {
        return $response;
    }

    $status_code = (int) wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($status_code >= 400) {
        return new \WP_Error('dailyve_station_api_http', 'API bến xe trả lỗi HTTP ' . $status_code . '.');
    }

    if (!is_array($data)) {
        return new \WP_Error('dailyve_station_api_parse', 'Không thể phân tích phản hồi từ máy chủ.');
    }

    // Cache results for 1 hour
    set_transient($cache_key, $data, DAY_IN_SECONDS);

    return $data;
}

add_action('wp_ajax_dailyve_get_station_routes', function() {
    $location_id = sanitize_text_field($_GET['location_id'] ?? '');
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $page_size = isset($_GET['page_size']) ? (int) $_GET['page_size'] : 30;
    $station_name = sanitize_text_field($_GET['station_name'] ?? '');

    $data = \App\dailyve_get_station_routes($location_id, $page, $page_size);
    if (is_wp_error($data)) {
        wp_send_json_error(['message' => $data->get_error_message()]);
    }

    if (is_array($data)) {
        if (isset($data['departing']['items']) && is_array($data['departing']['items'])) {
            foreach ($data['departing']['items'] as &$item) {
                $from_name = $item['from']['name'] ?? '';
                $to_name = $item['to']['name'] ?? '';
                $item['seo_url'] = function_exists('\App\dailyve_get_route_seo_url')
                    ? \App\dailyve_get_route_seo_url($from_name, $to_name, $station_name)
                    : '';
            }
            unset($item);
        }
        if (isset($data['arriving']['items']) && is_array($data['arriving']['items'])) {
            foreach ($data['arriving']['items'] as &$item) {
                $from_name = $item['from']['name'] ?? '';
                $to_name = $item['to']['name'] ?? '';
                $item['seo_url'] = function_exists('\App\dailyve_get_route_seo_url')
                    ? \App\dailyve_get_route_seo_url($from_name, $to_name, $station_name)
                    : '';
            }
            unset($item);
        }
    }

    wp_send_json_success($data);
});
add_action('wp_ajax_nopriv_dailyve_get_station_routes', function() {
    $location_id = sanitize_text_field($_GET['location_id'] ?? '');
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $page_size = isset($_GET['page_size']) ? (int) $_GET['page_size'] : 30;
    $station_name = sanitize_text_field($_GET['station_name'] ?? '');

    $data = \App\dailyve_get_station_routes($location_id, $page, $page_size);
    if (is_wp_error($data)) {
        wp_send_json_error(['message' => $data->get_error_message()]);
    }

    if (is_array($data)) {
        if (isset($data['departing']['items']) && is_array($data['departing']['items'])) {
            foreach ($data['departing']['items'] as &$item) {
                $from_name = $item['from']['name'] ?? '';
                $to_name = $item['to']['name'] ?? '';
                $item['seo_url'] = function_exists('\App\dailyve_get_route_seo_url')
                    ? \App\dailyve_get_route_seo_url($from_name, $to_name, $station_name)
                    : '';
            }
            unset($item);
        }
        if (isset($data['arriving']['items']) && is_array($data['arriving']['items'])) {
            foreach ($data['arriving']['items'] as &$item) {
                $from_name = $item['from']['name'] ?? '';
                $to_name = $item['to']['name'] ?? '';
                $item['seo_url'] = function_exists('\App\dailyve_get_route_seo_url')
                    ? \App\dailyve_get_route_seo_url($from_name, $to_name, $station_name)
                    : '';
            }
            unset($item);
        }
    }

    wp_send_json_success($data);
});

if (!function_exists('App\dailyve_get_route_seo_url')) {
    function dailyve_get_route_seo_url($from_name, $to_name, $fallback_from_name = '')
    {
        $from_name = trim((string) $from_name);
        $to_name = trim((string) $to_name);
        $fallback_from_name = trim((string) $fallback_from_name);

        if ($to_name === '') {
            return '';
        }

        $from_candidates = array_filter(array_unique([
            $from_name,
            $fallback_from_name,
        ]));

        foreach ($from_candidates as $candidate_from_name) {
            if ($candidate_from_name === '') {
                continue;
            }

            $route_slug = sanitize_title($candidate_from_name) . '-di-' . sanitize_title($to_name);

            $page = get_page_by_path(
                've-xe-khach/tuyen-duong/' . $route_slug,
                OBJECT,
                'page'
            );

            if ($page && $page->post_status === 'publish') {
                return get_permalink($page->ID);
            }
        }

        return '';
    }
}


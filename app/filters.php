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
 */
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // WooCommerce blocks
}, 100);

/**
 * Auto-select page-tuyen-duong-seo.blade.php template for child pages of "Vé xe khách > Tuyến đường".
 * Parent page ID = 15738.
 */
add_filter('page_template_hierarchy', function ($templates) {
    $post_id = get_the_ID();
    if ($post_id) {
        $parent_id = wp_get_post_parent_id($post_id);
        if ((int) $parent_id === 15738) {
            array_unshift($templates, 'page-tuyen-duong-seo.php');
        }
    }
    return $templates;
}, 5);

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

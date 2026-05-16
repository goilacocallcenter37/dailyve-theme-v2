<?php

namespace App;

/**
 * Integrate custom Schema Markup using Rank Math filters.
 */
add_filter('rank_math/json_ld', function ($data, $jsonld) {
    if (is_front_page()) {
        $data['Organization'] = [
            '@type' => 'Organization',
            'name'  => 'Dailyve',
            'url'   => home_url(),
            'logo'  => 'https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/logo-dailyve.png', // Giả định logo path
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'telephone'   => '1900 0155',
                'contactType' => 'customer service'
            ]
        ];
    }

    // Nếu đang ở trang kết quả tìm kiếm hoặc trang đặt vé, thêm Schema BusTrip
    if (is_page('dat-ve-truc-tuyen') || isset($_GET['from']) || isset($_GET['to'])) {
        $from = isset($_GET['from_name']) ? sanitize_text_field($_GET['from_name']) : 'Điểm đi';
        $to = isset($_GET['to_name']) ? sanitize_text_field($_GET['to_name']) : 'Điểm đến';
        $date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : date('d/m/Y');

        $data['BusTrip'] = [
            '@type' => 'BusTrip',
            'name'  => "Vé xe từ $from đi $to",
            'departureTime' => $date,
            'itinerary' => [
                '@type' => 'City',
                'name'  => $from
            ],
            'arrivalBusStop' => [
                '@type' => 'BusStop',
                'name'  => $to
            ],
            'provider' => [
                '@type' => 'Organization',
                'name'  => 'Dailyve'
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => '150000', // Giá thấp nhất mặc định hoặc lấy từ API
                'priceCurrency' => 'VND',
                'availability' => 'https://schema.org/InStock'
            ]
        ];
    }

    return $data;
}, 99, 2);

/**
 * Add Breadcrumbs Schema logic if not handled by Rank Math.
 */
add_action('wp_head', function () {
    if (is_single() || is_page()) {
        // Có thể thêm logic breadcrumb JSON-LD thủ công ở đây nếu cần
    }
}, 1);

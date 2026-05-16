<?php

namespace App;

use WP_Query;
use DateTime;
use Exception;

/**
 * Register all cron schedules and hooks.
 */
add_filter('cron_schedules', function ($schedules) {
    if (!isset($schedules['every_minute'])) {
        $schedules['every_minute'] = [
            'interval' => 60,
            'display'  => __('Every Minute')
        ];
    }
    if (!isset($schedules['every_five_minutes'])) {
        $schedules['every_five_minutes'] = [
            'interval' => 300,
            'display'  => __('Every 5 Minutes')
        ];
    }
    return $schedules;
});

/**
 * Schedule events on init.
 */
add_action('init', function () {
    if (!wp_next_scheduled('dailyve_ticket_cleanup_cron')) {
        wp_schedule_event(time(), 'every_minute', 'dailyve_ticket_cleanup_cron');
    }
    if (!wp_next_scheduled('dailyve_sync_reviews_event')) {
        wp_schedule_event(time(), 'daily', 'dailyve_sync_reviews_event');
    }
    if (!wp_next_scheduled('refresh_route_cache_event')) {
        wp_schedule_event(time(), 'daily', 'refresh_route_cache_event');
    }
    if (!wp_next_scheduled('ams_cron_check_ticket_status_event')) {
        wp_schedule_event(time(), 'every_five_minutes', 'ams_cron_check_ticket_status_event');
    }
});

/**
 * CRON 1: Auto cancel expired Goopay tickets (10 mins)
 */
add_action('dailyve_ticket_cleanup_cron', function () {
    $args = [
        'post_type'      => 'book-ticket',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'payment_status',
                'value'   => 1, // Pending
                'compare' => '=',
            ],
            [
                'key'     => 'partner_id',
                'value'   => 'goopay',
                'compare' => '=',
            ],
        ],
        'date_query'     => [
            [
                'before' => '10 minutes ago',
            ],
        ],
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $processed_groups = [];
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $group_id = get_post_meta($post_id, 'journey_group_id', true);
            $cancel_key = !empty($group_id) ? $group_id : get_the_title();

            if (in_array($cancel_key, $processed_groups)) {
                continue;
            }

            if (function_exists('dailyve_perform_ticket_cancellation')) {
                dailyve_perform_ticket_cancellation($cancel_key);
            }
            $processed_groups[] = $cancel_key;
        }
        wp_reset_postdata();
    }
});

/**
 * CRON 2: Sync company reviews from Vexere
 */
add_action('dailyve_sync_reviews_event', function () {
    $args = [
        'post_type' => 'page',
        'post_parent' => 15764, // Cần kiểm tra ID này có khớp ở site mới không
        'posts_per_page' => -1,
        'fields' => 'ids'
    ];
    $company_ids = get_posts($args);

    foreach ($company_ids as $post_id) {
        $vexere_company_id = get_post_meta($post_id, 'company_id', true);
        if (!$vexere_company_id) continue;

        if (!function_exists('call_api_v2')) continue;

        // Sync Reviews List
        $response = call_api_v2("companies/vexere/$vexere_company_id/reviews?page=1&limit=20", 'GET');
        if (!is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            if (!empty($data['data'])) {
                if (isset($data['data']['average_rating'])) {
                    update_post_meta($post_id, 'rating', round((float)$data['data']['average_rating'], 1));
                }
                if (isset($data['data']['total'])) {
                    update_post_meta($post_id, 'reviews', (int)$data['data']['total']);
                }
            }
        }

        // Sync Rating Details
        $response_rating = call_api_v2("companies/vexere/$vexere_company_id/rating", 'GET');
        if (!is_wp_error($response_rating)) {
            $data_rating = json_decode(wp_remote_retrieve_body($response_rating), true);
            if (!empty($data_rating['data'])) {
                update_post_meta($post_id, 'vexere_rating_data', $data_rating['data']);
                if (isset($data_rating['data']['overall']['rv_main_value'])) {
                    update_post_meta($post_id, 'rating', round((float)$data_rating['data']['overall']['rv_main_value'], 1));
                }
                if (isset($data_rating['data']['overall']['total_reviews'])) {
                    update_post_meta($post_id, 'reviews', (int)$data_rating['data']['overall']['total_reviews']);
                }
            }
        }
    }
});

/**
 * CRON 3: Refresh Route Cache for ACF
 */
add_action('refresh_route_cache_event', function () {
    delete_transient('acf_route_choices_' . md5(defined('API_KEY_CLIENT') ? API_KEY_CLIENT : ''));
    delete_option('acf_route_choices_long_term_time');

    if (function_exists('populate_acf_select_route_field')) {
        $dummy_field = ['choices' => []];
        populate_acf_select_route_field($dummy_field);
    }
});

/**
 * CRON 4: Check Vexere ticket status (Every 5 mins)
 */
add_action('ams_cron_check_ticket_status_event', function () {
    $args = [
        'post_type'      => 'book-ticket',
        'posts_per_page' => 50,
        'meta_query'     => [
            [
                'key'     => 'payment_status',
                'value'   => 1,
                'compare' => '='
            ],
            [
                'key'     => 'partner_id',
                'value'   => 'vexere',
                'compare' => '='
            ]
        ],
        'date_query'     => [
            [
                'after' => '24 hours ago',
            ],
        ],
    ];

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $partner = get_post_meta($post_id, 'partner_id', true);
            $booking_code = get_the_title($post_id);

            if (empty($booking_code) || empty($partner) || !function_exists('call_api_v2')) continue;

            $code_arr = explode(' ', trim($booking_code));
            $first_code = $code_arr[0];

            $response = call_api_v2("booking/{$first_code}?partner={$partner}", 'GET');
            if (!is_wp_error($response)) {
                $data = json_decode(wp_remote_retrieve_body($response), true);
                if (!empty($data) && isset($data['status'])) {
                    $remote_status = strtoupper($data['status']);
                    $statusDesc = strtoupper($data['statusDescription'] ?? '');

                    if ($remote_status === 'PAID') {
                        update_post_meta($post_id, 'payment_status', 2);
                        if (isset($data['departureTime'])) {
                            update_post_meta($post_id, 'pickup_date', $data['departureTime']);
                        }
                    } elseif ($remote_status === 'REFUNDED' && $statusDesc != 'CANCELED') {
                        update_post_meta($post_id, 'payment_status', 5);
                    } elseif ($remote_status === 'REFUNDED' && $statusDesc === 'CANCELED') {
                        update_post_meta($post_id, 'payment_status', 3);
                    } elseif ($remote_status === 'CANCELED') {
                        update_post_meta($post_id, 'payment_status', 3);
                    }
                }
            }
        }
        wp_reset_postdata();
    }
});

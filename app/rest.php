<?php

namespace App;

use WP_REST_Request;

/**
 * Register Custom REST API Endpoints.
 */
add_action('rest_api_init', function () {
    register_rest_route('api/v1', '/state-city-new', [
        'methods'             => 'GET',
        'callback'            => __NAMESPACE__ . '\\handle_get_state_city_new',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('api/v1', '/trips', [
        'methods'             => 'GET',
        'callback'            => __NAMESPACE__ . '\\handle_get_trips',
        'permission_callback' => '__return_true',
    ]);
});

/**
 * Callback for /api/v1/state-city-new
 */
function handle_get_state_city_new()
{
    $cache_key = 'state_city_data_cache';
    $cached_data = get_transient($cache_key);
    
    if ($cached_data !== false) {
        return rest_ensure_response([
            'success' => true,
            'data'    => $cached_data
        ]);
    }

    $response = call_api_v2('/locations/search', 'GET');

    if (is_wp_error($response)) {
        return rest_ensure_response([
            'success' => false,
            'data'    => $response->get_error_message()
        ]);
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($data['data'])) {
        set_transient($cache_key, $data['data'], 3600 * 24 * 30);
        return rest_ensure_response([
            'success' => true,
            'data'    => $data['data']
        ]);
    }

    return rest_ensure_response([
        'success' => false,
        'data'    => 'Invalid response format'
    ]);
}

/**
 * Callback for /api/v1/trips
 */
function handle_get_trips(WP_REST_Request $request)
{
    $params = $request->get_params();
    
    // Normalize params for Dailyve API
    $api_params = [
        'from'      => $params['from'] ?? '',
        'to'        => $params['to'] ?? '',
        'date'      => $params['date'] ?? date('Y-m-d', strtotime('+1 day')),
        'time'      => $params['time'] ?? '00:00-23:59',
        'sort'      => $params['sort'] ?? 'time:asc',
        'pageSize'  => $params['pageSize'] ?? 20,
        'cursor'    => $params['cursor'] ?? '',
    ];

    // Optional filters
    if (!empty($params['companies'])) {
        $api_params['companies'] = $params['companies'];
    }
    if (isset($params['islimousine'])) {
        $api_params['isLimousine'] = $params['islimousine'] === '1' || $params['islimousine'] === 'true';
    }

    $response = call_api_v2('trips', 'GET', $api_params);

    if (is_wp_error($response)) {
        return rest_ensure_response([
            'success' => false,
            'data'    => $response->get_error_message()
        ]);
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    return rest_ensure_response([
        'success' => true,
        'data'    => $data
    ]);
}

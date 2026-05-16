<?php

namespace App;

use WP_Error;

/**
 * Call API V2 with predefined headers and endpoint.
 */
function call_api_v2($endpoint, $method = 'GET', $data = [], $headers = [], $timeout = 180)
{
    $method = strtoupper($method);

    if (!defined('END_POINT_V2') || !defined('X_API_KEY')) {
        return new WP_Error('missing_api_config', 'Thiếu END_POINT_V2 hoặc X_API_KEY trong wp-config.php.');
    }

    if (preg_match('#^https?://#i', $endpoint)) {
        $url = $endpoint;
    } else {
        $base = rtrim(END_POINT_V2, '/');
        $url = $base . '/' . ltrim($endpoint, '/');
    }

    if ($method === 'GET' && !empty($data)) {
        $data = array_filter($data, function ($v) {
            return $v !== null && $v !== '';
        });
        $url = add_query_arg($data, $url);
    }

    $default_headers = [
        'x-api-key'     => X_API_KEY,
        'Content-Type'  => 'application/json',
        'Accept'        => 'application/json',
    ];

    $args = [
        'method'      => $method,
        'headers'     => array_merge($default_headers, $headers),
        'timeout'     => $timeout,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.1',
        'sslverify'   => false,
    ];

    if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
        $args['body'] = !empty($data) ? wp_json_encode($data) : '{}';
    }

    return wp_remote_request($url, $args);
}

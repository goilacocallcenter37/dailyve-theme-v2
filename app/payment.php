<?php

namespace App;

use WP_Error;

/**
 * Handle AJAX request to check transaction status.
 */
add_action('wp_ajax_check_transaction_ticket', 'App\handle_check_transaction_ticket');
add_action('wp_ajax_nopriv_check_transaction_ticket', 'App\handle_check_transaction_ticket');

function handle_check_transaction_ticket()
{
    check_ajax_referer('ams_vexe_check_transaction', 'nonce');

    $code = isset($_POST['code']) ? sanitize_text_field($_POST['code']) : '';
    if (empty($code)) {
        wp_send_json_error(['message' => 'Thiếu mã đơn hàng!']);
    }

    $existing_posts = get_posts([
        'post_type'      => 'book-ticket',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'journey_group_id',
                'value'   => $code,
                'compare' => '=',
            ]
        ],
    ]);

    if (empty($existing_posts)) {
        $existing_posts = get_posts([
            'post_type'      => 'book-ticket',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'title'          => $code,
        ]);
    }

    if (empty($existing_posts)) {
        wp_send_json_error(['message' => 'Đơn hàng không tồn tại!']);
    }

    // Sort tickets by index
    usort($existing_posts, function ($a, $b) {
        $ia = (int)get_post_meta($a->ID, 'journey_ticket_index', true);
        $ib = (int)get_post_meta($b->ID, 'journey_ticket_index', true);
        return $ia <=> $ib;
    });

    $totalPrice = 0;
    $paymentContent = '';
    $partnerGroups = ['vexere' => [], 'goopay' => []];

    foreach ($existing_posts as $p) {
        if ($paymentContent === '') {
            $paymentContent = (string)get_post_meta($p->ID, 'payment_content', true);
        }
        $totalPrice += (float)get_post_meta($p->ID, 'total_price', true);
        
        $bc = (string)get_post_meta($p->ID, 'booking_codes', true);
        $partnerId = strtolower((string)get_post_meta($p->ID, 'partner_id', true));
        
        if (!empty($bc)) {
            $codes = preg_split('/\s+/', trim($bc));
            if (in_array($partnerId, ['vexere', 'goopay'])) {
                $partnerGroups[$partnerId] = array_merge($partnerGroups[$partnerId], $codes);
            }
        }
    }

    // Call Bank API
    $bank_url = defined('DAILYVE_BANK_URL') ? DAILYVE_BANK_URL : '';
    if (!$bank_url) {
        wp_send_json_error(['message' => 'Bank API URL not configured.']);
    }

    $response = call_api_with_token($bank_url . '/Transaction?pageSize=30&page=1');
    if (is_wp_error($response)) {
        wp_send_json_error(['message' => $response->get_error_message()]);
    }

    $bankData = json_decode(wp_remote_retrieve_body($response), true);
    $found = false;

    if (isset($bankData['data']) && is_array($bankData['data'])) {
        foreach ($bankData['data'] as $transaction) {
            $content = (string)($transaction['content'] ?? '');
            $amount = (float)($transaction['amount'] ?? 0);

            if ($paymentContent !== '' && str_contains($content, $paymentContent) && $amount >= $totalPrice) {
                $found = true;
                break;
            }
        }
    }

    if (!$found) {
        wp_send_json_success(['status' => false]);
        return;
    }

    // If found, confirm payment with partners
    $all_success = true;
    foreach ($partnerGroups as $partner => $codes) {
        if (empty($codes)) continue;
        $codes = array_values(array_unique(array_filter($codes)));
        
        // Vexere roundtrip payload format
        $payload = count($codes) > 1 
            ? array_map(fn($c) => ['code' => $c, 'coupon' => '', 'transaction_id' => ''], $codes)
            : ['code' => $codes[0], 'coupon' => '', 'transaction_id' => ''];

        $res = call_api_v2("booking/{$partner}/pay", 'POST', $payload);
        if (is_wp_error($res) || wp_remote_retrieve_response_code($res) !== 200) {
            $all_success = false;
        }
    }

    if ($all_success) {
        foreach ($existing_posts as $p) {
            update_post_meta($p->ID, 'payment_status', 2);
        }
        wp_send_json_success(['status' => true]);
    } else {
        wp_send_json_error(['message' => 'Lỗi khi xác nhận thanh toán với đối tác.']);
    }
}

/**
 * Handle AJAX request to delete/cancel ticket.
 */
add_action('wp_ajax_delete_ticket', 'App\handle_delete_ticket');
add_action('wp_ajax_nopriv_delete_ticket', 'App\handle_delete_ticket');

function handle_delete_ticket()
{
    check_ajax_referer('ams_vexe_delete_ticket', 'nonce');

    $code = isset($_POST['code']) ? sanitize_text_field($_POST['code']) : '';
    $journey_group_id = isset($_POST['journey_group_id']) ? sanitize_text_field($_POST['journey_group_id']) : '';

    $key = !empty($journey_group_id) ? $journey_group_id : $code;
    $result = dailyve_perform_ticket_cancellation($key);

    if (isset($result['status']) && $result['status'] === true) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}

/**
 * Perform ticket cancellation logic.
 */
function dailyve_perform_ticket_cancellation($key)
{
    if (empty($key)) {
        return ['status' => false, 'message' => 'Thiếu mã đơn hàng!'];
    }

    $posts = get_posts([
        'post_type'      => 'book-ticket',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'journey_group_id',
                'value'   => $key,
                'compare' => '=',
            ]
        ],
    ]);

    if (empty($posts)) {
        $posts = get_posts([
            'post_type'      => 'book-ticket',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'title'          => $key,
        ]);
    }

    if (empty($posts)) {
        return ['status' => false, 'message' => 'Đơn hàng không tồn tại!'];
    }

    $post_ids = [];
    $partnerGroups = ['vexere' => [], 'goopay' => []];

    foreach ($posts as $p) {
        $pid = $p->ID;
        $post_ids[] = $pid;
        $partnerId = strtolower((string)get_post_meta($pid, 'partner_id', true));
        $bc = (string)get_post_meta($pid, 'booking_codes', true);

        if (!empty($bc) && in_array($partnerId, ['vexere', 'goopay'])) {
            $codes = preg_split('/\s+/', trim($bc));
            $partnerGroups[$partnerId] = array_merge($partnerGroups[$partnerId], $codes);
        }
    }

    $all_canceled = true;
    foreach ($partnerGroups as $partner => $codes) {
        if (empty($codes)) continue;
        $codes = array_values(array_unique(array_filter($codes)));
        
        foreach ($codes as $c) {
            $res = call_api_v2("booking/{$partner}/cancel", 'POST', ['code' => $c]);
            if (is_wp_error($res) || wp_remote_retrieve_response_code($res) !== 200) {
                // We keep going but mark as not fully successful if needed
            }
        }
    }

    foreach ($post_ids as $pid) {
        update_post_meta($pid, 'payment_status', 3);
    }

    return [
        'status'  => true,
        'message' => 'Đã hủy vé thành công.',
        'post_ids' => $post_ids
    ];
}

/**
 * Token management for Bank API.
 */
function call_api_with_token($endpoint, $method = 'GET', $data = [])
{
    if (is_token_expired()) {
        $new_token = refresh_bank_token();
        if (!$new_token) {
            return new WP_Error('token_refresh_failed', 'Không thể làm mới token ngân hàng.');
        }
    }
    
    $token = get_option('api_auth_token');
    $response = wp_remote_request($endpoint, [
        'method'  => $method,
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json'
        ],
        'body'    => $method === 'POST' ? json_encode($data) : null,
        'sslverify' => false,
        'timeout' => 30
    ]);
    return $response;
}

function is_token_expired()
{
    $expiration = get_option('api_auth_expiration');
    if (!$expiration) return true;
    return strtotime(current_time('mysql')) >= strtotime($expiration);
}

function refresh_bank_token()
{
    if (!defined('DAILYVE_BANK_URL') || !defined('DAILYVE_BANK_EMAIL') || !defined('DAILYVE_BANK_PASSWORD')) {
        return false;
    }

    $response = wp_remote_post(DAILYVE_BANK_URL . '/Auth/Login', [
        'body'    => json_encode([
            'email'    => DAILYVE_BANK_EMAIL,
            'password' => DAILYVE_BANK_PASSWORD
        ]),
        'headers' => ['Content-Type' => 'application/json'],
        'sslverify' => false
    ]);

    if (!is_wp_error($response)) {
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!empty($body['successful']) && isset($body['data']['token'])) {
            update_option('api_auth_token', $body['data']['token']);
            update_option('api_auth_expiration', $body['data']['expiration']);
            return $body['data']['token'];
        }
    }
    return false;
}

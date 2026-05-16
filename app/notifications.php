<?php

namespace App;

/**
 * Trigger email notification when payment status is updated to PAID (2).
 */
add_action('updated_post_meta', 'App\maybe_send_booking_email', 10, 4);
add_action('added_post_meta', 'App\maybe_send_booking_email', 10, 4);

function maybe_send_booking_email($meta_id, $object_id, $meta_key, $meta_value)
{
    if ($meta_key === 'payment_status' && (int)$meta_value === 2) {
        if (get_post_type($object_id) === 'book-ticket') {
            if (get_post_meta($object_id, '_email_sent', true)) {
                return;
            }
            send_booking_confirmation_email($object_id);
            update_post_meta($object_id, '_email_sent', time());
        }
    }
}

/**
 * Send booking confirmation email using Sage Blade template.
 */
function send_booking_confirmation_email($post_id)
{
    $customer_email = get_post_meta($post_id, 'email', true);
    if (!is_email($customer_email)) {
        return;
    }

    $data = [
        'customer_name' => get_post_meta($post_id, 'full_name', true),
        'booking_code'  => get_the_title($post_id),
        'route_name'    => get_post_meta($post_id, 'routeName', true),
        'pickup_date'   => get_post_meta($post_id, 'pickup_date', true),
        'seats'         => get_post_meta($post_id, 'seat', true),
        'total_price'   => get_post_meta($post_id, 'total_price', true),
        'company'       => get_post_meta($post_id, 'company_bus', true),
    ];

    $subject = "Xác nhận đặt vé thành công - Mã đơn hàng: #{$data['booking_code']}";
    
    // Render Blade template
    try {
        $body = \Roots\view('emails.booking-confirmation', $data)->render();
    } catch (\Exception $e) {
        // Fallback if template fails
        $body = "Chào {$data['customer_name']}, vé của bạn (#{$data['booking_code']}) đã được xác nhận.";
    }

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    wp_mail($customer_email, $subject, $body, $headers);
}

<?php

namespace App;

/**
 * Custom columns for book-ticket post type.
 */
add_filter('manage_edit-book-ticket_columns', function ($columns) {
    $new_columns = [];
    foreach ($columns as $key => $title) {
        if ($key === 'date') {
            $new_columns['full_name'] = __('Họ tên');
            $new_columns['phone'] = __('Điện thoại');
            $new_columns['route_name'] = __('Tuyến đường');
            $new_columns['total_price'] = __('Giá vé');
            $new_columns['applied_coupon'] = __('Mã giảm giá');
            $new_columns['partner_id'] = __('Đối tác');
            $new_columns['ticket_status'] = __('Trạng thái');
        }
        $new_columns[$key] = $title;
    }
    return $new_columns;
});

/**
 * Render custom column content.
 */
add_action('manage_book-ticket_posts_custom_column', function ($column, $post_id) {
    switch ($column) {
        case 'full_name':
            echo esc_html(get_post_meta($post_id, 'full_name', true));
            break;
        case 'phone':
            echo esc_html(get_post_meta($post_id, 'phone', true));
            break;
        case 'partner_id':
            echo esc_html(strtoupper(get_post_meta($post_id, 'partner_id', true)));
            break;
        case 'route_name':
            echo esc_html(get_post_meta($post_id, 'routeName', true));
            break;
        case 'total_price':
            $price = get_post_meta($post_id, 'total_price', true);
            echo $price ? number_format((float)$price, 0, ',', '.') . 'đ' : '0đ';
            break;
        case 'ticket_status':
            $status = (int)get_post_meta($post_id, 'payment_status', true);
            $labels = [
                1 => ['label' => 'Chờ thanh toán', 'color' => '#f39c12'],
                2 => ['label' => 'Đã thanh toán', 'color' => '#27ae60'],
                3 => ['label' => 'Đã hủy', 'color' => '#e74c3c'],
                5 => ['label' => 'Hủy vé hoàn tiền', 'color' => '#f36412'],
            ];
            $current = $labels[$status] ?? ['label' => 'Không xác định', 'color' => '#999'];
            printf('<span style="color: %s; font-weight: bold;">%s</span>', $current['color'], $current['label']);
            break;
        case 'applied_coupon':
            global $wpdb;
            $table_name = $wpdb->prefix . 'ticket_coupon';
            $coupon = $wpdb->get_row($wpdb->prepare(
                "SELECT code, status FROM $table_name WHERE ticket_id = %d ORDER BY created_at DESC LIMIT 1",
                $post_id
            ));
            if ($coupon) {
                $is_completed = $coupon->status === 'completed';
                $bg = $is_completed ? '#e7f5ea' : '#fff7ed';
                $color = $is_completed ? '#15803d' : '#ea580c';
                printf(
                    '<span style="background: %s; color: %s; padding: 2px 6px; border-radius: 4px; font-weight: bold; border: 1px solid %s20;">%s</span>',
                    $bg, $color, $color, esc_html($coupon->code)
                );
            } else {
                echo '<span style="color: #999;">-</span>';
            }
            break;
    }
}, 10, 2);

/**
 * Filter by Partner in admin list.
 */
add_action('restrict_manage_posts', function ($post_type) {
    if ($post_type === 'book-ticket') {
        $current = $_GET['partner_id_filter'] ?? '';
        $partners = ['vexere' => 'Vexere', 'goopay' => 'Goopay'];
        echo '<select name="partner_id_filter">';
        echo '<option value="">Tất cả đối tác</option>';
        foreach ($partners as $val => $label) {
            printf('<option value="%s" %s>%s</option>', $val, selected($current, $val, false), $label);
        }
        echo '</select>';
    }
});

add_action('pre_get_posts', function ($query) {
    global $pagenow;
    if (is_admin() && $pagenow === 'edit.php' && ($query->get('post_type') === 'book-ticket') && !empty($_GET['partner_id_filter'])) {
        $meta_query = (array)$query->get('meta_query');
        $meta_query[] = [
            'key' => 'partner_id',
            'value' => sanitize_text_field($_GET['partner_id_filter']),
            'compare' => '='
        ];
        $query->set('meta_query', $meta_query);
    }
});

/**
 * Coupon Info Meta Box.
 */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'dailyve_coupon_info',
        'Thông tin Mã giảm giá',
        function ($post) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'ticket_coupon';
            $coupon = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE ticket_id = %d ORDER BY created_at DESC LIMIT 1",
                $post->ID
            ));

            if ($coupon) {
                ?>
                <div style="padding: 10px 0;">
                    <p><strong>Mã code:</strong> <span style="color: #43a72f; font-weight: bold; font-size: 16px;"><?= esc_html($coupon->code) ?></span></p>
                    <p><strong>Trạng thái:</strong> <?= $coupon->status === 'completed' ? '<span style="color: blue;">Đã hoàn thành</span>' : '<span style="color: orange;">Đang chờ (Pending)</span>' ?></p>
                    <p><strong>Ngày áp dụng:</strong> <?= date('d/m/Y H:i', strtotime($coupon->created_at)) ?></p>
                    <hr>
                    <p><a href="<?= get_edit_post_link($coupon->coupon_id) ?>" target="_blank">Xem chi tiết cấu hình mã →</a></p>
                </div>
                <?php
            } else {
                echo '<p>Vé này chưa áp dụng mã giảm giá nào.</p>';
            }
        },
        'book-ticket',
        'side',
        'high'
    );
});

/**
 * Sync coupon status when payment status changes.
 */
add_action('updated_post_meta', 'App\sync_coupon_status_on_payment', 10, 4);
add_action('added_post_meta', 'App\sync_coupon_status_on_payment', 10, 4);

function sync_coupon_status_on_payment($meta_id, $object_id, $meta_key, $meta_value) {
    if ($meta_key === 'payment_status' && (int)$meta_value === 2) {
        if (get_post_type($object_id) === 'book-ticket') {
            global $wpdb;
            $table_name = $wpdb->prefix . 'ticket_coupon';
            $wpdb->update(
                $table_name,
                ['status' => 'completed', 'updated_at' => current_time('mysql')],
                ['ticket_id' => $object_id, 'status' => 'pending']
            );
        }
    }
}

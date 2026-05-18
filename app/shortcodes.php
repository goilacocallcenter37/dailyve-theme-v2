<?php

namespace App;

use WP_Query;

/**
 * Register React Search Form Shortcode
 */
add_shortcode('react_search_form', function () {
    return '<div id="react-search-form"></div>';
});

/**
 * Register routes_popular Shortcode
 */
add_shortcode('routes_popular', function () {
    $results = null;
    $routes_popular = get_field('popular_routes', 'option');

    if ($routes_popular) {
        ob_start();
        ?>
        <div class="silde-route-popular">
            <?php foreach ($routes_popular as $key => $route) { ?>
                <div class="box-item">
                    <div style="background-image: url(<?= $route['popular_route_image']; ?>);" class="box-item__bg">
                        <div class="box-item__layer">
                            Tuyến xe từ<br>
                            <strong><?= $route['popular_departure']['label'] ?? ''; ?></strong>
                        </div>
                    </div>
                    <div class="box-item__content">
                        <?php 
                        $destinations = $route['popular_destinations'] ?? [];
                        for ($i = 0; $i < count($destinations); $i++) { 
                            $dest = $destinations[$i]['popular_destination'] ?? null;
                            if (!$dest) continue;
                            ?>
                            <div class="box-item__content__index">
                                <a href="<?= function_exists('convertIdToSlug') ? convertIdToSlug($route['popular_departure'], $dest) : '#'; ?>"
                                    class="flex box-item__content__index__wrap">
                                    <div class="box-item__content__index__info">
                                        <div class="box-item__content__index__title">
                                            <?= $dest['label'] ?? ''; ?>
                                        </div>
                                        <p class="box-item__content__index__desc">
                                            <?= $destinations[$i]['popular_destination_km'] ?? ''; ?> -
                                            <?= $destinations[$i]['popular_destination_time'] ?? ''; ?>
                                        </p>
                                    </div>
                                    <div class="box-item__content__index__price">
                                        <span><?= $destinations[$i]['popular_destination_price'] ?? ''; ?></span>
                                        <span>Đặt vé</span>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php 
        $results = ob_get_clean();
    }
    return $results;
});

/**
 * Register category_posts Shortcode
 */
add_shortcode('category_posts', function ($atts) {
    $atts = shortcode_atts(array(
        'category_id' => 0,
        'posts_per_page' => 10,
        'orderby' => 'date',
        'order' => 'DESC'
    ), $atts);

    $args = array(
        'cat' => $atts['category_id'],
        'posts_per_page' => $atts['posts_per_page'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'no_found_rows' => false,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => false,
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);
    ob_start();

    if ($query->have_posts()) {
        echo '<div class="category-posts-list-slide flex overflow-x-auto gap-4 py-2">';
        $count = 0;
        while ($query->have_posts()) {
            $query->the_post();
            $count++;
            $attr = array();
            if ($count <= 1) {
                $attr['fetchpriority'] = 'high';
                $attr['loading'] = 'eager';
            }
            ?>
            <div id="post-<?php the_ID(); ?>" class="flex-none w-64 shadow-md rounded-lg overflow-hidden bg-white">
                <div class="post-thumbnail shine aspect-video overflow-hidden">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <?php the_post_thumbnail('medium', $attr); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>Đang cập nhật.</p>';
    }

    return ob_get_clean();
});

/**
 * Helper to render post item layout
 */
function renderPostItem($type, $isAddress = false) {
    $post_id = get_the_ID();
    $cache_key = 'post_render_' . $post_id . '_' . md5($type . ($isAddress ? '1' : '0'));
    $content = wp_cache_get($cache_key, 'post_renders');

    if ($content === false) {
        ob_start();
        ?>
        <div class="tuyen-duong-item">
            <?php if (has_post_thumbnail($post_id)) {
                $thumb = get_the_post_thumbnail($post_id, 'medium', [
                    'loading' => 'lazy',
                    'decoding' => 'async'
                ]);
                ?>
                <picture class="post-thumbnail block overflow-hidden">
                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>"
                        title="<?php echo esc_attr(get_the_title($post_id)); ?>">
                        <?php echo $thumb; ?>
                    </a>
                </picture>
            <?php } ?>

            <div class="tuyen-duong-item__content">
                <?php if ($type === 'tuyenduong') {
                    // Cache meta values
                    $price = get_field('routes_price', $post_id);
                    $distance = get_field('routes_distance', $post_id);
                    $time = get_field('routes_time', $post_id);
                    ?>
                    <div class="tuyen-duong-item__content__title">
                        <h3 class="one-line">
                            <a href="<?php echo esc_url(get_permalink($post_id)); ?>"
                                title="<?php echo esc_attr(get_the_title($post_id)); ?>">
                                <?php echo get_the_title($post_id); ?>
                            </a>
                        </h3>
                        <?php if ($price): ?>
                            <span><?php echo number_format($price, 0, ',', '.') ?>đ</span>
                        <?php endif; ?>
                    </div>
                    <div class="tuyen-duong-item__content__desc">
                        <?php if ($distance && $time): ?>
                            <span><?php echo $distance . ' - ' . $time ?></span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" title="Đặt vé" target="_blank">Đặt vé</a>
                    </div>
                <?php } else { 
                    $address = $isAddress ? get_field('company_address', $post_id) : '';
                    ?>
                    <div class="tuyen-duong-item__content__title">
                        <h3 class="one-line">
                            <a href="<?php echo esc_url(get_permalink($post_id)); ?>"
                                title="<?php echo esc_attr(get_the_title($post_id)); ?>">
                                <?php echo get_the_title($post_id); ?>
                            </a>
                        </h3>
                    </div>
                    <?php if ($isAddress && !empty($address)): ?>
                        <div class="tuyen-duong-item__content__address">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <span><?php echo esc_html($address); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="tuyen-duong-item__content__desc two-line">
                        <?php
                        $excerpt = get_the_excerpt($post_id);
                        if (!$excerpt) {
                            $excerpt = wp_trim_words(get_post_field('post_content', $post_id), 20);
                        }
                        echo $excerpt;
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        wp_cache_set($cache_key, $content, 'post_renders', HOUR_IN_SECONDS * 2);
    }

    return $content;
}

/**
 * Register show_list_post Shortcode
 */
add_shortcode('show_list_post', function ($attr) {
    $atts = shortcode_atts([
        'type' => '',
        'post_type' => 'page',
        'page_parent_id' => 0,
        'posts_per_page' => 30,
        'orderby' => 'ID',
        'order' => 'DESC',
        'is_address' => 'false'
    ], $attr);

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $cache_key = 'post_grid_' . md5(serialize($atts)) . '_' . $paged;
    $output = get_transient($cache_key);

    if ($output === false) {
        $args = [
            'post_type' => $atts['post_type'],
            'posts_per_page' => $atts['posts_per_page'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'post_status' => 'publish',
            'post_parent' => $atts['page_parent_id'],
            'paged' => $paged,
            'no_found_rows' => false,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => false,
            'meta_key' => '_thumbnail_id',
            'fields' => 'ids'
        ];

        $posts_query = new WP_Query($args);
        ob_start();
        if ($posts_query->have_posts()) {
            $post_ids = $posts_query->posts;
            $full_args = [
                'post_type' => $atts['post_type'],
                'post__in' => $post_ids,
                'orderby' => 'post__in',
                'posts_per_page' => -1,
                'no_found_rows' => true,
                'update_post_meta_cache' => true,
                'update_post_term_cache' => false,
            ];

            $full_query = new WP_Query($full_args);
            $isAddress = $atts['is_address'] === 'true';

            echo '<div class="posts-list--grid">';
            while ($full_query->have_posts()) {
                $full_query->the_post();
                echo renderPostItem($atts['type'], $isAddress);
            }
            echo '</div>';

            $total_pages = $posts_query->max_num_pages;
            if ($total_pages > 1) {
                echo '<div class="pagination-wrapper"><div class="pagination">';
                echo paginate_links([
                    'base' => trailingslashit(get_pagenum_link(1)) . '%_%',
                    'format' => 'page/%#%/',
                    'current' => $paged,
                    'total' => $total_pages,
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                    'type' => 'list',
                    'mid_size' => 2,
                    'end_size' => 1,
                    'before_page_number' => '<span class="page-number">',
                    'after_page_number' => '</span>'
                ]);
                echo '</div></div>';
            }
            wp_reset_postdata();
        } else {
            echo '<p>Đang cập nhật.</p>';
        }

        $output = ob_get_clean();
        set_transient($cache_key, $output, HOUR_IN_SECONDS * 6);
    }

    return $output;
});

/**
 * Register show_post_page Shortcode
 */
add_shortcode('show_post_page', function ($attr) {
    $atts = shortcode_atts([
        'type' => '',
        'post_type' => 'page',
        'page_parent_id' => 0,
        'posts_per_page' => 12,
        'orderby' => 'ID',
        'order' => 'DESC',
    ], $attr);

    $args = [
        'post_type' => $atts['post_type'],
        'posts_per_page' => $atts['posts_per_page'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish',
        'post_parent' => $atts['page_parent_id'],
        'update_post_meta_cache' => true,
        'update_post_term_cache' => false,
        'no_found_rows' => true,
    ];

    ob_start();
    $posts_query = new WP_Query($args);

    if ($posts_query->have_posts()) {
        echo '<div class="posts-list-slide">';
        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            echo renderPostItem($atts['type']);
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>Đang cập nhật.</p>';
    }

    return ob_get_clean();
});

/**
 * Register book_ticket_company Shortcode
 */
add_shortcode('book_ticket_company', function ($atts) {
    $atts = shortcode_atts([
        'post-type' => 'page',
        'posts_per_page' => 12,
        'orderby' => 'ID',
        'order' => 'DESC'
    ], $atts);

    $getBaseArgs = function ($postType, $postParent = 0, $hasOutstanding = true) use ($atts) {
        $args = [
            'post_type' => $postType,
            'posts_per_page' => $atts['posts_per_page'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'post_status' => 'publish',
            'post_parent' => $postParent,
            'no_found_rows' => true,
        ];
        if ($hasOutstanding) {
            $args['meta_query'][] = [
                'key' => 'outstanding',
                'value' => true,
                'compare' => '='
            ];
        }
        return $args;
    };

    $queries = [
        'tuyenduong' => $getBaseArgs($atts['post-type'], 15738, true),
        'nhaxe' => $getBaseArgs($atts['post-type'], 15764, true),
        'benxe' => $getBaseArgs($atts['post-type'], 15896, true)
    ];

    ob_start(); 
    ?>
    <script>
    if (typeof openTabs !== 'function') {
        function openTabs(event, tabName) {
            const parentSection = event.currentTarget.closest("div.lvn_tabs_wrapper") || event.currentTarget.parentNode.parentNode;
            parentSection.querySelectorAll(".lvn-tab-item").forEach((tab) => {
                tab.classList.remove("active");
            });
            event.currentTarget.classList.add("active");
            parentSection.querySelectorAll(".lvn_tab_custom").forEach((content) => {
                content.style.display = "none";
            });
            const selectedTab = parentSection.querySelector("#" + tabName);
            if (selectedTab) {
                selectedTab.style.display = "block";
            }
        }
    }
    </script>
    <div class="lvn_tabs_wrapper">
        <div class="lvn-tab-content">
            <div class="lvn-tab-content__title">
                <button class="lvn-tab-item active" onclick="openTabs(event, 'tuyenduong')">Tuyến đường</button>
                <button class="lvn-tab-item" onclick="openTabs(event, 'nhaxe')">Nhà xe</button>
                <button class="lvn-tab-item" onclick="openTabs(event, 'benxe')">Bến xe</button>
            </div>
        </div>

        <?php foreach (['tuyenduong', 'nhaxe', 'benxe'] as $index => $tabType): ?>
            <div id="<?= $tabType ?>" class="lvn_tab_custom" <?= $index > 0 ? 'style="display:none"' : '' ?>>
                <?php
                $posts_query = new WP_Query($queries[$tabType]);
                if ($posts_query->have_posts()): ?>
                    <div class="posts-list-slide">
                        <?php while ($posts_query->have_posts()):
                            $posts_query->the_post();
                            echo renderPostItem($tabType);
                        endwhile; ?>
                    </div>
                <?php wp_reset_postdata();
                else: ?>
                    <p>Đang cập nhật.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php 
    return ob_get_clean();
});

/**
 * Register book_ticket_airline Shortcode
 */
add_shortcode('book_ticket_airline', function ($atts) {
    $atts = shortcode_atts([
        'post-type' => 'page',
        'posts_per_page' => 12,
        'orderby' => 'ID',
        'order' => 'DESC'
    ], $atts);

    $getBaseArgs = function ($postType, $postParent, $hasOutstanding = false) use ($atts) {
        $args = [
            'post_type' => $postType,
            'posts_per_page' => $atts['posts_per_page'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'post_parent' => $postParent,
            'post_status' => 'publish',
            'no_found_rows' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => false,
            'meta_key' => '_thumbnail_id'
        ];
        if ($hasOutstanding) {
            $args['meta_query'][] = [
                'key' => 'outstanding',
                'value' => true,
                'compare' => '='
            ];
        }
        return $args;
    };

    $queries = [
        'tuyenduongairline' => $getBaseArgs($atts['post-type'], 16844, true)
    ];

    ob_start(); 
    ?>
    <div class="lvn_tabs_wrapper">
        <div class="lvn-tab-content">
            <div class="lvn-tab-content__title">
                <button class="lvn-tab-item active">Tuyến đường</button>
            </div>
        </div>

        <div id="tuyenduongairline" class="lvn_tab_custom">
            <?php
            $posts_query = new WP_Query($queries['tuyenduongairline']);
            if ($posts_query->have_posts()): ?>
                <div class="posts-list-slide">
                    <?php while ($posts_query->have_posts()):
                        $posts_query->the_post();
                        echo renderPostItem('tuyenduongairline');
                    endwhile; ?>
                </div>
            <?php wp_reset_postdata();
            else: ?>
                <p>Đang cập nhật.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php 
    return ob_get_clean();
});

/**
 * Clear meta cache when posts are updated
 */
add_action('post_updated', function ($post_id) {
    wp_cache_delete('post_render_' . $post_id . '_' . md5(''), 'post_renders');
    wp_cache_delete('post_render_' . $post_id . '_' . md5('tuyenduong0'), 'post_renders');
    wp_cache_delete('post_render_' . $post_id . '_' . md5('tuyenduong1'), 'post_renders');

    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_post_grid_%'");
});

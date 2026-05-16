<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

function dailyve_is_trip_results_page(): bool
{
    return is_page('dat-ve-truc-tuyen') || is_page_template('template-search-results.blade.php');
}

add_action('wp_enqueue_scripts', function () {
    if (! dailyve_is_trip_results_page()) {
        return;
    }

    $legacy_uri = content_url('themes/flatsome-child');
    $legacy_dir = WP_CONTENT_DIR . '/themes/flatsome-child';
    $version = static function (string $relative) use ($legacy_dir) {
        $path = $legacy_dir . '/' . ltrim($relative, '/');
        return file_exists($path) ? (string) filemtime($path) : null;
    };

    wp_enqueue_style('dailyve-legacy-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', [], '5.15.4');
    wp_enqueue_style('dailyve-legacy-jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css', [], '1.13.2');
    wp_enqueue_style('dailyve-legacy-slick', $legacy_uri . '/assets/slick/slick.css', [], $version('assets/slick/slick.css'));
    wp_enqueue_style('dailyve-legacy-static', $legacy_uri . '/assets/css/static.css', [], $version('assets/css/static.css'));
    wp_enqueue_style('dailyve-legacy-booking', $legacy_uri . '/assets/css/style-child.css', [], $version('assets/css/style-child.css'));
    wp_enqueue_style('dailyve-legacy-toastr', $legacy_uri . '/assets/css/toastr.css', [], $version('assets/css/toastr.css'));

    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script('jquery-ui-mouse');
    wp_enqueue_script(
        'dailyve-legacy-jquery-ui-touch-punch',
        'https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js',
        ['jquery', 'jquery-ui-core', 'jquery-ui-mouse', 'jquery-ui-slider'],
        '0.2.3',
        true
    );
    wp_enqueue_script('dailyve-legacy-sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', ['jquery'], '11', true);
    wp_enqueue_script('dailyve-legacy-slick', $legacy_uri . '/assets/slick/slick.min.js', ['jquery'], $version('assets/slick/slick.min.js'), true);
    wp_enqueue_script('dailyve-legacy-pagination', $legacy_uri . '/assets/js/pagination.min.js', ['jquery'], $version('assets/js/pagination.min.js'), true);
    wp_enqueue_script('dailyve-legacy-notify', $legacy_uri . '/assets/js/notify.min.js', ['jquery'], $version('assets/js/notify.min.js'), true);
    wp_enqueue_script('dailyve-legacy-toastr', $legacy_uri . '/assets/js/toastr.js', ['jquery'], $version('assets/js/toastr.js'), true);
    wp_enqueue_script(
        'dailyve-legacy-functions',
        $legacy_uri . '/assets/js/functions.js',
        ['jquery', 'jquery-ui-datepicker', 'dailyve-legacy-slick', 'dailyve-legacy-pagination'],
        $version('assets/js/functions.js'),
        true
    );
    wp_enqueue_script(
        'dailyve-legacy-script-ams',
        $legacy_uri . '/assets/js/script-ams.js',
        [
            'jquery',
            'jquery-ui-slider',
            'dailyve-legacy-jquery-ui-touch-punch',
            'dailyve-legacy-sweetalert2',
            'dailyve-legacy-notify',
            'dailyve-legacy-pagination',
            'dailyve-legacy-functions',
        ],
        $version('assets/js/script-ams.js'),
        true
    );

    wp_localize_script('dailyve-legacy-script-ams', 'generic_data', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ams_vexe'),
        'delete_ticket_nonce' => wp_create_nonce('ams_vexe_delete_ticket'),
        'user_id' => get_current_user_id(),
    ]);
});

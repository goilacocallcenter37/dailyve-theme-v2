<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'siteName' => $this->siteName(),
            'appStore' => home_url('/wp-content/themes/dailyve-theme/resources/images/download-app-store.png'),
            'googlePlay' => home_url('/wp-content/themes/dailyve-theme/resources/images/download-gg-play.png'),
            'qrCode' => 'https://object.dailyve.com/dailyve/wp-content/uploads/2025/08/QR-CODE-APP-DLV.png',
        ];
    }
}

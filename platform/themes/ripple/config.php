<?php

use Botble\Theme\Theme;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials" and "views"
    |
    | [Notice] assets cannot inherit.
    |
     */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these event can be override by package config.
    |
     */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before'             => function (Theme $theme) {

        },
        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme'  => function (Theme $theme) {
            // You may use this event to set up your assets.
            $theme
                ->asset()
                ->container('footer')
                ->usePath()->add('jquery', 'plugins/jquery/jquery.min.js')
                ->usePath()->add('bootstrap-js', 'plugins/bootstrap/js/bootstrap.min.js', ['jquery'])
                ->usePath()->add('overflow-text', 'plugins/overflow-text.js', ['jquery'])
                ->usePath()->add('jquery.parallax', 'plugins/jquery.parallax-1.1.3.js', ['jquery'])
                ->usePath()->add('custom', 'js/custom.min.js', ['jquery'])
                ->usePath()->add('ripple.js', 'js/ripple.js', ['jquery'])
                ->usePath()->add('sweet-alert-js', 'js/sweetalert.min.js', ['jquery']);

            $theme
                ->asset()
                ->usePath()->add('bootstrap-css', 'plugins/bootstrap/css/bootstrap.min.css')
                ->usePath()->add('font-awesome', 'plugins/font-awesome/css/font-awesome.min.css')
                ->usePath()->add('ionicons', 'plugins/ionicons/css/ionicons.min.css')
                ->usePath()->add('style', 'css/style.css');

            if (function_exists('shortcode')) {
                $theme->composer(['page', 'post', 'index'], function (\Botble\Shortcode\View\View $view) {
                    $view->withShortcodes();
                });
            }
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [

            'default' => function (Theme $theme) {
            },
        ],
    ],
];

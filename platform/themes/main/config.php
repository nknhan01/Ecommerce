<?php

use Platform\Theme\Theme;

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
        'before' => function ($theme) {
            // You can remove this line anytime.
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.

        'beforeRenderTheme' => function (Theme $theme) {
            // You may use this event to set up your assets.
            //CDN CSS:
            $theme->asset()->container('after_header')->add('bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css'); //bootstrap css
            $theme->asset()->container('after_header')->add('uikit_js', 'https://getuikit.com/assets/uikit/dist/js/uikit.js'); //uikit css
            $theme->asset()->add('aos_animation', 'https://unpkg.com/aos@next/dist/aos.css'); //aos css
            $theme->asset()->add('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'); //fontawesome
            $theme->asset()->container('after_header')->add('animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'); //aminate 4.1.1
            $theme->asset()->add('swiper_css', 'https://unpkg.com/swiper/swiper-bundle.min.css'); //swiper css
            $theme->asset()->add('swiper_codebean', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css'); //swiper codebean
            // CDN JS:
            $theme->asset()->container('footer')->add('jqueryboostrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js'); //bootstrap js
            $theme->asset()->container('after_header')->add('uikit_icon_js', 'https://getuikit.com/assets/uikit/dist/js/uikit-icons.js'); //uikits js
            $theme->asset()->container('footer')->add('aos.js', 'https://unpkg.com/aos@next/dist/aos.js'); //aos css
            $theme->asset()->container('footer')->add('swiper_js', 'https://unpkg.com/swiper/swiper-bundle.min.js'); //swiper JS
            $theme->asset()->container('footer')->add('jquery', 'https://code.jquery.com/jquery-3.5.1.min.js'); //j_query
            $theme->asset()->container('footer')->add('cloudflare.js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'); //cloudflare js
            //Path CSS:
            $theme->asset()->container('after_header')->usePath()->add('common', 'css/common.css'); //css
            // Path JS:
            $theme->asset()->container('footer')->usePath()->add('script', 'js/common.js'); //js

            if (function_exists('shortcode')) {
                $theme->composer(['index', 'page', 'post'], function (\Platform\Shortcode\View\View $view) {
                    $view->withShortcodes();
                });
            }
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [

            'default' => function ($theme) {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
            }
        ]
    ]
];

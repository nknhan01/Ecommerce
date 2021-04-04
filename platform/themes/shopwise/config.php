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

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function (Theme $theme)
        {

            // You may use this event to set up your assets.
            $theme->asset()->usePath()->add('animate', 'css/animate.css');
            $theme->asset()->usePath()->add('bootstrap-css', 'bootstrap/css/bootstrap.min.css');
            $theme->asset()->usePath()->add('ionicons', 'css/ionicons.min.css');
            $theme->asset()->usePath()->add('themify-icons', 'css/themify-icons.css');
            $theme->asset()->usePath()->add('linearicons', 'css/linearicons.css');
            $theme->asset()->usePath()->add('flaticon', 'css/flaticon.css');
            $theme->asset()->usePath()->add('simple-line-icons', 'css/simple-line-icons.css');
            $theme->asset()->usePath()->add('owl.carousel', 'plugins/owlcarousel/css/owl.carousel.min.css');
            $theme->asset()->usePath()->add('owl.theme', 'plugins/owlcarousel/css/owl.theme.css');
            $theme->asset()->usePath()->add('owl.theme.default', 'plugins/owlcarousel/css/owl.theme.default.min.css');
            $theme->asset()->usePath()->add('slick-theme-css', 'plugins/slick/slick-theme.css');
            $theme->asset()->usePath()->add('slick-css', 'plugins/slick/slick.css');
            $theme->asset()->usePath()->add('magnific-popup-css', 'css/magnific-popup.css');
            $theme->asset()->usePath()->add('style', 'css/style.css', [], [], '1.0.17');

            if (BaseHelper::siteLanguageDirection() == 'rtl') {
                $theme->asset()->usePath()->add('rtl-style', 'css/rtl-style.css', [], [], '1.0.16');
            }

            $theme->asset()->container('header')->usePath()->add('jquery', 'js/jquery-3.5.1.min.js');
            $theme->asset()->container('footer')->usePath()->add('popper', 'js/popper.min.js', ['jquery']);
            $theme->asset()->container('footer')->usePath()->add('bootstrap-js', 'bootstrap/js/bootstrap.min.js', ['jquery']);
            $theme->asset()->container('footer')->usePath()->add('magnific-popup-js', 'js/magnific-popup.min.js', ['jquery']);
            $theme->asset()->container('footer')->usePath()->add('waypoints', 'js/waypoints.min.js', ['jquery'], [], '4.0.1');
            $theme->asset()->container('footer')->usePath()->add('slick-js', 'plugins/slick/slick.min.js');
            $theme->asset()->container('footer')->usePath()->add('carousel-js', 'plugins/owlcarousel/js/owl.carousel.min.js', ['jquery']);
            $theme->asset()->container('footer')->usePath()->add('elevatezoom-js', 'js/jquery.elevatezoom.js', ['jquery']);
            $theme->asset()->container('footer')->usePath()->add('scripts', 'js/scripts.js', ['jquery'], [], '1.0.16');
            $theme->asset()->container('footer')->usePath()->add('backend-js', 'js/backend.js', ['jquery'], [], '1.0.16');
            $theme->asset()->container('footer')->add('change-product-swatches', 'vendor/core/plugins/ecommerce/js/change-product-swatches.js', ['jquery']);

            $theme->asset()->container('footer')->usePath()->add('countdown', 'js/jquery.countdown.min.js', ['jquery']);
            /*$theme->asset()->container('footer')->usePath()->add('imagesloaded', 'js/imagesloaded.pkgd.min.js', ['jquery']);
            $theme->asset()->container('footer')->usePath()->add('isotope', 'js/isotope.min.js', ['jquery']);*/

            if (function_exists('shortcode')) {
                $theme->composer(['index', 'page', 'post', 'ecommerce.product'], function (\Platform\Shortcode\View\View $view) use ($theme) {
                    $theme->asset()->container('footer')->usePath()->add('app-js', 'js/app.js', ['jquery', 'carousel-js'], [], '1.0.16');
                    $view->withShortcodes();
                });
            }
        },
    ]
];

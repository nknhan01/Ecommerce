<?php
/**
 * Date: 22/07/2015
 * Time: 8:11 PM
 */

return [
    'offline'        => env('ASSETS_OFFLINE', true),
    'enable_version' => env('ASSETS_ENABLE_VERSION', false),
    'version'        => env('ASSETS_VERSION', time()),
    'scripts'        => [
        'respond',
        'excanvas',
        'ie8-fix',
        'modernizr',
        'select2',
        'datepicker',
        'cookie',
        'core',
        'app',
        'toastr',
        'pace',
        'custom-scrollbar',
        'stickytableheaders',
        'jquery-waypoints',
        'spectrum',
        'fancybox',
    ],
    'styles'         => [
        'bootstrap',
        'fontawesome',
        'simple-line-icons',
        'select2',
        'pace',
        'toastr',
        'custom-scrollbar',
        'datepicker',
        'spectrum',
        'fancybox',
    ],
    'resources'      => [
        'scripts' => [
            'core'                   => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/js/core.js',
                ],
            ],
            'app'                    => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/js/app.js',
                ],
            ],
            'modernizr'              => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/modernizr/modernizr.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js',
                ],
            ],
            'respond'                => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/respond.min.js',
                ],
            ],
            'excanvas'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/excanvas.min.js',
                ],
            ],
            'ie8-fix'                => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/ie8.fix.min.js',
                ],
            ],
            'counterup'              => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/libraries/counterup/jquery.counterup.min.js',
                    ],
                ],
            ],
            'jquery-validation'      => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/libraries/jquery-validation/jquery.validate.min.js',
                        '/vendor/core/libraries/jquery-validation/additional-methods.min.js',
                    ],
                ],
            ],
            'blockui'                => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jquery.blockui.min.js',
                ],
            ],
            'jquery-ui'              => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jquery-ui/jquery-ui.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
                ],
            ],
            'cookie'                 => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jquery-cookie/jquery.cookie.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js',
                ],
            ],
            'jqueryTree'             => [
                'use_cdn'       => false,
                'location'      => 'footer',
                'include_style' => true,
                'src'           => [
                    'local' => '/vendor/core/libraries/jquery-tree/jquery.tree.min.js',
                ],
            ],
            'bootstrap-editable'     => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap3-editable/js/bootstrap-editable.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js',
                ],
            ],
            'toastr'                 => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/toastr/toastr.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.js',
                ],
            ],
            'pace'                   => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/pace/pace.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
            'fancybox'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/fancybox/jquery.fancybox.min.js',
                    'cdn'   => '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js',
                ],
            ],
            'datatables'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/libraries/datatables/media/js/jquery.dataTables.min.js',
                        '/vendor/core/libraries/datatables/media/js/dataTables.bootstrap.min.js',
                        '/vendor/core/libraries/datatables/extensions/Buttons/js/dataTables.buttons.min.js',
                        '/vendor/core/libraries/datatables/extensions/Buttons/js/buttons.bootstrap.min.js',
                    ],
                ],
            ],

            'raphael'                => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/libraries/raphael-min.js',
                    ],
                ],
            ],
            'morris'                 => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/morris/morris.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js',
                ],
            ],
            'select2'                => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/select2/js/select2.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
            ],
            'cropper'                => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/cropper.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/cropper/0.7.9/cropper.min.js',
                ],
            ],
            'datepicker'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                ],
            ],
            'sortable'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/sortable/sortable.min.js',
                ],
            ],
            'custom-scrollbar'       => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/mcustom-scrollbar/jquery.mCustomScrollbar.js',
                ],
            ],
            'stickytableheaders'     => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/stickytableheaders/jquery.stickytableheaders.js',
                ],
            ],
            'equal-height'           => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jQuery.equalHeights/jquery.equalheights.min.js',
                ],
            ],
            'are-you-sure'           => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jquery.are-you-sure/jquery.are-you-sure.js',
                ],
            ],
            'moment'                 => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/moment-with-locales.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js',
                ],
            ],
            'datetimepicker'         => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js',
                ],
            ],
            'jquery-waypoints'       => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jquery-waypoints/jquery.waypoints.min.js',
                ],
            ],
            'colorpicker'            => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
                ],
            ],
            'timepicker'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
                ],
            ],
            'spectrum'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/spectrum/spectrum.js',
                ],
            ],
            'input-mask'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jquery-inputmask/jquery.inputmask.bundle.min.js',
                ],
            ],
            'form-validation'        => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/js-validation/js/js-validation.js',
                ],
            ],
            // End JS
        ],
        /* -- STYLESHEET ASSETS -- */
        'styles'  => [
            'bootstrap'           => [
                'use_cdn'    => true,
                'location'   => 'header',
                'src'        => [
                    'local' => '/vendor/core/libraries/bootstrap/css/bootstrap.min.css',
                    'cdn'   => '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css',
                ],
            ],
            'fontawesome'         => [
                'use_cdn'    => true,
                'location'   => 'header',
                'src'        => [
                    'local' => '/vendor/core/libraries/font-awesome/css/fontawesome.min.css',
                    'cdn'   => '//use.fontawesome.com/releases/v5.0.13/css/all.css',
                ],
            ],
            'simple-line-icons'   => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/simple-line-icons/css/simple-line-icons.css',
                ],
            ],
            'core'                => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/css/core.css',
                ],
            ],
            'jqueryTree'          => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jquery-tree/jquery.tree.min.css',
                ],
            ],
            'videojs'             => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/videojs/video-js.min.css',
                    'cdn'   => '//vjs.zencdn.net/5.8/video-js.min.css',
                ],
            ],
            'jquery-ui'           => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/jquery-ui/jquery-ui.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css',
                ],
            ],
            'toastr'              => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/toastr/toastr.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.css',
                ],
            ],
            'pace'                => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/pace/pace-theme-minimal.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-minimal.css',
                ],
            ],
            'kendo'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/kendo/kendo.min.css',
                ],
            ],
            'datatables'          => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => [
                        '/vendor/core/libraries/datatables/media/css/dataTables.bootstrap.min.css',
                    ],
                ],
            ],
            'bootstrap-editable'  => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap3-editable/css/bootstrap-editable.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css',
                ],
            ],
            'morris'              => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/morris/morris.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
                ],
            ],
            'datepicker'          => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ],
            ],
            'select2'             => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => [
                        '/vendor/core/libraries/select2/css/select2.min.css',
                        '/vendor/core/libraries/select2/css/select2-bootstrap.min.css',
                    ],
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css',
                ],
            ],
            'fancybox'            => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/fancybox/jquery.fancybox.min.css',
                    'cdn'   => '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css',
                ],
            ],
            'custom-scrollbar'    => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/mcustom-scrollbar/jquery.mCustomScrollbar.css',
                ],
            ],
            'datetimepicker'      => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/css/bootstrap-datetimepicker.min.css',
                ],
            ],
            'colorpicker'         => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',
                ],
            ],
            'timepicker'          => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
                ],
            ],
            'spectrum'            => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/libraries/spectrum/spectrum.css',
                ],
            ],
        ],
    ],
];

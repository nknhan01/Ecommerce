<?php

return [
    'driver' => env('CMS_SETTING_STORE_DRIVER', 'database'),
    'cache' => [
        'enabled' => env('CMS_SETTING_STORE_CACHE', false),
    ],
];
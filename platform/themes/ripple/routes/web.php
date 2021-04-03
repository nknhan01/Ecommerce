<?php

Theme::routes();

Route::group(['namespace' => 'Theme\Ripple\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::get('/', 'RippleController@getIndex')->name('public.index');

        Route::get('sitemap.xml', [
            'as'   => 'public.sitemap',
            'uses' => 'RippleController@getSiteMap',
        ]);

        Route::get('{slug?}' . config('core.base.general.public_single_ending_url'), [
            'as'   => 'public.single',
            'uses' => 'RippleController@getView',
        ]);

    });

});

Route::group([
    'middleware' => 'api',
    'prefix'     => 'api/v1',
    'namespace'  => 'Theme\Ripple\Http\Controllers',
], function () {
    Route::get('search', 'RippleController@getSearch')->name('public.api.search');
});


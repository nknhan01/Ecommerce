<?php

Route::group(['namespace' => 'Botble\Theme\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'theme'], function () {
            Route::get('all', [
                'as'   => 'theme.index',
                'uses' => 'ThemeController@index',
            ]);

            Route::post('active', [
                'as'         => 'theme.active',
                'uses'       => 'ThemeController@postActivateTheme',
                'permission' => 'theme.index',
            ]);

            Route::post('remove', [
                'as'         => 'theme.remove',
                'uses'       => 'ThemeController@postRemoveTheme',
                'middleware' => 'preventDemo',
                'permission' => 'theme.index',
            ]);
        });

        Route::group(['prefix' => 'theme/options'], function () {
            Route::get('', [
                'as'   => 'theme.options',
                'uses' => 'ThemeController@getOptions',
            ]);

            Route::post('', [
                'as'   => 'theme.options',
                'uses' => 'ThemeController@postUpdate',
            ]);
        });

        Route::group(['prefix' => 'theme/custom-css'], function () {
            Route::get('', [
                'as'   => 'theme.custom-css',
                'uses' => 'ThemeController@getCustomCss',
            ]);

            Route::post('', [
                'as'         => 'theme.custom-css.post',
                'uses'       => 'ThemeController@postCustomCss',
                'permission' => 'theme.custom-css',
                'middleware' => 'preventDemo',
            ]);
        });
    });
});

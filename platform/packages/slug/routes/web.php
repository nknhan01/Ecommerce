<?php

Route::group(['namespace' => 'Platform\Slug\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => 'ajax'], function () {
        Route::group(['prefix' => 'slug'], function () {
            Route::post('create', [
                'as'   => 'slug.create',
                'uses' => 'SlugController@store',
            ]);
        });
    });

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'settings'], function () {
            Route::group(['prefix' => 'permalink'], function () {
                Route::get('', [
                    'as'         => 'slug.settings',
                    'uses'       => 'SlugController@getSettings',
                    'permission' => 'settings.options',
                ]);

                Route::post('', [
                    'as'         => 'slug.settings',
                    'uses'       => 'SlugController@postSettings',
                    'permission' => 'settings.options',
                    'middleware' => 'preventDemo',
                ]);
            });
        });
    });
});

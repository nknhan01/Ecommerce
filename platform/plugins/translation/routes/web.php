<?php

Route::group(['namespace' => 'Botble\Translation\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'system/translations'], function () {
            Route::get('/', [
                'as'         => 'translations.index',
                'uses'       => 'TranslationController@getIndex',
                'permission' => 'translations.edit',
            ]);

            Route::post('edit', [
                'as'         => 'translations.group.edit',
                'uses'       => 'TranslationController@update',
                'permission' => 'translations.edit',
            ]);

            Route::post('publish', [
                'as'         => 'translations.group.publish',
                'uses'       => 'TranslationController@postPublish',
                'permission' => 'translations.edit',
                'middleware' => 'preventDemo',
            ]);

            Route::post('import', [
                'as'         => 'translations.import',
                'uses'       => 'TranslationController@postImport',
                'permission' => 'translations.edit',
            ]);
        });
    });
});

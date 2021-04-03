<?php

Route::group(['namespace' => 'Platform\Translation\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'translations'], function () {

            Route::group(['prefix' => 'locales'], function () {
                Route::get('', [
                    'as'   => 'translations.locales',
                    'uses' => 'TranslationController@getLocales',
                ]);

                Route::post('', [
                    'as'         => 'translations.locales',
                    'uses'       => 'TranslationController@postLocales',
                    'middleware' => 'preventDemo',
                ]);

                Route::delete('{locale}', [
                    'as'         => 'translations.locales.delete',
                    'uses'       => 'TranslationController@deleteLocale',
                    'middleware' => 'preventDemo',
                ]);
            });

            Route::group(['prefix' => 'admin'], function () {
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

            Route::group(['prefix' => 'theme'], function () {
                Route::get('', [
                    'as'   => 'translations.theme-translations',
                    'uses' => 'TranslationController@getThemeTranslations',
                ]);

                Route::post('', [
                    'as'         => 'translations.theme-translations',
                    'uses'       => 'TranslationController@postThemeTranslations',
                    'middleware' => 'preventDemo',
                ]);
            });
        });
    });
});

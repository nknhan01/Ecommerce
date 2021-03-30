<?php

Route::group(['namespace' => 'Botble\Blog\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
            Route::resource('', 'PostController')->parameters(['' => 'post']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PostController@deletes',
                'permission' => 'posts.destroy',
            ]);

            Route::get('widgets/recent-posts', [
                'as'         => 'widget.recent-posts',
                'uses'       => 'PostController@getWidgetRecentPosts',
                'permission' => 'posts.index',
            ]);
        });

        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
            Route::resource('', 'CategoryController')->parameters(['' => 'category']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CategoryController@deletes',
                'permission' => 'categories.destroy',
            ]);
        });

        Route::group(['prefix' => 'tags', 'as' => 'tags.'], function () {
            Route::resource('', 'TagController')->parameters(['' => 'tag']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'TagController@deletes',
                'permission' => 'tags.destroy',
            ]);

            Route::get('all', [
                'as'         => 'all',
                'uses'       => 'TagController@getAllTags',
                'permission' => 'tags.index',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::get('search', [
                'as'   => 'public.search',
                'uses' => 'PublicController@getSearch',
            ]);

            Route::get('tag/{slug}', [
                'as'   => 'public.tag',
                'uses' => 'PublicController@getTag',
            ]);
        });
    }
});

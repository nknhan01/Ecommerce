<?php

Route::group(['namespace' => 'Platform\DemoHomework\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'demo-homeworks', 'as' => 'demo-homework.'], function () {
            Route::resource('', 'DemoHomeworkController')->parameters(['' => 'demo-homework']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'DemoHomeworkController@deletes',
                'permission' => 'demo-homework.destroy',
            ]);
        });
    });

});

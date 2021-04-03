<?php

Route::group([
    'namespace'  => 'Platform\Member\Http\Controllers',
    'prefix'     => BaseHelper::getAdminPrefix(),
    'middleware' => ['web', 'auth'],
], function () {

    Route::group(['prefix' => 'members', 'as' => 'member.'], function () {

        Route::resource('', 'MemberController')->parameters(['' => 'member']);

        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'MemberController@deletes',
            'permission' => 'member.destroy',
        ]);
    });
});

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::group([
            'namespace'  => 'Platform\Member\Http\Controllers',
            'middleware' => ['web'],
            'as'         => 'public.member.',
        ], function () {

            Route::group(['middleware' => ['member.guest']], function () {
                Route::get('login', 'LoginController@showLoginForm')->name('login');
                Route::post('login', 'LoginController@login')->name('login.post');

                Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
                Route::post('register', 'RegisterController@register')->name('register.post');

                Route::get('verify', 'RegisterController@getVerify')->name('verify');

                Route::get('password/request',
                    'ForgotPasswordController@showLinkRequestForm')->name('password.request');
                Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
                Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
                Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
            });

            Route::group([
                'middleware' => [setting('verify_account_email', config('plugins.member.general.verify_email')) ? 'member.guest' : 'member'],
            ], function () {
                Route::get('register/confirm/resend',
                    'RegisterController@resendConfirmation')->name('resend_confirmation');
                Route::get('register/confirm/{email}', 'RegisterController@confirm')->name('confirm');
            });
        });

        Route::group([
            'namespace'  => 'Platform\Member\Http\Controllers',
            'middleware' => ['web', 'member'],
            'as'         => 'public.member.',
        ], function () {
            Route::group([
                'prefix' => 'account',
            ], function () {

                Route::post('logout', 'LoginController@logout')->name('logout');

                Route::get('dashboard', [
                    'as'   => 'dashboard',
                    'uses' => 'PublicController@getDashboard',
                ]);

                Route::get('settings', [
                    'as'   => 'settings',
                    'uses' => 'PublicController@getSettings',
                ]);

                Route::post('settings', [
                    'as'   => 'post.settings',
                    'uses' => 'PublicController@postSettings',
                ]);

                Route::get('security', [
                    'as'   => 'security',
                    'uses' => 'PublicController@getSecurity',
                ]);

                Route::put('security', [
                    'as'   => 'post.security',
                    'uses' => 'PublicController@postSecurity',
                ]);

                Route::post('avatar', [
                    'as'   => 'avatar',
                    'uses' => 'PublicController@postAvatar',
                ]);

            });

            Route::group(['prefix' => 'ajax/members'], function () {
                Route::get('activity-logs', [
                    'as'   => 'activity-logs',
                    'uses' => 'PublicController@getActivityLogs',
                ]);

                Route::post('upload', [
                    'as'   => 'upload',
                    'uses' => 'PublicController@postUpload',
                ]);
            });

            Route::group([
                'prefix' => 'account/posts',
            ], function () {

                Route::get('', [
                    'as'   => 'posts.index',
                    'uses' => 'PostController@index',
                ]);

                Route::get('create', [
                    'as'   => 'posts.create',
                    'uses' => 'PostController@create',
                ]);

                Route::post('create', [
                    'as'   => 'posts.create',
                    'uses' => 'PostController@store',
                ]);

                Route::get('edit/{id}', [
                    'as'   => 'posts.edit',
                    'uses' => 'PostController@edit',
                ]);

                Route::post('edit/{id}', [
                    'as'   => 'posts.edit',
                    'uses' => 'PostController@update',
                ]);

            });

            Route::group(['prefix' => 'ajax/members'], function () {
                Route::delete('delete/{id}', [
                    'as'   => 'posts.destroy',
                    'uses' => 'PostController@delete',
                ]);
                Route::get('tags/all', [
                    'as'   => 'tags.all',
                    'uses' => 'PostController@getAllTags',
                ]);
            });
        });

    });
}

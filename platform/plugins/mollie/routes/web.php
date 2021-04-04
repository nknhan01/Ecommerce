<?php

Route::group(['namespace' => 'Platform\Mollie\Http\Controllers', 'middleware' => ['core']], function () {
    Route::post('mollie/payment/callback', [
        'as'   => 'mollie.payment.callback',
        'uses' => 'MollieController@paymentCallback',
    ]);
});

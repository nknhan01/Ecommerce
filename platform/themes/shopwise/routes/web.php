<?php

Route::group(['namespace' => 'Theme\Shopwise\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::get('ajax/products', 'ShopwiseController@ajaxGetProducts')
            ->name('public.ajax.products');

        Route::get('ajax/featured-product-categories', 'ShopwiseController@getFeaturedProductCategories')
            ->name('public.ajax.featured-product-categories');

        Route::get('ajax/trending-products', 'ShopwiseController@ajaxGetTrendingProducts')
            ->name('public.ajax.trending-products');

        Route::get('ajax/featured-brands', 'ShopwiseController@ajaxGetFeaturedBrands')
            ->name('public.ajax.featured-brands');

        Route::get('ajax/featured-products', 'ShopwiseController@ajaxGetFeaturedProducts')
            ->name('public.ajax.featured-products');

        Route::get('ajax/top-rated-products', 'ShopwiseController@ajaxGetTopRatedProducts')
            ->name('public.ajax.top-rated-products');

        Route::get('ajax/on-sale-products', 'ShopwiseController@ajaxGetOnSaleProducts')
            ->name('public.ajax.on-sale-products');

        Route::get('ajax/cart', 'ShopwiseController@ajaxCart')
            ->name('public.ajax.cart');

        Route::get('ajax/quick-view/{id}', 'ShopwiseController@getQuickView')
            ->name('public.ajax.quick-view');

        Route::get('ajax/featured-posts', 'ShopwiseController@ajaxGetFeaturedPosts')
            ->name('public.ajax.featured-posts');

        Route::get('ajax/testimonials', 'ShopwiseController@ajaxGetTestimonials')
            ->name('public.ajax.testimonials');

        Route::get('ajax/product-reviews/{id}', 'ShopwiseController@ajaxGetProductReviews')
            ->name('public.ajax.product-reviews');

        Route::get('ajax/get-flash-sale/{id}', 'ShopwiseController@ajaxGetFlashSale')
            ->name('public.ajax.get-flash-sale');
    });

});

Theme::routes();

Route::group(['namespace' => 'Theme\Shopwise\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::get('/', 'ShopwiseController@getIndex')
            ->name('public.index');

        Route::get('sitemap.xml', 'ShopwiseController@getSiteMap')
            ->name('public.sitemap');

        Route::get('{slug?}' . config('core.base.general.public_single_ending_url'), 'ShopwiseController@getView')
            ->name('public.single');

    });

});

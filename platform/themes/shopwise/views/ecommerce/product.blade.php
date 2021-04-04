@php
    Theme::asset()->remove('app-js');

    $originalProduct = $product;
    $selectedAttrs = [];
    $productImages = $product->images;
    if ($product->is_variation) {
        $product = get_parent_product($product->id);
        $selectedAttrs = app(\Platform\Ecommerce\Repositories\Interfaces\ProductVariationInterface::class)
            ->getAttributeIdsOfChildrenProduct($originalProduct->id);
        if (count($productImages) == 0) {
            $productImages = $product->images;
        }
    } else {
        $selectedAttrs = $product->defaultVariation->productAttributes->pluck('id')->all();
    }

@endphp

@php Theme::set('pageName', $product->name) @endphp

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5 mb-4 mb-md-0">
                <div class="product-image">
                    <div class="product_img_box">
                        <img id="product_img" src="{{ RvMedia::getImageUrl($product->image, null, false, RvMedia::getDefaultImage()) }}" data-zoom-image="{{ RvMedia::getImageUrl($product->image, null, false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}" />
                        <a href="#" class="product_img_zoom" title="Zoom">
                            <span class="linearicons-zoom-in"></span>
                        </a>
                    </div>
                    <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-slides-to-show="4" data-slides-to-scroll="1" data-infinite="false">
                        @foreach ($productImages as $img)
                            <div class="item">
                                <a href="#" class="product_gallery_item @if ($loop->first) active @endif" data-image="{{ RvMedia::getImageUrl($img) }}" data-zoom-image="{{ RvMedia::getImageUrl($img) }}">
                                    <img src="{{ RvMedia::getImageUrl($img, 'thumb') }}" alt="{{ $originalProduct->name }}"/>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7">
                <div class="pr_detail">
                <div class="product_description">
                    <h4 class="product_title"><a href="{{ $product->url }}">{{ $product->name }}</a></h4>
                    <div class="product_price">
                        <span class="price product-sale-price-text">{{ format_price($product->front_sale_price_with_taxes) }}</span>
                        <del class="product-price-text" @if ($product->front_sale_price == $product->price) style="display: none" @endif>{{ format_price($product->price_with_taxes) }}</del>
                        <div class="on_sale" @if ($product->front_sale_price == $product->price) style="display: none" @endif>
                            <span class="on_sale_percentage_text">{{ get_sale_percentage($product->price, $product->front_sale_price) }}</span> <span>{{ __('Off') }}</span>
                        </div>
                    </div>
                    @if (EcommerceHelper::isReviewEnabled())
                        @php $countRating = get_count_reviewed_of_product($originalProduct->id) @endphp
                        @if ($countRating > 0)
                            <div class="rating_wrap">
                                <div class="rating">
                                    <div class="product_rate" style="width: {{ get_average_star_of_product($originalProduct->id) * 20 }}%"></div>
                                </div>
                                <span class="rating_num">({{ $countRating }})</span>
                            </div>
                        @endif
                    @endif
                    <div class="clearfix"></div>
                    <div class="pr_desc">
                        <p>{!! clean($product->description, 'youtube') !!}</p>
                    </div>
                    @if ($product->variations()->count() > 0)
                        <div class="pr_switch_wrap">
                            {!! render_product_swatches($product, [
                                'selected' => $selectedAttrs,
                                'view'     => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer'
                            ]) !!}
                        </div>
                    @endif

                    <hr />
                    <div class="cart_extra">
                        <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}">
                            @csrf
                            {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null) !!}
                            <input type="hidden" name="id" id="hidden-product-id" value="{{ ($originalProduct->is_variation || !$originalProduct->defaultVariation->product_id) ? $originalProduct->id : $originalProduct->defaultVariation->product_id }}"/>
                            <div class="cart-product-quantity">
                                <div class="quantity float-left">
                                    <input type="button" value="-" class="minus">
                                    <input type="text" name="qty" value="1" title="{{ __('Qty') }}" class="qty" size="4">
                                    <input type="button" value="+" class="plus">
                                </div> &nbsp;
                                <div class="float-right number-items-available" style="display: none; line-height: 45px;"></div>
                            </div>
                            <br>
                            <div class="cart_btn">
                                <button class="btn btn-fill-out btn-addtocart" type="submit"><i class="icon-basket-loaded"></i> {{ __('Add to cart') }}</button>
                                @if (EcommerceHelper::isQuickBuyButtonEnabled())
                                    &nbsp;
                                    <button class="btn btn-dark btn-addtocart" type="submit" name="checkout">{{ __('Quick Buy') }}</button>
                                @endif
                                {{--<a class="add_compare" href="#"><i class="icon-shuffle"></i></a>--}}
                                <a class="add_wishlist js-add-to-wishlist-button" href="{{ route('public.wishlist.add', $product->id) }}"><i class="icon-heart"></i></a>
                            </div>
                            <br>
                            <div class="success-message text-success" style="display: none;">
                                <span></span>
                            </div>
                            <div class="error-message text-danger" style="display: none;">
                                <span></span>
                            </div>
                        </form>
                    </div>
                    <hr />
                    <ul class="product-meta">
                        <li>{{ __('SKU') }}: <span id="product-sku">{{ $product->sku }}</span></li>
                        <li>{{ __('Category') }}:
                            @foreach ($product->categories()->get() as $category)
                                <a href="{{ $category->url }}">{{ $category->name }}</a>@if (!$loop->last),@endif
                            @endforeach
                        </li>
                        @if (!$product->tags->isEmpty())
                            <li>{{ __('Tags') }}:
                                @foreach ($product->tags as $tag)
                                    <a href="{{ $tag->url }}" rel="tag">{{ $tag->name }}</a>@if (!$loop->last),@endif
                                @endforeach
                            </li>
                        @endif
                    </ul>

                    <div class="product_share">
                        <span>{{ __('Share') }}:</span>
                        <ul class="social_icons">
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($product->url) }}&title={{ rawurldecode($product->description) }}" target="_blank" title="{{ __('Share on Facebook') }}"><i class="ion-social-facebook"></i></a></li>
                            <li><a href="https://twitter.com/intent/tweet?url={{ urlencode($product->url) }}&text={{ rawurldecode($product->description) }}" target="_blank" title="{{ __('Share on Twitter') }}"><i class="ion-social-twitter"></i></a></li>
                            <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($product->url) }}&summary={{ rawurldecode($product->description) }}&source=Linkedin" title="{{ __('Share on Linkedin') }}" target="_blank"><i class="ion-social-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="large_divider clearfix"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="tab-style3">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">{{ __('Description') }}</a>
                        </li>
                        @if (EcommerceHelper::isReviewEnabled())
                            <li class="nav-item">
                                <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">{{ __('Reviews') }} ({{ $countRating }})</a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content shop_info_tab">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <div id="app">
                                {!! clean($product->content, 'youtube') !!}
                            </div>
                            @if (theme_option('facebook_comment_enabled_in_product', 'yes') == 'yes')
                                <br />
                                {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, Theme::partial('comments')) !!}
                            @endif
                        </div>
                        @if (EcommerceHelper::isReviewEnabled())
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div id="list-reviews">
                                    <div class="comments">
                                        <h5 class="product_tab_title">{{ __(':count Reviews For :product', ['count' => $countRating, 'product' => $product->name]) }}</h5>
                                        <product-reviews-component url="{{ route('public.ajax.product-reviews', $product->id) }}"></product-reviews-component>
                                    </div>
                                </div>
                                <div class="review_form field_form mt-3">
                                    <h5>{{ __('Add a review') }}</h5>
                                    @if (!auth('customer')->check())
                                        <p class="text-danger">{{ __('Please') }} <a href="{{ route('customer.login') }}">{{ __('login') }}</a> {{ __('to write review!') }}</p>
                                    @endif
                                    {!! Form::open(['route' => 'public.reviews.create', 'method' => 'post', 'class' => 'row form-review-product']) !!}
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="star" value="1">
                                        <div class="form-group col-12">
                                            <div class="star_rating">
                                                <span data-value="1"><i class="ion-star"></i></span>
                                                <span data-value="2"><i class="ion-star"></i></span>
                                                <span data-value="3"><i class="ion-star"></i></span>
                                                <span data-value="4"><i class="ion-star"></i></span>
                                                <span data-value="5"><i class="ion-star"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-12">
                                            <textarea class="form-control" name="comment" id="txt-comment" rows="4" placeholder="{{ __('Write your review') }}" @if (!auth('customer')->check()) disabled @endif></textarea>
                                        </div>
                                        <div class="form-group col-12">
                                            <button type="submit" class="btn btn-fill-out @if (!auth('customer')->check()) btn-disabled @endif" @if (!auth('customer')->check()) disabled @endif name="submit" value="Submit">Submit Review</button>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @php
            $crossSellProducts = get_cross_sale_products($originalProduct);
        @endphp
        @if (count($crossSellProducts) > 0)
            <div class="row">
                <div class="col-12">
                    <div class="small_divider"></div>
                    <div class="divider"></div>
                    <div class="medium_divider"></div>
                </div>
            </div>
            <div class="row shop_container grid">
                <div class="col-12">
                    <div class="heading_s1">
                        <h3>{{ __('Customers who bought this item also bought') }}</h3>
                    </div>
                    <div class="releted_product_slider carousel_slider owl-carousel owl-theme" data-margin="20" data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>
                        @foreach ($crossSellProducts as $crossId)
                            {!! Theme::partial('product-item-grid', ['product' => get_product_by_id($crossId)]) !!}
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="row shop_container grid">
            <div class="col-12">
                <div class="heading_s1">
                    <h3>{{ __('Related Products') }}</h3>
                </div>
                <div class="releted_product_slider carousel_slider owl-carousel owl-theme" data-margin="20" data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>
                    @php
                        $relatedProducts = get_related_products($product);
                    @endphp
                    @if (!empty($relatedProducts))
                        @foreach ($relatedProducts as $related)
                            {!! Theme::partial('product-item-grid', ['product' => $related]) !!}
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@php
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

    Theme::asset()->remove('app-js');

@endphp

<div class="ajax_quick_view">
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
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
        <div class="col-lg-6 col-md-6">
            <div class="pr_detail">
                <div class="product_description">
                    <h4 class="product_title"><a href="{{ $product->url }}">{{ $product->name }}</a></h4>
                    <div class="product_price">
                        <span class="price">{{ format_price($product->front_sale_price_with_taxes) }}</span>
                        @if ($product->front_sale_price !== $product->price)
                            <del>{{ format_price($product->price_with_taxes) }}</del>
                            <div class="on_sale">
                                <span>{{ __(':percentage Off', ['percentage' => get_sale_percentage($product->price, $product->front_sale_price)]) }}</span>
                            </div>
                        @endif
                    </div>
                    @if (EcommerceHelper::isReviewEnabled())
                        @php $countRating = get_count_reviewed_of_product($product->id) @endphp
                        @if ($countRating > 0)
                            <div class="rating_wrap">
                                <div class="rating">
                                    <div class="product_rate" style="width: {{ get_average_star_of_product($product->id) * 20 }}%"></div>
                                </div>
                                <span class="rating_num">({{ $countRating }})</span>
                            </div>
                        @endif
                    @endif
                    <div class="clearfix"></div>
                    <div class="pr_desc">
                        <p>{!! clean($product->description) !!}</p>
                    </div>
                    @if ($product->variations()->count() > 0)
                        <div class="pr_switch_wrap">
                            {!! render_product_swatches($product, [
                                'selected' => $selectedAttrs,
                                'view'     => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer'
                            ]) !!}
                        </div>
                    @endif
                </div>
                <hr />
                <div class="cart_extra">
                    <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}">
                        @csrf
                        <input type="hidden" name="id" id="hidden-product-id" value="{{ ($originalProduct->is_variation || !$originalProduct->defaultVariation->product_id) ? $originalProduct->id : $originalProduct->defaultVariation->product_id }}"/>
                        <div class="cart-product-quantity">
                            <div class="quantity">
                                <input type="button" value="-" class="minus">
                                <input type="text" name="qty" value="1" title="{{ __('Qty') }}" class="qty" size="4">
                                <input type="button" value="+" class="plus">
                            </div>
                        </div>
                        <br>
                        <div class="cart_btn">
                            <button class="btn btn-fill-out btn-addtocart" type="submit"><i class="icon-basket-loaded"></i> {{ __('Add to cart') }}</button>
                            {{--<a class="add_compare" href="#"><i class="icon-shuffle"></i></a>--}}
                            <a class="add_wishlist js-add-to-wishlist-button" href="{{ route('public.wishlist.add', $product->id) }}"><i class="icon-heart"></i></a>
                        </div>
                        <br>
                        <div class="success-message text-success text-center" style="display: none;">
                            <span></span>
                        </div>
                        <div class="error-message text-danger text-center" style="display: none;">
                            <span></span>
                        </div>
                    </form>
                </div>
                <hr />
                <ul class="product-meta">
                    <li>{{ __('SKU') }}: {{ $product->sku }}</li>
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

<script src="{{ Theme::asset()->url('js/scripts.js') }}"></script>

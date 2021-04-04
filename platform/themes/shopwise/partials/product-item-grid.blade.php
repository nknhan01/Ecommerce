<div class="product">
    <div class="product_img">
        <a href="{{ $product->url }}">
            <img src="{{ RvMedia::getImageUrl($product->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}">
        </a>
        <div class="product_action_box">
            <ul class="list_none pr_action_btn">
                <li class="add-to-cart"><a class="add-to-cart-button" data-id="{{ $product->id }}" href="{{ route('public.cart.add-to-cart') }}"><i class="icon-basket-loaded"></i> {{ __('Add To Cart') }}</a></li>
                {{--<li><a href="shop-compare.html" class="popup-ajax"><i class="icon-shuffle"></i></a></li>--}}
                <li><a href="{{ route('public.ajax.quick-view', $product->id) }}" class="popup-ajax"><i class="icon-magnifier-add"></i></a></li>
                <li><a class="js-add-to-wishlist-button" href="{{ route('public.wishlist.add', $product->id) }}"><i class="icon-heart"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="product_info">
        <h6 class="product_title"><a href="{{ $product->url }}">{{ $product->name }}</a></h6>
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
        <div class="pr_desc">
            <p>{!! clean($product->description) !!}</p>
        </div>
        @if (count($product->variationAttributeSwatchesForProductList))
            <div class="pr_switch_wrap">
                <div class="product_color_switch">
                    @foreach($product->variationAttributeSwatchesForProductList->unique('attribute_id') as $attribute)
                        @if ($attribute->display_layout == 'visual')
                            <span @if ($attribute->image) style="background-image: url({{ RvMedia::getImageUrl($attribute->image) }});" @else data-color="{{ $attribute->color }}" @endif></span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        <div class="list_product_action_box">
            <ul class="list_none pr_action_btn">
                <li class="add-to-cart"><a class="add-to-cart-button" data-id="{{ $product->id }}" href="{{ route('public.cart.add-to-cart') }}"><i class="icon-basket-loaded"></i> {{ __('Add To Cart') }}</a></li>
                {{--<li><a href="shop-compare.html" class="popup-ajax"><i class="icon-shuffle"></i></a></li>--}}
                <li><a href="{{ route('public.ajax.quick-view', $product->id) }}" class="popup-ajax"><i class="icon-magnifier-add"></i></a></li>
                <li><a class="js-add-to-wishlist-button" href="{{ route('public.wishlist.add', $product->id) }}"><i class="icon-heart"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="product_wrap">
    <div class="product_img">
        <a href="{{ $product->url }}">
            <img src="{{ RvMedia::getImageUrl($product->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}">
            @if (isset($product->images[1]))
                <img class="product_hover_img" src="{{ RvMedia::getImageUrl($product->images[1], 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}">
            @endif
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
    </div>
</div>

<script>
    $(document).ready(function () {
        function carousel_slider() {
            $('.carousel_slider').each( function() {
                var $carousel = $(this);
                $carousel.owlCarousel({
                    dots : $carousel.data("dots"),
                    loop : $carousel.data("loop"),
                    items: $carousel.data("items"),
                    margin: $carousel.data("margin"),
                    mouseDrag: $carousel.data("mouse-drag"),
                    touchDrag: $carousel.data("touch-drag"),
                    autoHeight: $carousel.data("autoheight"),
                    center: $carousel.data("center"),
                    nav: $carousel.data("nav"),
                    rewind: $carousel.data("rewind"),
                    navText: ['<i class="ion-ios-arrow-left"></i>', '<i class="ion-ios-arrow-right"></i>'],
                    autoplay : $carousel.data("autoplay"),
                    animateIn : $carousel.data("animate-in"),
                    animateOut: $carousel.data("animate-out"),
                    autoplayTimeout : $carousel.data("autoplay-timeout"),
                    smartSpeed: $carousel.data("smart-speed"),
                    responsive: $carousel.data("responsive")
                });
            });
        }

        function slick_slider() {
            $('.slick_slider').each( function() {
                var $slick_carousel = $(this);
                $slick_carousel.slick({
                    arrows: $slick_carousel.data("arrows"),
                    dots: $slick_carousel.data("dots"),
                    infinite: $slick_carousel.data("infinite"),
                    centerMode: $slick_carousel.data("center-mode"),
                    vertical: $slick_carousel.data("vertical"),
                    fade: $slick_carousel.data("fade"),
                    cssEase: $slick_carousel.data("css-ease"),
                    autoplay: $slick_carousel.data("autoplay"),
                    verticalSwiping: $slick_carousel.data("vertical-swiping"),
                    autoplaySpeed: $slick_carousel.data("autoplay-speed"),
                    speed: $slick_carousel.data("speed"),
                    pauseOnHover: $slick_carousel.data("pause-on-hover"),
                    draggable: $slick_carousel.data("draggable"),
                    slidesToShow: $slick_carousel.data("slides-to-show"),
                    slidesToScroll: $slick_carousel.data("slides-to-scroll"),
                    asNavFor: $slick_carousel.data("as-nav-for"),
                    focusOnSelect: $slick_carousel.data("focus-on-select"),
                    responsive: $slick_carousel.data("responsive")
                });
            });
        }

        $('.popup-ajax').magnificPopup({
            type: 'ajax',
            callbacks: {
                ajaxContentAdded: function() {
                    carousel_slider();
                    slick_slider();
                }
            }
        });
    });
</script>

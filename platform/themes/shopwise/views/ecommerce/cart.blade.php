@php Theme::set('pageName', __('Shopping Cart')); $crossSellProducts = []; @endphp

<div class="section">
    <div class="container">
        @if (Cart::instance('cart')->count() > 0)
            @php
                $productIds = Cart::instance('cart')->content()->pluck('id')->toArray();

                if ($productIds) {
                    $products = get_products([
                        'condition' => [
                            ['ec_products.id', 'IN', $productIds],
                        ],
                    ]);
                }
            @endphp

            @if (session()->has('success_msg'))
                <div class="alert alert-success">
                    <span>{{ session('success_msg') }}</span>
                </div>
            @endif

            @if (session()->has('error_msg'))
                <div class="alert alert-warning">
                    <span>{{ session('error_msg') }}</span>
                </div>
            @endif

            @if (isset($errors) && count($errors->all()) > 0)
                <div class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="form--shopping-cart" method="post" action="{{ route('public.cart.update') }}">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive shop_cart_table">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="product-thumbnail">{{ __('Image') }}</th>
                                    <th class="product-name">{{ __('Product') }}</th>
                                    <th class="product-price">{{ __('Price') }}</th>
                                    <th class="product-quantity">{{ __('Quantity') }}</th>
                                    <th class="product-subtotal">{{ __('Total') }}</th>
                                    <th class="product-remove">{{ __('Remove') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($products) && $products)
                                    @foreach(Cart::instance('cart')->content() as $key => $cartItem)
                                        @php
                                            $product = $products->where('id', $cartItem->id)->first();
                                            if ($product) {
                                                $crossSellProducts = array_unique(array_merge($crossSellProducts, get_cross_sale_products($product->original_product)));
                                            }
                                        @endphp

                                        @if (!empty($product))
                                            <tr>
                                                <td class="product-thumbnail">
                                                    <a href="{{ $product->original_product->url }}">
                                                        <img src="{{ $cartItem->options['image'] }}" alt="{{ $product->name }}" />
                                                    </a>
                                                </td>
                                                <td class="product-name" data-title="Product">
                                                    <a href="{{ $product->original_product->url }}" title="{{ $product->name }}">{{ $product->name }}</a>
                                                    <p style="margin-bottom: 0">
                                                        <small>{{ $cartItem->options['attributes'] ?? '' }}</small>
                                                    </p>
                                                    @if (!empty($cartItem->options['extras']) && is_array($cartItem->options['extras']))
                                                        @foreach($cartItem->options['extras'] as $option)
                                                            @if (!empty($option['key']) && !empty($option['value']))
                                                                <p style="margin-bottom: 0;"><small>{{ $option['key'] }}: <strong> {{ $option['value'] }}</strong></small></p>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="product-price" data-title="Price">
                                                    {{ format_price($cartItem->price) }}
                                                    <input type="hidden" name="items[{{ $key }}][rowId]" value="{{ $cartItem->rowId }}">
                                                </td>
                                                <td class="product-quantity" data-title="Quantity">
                                                    <div class="quantity">
                                                        <input type="button" value="-" class="minus">
                                                        <input type="text" value="{{ $cartItem->qty }}" title="Qty" class="qty" size="4" name="items[{{ $key }}][values][qty]">
                                                        <input type="button" value="+" class="plus">
                                                    </div>
                                                </td>
                                                <td class="product-subtotal" data-title="Total">{{ format_price($cartItem->price * $cartItem->qty) }}</td>
                                                <td class="product-remove" data-title="Remove"><a href="{{ route('public.cart.remove', $cartItem->rowId) }}" class="remove-cart-button"><i class="ti-close"></i></a></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6" class="px-0">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                                @if (!session()->has('applied_coupon_code'))
                                                    <div class="coupon field_form input-group form-coupon-wrapper">
                                                        <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" class="form-control form-control-sm coupon-code" placeholder="{{ __('Enter Coupon Code...') }}">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-fill-out btn-sm btn-apply-coupon-code" type="button" data-url="{{ route('public.coupon.apply') }}">{{ __('Apply Coupon') }}</button>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (session('applied_coupon_code'))
                                                    <div class="mt-2 text-left">
                                                        <small><strong>{{ __('Coupon code: :code', ['code' => session('applied_coupon_code')]) }}</strong> <a class="btn-remove-coupon-code text-danger" data-url="{{ route('public.coupon.remove') }}" href="javascript:void(0)"><i class="ti-close"></i></a></small>
                                                    </div>
                                                @endif
                                                <div class="coupon-error-msg text-left">
                                                    <small><span class="text-danger"></span></small>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-6 text-left text-md-right">
                                                <button type="submit" class="btn btn-line-fill btn-sm">{{ __('Update cart') }}</button>&nbsp;&nbsp;&nbsp;
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="medium_divider"></div>
                        <div class="divider center_icon"><i class="ti-shopping-cart-full"></i></div>
                        <div class="medium_divider"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="border p-3 p-md-4">
                            <div class="heading_s1 mb-3">
                                <h6>{{ __('Cart Totals') }}</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="cart_total_label">{{ __('Subtotal') }}</td>
                                        <td class="cart_total_amount">{{ format_price(Cart::instance('cart')->rawSubTotal()) }}</td>
                                    </tr>
                                    @if ($couponDiscountAmount > 0)
                                        <tr>
                                            <td class="cart_total_label">{{ __('Coupon code discount amount') }}</td>
                                            <td class="cart_total_amount">{{ format_price($couponDiscountAmount) }}</td>
                                        </tr>
                                    @endif
                                    @if ($promotionDiscountAmount)
                                        <tr>
                                            <td class="cart_total_label">{{ __('Discount promotion') }}</td>
                                            <td class="cart_total_amount">{{ format_price($promotionDiscountAmount) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="cart_total_label">{{ __('Tax') }}</td>
                                        <td class="cart_total_amount">{{ format_price(Cart::instance('cart')->rawTax()) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">{{ __('Total') }} ({{ __('Shipping fees not included') }})</td>
                                        <td class="cart_total_amount"><strong>{{ format_price(Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount) }}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-fill-out" name="checkout">{{ __('Proceed To CheckOut') }}</button>
                        </div>
                    </div>
                </div>
            </form>

            @php
                $crossSellProducts = array_slice($crossSellProducts, 0, 10);
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
        @else
            <p class="text-center">{{ __('Your cart is empty!') }}</p>
        @endif
    </div>
</div>

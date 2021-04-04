@if (Cart::instance('cart')->count() > 0)
    <ul class="cart_list">
        @php
            $products = [];
            $productIds = Cart::instance('cart')->content()->pluck('id')->toArray();

            if ($productIds) {
                $products = get_products([
                    'condition' => [
                        ['ec_products.id', 'IN', $productIds],
                    ],
                    'with' => ['slugable'],
                ]);
            }
        @endphp
        @if (count($products))
            @foreach(Cart::instance('cart')->content() as $key => $cartItem)
                @php
                    $product = $products->where('id', $cartItem->id)->first();
                @endphp

                @if (!empty($product))
                    <li>
                        <a href="{{ route('public.cart.remove', $cartItem->rowId) }}" class="item_remove remove-cart-button"><i class="ion-close"></i></a>
                        <a href="{{ $product->original_product->url }}"><img src="{{ $cartItem->options['image'] }}" alt="{{ $product->name }}" /> {{ $product->name }}</a>
                        <p style="margin-bottom: 0; line-height: 20px; color: #fff;">
                            <small>{{ $cartItem->options['attributes'] ?? '' }}</small>
                        </p>
                        @if (!empty($cartItem->options['extras']) && is_array($cartItem->options['extras']))
                            @foreach($cartItem->options['extras'] as $option)
                                @if (!empty($option['key']) && !empty($option['value']))
                                    <p style="margin-bottom: 0; line-height: 20px; color: #fff;"><small>{{ $option['key'] }}: <strong> {{ $option['value'] }}</strong></small></p>
                                @endif
                            @endforeach
                        @endif
                        <span class="cart_quantity"> {{ $cartItem->qty }} x <span class="cart_amount"> <span class="price_symbole">{{ get_application_currency()->symbol }}</span></span>{{ format_price($cartItem->price, null, true, false) }}</span>
                    </li>
                @endif
            @endforeach
        @endif
    </ul>
    <div class="cart_footer">
        @if (EcommerceHelper::isTaxEnabled())
            <p class="cart_total sub_total"><strong>{{ __('Sub Total') }}:</strong> <span class="cart_price">{{ format_price(Cart::instance('cart')->rawSubTotal()) }}</span></p>
            <p class="cart_total sub_total"><strong>{{ __('Tax') }}:</strong> <span class="cart_price">{{ format_price(Cart::instance('cart')->rawTax()) }}</span></p>
            <p class="cart_total"><strong>{{ __('Total') }}:</strong> <span class="cart_price">{{ format_price(Cart::instance('cart')->rawSubTotal() + Cart::instance('cart')->rawTax()) }}</span></p>
        @else
            <p class="cart_total"><strong>{{ __('Sub Total') }}:</strong> <span class="cart_price">{{ format_price(Cart::instance('cart')->rawSubTotal()) }}</span></p>
        @endif
        <p class="cart_buttons">
            <a href="{{ route('public.cart') }}" class="btn btn-fill-line view-cart">{{ __('View Cart') }}</a>
            @if (session('tracked_start_checkout'))
                <a href="{{ route('public.checkout.information', session('tracked_start_checkout')) }}" class="btn btn-fill-out checkout">{{ __('Checkout') }}</a>
            @endif
        </p>
    </div>
@else
    <p class="text-center">{{ __('Your cart is empty!') }}</p>
@endif

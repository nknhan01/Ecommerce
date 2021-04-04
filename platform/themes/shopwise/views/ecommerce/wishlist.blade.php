@php Theme::set('pageName', __('Wishlist')) @endphp

<div class="section">
    <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (auth('customer')->check())
                        @if (count($wishlist) > 0 && $wishlist->count() > 0)
                            @foreach ($wishlist as $item)
                                <tr>
                                    <td>
                                        <img alt="{{ $item->product->name }}" width="50" height="70" class="img-fluid"
                                             style="max-height: 75px"
                                             src="{{ RvMedia::getImageUrl($item->product->image, 'thumb', false, RvMedia::getDefaultImage()) }}">
                                    </td>
                                    <td><a href="{{ $item->product->original_product->url }}">{{ $item->product->name }}</a></td>

                                    <td style="width: 300px">
                                        <a class="btn btn-dark btn-sm js-remove-from-wishlist-button" href="{{ route('public.wishlist.remove', $item->product_id) }}">{{ __('Remove') }}</a>
                                        <a class="btn btn-fill-out btn-sm add-to-cart-button" data-id="{{ $item->product->id }}" href="{{ route('public.cart.add-to-cart') }}">{{ __('Add to cart') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">{{ __('No product in wishlist!') }}</td>
                            </tr>
                        @endif
                    @else
                        @if (Cart::instance('wishlist')->count())
                            @foreach(Cart::instance('wishlist')->content() as $cartItem)
                                @php
                                    $item = app(\Platform\Ecommerce\Repositories\Interfaces\ProductInterface::class)->findById($cartItem->id);
                                @endphp
                                @if (!empty($item))
                                    <tr>
                                        <td>
                                            <img alt="{{ $item->name }}" width="50" height="70" class="img-fluid"
                                                 style="max-height: 75px"
                                                 src="{{ RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()) }}">
                                        </td>
                                        <td><a href="{{ $item->original_product->url }}">{{ $item->name }}</a></td>
                                        <td style="width: 300px">
                                            <a class="btn btn-dark btn-sm js-remove-from-wishlist-button" href="{{ route('public.wishlist.remove', $item->id) }}">{{ __('Remove') }}</a>
                                            <a class="btn btn-fill-out btn-sm add-to-cart-button" data-id="{{ $item->id }}" href="{{ route('public.cart.add-to-cart') }}">{{ __('Add to cart') }}</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">{{ __('No product in wishlist!') }}</td>
                            </tr>
                        @endif
                    @endif
                    </tbody>
                </table>
            </div>
            @if (auth('customer')->check())
                <div class="mt-3 justify-content-center pagination_style1">
                    {!! $wishlist->links() !!}
                </div>
            @endif
    </div>
</div>

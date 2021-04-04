@php Theme::set('pageName', $brand->name) @endphp

<div class="section">
    <form action="{{ URL::current() }}" method="GET">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row align-items-center mb-4 pb-1">
                        <div class="col-12">
                            <div class="product_header">
                                @include(Theme::getThemeNamespace() . '::views/ecommerce/includes/sort')
                            </div>
                        </div>
                    </div>
                    <div class="row shop_container grid">
                        @if ($products->count() > 0)
                            @foreach($products as $product)
                                <div class="col-md-4 col-6">
                                    {!! Theme::partial('product-item-grid', compact('product')) !!}
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-12">
                                    <div class="mt-3 justify-content-center pagination_style1">
                                        {!! $products->appends(request()->query())->links() !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            <br>
                            <div class="col-12 text-center">{{ __('No products!') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                    <div class="sidebar">
                        @include(Theme::getThemeNamespace() . '::views/ecommerce/includes/filters')
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END SECTION SHOP -->

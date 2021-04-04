<!-- START SECTION SHOP -->
<div class="section pt-0 pb_20 product-blocks">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-12">
                        <div class="heading_tab_header">
                            <div class="heading_s2">
                                <h4>{!! clean($featured_product_title) !!}</h4>
                            </div>
                            <div class="view_all">
                                <a href="{{ route('public.products') }}" class="text_default"><span>{{ __('View All') }}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <featured-products-component url="{{ route('public.ajax.featured-products') }}"></featured-products-component>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-12">
                        <div class="heading_tab_header">
                            <div class="heading_s2">
                                <h4>{!! clean($top_rated_product_title) !!}</h4>
                            </div>
                            <div class="view_all">
                                <a href="{{ route('public.products') }}" class="text_default"><span>{{ __('View All') }}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <top-rated-products-component url="{{ route('public.ajax.top-rated-products') }}"></top-rated-products-component>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-12">
                        <div class="heading_tab_header">
                            <div class="heading_s2">
                                <h4>{!! clean($on_sale_product_title) !!}</h4>
                            </div>
                            <div class="view_all">
                                <a href="{{ route('public.products') }}" class="text_default"><span>{{ __('View All') }}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <on-sale-products-component url="{{ route('public.ajax.on-sale-products') }}"></on-sale-products-component>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION SHOP -->

<!-- START SECTION SHOP -->
<div class="section pt-0 pb-0">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading_tab_header">
                    <div class="heading_s2">
                        <h4>{!! isset($title) ? clean($title) : $flashSale->name !!}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <flash-sale-products-component url="{{ route('public.ajax.get-flash-sale', $flashSale->id) }}"></flash-sale-products-component>
        </div>
    </div>
</div>
<!-- END SECTION SHOP -->

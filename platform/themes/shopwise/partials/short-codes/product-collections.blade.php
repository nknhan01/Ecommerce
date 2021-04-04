<!-- START SECTION SHOP -->
<div class="section small_pt pb-0">
    <product-collections-component title="{!! clean($title) !!}" :product_collections="{{ json_encode($productCollections) }}" url="{{ route('public.ajax.products') }}"></product-collections-component>
</div>
<!-- END SECTION SHOP -->

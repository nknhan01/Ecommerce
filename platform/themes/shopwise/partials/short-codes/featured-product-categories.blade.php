<!-- START SECTION CATEGORIES -->
<div class="section small_pb small_pt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="heading_s4 text-center">
                    <h2>{!! clean($title) !!}</h2>
                </div>
                <p class="text-center leads">
                    {!! clean($description) !!}
                </p>
            </div>
        </div>
        <div class="row align-items-center">
            <featured-product-categories-component url="{{ route('public.ajax.featured-product-categories') }}"></featured-product-categories-component>
        </div>
    </div>
</div>
<!-- END SECTION CATEGORIES -->

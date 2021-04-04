<!-- START SECTION TESTIMONIAL -->
<div class="section bg_redon">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="heading_s1 text-center">
                    <h2>{!! clean($title) !!}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <testimonials-component url="{{ route('public.ajax.testimonials') }}"></testimonials-component>
        </div>
    </div>
</div>
<!-- END SECTION TESTIMONIAL -->

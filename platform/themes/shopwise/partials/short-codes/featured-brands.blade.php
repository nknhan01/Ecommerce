<!-- START SECTION CLIENT LOGO -->
<div class="section pt-0 small_pb">
    <div class="container">
        <div class="heading_tab_header">
            <div class="heading_s2">
                <h4>{!! clean($title) !!}</h4>
            </div>
        </div>
        <featured-brands-component url="{{ route('public.ajax.featured-brands') }}"></featured-brands-component>
    </div>
</div>
<!-- END SECTION CLIENT LOGO -->

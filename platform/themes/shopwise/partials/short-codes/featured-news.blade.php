<!-- START SECTION BLOG -->
<div class="section pb_20">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="heading_s1 text-center">
                    <h2>{!! clean($title) !!}</h2>
                </div>
                <p class="leads text-center">{!! clean($description) !!}</p>
            </div>
        </div>
        <div class="justify-content-center">
            <featured-news-component url="{{ route('public.ajax.featured-posts') }}"></featured-news-component>
        </div>
    </div>
</div>
<!-- END SECTION BLOG -->

{!! Theme::partial('header') !!}

<div class="breadcrumb_section bg_gray page-title-mini">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="page-title">
                    <h1>{{ Theme::get('pageName') }}</h1>
                </div>
            </div>
            <div class="col-md-6">
                {!! Theme::partial('breadcrumbs') !!}
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                {!! Theme::content() !!}
            </div>
            <div class="col-lg-3 mt-4 pt-2 mt-lg-0 pt-lg-0">
                <div class="sidebar">
                    {!! dynamic_sidebar('primary_sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</div>

{!! Theme::partial('footer') !!}

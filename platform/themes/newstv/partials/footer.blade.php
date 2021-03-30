<footer class="footer" id="footer">
    <div class="container">
        <div class="row">
            {!! dynamic_sidebar('footer_sidebar') !!}
        </div>
        <div class="footer-txt">
            <p>
                <a href=".">
                    <img src="{{ get_image_url(theme_option('logo', Theme::asset()->url('images/logo.png'))) }}" alt="{{ theme_option('site_title') }}">
                </a>
            </p>
            <p>{{ theme_option('site_title') }}</p>
            <div class="hi-icon-wrap hi-icon-effect-3 hi-icon-effect-3a">
                <a href="{{ theme_option('facebook') }}" title="Facebook" class="hi-icon fa fa-facebook"></a>
                <a href="{{ theme_option('twitter') }}" title="Twitter" class="hi-icon fa fa-twitter"></a>
                <a href="{{ theme_option('youtube') }}" title="Youtube" class="hi-icon fa fa-youtube"></a>
            </div>
        </div>
    </div>
    <div class="footer-end">
        <div class="container">
            <p>{!! clean(theme_option('copyright')) !!}</p>
        </div>
    </div>
</footer>

@if (app()->environment() != 'production')
    <div class="theme-panel-wrap">
            <span class="theme-panel-control">
                <i class="fa fa-cogs"></i>
                <i class="fa fa-times"></i>
            </span>
        <div class="theme-panel">
            <div class="theme-options">
                <div class="theme-option theme-colors">
                    <h4>THEME COLOR</h4>
                    <ul>
                        <li><a href="#" data-style="blue"></a></li>
                        <li><a href="#" data-style="green"></a></li>
                        <li><a href="#" data-style="red"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

</div>

{!! Theme::footer() !!}

@if (session()->has('success_msg'))
    <script type="text/javascript">
        swal('{{ __('Success') }}', "{{ session('success_msg', '') }}", 'success');
    </script>
@endif

@if (session()->has('error_msg'))
    <script type="text/javascript">
        swal('{{ __('Success') }}', "{{ session('error_msg', '') }}", 'error');
    </script>
@endif
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58b80e5cfacf57001271be31&product=sticky-share-buttons"></script>

<script>
    "use strict";
    $(document).ready(function () {
        $('.banner-slider-wrap').slick({
            dots: true
        });
    });
</script>

    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v4.0&appId={{ setting('facebook_app_id', config('plugins.facebook.general.app_id')) }}&autoLogAppEvents=1"></script>

    <div class="fb-customerchat"
         attribution=setup_tool
         page_id="157007981299897"
         theme_color="#0084ff">
    </div>
</body>
</html>

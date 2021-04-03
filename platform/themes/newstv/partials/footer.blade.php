<footer class="footer" id="footer">
    <div class="container">
        <div class="row">
            {!! dynamic_sidebar('footer_sidebar') !!}
        </div>
        <div class="footer-txt">
            <p>
                <a href=".">
                    <img src="{{ RvMedia::getImageUrl(theme_option('logo', Theme::asset()->url('images/logo.png'))) }}" alt="{{ theme_option('site_title') }}">
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

</div>

{!! Theme::footer() !!}

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

    @if (theme_option('facebook_comment_enabled_in_post', 'yes') == 'yes' || (theme_option('facebook_chat_enabled', 'yes') == 'yes' && theme_option('facebook_page_id')))
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    xfbml            : true,
                    version          : 'v7.0'
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>

        @if (theme_option('facebook_chat_enabled', 'yes') == 'yes' && theme_option('facebook_page_id'))
            <div class="fb-customerchat"
                 attribution="install_email"
                 page_id="{{ theme_option('facebook_page_id') }}">
            </div>
        @endif
    @endif
</body>
</html>

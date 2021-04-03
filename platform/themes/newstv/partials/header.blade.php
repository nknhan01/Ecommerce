<!--[if IE 8]>
<html lang="{{ app()->getLocale() }}" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="{{ app()->getLocale() }}" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ app()->getLocale() }}">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport"/>

    <!-- Fonts-->
    <link href="https://fonts.googleapis.com/css?family={{ urlencode(theme_option('primary_font', 'Roboto Slab')) }}:100,300,400,700" rel="stylesheet" type="text/css">
    <!-- CSS Library-->

    <style>
        :root {
            --color-1st: {{ theme_option('primary_color', '#d8403f') }};
            --primary-font: '{{ theme_option('primary_font', 'Roboto Slab') }}', sans-serif;
        }
    </style>

    {!! Theme::header() !!}

</head>
<body @if (class_exists('Language', false) && Language::getCurrentLocaleRTL()) dir="rtl" @endif>
<div class="wrapper" id="site_wrapper">
    <header class="header" id="header">
        <div class="header-wrap">
            <nav class="nav-top">
                <div class="container">
                    <div class="pull-left">
                        <div class="hi-icon-wrap hi-icon-effect-3 hi-icon-effect-3a">
                            <a href="{{ theme_option('facebook') }}" title="Facebook" class="hi-icon fa fa-facebook"></a>
                            <a href="{{ theme_option('twitter') }}" title="Twitter" class="hi-icon fa fa-twitter"></a>
                            <a href="{{ theme_option('youtube') }}" title="Youtube" class="hi-icon fa fa-youtube"></a>
                        </div>
                    </div>

                    <div class="pull-right">
                        @if (is_plugin_active('member'))
                            <ul class="pull-left">
                                @auth('member')
                                    <li><a href="{{ route('public.member.dashboard') }}" rel="nofollow"><i class="fa fa-user"></i> <span>{{ auth('member')->user()->getFullName() }}</span></a></li>
                                    <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" rel="nofollow"><i class="fa fa-sign-out"></i> {{ __('Logout') }}</a></li>
                                @elseauth
                                    <li><a href="{{ route('public.member.login') }}" rel="nofollow"><i class="fa fa-sign-in"></i> {{ __('Login') }}</a></li>
                                @endauth
                            </ul>
                            @auth('member')
                                <form id="logout-form" action="{{ route('public.member.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @endauth
                        @endif
                        <div class="pull-left">
                            <div class="pull-right">
                                <div class="language-wrapper">
                                    {!! apply_filters('language_switcher') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="header-content">
                <div class="container">
                    <h1 class="logo">
                        <a href="{{ route('public.single') }}" title="{{ theme_option('site_title') }}">
                            <img src="{{ RvMedia::getImageUrl(theme_option('logo', Theme::asset()->url('images/logo.png'))) }}" alt="{{ theme_option('site_title') }}">
                        </a>
                    </h1>
                    <div class="header-content-right">
                        <p><img alt="Banner" src="{{ theme_option('top_banner') ? RvMedia::getImageUrl(theme_option('top_banner')) : Theme::asset()->url('images/banner.png') }}" style="width: 728px; height: 90px;"></p>
                    </div>
                </div>
            </div>
        </div>
        @if (is_plugin_active('blog'))
            <section class="header-hotnews">
                <div class="container">
                    <div class="hotnews-content">
                        <h2 class="hotnews-tt">{{ __('Hot of the day') }}</h2>
                        <div class="hotnews-dv">
                            <div class="hotnews-slideshow">
                                <div class="js-marquee">
                                    @foreach (get_featured_posts(5) as $feature_item)
                                        <a href="{{ $feature_item->url }}"
                                           title="{{ $feature_item->name }}">{{ $feature_item->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand"  href="{{ route('public.single') }}" title="{{ theme_option('site_title') }}">
                        <img src="{{ RvMedia::getImageUrl(theme_option('logo', Theme::asset()->url('images/logo.png'))) }}" alt="{{ theme_option('site_title') }}">
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">

                    {!!
                        Menu::renderMenuLocation('main-menu', [
                            'options' => ['class' => 'nav navbar-nav'],
                            'view' => 'main-menu',
                        ])
                    !!}

                    @if (is_plugin_active('blog'))
                        <form class="navbar-form navbar-right" role="search"
                              accept-charset="UTF-8"
                              action="{{ route('public.search') }}"
                              method="GET">
                            <div class="tn-searchtop">
                                <button type="button" class="btn btn-default js-btn-searchtop">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="{{ __('Search news...') }}" name="q">
                                </div>
                            </div>
                            <button id="tn-searchtop" class="js-btn-searchtop" type="button"><i class="fa fa-search"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </nav>
    </header>


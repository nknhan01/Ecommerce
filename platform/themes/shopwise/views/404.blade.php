<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family={{ urlencode(theme_option('primary_font', 'Poppins')) }}:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-1st: {{ theme_option('primary_color', '#FF324D') }};
            --color-2nd: {{ theme_option('secondary_color', '#1D2224') }};
            --primary-font: '{{ theme_option('primary_font', 'Poppins') }}', sans-serif;
        }
    </style>

    @php
        SeoHelper::setTitle(__('Oops! The page you requested was not found!'));
        Theme::fire('beforeRenderTheme', app(\Platform\Theme\Contracts\Theme::class));
    @endphp
    {!! Theme::header() !!}
</head>
<body @if (setting('locale_direction', 'ltr') == 'rtl') dir="rtl" @endif>
{!! Theme::partial('header') !!}

<div class="section">
    <div class="error_wrap">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-md-10 order-lg-first">
                    <div class="text-center">
                        <div class="error_txt">404</div>
                        <h5 class="mb-2 mb-sm-3">{{ __('Oops! The page you requested was not found!') }}</h5>
                        <p>{{ __('The page you are looking for was moved, removed, renamed or might never existed.') }}</p>
                        <div class="search_form pb-3 pb-md-4">
                            <form action="{{ route('public.products') }}" method="GET">
                                <input name="q" type="text" placeholder="{{ __('Search') }}" class="form-control">
                                <button type="submit" class="btn icon_search"><i class="ion-ios-search-strong"></i></button>
                            </form>
                        </div>
                        <a href="{{ url('/') }}" class="btn btn-fill-out">{{ __('Back To Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{!! Theme::partial('footer') !!}

{!! Theme::footer() !!}
</body>
</html>


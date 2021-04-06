@php
    SeoHelper::setTitle(__('Service Unavailable'));
    Theme::fire('beforeRenderTheme', app(\Platform\Theme\Contracts\Theme::class));
@endphp

<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport"/>
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Fonts-->
    <link href="https://fonts.googleapis.com/css?family={{ urlencode(theme_option('primary_font', 'Roboto')) }}" rel="stylesheet" type="text/css">
    <!-- CSS Library-->

    <style>
        :root {
            --color-1st: {{ theme_option('primary_color', '#ff2b4a') }};
            --primary-font: '{{ theme_option('primary_font', 'Roboto') }}', sans-serif;
        }
    </style>

    {!! Theme::header() !!}
    <style>
        .container {
            width: 1170px;
            margin: 0 auto;
            position: relative;
        }

        .error-border {
            background-color: var(--color-1st);
            height: .25rem;
            width: 6rem;
            margin-bottom: 1.5rem;
        }

        h1 {
            color: #22292f;
            font-size : 6rem;
            margin-bottom: 2.5rem;
        }

        ul li {
            margin-bottom : 5px;
        }

        .error-page {
            margin-top: 150px;
        }

        a {
            color: var(--color-1st);
        }
    </style>
</head>

<body @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
    <div class="container">
        <div class="error-page">
            <div class="error-code">
                <h1>503</h1>
            </div>

            <div class="error-border"></div>

            <h4>{{ __($exception->getMessage() ?: 'Sorry, we are doing some maintenance. Please check back soon.') }}</h4>
        </div>
    </div>
    {!! Theme::footer() !!}
</body>

</html>


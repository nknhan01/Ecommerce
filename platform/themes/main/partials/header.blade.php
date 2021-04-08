<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1"
        name="viewport" />
    {{-- favicon --}}
    <link id="favicon" rel="shortcut icon" href="{{ RvMedia::getImageUrl(theme_option('favicon')) }}"
        type="image/x-icon">
    <!-- Fonts-->
    <link href="https://fonts.googleapis.com/css?family={{ urlencode(theme_option('primary_font', 'Roboto')) }}"
        rel="stylesheet" type="text/css">
    <!-- CSS Library-->

    <style>
        :root {
            /* Color-Management */
            --primary_color: {{ theme_option('primary_color') }};
            --primary_color_hover: {{ theme_option('primary_color_hover') }};
            --primary_color_text: {{ theme_option('primary_color_text') }};
            --primary_color_text_hover: {{ theme_option('primary_color_text_hover') }};
            /* Font-Size-Managerment */
            /* --primary-font: '{{ theme_option('primary_font', 'Roboto') }}', sans-serif; */
        }

    </style>

    {!! Theme::header() !!}
</head>

<body @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
    <div class="headerMain">
        @includeIf('theme.main::partials.home.header_desktop')
        @includeIf('theme.main::partials.home.header_mobile')
    </div>

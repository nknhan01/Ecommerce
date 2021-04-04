@php
    Theme::set('pageName', $tag->name);
    Theme::layout('blog-sidebar');
@endphp

@include(Theme::getThemeNamespace() . '::views.templates.posts')

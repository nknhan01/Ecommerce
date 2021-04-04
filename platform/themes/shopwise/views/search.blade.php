@php
    Theme::set('pageName', __('Search result for: ') . ' "' . Request::input('q') . '"');
    Theme::layout('blog-sidebar');
@endphp

@include(Theme::getThemeNamespace() . '::views.templates.posts')

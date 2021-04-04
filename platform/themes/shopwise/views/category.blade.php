@php
    Theme::set('pageName', $category->name);
    Theme::layout('blog-sidebar');
@endphp

@include(Theme::getThemeNamespace() . '::views.templates.posts')

{!! Theme::breadcrumb()->render() !!}
<br />
<div>
    {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, $page->content, $page) !!}
</div>
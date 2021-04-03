{!! Theme::breadcrumb()->render() !!}
<br />
<div>
    {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content, 'youtube'), $page) !!}
</div>

@php Theme::set('pageName', $page->name) @endphp

<div id="app">
    @if ($page->template === 'homepage')
        {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content, 'youtube'), $page) !!}
    @else
        <div class="section">
            <div class="container">
                {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content, 'youtube'), $page) !!}
            </div>
        </div>
    @endif
</div>

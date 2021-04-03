<div class="row">
    @if (count($pages) > 0)
        <ul class="search-list">
            @foreach ($pages as $page)
                <li class="col-md-4 col-sm-6 col-xs-12">
                    <a href="{{ $page->url }}" class="squared">
                        <span class="img"><i class="{{ $page->icon }}"></i></span>
                        <span class="spoofer">{{ $page->name }}</span>
                        <span class="visible">{{ $page->name }}</span>
                    </a>
                </li>
            @endforeach
            <div class="clearfix"></div>
        </ul>
    @else
        <div class="col-md-12">
            <p>{{ __('No result available for :name', ['name' => 'pages']) }}</p>
        </div>
    @endif
</div>
<div class="clearfix"></div>

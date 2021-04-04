<div class="widget">
    <div class="search_form">
        <form action="{{ route('public.search') }}" method="GET">
            <input class="form-control" name="q" placeholder="{{ __('Search') }}..." type="text">
            <button type="submit" title="{{ __('Search') }}" class="btn icon_search">
                <i class="ion-ios-search-strong"></i>
            </button>
        </form>
    </div>
</div>

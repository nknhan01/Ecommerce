<ol class="breadcrumb justify-content-md-end" itemscope itemtype="http://schema.org/BreadcrumbList">
    @foreach ($crumbs = Theme::breadcrumb()->getCrumbs() as $i => $crumb)
        @if ($i != (count($crumbs) - 1))
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <meta itemprop="position" content="{{ $i + 1}}" />
                <a href="{{ $crumb['url'] }}" itemprop="item" title="{{ $crumb['label'] }}">
                    {{ $crumb['label'] }}
                    <meta itemprop="name" content="{{ $crumb['label'] }}" />
                </a>
            </li>
        @else
            <li class="breadcrumb-item active">{{ $crumb['label'] }}</li>
        @endif
    @endforeach
</ol>

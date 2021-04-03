@if ($posts->count() > 0)
    @foreach ($posts as $post)
        <div class="media-news">
            <a href="{{ $post->url }}" class="media-news-img" title="{{ $post->name }}">
                <img class="img-full img-bg" src="{{ RvMedia::getImageUrl($post->image, 'medium') }}" style="background-image: url('{{ RvMedia::getImageUrl($post->image) }}');" alt="{{ $post->name }}">
            </a>
            <div class="media-news-body">
                <p class="common-title">
                    <a href="{{ $post->url }}" title="{{ $post->name }}">
                        {{ $post->name }}
                    </a>
                </p>
                <p class="common-date">
                    <time datetime="">{{ $post->created_at->format('M d, Y') }}</time>
                </p>
                <div class="common-summary">
                    {{ $post->description }}
                </div>
            </div>
        </div>
    @endforeach
    <nav class="pagination-wrap">
        {!! $posts->appends(request()->query())->links() !!}
    </nav>

    <style>
        .media-news {
            background: #fff;
            padding: 10px;
        }
    </style>
@endif

<div>
    <h3>{{ $post->name }}</h3>
    {!! Theme::breadcrumb()->render() !!}
</div>
<header>
    <h3>{{ $post->name }}</h3>
    <div>
        @if (!$post->categories->isEmpty())
            <span>
                <a href="{{ $post->categories->first()->url }}">{{ $post->categories->first()->name }}</a>
            </span>
        @endif
        <span>{{ date_from_database($post->created_at, 'M d, Y') }}</span>

        @if (!$post->tags->isEmpty())
            <span>
                @foreach ($post->tags as $tag)
                    <a href="{{ $tag->url }}">{{ $tag->name }}</a>
                @endforeach
            </span>
        @endif
    </div>
</header>
{!! clean($post->content) !!}
<br />
{!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null) !!}
<footer>
    @foreach (get_related_posts($post->slug, 2) as $related_item)
        <div>
            <article>
                <div><a href="{{ $related_item->url }}"></a>
                    <img src="{{ url($related_item->image) }}" alt="{{ $related_item->name }}">
                </div>
                <header><a href="{{ $related_item->url }}"> {{ $related_item->name }}</a></header>
            </article>
        </div>
    @endforeach
</footer>

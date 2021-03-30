@foreach ($posts as $post)
    <div>
        <article>
            <div><a href="{{ $post->url }}"></a>
                <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}">
            </div>
            <header><a href="{{ $post->url }}"> {{ $post->name }}</a></header>
        </article>
    </div>
@endforeach

<div class="pagination">
    {!! $posts->links() !!}
</div>

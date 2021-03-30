@if ($posts->count() > 0)
    @foreach ($posts as $post)
        <article>
            <div>
                <a href="{{ $post->url }}"><img src="{{ url($post->image) }}" alt="{{ $post->name }}"></a>
            </div>
            <div>
                <header>
                    <h3><a href="{{ $post->url }}">{{ $post->name }}</a></h3>
                    <div><span><a href="#">{{ date_from_database($post->created_at, 'M d, Y') }}</a></span><span>{{ $post->user->getFullName() }}</span> -
                        {{ __('Categories') }}:
                        @foreach($post->categories as $category)
                            <a href="{{ $category->url }}">{{ $category->name }}</a>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </div>
                </header>
                <div>
                    <p>{{ $post->description }}</p>
                </div>
            </div>
        </article>
    @endforeach
    <div>
        {!! $posts->links() !!}
    </div>
@endif

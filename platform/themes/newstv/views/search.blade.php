{!! Theme::breadcrumb()->render() !!}
<br>
<section class="main-box">
    <div class="main-box-header">
        <h2>
            <i class="fa fa-leaf"></i>
            {{ __('Search result for: ') }} "{{ Request::input('q') }}"
        </h2>
    </div>
    <div class="main-box-content">
        <div class="box-style box-style-3">
            @if ($posts->count() > 0)
                @foreach ($posts as $post)
                    <div class="media-news">
                        <a href="{{ $post->url }}" class="media-news-img" title="{{ $post->name }}">
                            <img class="img-full img-bg" src="{{ get_object_image($post->image, 'medium') }}" style="background-image: url('{{ get_object_image($post->image) }}');" alt="{{ $post->name }}">
                        </a>
                        <div class="media-news-body">
                            <p class="common-title">
                                <a href="{{ $post->url }}" title="{{ $post->name }}">
                                    {{ $post->name }}
                                </a>
                            </p>
                            <p class="common-date">
                                <time datetime="">{{ date_from_database($post->created_at, 'M d, Y') }}</time>
                            </p>
                            <div class="common-summary">
                                {{ $post->description }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div>
                    <p>{{ __('There is no data to display!') }}</p>
                </div>
            @endif
        </div>
    </div>
</section>

@if ($posts->count() > 0)
    <nav class="pagination-wrap">
        {!! $posts->links() !!}
    </nav>
@endif

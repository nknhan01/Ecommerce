@if (is_plugin_active('blog'))
    <div class="aside-box">
        <div class="aside-box-header">
            <h4>{{ $config['name'] }}</h4>
        </div>
        <div class="aside-box-content">
            @foreach(get_popular_posts($config['number_display']) as $post)
                <div class="media-news">
                    <a href="{{ $post->url }}"
                       title="{{ $post->name }}" class="media-news-img">
                        <img class="img-full img-bg" src="{{ RvMedia::getImageUrl($post->image, 'thumb') }}"
                             style="background-image: url('{{ RvMedia::getImageUrl($post->image) }}');"
                             alt="{{ $post->name }}">
                    </a>
                    <div class="media-news-body">
                        <p class="common-title">
                            <a href="{{ $post->url }}"
                               title="{{ $post->name }}">
                                {{ $post->name }}
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

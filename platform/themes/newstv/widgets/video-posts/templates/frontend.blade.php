@if (is_plugin_active('blog'))
    <div class="aside-box">
        <div class="aside-box-header">
            <h4>{{ $config['name'] }}</h4>
        </div>
        <div class="aside-box-content">
            @foreach(get_popular_posts($config['number_display'], ['where' => ['status' => \Platform\Base\Enums\BaseStatusEnum::PUBLISHED, 'format_type' => 'video']]) as $post)
                <div class="media-news media-video">
                    <a href="{{ $post->url }}"
                       class="media-news-img" title="{{ $post->name }}">
                        <img class="img-full img-bg" src="{{ RvMedia::getImageUrl($post->image, 'thumb') }}"
                             style="background-image: url('{{ RvMedia::getImageUrl($post->image) }}');"
                             alt="{{ $post->name }}">
                    </a>
                    <div class="media-news-body">
                        <p class="common-title">
                            <a href="{{ $post->url }}"
                               title="{{ $post->name }}">{{ $post->name }}</a>
                        </p>
                        <p class="common-date">
                            <time datetime="">{{ $post->created_at->format('M d, Y') }}</time>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

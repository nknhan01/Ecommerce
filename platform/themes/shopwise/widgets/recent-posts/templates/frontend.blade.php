@if (is_plugin_active('blog'))
    @php
        $posts = get_recent_posts($config['number_display']);
    @endphp
    @if ($posts->count())
        <div class="widget">
            <h5 class="widget_title">{{ $config['name'] }}</h5>
            <ul class="widget_recent_post">
                @foreach ($posts as $post)
                    <li>
                        <div class="post_footer">
                            <div class="post_img">
                                <a href="{{ $post->url }}"><img src="{{ RvMedia::getImageUrl($post->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $post->name }}"></a>
                            </div>
                            <div class="post_content">
                                <h6><a href="{{ $post->url }}">{{ $post->name }}</a></h6>
                                <p class="small m-0">{{ $post->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endif

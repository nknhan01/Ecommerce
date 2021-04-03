@if (is_plugin_active('blog'))
    @php
        $featured = get_featured_posts(5);
    @endphp
    @if (count($featured) > 0)
        <div class="main-feature">
            @foreach ($featured as $feature_item)
                <div class="feature-item">
                    <div class="feature-item-dv">
                        <a href="{{ $feature_item->url }}"
                           title="{{ $feature_item->name }}"
                           style="display: block">
                            <img class="img-full img-bg" src="{{ RvMedia::getImageUrl($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}"
                                 style="background-image: url('{{ RvMedia::getImageUrl($feature_item->image) }}');">
                            <span class="feature-item-link"
                                  title="{{ $feature_item->name }}">
                                        <span>{{ $feature_item->name }}</span>
                                    </span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endif

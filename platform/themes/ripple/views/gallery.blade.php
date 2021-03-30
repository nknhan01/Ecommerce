@php Theme::set('section-name', $gallery->name) @endphp

<article class="post post--single">
    <div class="post__content">
        <p>
            {{ $gallery->description }}
        </p>
        <div id="list-photo">
            @foreach (gallery_meta_data($gallery) as $image)
                @if ($image)
                    <div class="item" data-src="{{ get_object_image(Arr::get($image, 'img')) }}" data-sub-html="{{ Arr::get($image, 'description') }}">
                        <div class="photo-item">
                            <div class="thumb">
                                <a href="#">
                                    <img src="{{ get_object_image(Arr::get($image, 'img')) }}" alt="{{ Arr::get($image, 'description') }}">
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <br>
        {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, Theme::partial('comments')) !!}
    </div>
</article>

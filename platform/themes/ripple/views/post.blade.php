@php Theme::set('section-name', $post->name) @endphp

<article class="post post--single">
    <header class="post__header">
        <h3 class="post__title">{{ $post->name }}</h3>
        <div class="post__meta">
            @if (!$post->categories->isEmpty())
                <span class="post-category"><i class="ion-cube"></i>
                    <a href="{{ $post->categories->first()->url }}">{{ $post->categories->first()->name }}</a>
                </span>
            @endif
            <span class="post__created-at"><i class="ion-clock"></i><a href="#">{{ date_from_database($post->created_at, 'M d, Y') }}</a></span>
            @if ($post->user->username)
                <span class="post__author"><i class="ion-android-person"></i><span>{{ $post->user->getFullName() }}</span></span>
            @endif

            @if (!$post->tags->isEmpty())
                <span class="post__tags"><i class="ion-pricetags"></i>
                    @foreach ($post->tags as $tag)
                        <a href="{{ $tag->url }}">{{ $tag->name }}</a>
                    @endforeach
                </span>
            @endif
        </div>
        <div class="post__social"></div>
    </header>
    <div class="post__content">
        @if (defined('GALLERY_MODULE_SCREEN_NAME') && !empty($galleries = gallery_meta_data($post)))
            {!! render_object_gallery($galleries, ($post->categories()->first() ? $post->categories()->first()->name : __('Uncategorized'))) !!}
        @endif
        {!! $post->content !!}
        <div class="fb-like" data-href="{{ Request::url() }}" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
    </div>
    <footer class="post__footer">
        <div class="row">
            @foreach (get_related_posts($post->id, 2) as $relatedItem)
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="post__relate-group @if ($loop->last) post__relate-group--right @endif">
                        <h4 class="relate__title">@if ($loop->first) {{ __('Previous Post') }} @else {{ __('Next Post') }} @endif</h4>
                        <article class="post post--related">
                            <div class="post__thumbnail"><a href="{{ $relatedItem->url }}" class="post__overlay"></a>
                                <img src="{{ get_object_image($relatedItem->image, 'thumb') }}" alt="{{ $relatedItem->name }}">
                            </div>
                            <header class="post__header"><a href="{{ $relatedItem->url }}" class="post__title"> {{ $relatedItem->name }}</a></header>
                        </article>
                    </div>
                </div>
            @endforeach
        </div>
    </footer>
    <br />
    {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, Theme::partial('comments')) !!}
</article>

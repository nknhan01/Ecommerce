@if ($posts->count() > 0)
    @foreach ($posts as $post)
        <article class="post post__horizontal mb-40 clearfix">
            <div class="post__thumbnail">
                <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}"><a href="{{ $post->url }}" class="post__overlay"></a>
            </div>
            <div class="post__content-wrap">
                <header class="post__header">
                    <h3 class="post__title"><a href="{{ $post->url }}">{{ $post->name }}</a></h3>
                    <div class="post__meta"><span class="post__created-at"><i class="ion-clock"></i><a href="#">{{ date_from_database($post->created_at, 'M d, Y') }}</a></span>
                        @if ($post->user->username)
                            <span class="post__author"><i class="ion-android-person"></i><span>{{ $post->user->getFullName() }}</span></span>
                        @endif
                        <span class="post-category"><i class="ion-cube"></i>
                            @foreach($post->categories as $category)
                                <a href="{{ $category->url }}">{{ $category->name }}</a>
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </span></div>
                </header>
                <div class="post__content">
                    <p data-number-line="4">{{ $post->description }}</p>
                </div>
            </div>
        </article>
    @endforeach
    <div class="page-pagination text-right">
        {!! $posts->links() !!}
    </div>
@endif

<style>
    .section.pt-50.pb-100 {
        background-color: #ecf0f1;
    }
</style>

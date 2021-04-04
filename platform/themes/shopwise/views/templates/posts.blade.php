@if ($posts->count() > 0)
    @foreach ($posts as $post)
        <div class="row blog_thumbs">
            <div class="col-12">
                <div class="blog_post blog_style2">
                    <div class="blog_img">
                        <a href="{{ $post->url }}"><img src="{{ RvMedia::getImageUrl($post->image, 'small', false, RvMedia::getDefaultImage()) }}" alt="{{ $post->name }}"></a>
                    </div>
                    <div class="blog_content bg-white">
                        <div class="blog_text">
                            <h6 class="blog_title"><a href="{{ $post->url }}">{{ $post->name }}</a></h6>
                            <ul class="list_none blog_meta">
                                <li><i class="ti-calendar"></i> {{ $post->created_at->format('M d, Y') }}</li>
                                <li><i class="ti-eye"></i> {{ number_format($post->views) }} {{ __('Views') }}</li>
                            </ul>
                            <p>{{ Str::limit($post->description, 110) }}</p>
                            <a href="{{ $post->url }}" class="btn btn-fill-line border-2 btn-xs rounded-0">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="row">
        <div class="col-12 mt-2 mt-md-4">
            <div class="pagination_style1 justify-content-center">
                {!! $posts->appends(request()->query())->links() !!}
            </div>
        </div>
    </div>
@endif

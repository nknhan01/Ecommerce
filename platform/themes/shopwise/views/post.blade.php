@php Theme::set('pageName', __('Blog')) @endphp

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-xl-9">
                <div class="single_post">
                    <h2 class="blog_title">{{ $post->name }}</h2>
                    <ul class="list_none blog_meta">
                        <li><i class="ti-calendar"></i> {{ $post->created_at->format('M d, Y') }}</li>
                        <li><i class="ti-pencil-alt"></i>
                            @if (!$post->categories->isEmpty())
                                @foreach($post->categories as $category)
                                    <a href="{{ $category->url }}">{{ $category->name }}</a>@if (!$loop->last),@endif
                                @endforeach
                            @endif
                        </li>
                        <li><i class="ti-eye"></i> {{ number_format($post->views) }} {{ __('Views') }}</li>
                    </ul>
                    <div class="blog_img">
                        <img src="{{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()) }}" alt="{{ $post->name }}">
                    </div>
                    <div class="blog_content">
                        <div class="blog_text">
                            {!! clean($post->content, 'youtube') !!}
                            <div class="blog_post_footer">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <div class="tags">
                                            @if (!$post->tags->isEmpty())
                                                @foreach ($post->tags as $tag)
                                                    <a href="{{ $tag->url }}">{{ $tag->name }}</a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="social_icons text-md-right">
                                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->url) }}&title={{ rawurldecode($post->description) }}" target="_blank" title="{{ __('Share on Facebook') }}"><i class="ion-social-facebook"></i></a></li>
                                            <li><a href="https://twitter.com/intent/tweet?url={{ urlencode($post->url) }}&text={{ rawurldecode($post->description) }}" target="_blank" title="{{ __('Share on Twitter') }}"><i class="ion-social-twitter"></i></a></li>
                                            <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($post->url) }}&summary={{ rawurldecode($post->description) }}&source=Linkedin" title="{{ __('Share on Linkedin') }}" target="_blank"><i class="ion-social-linkedin"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if (theme_option('facebook_comment_enabled_in_post', 'yes') == 'yes')
                                <br />
                                {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, Theme::partial('comments')) !!}
                            @endif
                        </div>
                    </div>
                </div>
                <br>
                <div class="related_post">
                    <div class="content_title">
                        <h5>{{ __('Related posts') }}</h5>
                    </div>
                    <div class="row">
                        @foreach (get_related_posts($post->slug, 2) as $relatedItem)
                            <div class="col-md-6">
                                <div class="blog_post blog_style2 box_shadow1">
                                    <div class="blog_img">
                                        <a href="{{ $relatedItem->url }}"><img src="{{ RvMedia::getImageUrl($relatedItem->image, 'small', false, RvMedia::getDefaultImage()) }}" alt="{{ $relatedItem->name }}"></a>
                                    </div>
                                    <div class="blog_content bg-white">
                                        <div class="blog_text">
                                            <h6 class="blog_title"><a href="{{ $relatedItem->url }}">{{ $relatedItem->name }}</a></h6>
                                            <ul class="list_none blog_meta">
                                                <li><i class="ti-calendar"></i> {{ $relatedItem->created_at->format('M d, Y') }}</li>
                                                <li><i class="ti-eye"></i> {{ number_format($relatedItem->views) }} {{ __('Views') }}</li>
                                            </ul>
                                            <p>{{ Str::limit($relatedItem->description, 110) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mt-4 pt-2 mt-xl-0 pt-xl-0">
                <div class="sidebar">
                    {!! dynamic_sidebar('primary_sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</div>

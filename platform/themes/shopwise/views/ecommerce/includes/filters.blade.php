@php
    $brands = get_all_brands(['status' => \Platform\Base\Enums\BaseStatusEnum::PUBLISHED], ['slugable'], ['products']);
    $categories = get_product_categories(['status' => \Platform\Base\Enums\BaseStatusEnum::PUBLISHED], ['slugable'], ['products'], true);
    $tags = app(\Platform\Ecommerce\Repositories\Interfaces\ProductTagInterface::class)->allBy(['status' => \Platform\Base\Enums\BaseStatusEnum::PUBLISHED], ['slugable']);

    Theme::asset()->usePath()->add('jquery-ui-css', 'css/jquery-ui.css');
    Theme::asset()->container('footer')->usePath()->add('jquery-ui-js', 'js/jquery-ui.js', ['jquery']);
@endphp

<div class="widget">
    <h5 class="widget_title">{{ __('Product Categories') }}</h5>
    <ul class="widget_categories">
        <li @if (URL::current() == route('public.products')) class="active" @endif><a href="{{ route('public.products') }}">{{ __('All Products') }}</a></li>
        @foreach($categories as $category)
            <li @if (URL::current() == $category->url) class="active" @endif><a href="{{ $category->url }}">{{ $category->name }} <span class="categories_num">({{ $category->products_count }})</span></a></li>
        @endforeach
    </ul>
</div>

@if (count($brands) > 0)
    <aside class="widget">
        <h5 class="widget_title">{{ __('Brands') }}</h5>
        <ul class="list_brand">
            @foreach($brands as $brand)
                <li>
                    <div class="custome-checkbox">
                        <input class="form-check-input submit-form-on-change" type="checkbox" name="brands[]" id="brand-{{ $brand->id }}" value="{{ $brand->id }}" @if (in_array($brand->id, request()->input('brands', []))) checked @endif>
                        <label class="form-check-label" for="brand-{{ $brand->id }}"><span>{{ $brand->name }} ({{ $brand->products_count }})</span></label>
                    </div>
                </li>
            @endforeach
        </ul>
    </aside>
@endif
<aside class="widget widget--tags">
    <h5 class="widget_title">{{ __('Product Tags') }}</h5>
    <ul class="list_brand">
        @foreach($tags as $tag)
            <li>
                <div class="custome-checkbox">
                    <input class="form-check-input submit-form-on-change" type="checkbox" name="tags[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}" @if (in_array($tag->id, request()->input('tags', []))) checked @endif>
                    <label class="form-check-label" for="tag-{{ $tag->id }}"><span>{{ $tag->name }}</span></label>
                </div>
            </li>
        @endforeach
    </ul>
</aside>
<aside class="widget">
    <h5 class="widget_title">{{ __('By Price') }}</h5>
    <div class="filter_price">
        <div id="price_filter" data-min="0" data-max="{{ theme_option('max_filter_price', 1000) }}" data-min-value="{{ request()->input('min_price', 0) }}" data-max-value="{{ request()->input('max_price', theme_option('max_filter_price', 1000)) }}" data-price-sign="{{ get_application_currency()->symbol }}"></div>
        <div data-current-exchange-rate="{{ get_current_exchange_rate() }}"></div>
        <div data-is-prefix-symbol="{{ get_application_currency()->is_prefix_symbol }}"></div>
        <div class="price_range">
            <span>{{ __('Price') }}: <span id="flt_price"></span></span>
            <input class="product-filter-item product-filter-item-price-0" id="price_first" name="min_price" value="{{ request()->input('min_price', 0) }}" type="hidden">
            <input class="product-filter-item product-filter-item-price-1" id="price_second" name="max_price" value="{{ request()->input('max_price', theme_option('max_filter_price', 1000)) }}" type="hidden">
        </div>
    </div>
</aside>

<aside class="widget" style="border: none">
    {!! render_product_swatches_filter([
        'view' => Theme::getThemeNamespace() . '::views.ecommerce.attributes.attributes-filter-renderer'
    ]) !!}
</aside>

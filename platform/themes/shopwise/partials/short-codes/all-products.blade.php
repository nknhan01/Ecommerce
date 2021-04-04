<div class="section pt-0 small_pb">
    <div class="container">
        @if (clean($title))
            <div class="heading_tab_header">
                <div class="heading_s2">
                    <h4>{!! clean($title) !!}</h4>
                </div>
            </div>
        @endif
        @if ($products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-4 col-6">
                        {!! Theme::partial('product-item', compact('product')) !!}
                    </div>
                @endforeach
            </div>
            <div class="shop__pagination">
                {!! $products->appends(request()->query())->links() !!}
            </div>
        @endif
    </div>
</div>

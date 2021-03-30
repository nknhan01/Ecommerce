<div class="flexbox-annotated-section">
    <div class="flexbox-annotated-section-annotation">
        <div class="annotated-section-title pd-all-20">
            <h2>{{ trans('packages/page::pages.settings.title') }}</h2>
        </div>
        <div class="annotated-section-description pd-all-20 p-none-t">
            <p class="color-note">{{ trans('packages/page::pages.settings.description') }}</p>
        </div>
    </div>

    <div class="flexbox-annotated-section-content">
        <div class="wrapper-content pd-all-20">
            <div class="form-group">
                <label class="text-title-field"
                       for="show_on_front">{{ trans('packages/page::pages.settings.show_on_front') }}
                </label>
                <div class="ui-select-wrapper">
                    <select name="show_on_front" class="ui-select" id="show_on_front">
                        <option value="">{{ trans('packages/page::pages.settings.select') }}</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->id }}" @if (setting('show_on_front') == $page->id) selected @endif>{{ $page->name }}</option>
                        @endforeach
                    </select>
                    <svg class="svg-next-icon svg-next-icon-size-16">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
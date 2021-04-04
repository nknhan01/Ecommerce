<div class="product_header_left">
    <div class="custom_select">
        <select class="form-control form-control-sm submit-form-on-change" name="sort-by" id="sort-by" >
            <option value="default_sorting" @if (request()->input('sort-by') == 'default_sorting') selected @endif>{{ __('Default') }}</option>
            <option value="date_asc" @if (request()->input('sort-by') == 'date_asc') selected @endif>{{ __('Oldest') }}</option>
            <option value="date_desc" @if (request()->input('sort-by') == 'date_desc') selected @endif>{{ __('Newest') }}</option>
            <option value="price_asc" @if (request()->input('sort-by') == 'price_asc') selected @endif>{{ __('Price') }}: {{ __('low to high') }}</option>
            <option value="price_desc" @if (request()->input('sort-by') == 'price_desc') selected @endif>{{ __('Price') }}: {{ __('high to low') }}</option>
            <option value="name_asc" @if (request()->input('sort-by') == 'name_asc') selected @endif>{{ __('Name') }}: {{ __('A-Z') }}</option>
            <option value="name_desc" @if (request()->input('sort-by') == 'name_desc') selected @endif>{{ __('Name') }}: {{ __('Z-A') }}</option>
        </select>
    </div>
</div>
<div class="product_header_right">
    <div class="products_view">
        <a href="javascript:void(0);" class="shorting_icon grid active"><i class="ti-view-grid"></i></a>
        <a href="javascript:void(0);" class="shorting_icon list"><i class="ti-layout-list-thumb"></i></a>
    </div>
    <div class="custom_select">
        <select class="form-control form-control-sm submit-form-on-change" name="num">
            <option value="">{{ __('Showing') }}</option>
            <option value="9" @if (request()->input('num') == 9) selected @endif>9</option>
            <option value="12" @if (request()->input('num') == 12) selected @endif>12</option>
            <option value="18" @if (request()->input('num') == 18) selected @endif>18</option>
        </select>
    </div>
</div>

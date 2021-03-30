@if ($is_featured)
    <span class="label-success status-label">@if (!empty($featured_text)) {{ $featured_text }} @else {{ trans('core/base::tables.activated') }} @endif</span>
@else
    <span class="label-danger status-label">@if (!empty($not_featured_text)) {{ $not_featured_text }} @else {{ trans('core/base::tables.deactivated') }} @endif</span>
@endif

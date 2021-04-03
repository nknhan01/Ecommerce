<div class="wrapper-filter">
    <p>{{ trans('core/table::general.filters') }}</p>

    <input type="hidden" class="filter-data-url" value="{{ route('tables.get-filter-input') }}">

    <div class="sample-filter-item-wrap hidden">
        <div class="filter-item form-filter">
            <div class="ui-select-wrapper">
                <select name="filter_columns[]" class="ui-select filter-column-key">
                    <option value="">{{ trans('core/table::general.select_field') }}</option>
                    @foreach($columns as $columnKey => $column)
                        <option value="{{ $columnKey }}">{{ $column['title'] }}</option>
                    @endforeach
                </select>
                <svg class="svg-next-icon svg-next-icon-size-16">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                </svg>
            </div>
            <div class="ui-select-wrapper">
                <select name="filter_operators[]" class="ui-select filter-operator filter-column-operator">
                    <option value="like">{{ trans('core/table::general.contains') }}</option>
                    <option value="=">{{ trans('core/table::general.is_equal_to') }}</option>
                    <option value=">">{{ trans('core/table::general.greater_than') }}</option>
                    <option value="<">{{ trans('core/table::general.less_than') }}</option>
                </select>
                <svg class="svg-next-icon svg-next-icon-size-16">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                </svg>
            </div>
            <span class="filter-column-value-wrap">
                <input class="form-control filter-column-value" type="text" placeholder="{{ trans('core/table::general.value') }}"
                       name="filter_values[]">
            </span>
            <span class="btn-remove-filter-item" title="{{ trans('core/table::general.delete') }}">
                <i class="fa fa-trash text-danger"></i>
            </span>
        </div>
    </div>

    {{ Form::open(['method' => 'GET', 'class' => 'filter-form']) }}
        <input type="hidden" name="filter_table_id" class="filter-data-table-id" value="{{ $tableId }}">
        <input type="hidden" name="class" class="filter-data-class" value="{{ $class }}">
        <div class="filter_list inline-block filter-items-wrap">
            @foreach($requestFilters as $filterItem)
                <div class="filter-item form-filter @if ($loop->first) filter-item-default @endif">
                    <div class="ui-select-wrapper">
                        <select name="filter_columns[]" class="ui-select filter-column-key">
                            <option value="">{{ trans('core/table::general.select_field') }}</option>
                            @foreach($columns as $columnKey => $column)
                                <option value="{{ $columnKey }}" @if ($filterItem['column'] == $columnKey) selected @endif>{{ $column['title'] }}</option>
                            @endforeach
                        </select>
                        <svg class="svg-next-icon svg-next-icon-size-16">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                        </svg>
                    </div>
                    <div class="ui-select-wrapper">
                        <select name="filter_operators[]" class="ui-select filter-column-operator">
                            <option value="like"
                                    @if ($filterItem['operator'] == 'like') selected @endif>{{ trans('core/table::general.contains') }}</option>
                            <option value="="
                                    @if ($filterItem['operator'] == '=') selected @endif>{{ trans('core/table::general.is_equal_to') }}</option>
                            <option value=">"
                                    @if ($filterItem['operator'] == '>') selected @endif>{{ trans('core/table::general.greater_than') }}</option>
                            <option value="<"
                                    @if ($filterItem['operator'] == '<') selected @endif>{{ trans('core/table::general.less_than') }}</option>
                        </select>
                        <svg class="svg-next-icon svg-next-icon-size-16">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                        </svg>
                    </div>
                    <span class="filter-column-value-wrap">
                        <input class="form-control filter-column-value" type="text" placeholder="{{ trans('core/table::general.value') }}"
                               name="filter_values[]" value="{{ $filterItem['value'] }}">
                    </span>
                    @if ($loop->first)
                        <span class="btn-reset-filter-item" title="{{ trans('core/table::general.reset') }}">
                            <i class="fa fa-eraser text-info" style="font-size: 13px;"></i>
                        </span>
                    @else
                        <span class="btn-remove-filter-item" title="{{ trans('core/table::general.delete') }}">
                            <i class="fa fa-trash text-danger"></i>
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
        <div style="margin-top: 10px;">
            <a href="javascript:;" class="btn btn-secondary add-more-filter">{{ trans('core/table::general.add_additional_filter') }}</a>
            <a href="{{ URL::current() }}" class="btn btn-info @if (!request()->has('filter_table_id')) hidden @endif">{{ trans('core/table::general.reset') }}</a>
            <button type="submit" class="btn btn-primary btn-apply">{{ trans('core/table::general.apply') }}</button>
        </div>

    {{ Form::close() }}
</div>

<div class="form-group">
    <div class="row">
        <label class="col-lg-3 col-md-3 control-label">{{ __('Block') }}</label>
        <div class="col-lg-9 col-md-9">
            <select name="alias" id="alias" class="form-control" data-shortcode-attribute="attribute">
                @foreach(get_list_blocks(['status' => \Botble\Base\Enums\BaseStatusEnum::PUBLISHED]) as $item)
                    <option value="{{ $item->alias }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
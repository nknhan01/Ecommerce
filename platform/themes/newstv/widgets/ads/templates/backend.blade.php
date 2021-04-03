<div class="form-group">
    <label for="widget-image-link">{{ __('Link to target') }}</label>
    <input type="text" class="form-control" name="image_link" value="{{ $config['image_link'] }}">
</div>
<div class="form-group">
    <label for="widget-image-new-tab">{{ __('Open in new tab?') }}</label>
    <select name="image_new_tab" class="form-control">
        <option value="0" @if ($config['image_new_tab'] == 0) selected @endif>{{ __('No') }}</option>
        <option value="1" @if ($config['image_new_tab'] == 1) selected @endif>{{ __('Yes') }}</option>
    </select>
</div>
<div class="form-group">
    <label for="widget-image-url">{{ __('Image URL') }}</label>
    <input type="text" class="form-control" name="image_url" value="{{ $config['image_url'] }}">
</div>
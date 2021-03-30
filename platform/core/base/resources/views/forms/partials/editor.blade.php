@if (!(isset($attributes['without-buttons']) && $attributes['without-buttons'] == true))
    <div style="height: 34px;">
        @php $result = !empty($attributes['id']) ? $attributes['id'] : $name; @endphp
        <span class="editor-action-item action-show-hide-editor">
            <button class="btn btn-primary show-hide-editor-btn" type="button" data-result="{{ $result }}">{{ trans('core/base::forms.show_hide_editor') }}</button>
        </span>
        <span class="editor-action-item">
            <a href="#" class="btn_gallery btn btn-primary"
               data-result="{{ $result }}"
               data-multiple="true"
               data-action="media-insert-{{ setting('rich_editor', config('core.base.general.editor.primary')) }}">
                <i class="far fa-image"></i> {{ trans('core/media::media.add') }}
            </a>
        </span>
        @if (isset($attributes['with-short-code']) && $attributes['with-short-code'] == true && function_exists('shortcode'))
            <span class="editor-action-item list-shortcode-items">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle add_shortcode_btn_trigger" data-result="{{ $result }}" type="button" data-toggle="dropdown"><i class="fa fa-code"></i> {{ trans('core/base::forms.short_code') }}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($shortcodes = shortcode()->getAll() as $key => $item)
                            <li data-html="{{ Arr::get($item, 'admin_config') }}">
                                <a href="#" data-key="{{ $key }}" data-description="{{ $item['description'] }}">{{ $item['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </span>
            @push('footer')
                <div class="modal fade short_code_modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('core/base::forms.add_short_code') }}</strong></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>

                            <div class="modal-body with-padding">
                                <form class="form-horizontal short-code-data-form">
                                    <input type="hidden" class="short_code_input_key">

                                    <div class="short-code-admin-config"></div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="float-left btn btn-secondary" data-dismiss="modal">{{ trans('core/base::tables.cancel') }}</button>
                                <button class="float-right btn btn-primary add_short_code_btn">{{ trans('core/base::forms.add') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Modal -->
            @endpush
        @endif

        {!! apply_filters(BASE_FILTER_FORM_EDITOR_BUTTONS, null) !!}
    </div>
    <div class="clearfix"></div>
@endif

{!! Form::textarea($name, $value, $attributes) !!}

@if (setting('rich_editor', config('core.base.general.editor.primary')) === 'tinymce')
    @push('footer')
        <script>
            'use strict';
            function setImageValue(file) {
                $('.mce-btn.mce-open').parent().find('.mce-textbox').val(file);
            }
        </script>
        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="tinymce_form" action="{{ route('media.files.upload.from.editor') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0;height:0;overflow:hidden;display: none;">
            @csrf
            <input name="upload" id="upload_file" type="file" onchange="$('#tinymce_form').submit();this.value='';">
            <input type="hidden" value="tinymce" name="upload_type">
        </form>
    @endpush
@endif

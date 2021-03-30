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
                                <a class="float-right btn btn-primary add_short_code_btn" href="#">{{ trans('core/base::forms.add') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Modal -->

                <script>
                    "use strict";

                    $(document).ready(function () {

                        var $short_code_key = $('.short_code_input_key');

                        $('.list-shortcode-items li a').on('click', function (event) {
                            event.preventDefault();
                            var admin_config = $(this).closest('li').data('html');
                            if (admin_config != null && admin_config !== '') {
                                $('.short-code-data-form').trigger('reset');
                                $short_code_key.val($(this).data('key'));
                                $('.short-code-admin-config').html(admin_config);
                                if ($(this).data('description') !== '' && $(this).data('description') != null) {
                                    $('.short_code_modal .modal-title strong').text($(this).data('description'));
                                }
                                $('.short_code_modal').modal('show');
                            } else {
                                if ($('.editor-ckeditor').length > 0) {
                                    CKEDITOR.instances[$('.add_shortcode_btn_trigger').data('result')].insertHtml('[' + $(this).data('key') + '][/' + $(this).data('key') + ']');
                                } else {
                                    tinymce.get($('.add_shortcode_btn_trigger').data('result')).execCommand(
                                        'mceInsertContent',
                                        false,
                                        '[' + $(this).data('key') + '][/' + $(this).data('key') + ']'
                                    );
                                }
                            }
                        });


                        $('.add_short_code_btn').on('click', function (event) {
                            event.preventDefault();
                            var formElement = $('.short-code-data-form');
                            var formData = formElement.serializeArray();
                            var attributes = '';
                            $.each(formData, function (index, item) {
                                var element = formElement.find('*[name=' + item.name + ']');
                                if (element.data('shortcode-attribute') !== 'content') {
                                    attributes += ' ' + item.name + '="' + item.value + '"';
                                }
                            });

                            var content = '';
                            var contentElement = formElement.find('*[data-shortcode-attribute=content]');
                            if (contentElement != null && contentElement.val() != null && contentElement.val() !== '') {
                                content = contentElement.val();
                            }

                            if ($('.editor-ckeditor').length > 0) {
                                CKEDITOR.instances[$('.add_shortcode_btn_trigger').data('result')].insertHtml('[' + $short_code_key.val() + attributes + ']' + content + '[/' + $short_code_key.val() + ']');
                            } else {
                                tinymce.get($('.add_shortcode_btn_trigger').data('result')).execCommand(
                                    'mceInsertContent',
                                    false,
                                    '[' + $short_code_key.val() + attributes + ']' + content + '[/' + $short_code_key.val() + ']'
                                );
                            }
                            $(this).closest('.modal').modal('hide');
                        });

                    });
                </script>

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
            function setImageValue(file) {
                $('.mce-btn.mce-open').parent().find('.mce-textbox').val(file);
            }
        </script>
        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('media.files.upload.from.editor') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden;display: none;">
            {{ csrf_field() }}
            <input name="upload" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" value="tinymce" name="upload_type">
        </form>
    @endpush
@endif

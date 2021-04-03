class LanguageGlobalManagement {
    init() {
        let language_choice_select = $('#post_lang_choice');
        language_choice_select.data('prev', language_choice_select.val());

        language_choice_select.on('change', event =>  {
            $('.change_to_language_text').text($(event.currentTarget).find('option:selected').text());
            $('#confirm-change-language-modal').modal('show');
        });

        $('#confirm-change-language-modal .btn-warning.float-left').on('click', event =>  {
            event.preventDefault();
            language_choice_select.val(language_choice_select.data('prev')).trigger('change');
            $('#confirm-change-language-modal').modal('hide');
        });

        $('#confirm-change-language-button').on('click', event =>  {
            event.preventDefault();
            let _self = $(event.currentTarget);
            let flag_path = $('#language_flag_path').val();

            _self.addClass('button-loading');

            $.ajax({
                url: $('div[data-change-language-route]').data('change-language-route'),
                data: {
                    lang_meta_current_language: language_choice_select.val(),
                    reference_id: $('#reference_id').val(),
                    reference_type: $('#reference_type').val(),
                    lang_meta_created_from: $('#lang_meta_created_from').val()
                },
                type: 'POST',
                success: data =>  {
                    $('.active-language').html('<img src="' + flag_path + language_choice_select.find('option:selected').data('flag') + '.svg" width="16" title="' + language_choice_select.find('option:selected').text() + '" alt="' + language_choice_select.find('option:selected').text() + '" />');
                    if (!data.error) {
                        $('.current_language_text').text(language_choice_select.find('option:selected').text());
                        let html = '';
                        $.each(data.data, (index, el) => {
                            html += '<img src="' + flag_path + el.lang_flag + '.svg" width="16" title="' + el.lang_name + '" alt="' + el.lang_name + '">';
                            if (el.reference_id) {
                                html += '<a href="' + $('#route_edit').val() + '"> ' + el.lang_name + ' <i class="fa fa-edit"></i> </a><br />';
                            } else {
                                html += '<a href="' + $('#route_create').val() + '?ref_from=' + $('#content_id').val() +'&ref_lang=' + index + '"> ' + el.lang_name + ' <i class="fa fa-plus"></i> </a><br />';
                            }
                        });

                        $('#list-others-language').html(html);
                        $('#confirm-change-language-modal').modal('hide');
                        language_choice_select.data('prev', language_choice_select.val()).trigger('change');
                    }
                    _self.removeClass('button-loading');
                },
                error: data =>  {
                    Botble.showError(data.message);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.change-data-language-item', event =>  {
            event.preventDefault();
            window.location.href = $(event.currentTarget).find('span[data-href]').data('href');
        });
    }
};

$(document).ready(() => {
    new LanguageGlobalManagement().init();

    $.ajaxSetup({
        data: {
            'ref_from': $('meta[name="ref_from"]').attr('content'),
            'ref_lang': $('meta[name="ref_lang"]').attr('content'),
        }
    });

});

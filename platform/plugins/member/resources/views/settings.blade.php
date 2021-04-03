<div class="flexbox-annotated-section">
    <div class="flexbox-annotated-section-annotation">
        <div class="annotated-section-title pd-all-20">
            <h2>{{ trans('plugins/member::settings.title') }}</h2>
        </div>
        <div class="annotated-section-description pd-all-20 p-none-t">
            <p class="color-note">{{ trans('plugins/member::settings.description') }}</p>
        </div>
    </div>

    <div class="flexbox-annotated-section-content">
        <div class="wrapper-content pd-all-20">
            <div class="form-group">
                <label class="text-title-field"
                       for="verify_account_email">{{ trans('plugins/member::settings.verify_account_email') }}
                </label>
                <div class="ui-select-wrapper">
                    <select name="verify_account_email" class="ui-select" id="verify_account_email">
                        <option value="1" @if (setting('verify_account_email', config('plugins.member.general.verify_email')) == 1) selected @endif>{{ trans('core/base::base.yes') }}</option>
                        <option value="0" @if (setting('verify_account_email', config('plugins.member.general.verify_email')) == 0) selected @endif>{{ trans('core/base::base.no') }}</option>
                    </select>
                    <svg class="svg-next-icon svg-next-icon-size-16">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

@extends('core/base::layouts.master')
@section('content')
    {!! Form::open(['route' => ['settings.email.edit']]) !!}
    <div class="max-width-1200">
        <div class="flexbox-annotated-section">

            <div class="flexbox-annotated-section-annotation">
                <div class="annotated-section-title pd-all-20">
                    <h2>{{ trans('core/setting::setting.email.title') }}</h2>
                </div>
                <div class="annotated-section-description pd-all-20 p-none-t">
                    <p class="color-note">{{ trans('core/setting::setting.email.description') }}</p>
                </div>
            </div>

            <div class="flexbox-annotated-section-content">
                <div class="wrapper-content pd-all-20" id="email-config-form">
                    <div class="form-group">
                        <label class="text-title-field" for="email_driver">{{ trans('core/setting::setting.email.driver') }}</label>
                        <div class="ui-select-wrapper">
                            <select name="email_driver" class="ui-select" id="email_driver">
                                <option value="smtp" @if (setting('email_driver', config('mail.default')) == 'smtp') selected @endif>SMTP</option>
                                <option value="sendmail" @if (setting('email_driver', config('mail.default')) == 'sendmail') selected @endif>SendMail</option>
                                <option value="mailgun" @if (setting('email_driver', config('mail.default')) == 'mailgun') selected @endif>MailGun</option>
                                <option value="mandrill" @if (setting('email_driver', config('mail.default')) == 'mandrill') selected @endif>Mandrill</option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-title-field" for="email_port">{{ trans('core/setting::setting.email.port') }}</label>
                        <input data-counter="10" type="number" class="next-input" name="email_port" id="email_port"
                               value="{{ setting('email_port', config('mail.mailers.smtp.port')) }}" placeholder="{{ trans('core/setting::setting.email.port_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label class="text-title-field" for="email_host">{{ trans('core/setting::setting.email.host') }}</label>
                        <input data-counter="60" type="text" class="next-input" name="email_host" id="email_host"
                               value="{{ setting('email_host', config('mail.mailers.smtp.host')) }}" placeholder="{{ trans('core/setting::setting.email.host_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label class="text-title-field" for="email_username">{{ trans('core/setting::setting.email.username') }}</label>
                        <input data-counter="60" type="text" class="next-input" name="email_username" id="email_username"
                               value="{{ setting('email_username', config('mail.mailers.smtp.username')) }}" placeholder="{{ trans('core/setting::setting.email.username_placeholder') }}">
                    </div>
                    <div class="form-group setting-mail-password @if (setting('email_driver', config('mail.default')) == 'mailgun') hidden @endif">
                        <label class="text-title-field" for="email_password">{{ trans('core/setting::setting.email.password')  }}</label>
                        <input data-counter="60" type="password" class="next-input" name="email_password" id="email_password"
                               value="{{ setting('email_password', config('mail.mailers.smtp.password')) }}" placeholder="{{ trans('core/setting::setting.email.password_placeholder') }}">
                    </div>
                    <div class="setting-mail-mail-gun @if (setting('email_driver', config('mail.default')) !== 'mailgun') hidden @endif">
                        <div class="form-group">
                            <label class="text-title-field" for="email_mail_gun_domain">{{ trans('core/setting::setting.email.mail_gun_domain') }}</label>
                            <input data-counter="60" type="text" class="next-input" name="email_mail_gun_domain" id="email_mail_gun_domain"
                                   value="{{ setting('email_mail_gun_domain', config('services.mailgun.domain')) }}" placeholder="{{ trans('core/setting::setting.email.mail_gun_domain_placeholder') }}">
                        </div>
                        @if (!app()->environment('demo'))
                            <div class="form-group" style="margin-bottom: 1em;">
                                <label class="text-title-field" for="email_mail_gun_secret">{{ trans('core/setting::setting.email.mail_gun_secret')  }}</label>
                                <input data-counter="60" type="text" class="next-input" name="email_mail_gun_secret" id="email_mail_gun_secret"
                                       value="{{ setting('email_mail_gun_secret', config('services.mailgun.secret')) }}" placeholder="{{ trans('core/setting::setting.email.mail_gun_secret_placeholder') }}">
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="text-title-field" for="email_encryption">{{ trans('core/setting::setting.email.encryption') }}</label>
                        <div class="ui-select-wrapper">
                            <select name="email_encryption" class="ui-select" id="email_encryption">
                                <option value="tls" @if (setting('email_encryption', config('mail.mailers.smtp.encryption')) == 'tls') selected @endif>TLS</option>
                                <option value="ssl" @if (setting('email_encryption', config('mail.mailers.smtp.encryption')) == 'ssl') selected @endif>SSL</option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                    </div>

                    <div class="form-group setting-mail-sendmail @if (setting('email_driver', config('mail.default')) !== 'sendmail') hidden @endif">
                        <label class="text-title-field" for="email_password">{{ __('Sendmail Path')  }}</label>
                        <input type="text" class="next-input" name="email_sendmail_path" id="email_sendmail_path"
                               value="{{ setting('email_sendmail_path', config('mail.sendmail.path')) }}" placeholder="{{ __('Sendmail Path') }}">
                        <span class="help-ts">Default: <code>{{ config('mail.sendmail.path') }}</code></span>
                    </div>

                    <div class="form-group">
                        <label class="text-title-field" for="email_from_name">{{ trans('core/setting::setting.email.sender_name') }}</label>
                        <input data-counter="60" type="text" class="next-input" name="email_from_name" id="email_from_name"
                               value="{{ setting('email_from_name', config('mail.from.name')) }}" placeholder="{{ trans('core/setting::setting.email.sender_name_placeholder') }}">
                    </div>

                    <div class="form-group">
                        <label class="text-title-field" for="email_from_address">{{ trans('core/setting::setting.email.sender_email') }}</label>
                        <input data-counter="60" type="text" class="next-input" name="email_from_address" id="email_from_address"
                               value="{{ setting('email_from_address', config('mail.from.address')) }}" placeholder="admin@example.com">
                    </div>

                </div>
            </div>
        </div>

        <div class="flexbox-annotated-section">

            <div class="flexbox-annotated-section-annotation">
                <div class="annotated-section-title pd-all-20">
                    <h2>{{ trans('core/setting::setting.email.template_title') }}</h2>
                </div>
                <div class="annotated-section-description pd-all-20 p-none-t">
                    <p class="color-note">{{ trans('core/setting::setting.email.template_description') }}</p>
                </div>
            </div>

            <div class="flexbox-annotated-section-content">
                <div class="wrapper-content pd-all-20">
                    <div class="table-wrap">
                        <table class="table product-list ws-nm">
                            <thead>
                            <tr>
                                <th class="border-none-b">{{ trans('core/setting::setting.template') }}</th>
                                <th class="border-none-b"> {{ trans('core/setting::setting.description') }} </th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a class="hover-underline a-detail-template"
                                           href="{{ route('setting.email.template.edit', ['type' => 'core', 'name' => 'base', 'template_file' => 'header']) }}">
                                            {{ trans('core/setting::setting.email.template_header') }}
                                        </a>
                                    </td>
                                    <td>{{ trans('core/setting::setting.email.template_header_description') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <a class="hover-underline a-detail-template"
                                           href="{{ route('setting.email.template.edit', ['type' => 'core', 'name' => 'base', 'template_file' => 'footer']) }}">
                                            {{ trans('core/setting::setting.email.template_footer') }}
                                        </a>
                                    </td>
                                    <td>{{ trans('core/setting::setting.email.template_footer_description') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {!! apply_filters(BASE_FILTER_AFTER_SETTING_EMAIL_CONTENT, null) !!}

        <div class="flexbox-annotated-section" style="border: none">
            <div class="flexbox-annotated-section-annotation">
                &nbsp;
            </div>
            <div class="flexbox-annotated-section-content">
                <button class="btn btn-secondary" type="button" data-target="#send-test-email-modal" data-toggle="modal">{{ trans('core/setting::setting.test_send_mail') }}</button>
                <button class="btn btn-info" type="submit">{{ trans('core/setting::setting.save_settings') }}</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    {!! Form::modalAction('send-test-email-modal', trans('core/setting::setting.test_email_modal_title'), 'info', view('core/setting::test-email')->render(), 'send-test-email-btn', trans('core/setting::setting.send')) !!}
@stop

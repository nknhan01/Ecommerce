<div class="section pb_0">
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="contact_wrap contact_style3">
                <div class="contact_icon">
                    <i class="linearicons-map2"></i>
                </div>
                <div class="contact_text">
                    <span>{{ __('Address') }}</span>
                    <p>{{ theme_option('address') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="contact_wrap contact_style3">
                <div class="contact_icon">
                    <i class="linearicons-envelope-open"></i>
                </div>
                <div class="contact_text">
                    <span>{{ __('Email Address') }}</span>
                    <a href="mailto:{{ theme_option('email') }}">{{ theme_option('email') }}</a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="contact_wrap contact_style3">
                <div class="contact_icon">
                    <i class="linearicons-tablet2"></i>
                </div>
                <div class="contact_text">
                    <span>{{ __('Phone') }}</span>
                    <p>{{ theme_option('hotline') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION CONTACT -->

<div class="section pb_0">
    <div class="row">
        <div class="col-12">
            <div class="heading_s1">
                <h2>{{ __('Maps') }}</h2>
            </div>
            <div style="height: 400px; width: 100%; position: relative; text-align: right;">
                <div  style="height: 400px; width: 100%; overflow: hidden; background: none!important;">
                    <iframe width="100%" height="500" src="https://maps.google.com/maps?q={{ addslashes(theme_option('address')) }}%20&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- START SECTION CONTACT -->
<div class="section pt-0">
    <div class="row">
        <div class="col-12">
            <div class="heading_s1">
                <h2>{{ __('Get In touch') }}</h2>
            </div>
            <div class="field_form">
                {!! Form::open(['route' => 'public.send.contact', 'class' => 'form--contact contact-form', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_name" class="control-label required">{{ __('Name') }}</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="contact_name"
                                       placeholder="{{ __('Name') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_email" class="control-label required">{{ __('Email') }}</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="contact_email"
                                       placeholder="{{ __('Email') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_address" class="control-label">{{ __('Address') }}</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}" id="contact_address"
                                       placeholder="{{ __('Address') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_phone" class="control-label">{{ __('Phone') }}</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" id="contact_phone"
                                       placeholder="{{ __('Phone') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contact_subject" class="control-label">{{ __('Subject') }}</label>
                                <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" id="contact_subject"
                                       placeholder="{{ __('Subject') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contact_content" class="control-label required">{{ __('Message') }}</label>
                                <textarea name="content" id="contact_content" class="form-control" rows="5" placeholder="{{ __('Message') }}">{{ old('content') }}</textarea>
                            </div>
                        </div>
                        @if (setting('enable_captcha') && is_plugin_active('captcha'))
                            <div class="form-group col-12">
                                {!! Captcha::display() !!}
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-fill-out">{{ __('Send Message') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="contact-message contact-success-message" style="display: none"></div>
                        <div class="contact-message contact-error-message" style="display: none"></div>
                    </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END SECTION CONTACT -->

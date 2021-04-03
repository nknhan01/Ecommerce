{!! Form::open(['route' => 'public.send.contact', 'method' => 'POST', 'class' => 'contact-form']) !!}
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
            <div class="col-md-12">
                <div class="form-group">
                    {!! Captcha::display() !!}
                </div>
            </div>
        @endif
    </div>

    <div class="form-group"><p>{!! clean(__('The field with (<span style="color:#FF0000;">*</span>) is required.')) !!}</p></div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
    </div>

    <div class="form-group">
        <div class="contact-message contact-success-message" style="display: none"></div>
        <div class="contact-message contact-error-message" style="display: none"></div>
    </div>

{!! Form::close() !!}

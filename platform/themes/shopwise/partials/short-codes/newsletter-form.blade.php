<!-- START SECTION SUBSCRIBE NEWSLETTER -->
<div class="section bg_default small_pt small_pb">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="newsletter_text text_white">
                    <h3>{!! clean($title) !!}</h3>
                    <p>{!! clean($description) !!}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="newsletter_form2 rounded_input newsletter-form">
                    <form method="post" action="{{ route('public.newsletter.subscribe') }}">
                        @csrf
                        <input name="email" type="email" class="form-control" placeholder="{{ __('Enter Your Email') }}">
                        <button type="submit" class="btn btn-dark btn-radius">{{ __('Subscribe') }}</button>

                        @if (setting('enable_captcha') && is_plugin_active('captcha'))
                            {!! Captcha::display() !!}
                        @endif
                    </form>

                    <div class="newsletter-message newsletter-success-message" style="display: none"></div>
                    <div class="newsletter-message newsletter-error-message" style="display: none"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- START SECTION SUBSCRIBE NEWSLETTER -->

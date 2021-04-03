@if ($cookieConsentConfig['enabled'] && !$alreadyConsentedWithCookies)

    <div class="js-cookie-consent cookie-consent">
        <div class="cookie-consent-body">
            <span class="cookie-consent__message">
                {{ theme_option('cookie_consent_message', __('Your experience on this site will be improved by allowing cookies.')) }}
            </span>

            <button class="js-cookie-consent-agree cookie-consent__agree">
                {{ theme_option('cookie_consent_button_text', __('Allow cookies')) }}
            </button>
        </div>
    </div>
    <div data-site-cookie-name="{{ $cookieConsentConfig['cookie_name'] }}"></div>
    <div data-site-cookie-lifetime="{{ $cookieConsentConfig['cookie_lifetime'] }}"></div>
    <div data-site-cookie-domain="{{ config('session.domain') ?? request()->getHost() }}"></div>
    <div data-site-session-secure="{{ config('session.secure') ? ';secure' : null }}"></div>

@endif

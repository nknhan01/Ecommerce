@if ($cookieConsentConfig['enabled'] && ! $alreadyConsentedWithCookies)

    <link rel="stylesheet" href="{{ asset('vendor/core/plugins/cookie-consent/css/cookie-consent.css') }}">
    <div class="js-cookie-consent cookie-consent">
        <span class="cookie-consent__message">
            {{ theme_option('cookie_consent_message', 'Your experience on this site will be improved by allowing cookies.') }}
        </span>

        <button class="js-cookie-consent-agree cookie-consent__agree">
            {{ theme_option('cookie_consent_button_text', 'Allow cookies') }}
        </button>
    </div>

    <script>

        'use strict';

        window.botbleCookieConsent = (function () {

            const COOKIE_VALUE = 1;
            const COOKIE_DOMAIN = '{{ config('session.domain') ?? request()->getHost() }}';

            function consentWithCookies() {
                setCookie('{{ $cookieConsentConfig['cookie_name'] }}', COOKIE_VALUE, {{ $cookieConsentConfig['cookie_lifetime'] }});
                hideCookieDialog();
            }

            function cookieExists(name) {
                return document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1;
            }

            function hideCookieDialog() {
                const dialogs = document.getElementsByClassName('js-cookie-consent');

                for (let i = 0; i < dialogs.length; ++i) {
                    dialogs[i].style.display = 'none';
                }
            }

            function setCookie(name, value, expirationInDays) {
                const date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value
                    + ';expires=' + date.toUTCString()
                    + ';domain=' + COOKIE_DOMAIN
                    + ';path=/{{ config('session.secure') ? ';secure' : null }}';
            }

            if (cookieExists('{{ $cookieConsentConfig['cookie_name'] }}')) {
                hideCookieDialog();
            }

            const buttons = document.getElementsByClassName('js-cookie-consent-agree');

            for (let i = 0; i < buttons.length; ++i) {
                buttons[i].addEventListener('click', consentWithCookies);
            }

            return {
                consentWithCookies: consentWithCookies,
                hideCookieDialog: hideCookieDialog
            };
        })();
    </script>

@endif

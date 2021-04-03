<?php

namespace Platform\CookieConsent\Providers;

use Platform\Base\Traits\LoadAndPublishDataTrait;
use Cookie;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\ServiceProvider;
use Theme;

class CookieConsentServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('plugins/cookie-consent')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->publishAssets();

        if (defined('THEME_FRONT_FOOTER') && theme_option('cookie_consent_enable', 'yes') == 'yes') {
            $this->app->resolving(EncryptCookies::class, function (EncryptCookies $encryptCookies) {
                $encryptCookies->disableFor(config('plugins.cookie-consent.general.cookie_name'));
            });

            $this->app['view']->composer('plugins/cookie-consent::index', function (View $view) {
                $cookieConsentConfig = config('plugins.cookie-consent.general');

                $alreadyConsentedWithCookies = Cookie::has($cookieConsentConfig['cookie_name']);

                $view->with(compact('alreadyConsentedWithCookies', 'cookieConsentConfig'));
            });

            Theme::asset()
                ->usePath(false)
                ->add('cookie-consent-css', asset('vendor/core/plugins/cookie-consent/css/cookie-consent.css'), [], [], '1.0.0');
            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add('cookie-consent-js', asset('vendor/core/plugins/cookie-consent/js/cookie-consent.js'),
                    ['jquery'], [], '1.0.0');

            add_filter(THEME_FRONT_FOOTER, [$this, 'registerCookieConsent'], 1346);
        }

        theme_option()
            ->setSection([
                'title'      => __('Cookie Consent'),
                'desc'       => __('Cookie consent settings'),
                'id'         => 'opt-text-subsection-cookie-consent',
                'subsection' => true,
                'icon'       => 'fas fa-cookie-bite',
                'priority'   => 9999,
                'fields'     => [
                    [
                        'id'         => 'cookie_consent_enable',
                        'type'       => 'select',
                        'label'      => __('Enable cookie consent?'),
                        'attributes' => [
                            'name'    => 'cookie_consent_enable',
                            'list'    => [
                                'yes' => 'Yes',
                                'no'  => 'No',
                            ],
                            'value'   => 'yes',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id'         => 'cookie_consent_message',
                        'type'       => 'text',
                        'label'      => __('Message'),
                        'attributes' => [
                            'name'    => 'cookie_consent_message',
                            'value'   => 'Your experience on this site will be improved by allowing cookies.',
                            'options' => [
                                'class'        => 'form-control',
                                'placeholder'  => __('Message'),
                                'data-counter' => 400,
                            ],
                        ],
                    ],

                    [
                        'id'         => 'cookie_consent_button_text',
                        'type'       => 'text',
                        'label'      => __('Button text'),
                        'attributes' => [
                            'name'    => 'cookie_consent_button_text',
                            'value'   => 'Allow cookies',
                            'options' => [
                                'class'        => 'form-control',
                                'placeholder'  => __('Button text'),
                                'data-counter' => 120,
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * @param string $html
     * @return string
     * @throws \Throwable
     */
    public function registerCookieConsent($html): string
    {
        return $html . view('plugins/cookie-consent::index')->render();
    }
}

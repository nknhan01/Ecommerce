<?php

namespace Botble\Captcha\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Captcha\Facades\CaptchaFacade;
use Botble\Captcha\Captcha;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CaptchaServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function register()
    {
        config([
            'plugins.captcha.general.secret'   => setting('captcha_secret'),
            'plugins.captcha.general.site_key' => setting('captcha_site_key'),
        ]);

        $this->app->singleton('captcha', function ($app) {
            return new Captcha($app);
        });

        AliasLoader::getInstance()->alias('Captcha', CaptchaFacade::class);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->setNamespace('plugins/captcha')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations();

        $this->bootValidator();

        $this->app->register(HookServiceProvider::class);
    }

    /**
     * Create captcha validator rule
     */
    public function bootValidator()
    {
        $app = $this->app;

        /**
         * @var Validator $validator
         */
        $validator = $app['validator'];
        $validator->extend('captcha', function ($attribute, $value, $parameters) use ($app) {
            /**
             * @var Captcha $captcha
             */
            $captcha = $app['captcha'];
            /**
             * @var Request $request
             */
            $request = $app['request'];

            return $captcha->verify($value, $request->getClientIp(), $this->mapParameterToOptions($parameters));
        });

        $validator->replacer('captcha', function ($message) {
            return $message === 'validation.captcha' ? 'Failed to validate the captcha.' : $message;
        });

        if ($app->bound('form')) {
            $app['form']->macro('captcha', function ($attributes = []) use ($app) {
                return $app['captcha']->display($attributes, ['lang' => $app->getLocale()]);
            });
        }
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function mapParameterToOptions($parameters = []): array
    {
        if (!is_array($parameters)) {
            return [];
        }
        $options = [];
        foreach ($parameters as $parameter) {
            $option = explode(':', $parameter);
            if (count($option) === 2) {
                Arr::set($options, $option[0], $option[1]);
            }
        }

        return $options;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['captcha'];
    }
}

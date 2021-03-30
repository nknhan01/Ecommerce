<?php

namespace Botble\Language;

use Botble\Base\Supports\Helper;
use Botble\Language\Models\Language;
use Botble\Language\Repositories\Interfaces\LanguageInterface;
use Botble\Language\Repositories\Interfaces\LanguageMetaInterface;
use Eloquent;
use Exception;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Request;

class LanguageManager
{
    /**
     * @var LanguageInterface
     */
    protected $languageRepository;

    /**
     * Illuminate translator class.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Illuminate router class.
     *
     * @var Router
     */
    protected $router;

    /**
     * @var Application|mixed
     */
    protected $app;

    /**
     * @var
     */
    protected $baseUrl;

    /**
     * Default locale
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * Supported Locales
     *
     * @var array
     */
    protected $supportedLocales;

    /**
     * Current locale
     *
     * @var string
     */
    protected $currentLocale = false;

    /**
     * An array that contains all routes that should be translated
     *
     * @var array
     */
    protected $translatedRoutes = [];

    /**
     * Name of the translation key of the current route, it is used for url translations
     *
     * @var string
     */
    protected $routeName;

    /**
     * @var LanguageMetaInterface
     */
    protected $languageMetaRepository;

    /**
     * @var string
     */
    protected $currentAdminLocaleCode;

    /**
     * @var array
     */
    protected $activeLanguages = [];

    /**
     * @var array
     */
    protected $activeLanguageSelect = ['*'];

    /**
     * @var Language
     */
    protected $defaultLanguage;

    /**
     * @var array
     */
    protected $defaultLanguageSelect = ['*'];

    /**
     * Language constructor.
     * @param LanguageInterface $languageRepository
     * @param LanguageMetaInterface $languageMetaRepository
     * @param HttpRequest $request
     *
     * @since 2.0
     */
    public function __construct(
        LanguageInterface $languageRepository,
        LanguageMetaInterface $languageMetaRepository,
        HttpRequest $request
    ) {
        $this->languageRepository = $languageRepository;

        $this->app = app();

        $this->translator = $this->app['translator'];
        $this->router = $this->app['router'];

        $this->supportedLocales = $this->getSupportedLocales();

        $this->setDefaultLocale();

        $this->defaultLocale = $this->getDefaultLocale();

        $this->languageMetaRepository = $languageMetaRepository;

        if ($request->has('ref_lang')) {
            $this->currentAdminLocaleCode = $request->input('ref_lang');
        }
    }

    /**
     * Return an array of all supported Locales
     *
     * @return array
     *
     */
    public function getSupportedLocales()
    {
        if (!empty($this->supportedLocales)) {
            return $this->supportedLocales;
        }

        $languages = $this->getActiveLanguage();

        $locales = [];
        foreach ($languages as $language) {
            if (!in_array($language->lang_id, json_decode(setting('language_hide_languages', '[]'), true))) {
                $locales[$language->lang_locale] = [
                    'lang_name'       => $language->lang_name,
                    'lang_locale'     => $language->lang_locale,
                    'lang_code'       => $language->lang_code,
                    'lang_flag'       => $language->lang_flag,
                    'lang_is_rtl'     => $language->lang_is_rtl,
                    'lang_is_default' => $language->lang_is_default,
                ];
            }
        }

        $this->supportedLocales = $locales;

        return $locales;
    }

    /**
     * Set and return supported locales
     *
     * @param array $locales Locales that the App supports
     */
    public function setSupportedLocales($locales)
    {
        $this->supportedLocales = $locales;
    }

    /**
     * @param array $select
     * @return array
     *
     * @since 2.0
     */
    public function getActiveLanguage($select = ['*'])
    {
        if ($this->activeLanguages && $this->activeLanguageSelect === $select) {
            return $this->activeLanguages;
        }

        $this->activeLanguages = $this->languageRepository->getActiveLanguage($select);
        $this->activeLanguageSelect = $select;

        return $this->activeLanguages;
    }

    /**
     * Returns default locale
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @return void
     */
    public function setDefaultLocale()
    {
        foreach ($this->supportedLocales as $key => $language) {
            if ($language['lang_is_default']) {
                $this->defaultLocale = $key;
            }
        }
        if (empty($this->defaultLocale)) {
            $this->defaultLocale = config('app.locale');
        }
    }

    /**
     * @return string
     *
     * @since 2.1
     */
    public function getHiddenLanguageText()
    {
        $hidden = json_decode(setting('language_hide_languages', '[]'), true);
        $text = '';
        $languages = $this->getActiveLanguage();
        if (!empty($languages)) {
            $languages = $languages->pluck('lang_name', 'lang_id')->all();
        } else {
            $languages = [];
        }
        foreach ($hidden as $item) {
            if (array_key_exists($item, $languages)) {
                if (!empty($text)) {
                    $text .= ', ';
                }
                $text .= $languages[$item];
            }
        }
        return $text;
    }

    /**
     * @param $id
     * @param $unique_key
     * @return mixed
     *
     * @since 2.0
     */
    public function getRelatedLanguageItem($id, $uniqueKey)
    {
        /**
         * @var Builder $meta
         */
        $meta = $this->languageMetaRepository->getModel()->where('lang_meta_origin', '=', $uniqueKey);
        if ($id != Request::input('ref_from')) {
            $meta = $meta->where('reference_id', '!=', $id);
        }
        return $meta->pluck('reference_id', 'lang_meta_code')->all();
    }

    /**
     * Set and return current locale
     *
     * @param string $locale Locale to set the App to (optional)
     * @return string Returns locale (if route has any) or null (if route does not have a locale)
     * @throws Exception
     */
    public function setLocale($locale = null)
    {
        if (empty($locale) || !is_string($locale)) {
            // If the locale has not been passed through the function
            // it tries to get it from the first segment of the url
            $locale = request()->segment(1);
        }

        if (array_key_exists($locale, $this->supportedLocales)) {
            if ($locale != $this->currentLocale) {
                Helper::clearCache();
            }
            $this->currentLocale = $locale;
        } else {
            // if the first segment/locale passed is not valid
            // the system would ask which locale have to take
            // it could be taken by the browser
            // depending on your configuration

            $locale = null;

            // if we reached this point and hideDefaultLocaleInURL is true
            // we have to assume we are routing to a defaultLocale route.
            if ($this->hideDefaultLocaleInURL()) {
                $this->currentLocale = $this->defaultLocale;
            }
            // but if hideDefaultLocaleInURL is false, we have
            // to retrieve it from the browser...
            else {
                $this->currentLocale = $this->getCurrentLocale();
            }
        }

        $this->app->setLocale($this->currentLocale);

        return $locale;
    }

    /**
     * Returns the translation key for a given path
     *
     * @return boolean Returns value of hideDefaultLocaleInURL in config.
     */
    public function hideDefaultLocaleInURL()
    {
        return setting('language_hide_default', config('plugins.language.general.hideDefaultLocaleInURL'));
    }

    /**
     * Returns current language
     *
     * @return string current language
     */
    public function getCurrentLocale()
    {
        if ($this->currentLocale) {
            return $this->currentLocale;
        }

        /*if ($this->useAcceptLanguageHeader() && !$this->app->runningInConsole()) {
            $negotiator = new LanguageNegotiator($this->defaultLocale, $this->getSupportedLocales(), request());

            return $negotiator->negotiateLanguage();
        }*/

        // or get application default language
        return $this->getDefaultLocale();
    }

    /**
     * Returns an URL adapted to $locale or current locale
     *
     * @param string $url URL to adapt. If not passed, the current url would be taken.
     * @param string|boolean $locale Locale to adapt, false to remove locale
     *
     * @return string URL translated
     */
    public function localizeURL($url = null, $locale = null)
    {
        return $this->getLocalizedURL($locale, $url);
    }

    /**
     * Returns an URL adapted to $locale
     *
     * @param string|boolean $locale Locale to adapt, false to remove locale
     * @param string|false $url URL to adapt in the current language. If not passed, the current url would be taken.
     * @param array $attributes Attributes to add to the route,
     * if empty, the system would try to extract them from the url.
     *
     * @return string|false URL translated, False if url does not exist
     */
    public function getLocalizedURL($locale = null, $url = null, $attributes = [])
    {
        if (empty($locale)) {
            $locale = $this->getCurrentLocale();
        }

        if (empty($attributes)) {
            $attributes = $this->extractAttributes($url, $locale);
        }

        if (empty($url)) {
            if (!empty($this->routeName)) {
                return $this->getURLFromRouteNameTranslated($locale, $this->routeName, $attributes);
            }

            $url = request()->fullUrl();
        }

        if ($locale && $translatedRoute = $this->findTranslatedRouteByUrl($url, $attributes, $this->currentLocale)) {
            return $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes);
        }

        $base_path = request()->getBaseUrl();
        $parsed_url = parse_url($url);
        $url_locale = $this->getDefaultLocale();

        if (!$parsed_url || empty($parsed_url['path'])) {
            $path = $parsed_url['path'] = '';
        } else {
            $parsed_url['path'] = str_replace($base_path, '', '/' . ltrim($parsed_url['path'], '/'));
            $path = $parsed_url['path'];
            foreach (array_keys($this->getSupportedLocales()) as $localeCode) {
                $parsed_url['path'] = preg_replace('%^/?' . $localeCode . '/%', '$1', $parsed_url['path']);
                if ($parsed_url['path'] !== $path) {
                    $url_locale = $localeCode;
                    break;
                }

                $parsed_url['path'] = preg_replace('%^/?' . $localeCode . '$%', '$1', $parsed_url['path']);
                if ($parsed_url['path'] !== $path) {
                    $url_locale = $localeCode;
                    break;
                }
            }
        }

        $parsed_url['path'] = ltrim($parsed_url['path'], '/');

        if ($translatedRoute = $this->findTranslatedRouteByPath($parsed_url['path'], $url_locale)) {
            return $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes);
        }

        if ($this->hideDefaultLocaleInURL()) {
            if (!empty($locale)) {
                $parsed_url['path'] = $locale . '/' . ltrim(ltrim($base_path, '/') . '/' . $parsed_url['path'], '/');
            }
            if ($this->getCurrentLocale() == $this->getDefaultLocale()) {
                $parsed_url['path'] = str_replace($this->getCurrentLocale() . '/', '/', $parsed_url['path']);
            }
        } else {
            if (!empty($locale) && ($locale != $this->defaultLocale || !$this->hideDefaultLocaleInURL())) {
                $parsed_url['path'] = $locale . '/' . ltrim($parsed_url['path'], '/');
            }
            $parsed_url['path'] = ltrim(ltrim($base_path, '/') . '/' . $parsed_url['path'], '/');
        }

        //Make sure that the pass path is returned with a leading slash only if it come in with one.
        if (Str::startsWith($path, '/') === true) {
            $parsed_url['path'] = '/' . $parsed_url['path'];
        }
        $parsed_url['path'] = rtrim($parsed_url['path'], '/');

        $url = $this->unparseUrl($parsed_url);

        if ($this->checkUrl($url)) {
            return $url;
        }

        return $this->createUrlFromUri($url);
    }

    /**
     * Extract attributes for current url
     *
     * @param bool|false|null|string $url to extract attributes,
     * if not present, the system will look for attributes in the current call
     *
     * @param string $locale
     * @return array Array with attributes
     */
    protected function extractAttributes($url = false, $locale = '')
    {
        if (!empty($url)) {
            $attributes = [];
            $parse = parse_url($url);
            if (isset($parse['path'])) {
                $parse = explode('/', $parse['path']);
            } else {
                $parse = [];
            }
            $url = [];
            foreach ($parse as $segment) {
                if (!empty($segment)) {
                    $url[] = $segment;
                }
            }
            foreach ($this->router->getRoutes() as $route) {
                $path = $route->uri();
                if (!preg_match('/{[\w]+}/', $path)) {
                    continue;
                }

                $path = explode('/', $path);
                $index = 0;

                $match = true;
                foreach ($path as $key => $segment) {
                    if (isset($url[$index])) {
                        if ($segment === $url[$index]) {
                            $index++;
                            continue;
                        }
                        if (preg_match('/{[\w]+}/', $segment)) {
                            // must-have parameters
                            $attribute_name = preg_replace(['/}/', '/{/', '/\?/'], '', $segment);
                            $attributes[$attribute_name] = $url[$index];
                            $index++;
                            continue;
                        }
                        if (preg_match('/{[\w]+\?}/', $segment)) {
                            // optional parameters
                            if (!isset($path[$key + 1]) || $path[$key + 1] !== $url[$index]) {
                                // optional parameter taken
                                $attribute_name = preg_replace(['/}/', '/{/', '/\?/'], '', $segment);
                                $attributes[$attribute_name] = $url[$index];
                                $index++;
                                continue;
                            }
                        }
                    } elseif (!preg_match('/{[\w]+\?}/', $segment)) {
                        // no optional parameters but no more $url given
                        // this route does not match the url
                        $match = false;
                        break;
                    }
                }

                if (isset($url[$index + 1])) {
                    $match = false;
                }

                if ($match) {
                    return $attributes;
                }
            }
        } else {
            if (!$this->router->current()) {
                return [];
            }
            $attributes = $this->normalizeAttributes($this->router->current()->parameters());
            $response = event('routes.translation', [$locale, $attributes]);
            if (!empty($response)) {
                $response = array_shift($response);
            }
            if (is_array($response)) {
                $attributes = array_merge($attributes, $response);
            }
        }
        return $attributes;
    }

    /**
     * Normalize attributes gotten from request parameters.
     *
     * @param array $attributes The attributes
     * @return     array  The normalized attributes
     */
    protected function normalizeAttributes($attributes)
    {
        if (array_key_exists('data', $attributes) && is_array($attributes['data']) && !count($attributes['data'])) {
            $attributes['data'] = null;
            return $attributes;
        }

        return $attributes;
    }

    /**
     * Returns an URL adapted to the route name and the locale given
     *
     * @param string|boolean $locale Locale to adapt
     * @param string $transKeyName Translation key name of the url to adapt
     * @param array $attributes Attributes for the route (only needed if transKeyName needs them)
     *
     * @return string|false URL translated
     */
    public function getURLFromRouteNameTranslated($locale, $transKeyName, $attributes = [])
    {
        if (!is_string($locale)) {
            $locale = $this->getDefaultLocale();
        }

        $route = '';

        if (!($locale === $this->defaultLocale && $this->hideDefaultLocaleInURL())) {
            $route = '/' . $locale;
        }
        if (is_string($locale) && $this->translator->has($transKeyName, $locale)) {
            $translation = $this->translator->trans($transKeyName, [], $locale);
            $route .= '/' . $translation;

            $route = $this->substituteAttributesInRoute($attributes, $route);
        }

        if (empty($route)) {
            // This locale does not have any key for this route name
            return false;
        }

        return rtrim($this->createUrlFromUri($route));
    }

    /**
     * Change route attributes for the ones in the $attributes array
     *
     * @param $attributes array Array of attributes
     * @param string $route string route to substitute
     * @return string route with attributes changed
     */
    protected function substituteAttributesInRoute($attributes, $route)
    {
        foreach ($attributes as $key => $value) {
            if ($value instanceOf UrlRoutable) {
                $value = $value->getRouteKey();
            }
            $route = str_replace('{' . $key . '}', $value, $route);
            $route = str_replace('{' . $key . '?}', $value, $route);
        }

        // delete empty optional arguments that are not in the $attributes array
        $route = preg_replace('/\/{[^)]+\?}/', '', $route);

        return $route;
    }

    /**
     * Create an url from the uri
     * @param string $uri Uri
     *
     * @return  string Url for the given uri
     */
    public function createUrlFromUri($uri)
    {
        $uri = ltrim($uri, '/');

        if (empty($this->baseUrl)) {
            return app('url')->to($uri);
        }

        return $this->baseUrl . $uri;
    }

    /**
     * Returns the translated route for an url and the attributes given and a locale
     *
     * @param string|false|null $url Url to check if it is a translated route
     * @param array $attributes Attributes to check if the url exists in the translated routes array
     * @param string $locale Language to check if the url exists
     *
     * @return string|false Key for translation, false if not exist
     */
    protected function findTranslatedRouteByUrl($url, $attributes, $locale)
    {
        if (empty($url)) {
            return false;
        }

        // check if this url is a translated url
        foreach ($this->translatedRoutes as $translatedRoute) {
            $routeName = $this->getURLFromRouteNameTranslated($locale, $translatedRoute, $attributes);

            if ($this->getNonLocalizedURL($routeName) == $this->getNonLocalizedURL($url)) {
                return $translatedRoute;
            }
        }
        return false;
    }

    /**
     * It returns an URL without locale (if it has it)
     * Convenience function wrapping getLocalizedURL(false)
     *
     * @param string|false $url URL to clean, if false, current url would be taken
     *
     * @return string URL with no locale in path
     */
    public function getNonLocalizedURL($url = null)
    {
        return $this->getLocalizedURL(false, $url);
    }

    /**
     * Returns the translated route for the path and the url given
     *
     * @param string $path Path to check if it is a translated route
     * @param string $urlLocale Language to check if the path exists
     *
     * @return string|false Key for translation, false if not exist
     */
    protected function findTranslatedRouteByPath($path, $urlLocale)
    {
        // check if this url is a translated url
        foreach ($this->translatedRoutes as $translatedRoute) {
            if ($this->translator->trans($translatedRoute, [], $urlLocale) == rawurldecode($path)) {
                return $translatedRoute;
            }
        }
        return false;
    }

    /**
     * Build URL using array data from parse_url
     *
     * @param array|false $parsed_url Array of data from parse_url function
     *
     * @return string Returns URL as string.
     */
    protected function unparseUrl($parsed_url)
    {
        if (empty($parsed_url)) {
            return '';
        }

        $url = '';
        $url .= isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $url .= isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $url .= isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $url .= $user . (($user || $pass) ? $pass . '@' : '');

        if (!empty($url)) {
            $url .= isset($parsed_url['path']) ? '/' . ltrim($parsed_url['path'], '/') : '';
        } else {
            $url .= isset($parsed_url['path']) ? $parsed_url['path'] : '';
        }

        $url .= isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $url .= isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return $url;
    }

    /**
     * Returns true if the string given is a valid url
     *
     * @param string $url String to check if it is a valid url
     *
     * @return boolean Is the string given a valid url?
     */
    protected function checkUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Returns current locale name
     *
     * @return string current locale name
     */
    public function getCurrentLocaleName()
    {
        if (empty($this->supportedLocales)) {
            return null;
        }
        return Arr::get($this->supportedLocales, $this->getCurrentLocale() . '.lang_name');
    }

    /**
     * Returns current text direction
     *
     * @return string current locale name
     *
     */
    public function getCurrentLocaleRTL()
    {
        if (empty($this->supportedLocales)) {
            return false;
        }

        return Arr::get($this->supportedLocales, $this->getCurrentLocale() . '.lang_is_rtl');
    }

    /**
     * Returns current locale code
     *
     * @return string current locale code
     */
    public function getCurrentLocaleCode()
    {
        if (empty($this->supportedLocales)) {
            return null;
        }
        return Arr::get($this->supportedLocales, $this->getCurrentLocale() . '.lang_code');
    }

    /**
     * @param string $code
     */
    public function setCurrentAdminLocale($code)
    {
        $this->currentAdminLocaleCode = $code;
    }

    /**
     * Returns current admin locale code
     *
     * @return string current locale code
     */
    public function getCurrentAdminLocale()
    {
        $adminLocale = $this->getCurrentAdminLocaleCode();
        foreach ($this->supportedLocales as $locale => $supportedLocale) {
            if ($supportedLocale['lang_code'] == $adminLocale) {
                return $locale;
            }
        }

        return $adminLocale;
    }

    /**
     * Returns current admin locale code
     *
     * @return string current locale code
     */
    public function getCurrentAdminLocaleCode()
    {
        if (empty($this->supportedLocales)) {
            return null;
        }

        if ($this->currentAdminLocaleCode) {
            return $this->currentAdminLocaleCode;
        }

        if (request()->has('ref_lang')) {
            return request()->input('ref_lang');
        }

        return Arr::get($this->supportedLocales, $this->getCurrentLocale() . '.lang_code');
    }

    /**
     * Returns current locale code
     *
     * @return string current locale code
     */
    public function getDefaultLocaleCode()
    {
        if (empty($this->supportedLocales)) {
            return null;
        }
        return Arr::get($this->supportedLocales, $this->getDefaultLocale() . '.lang_code', config('app.locale'));
    }

    /**
     * Returns current locale code
     *
     * @return string current locale code
     */
    public function getCurrentLocaleFlag()
    {
        if (empty($this->supportedLocales)) {
            return null;
        }
        return Arr::get($this->supportedLocales, $this->getCurrentLocale() . '.lang_flag');
    }

    /**
     * Returns supported languages language key
     *
     * @return array keys of supported languages
     */
    public function getSupportedLanguagesKeys()
    {
        return array_keys($this->supportedLocales);
    }

    /**
     * Check if Locale exists on the supported locales array
     *
     * @param string|boolean $locale string|bool Locale to be checked
     * @return boolean is the locale supported?
     */
    public function checkLocaleInSupportedLocales($locale)
    {
        $locales = $this->getSupportedLocales();
        if ($locale !== false && empty($locales[$locale])) {
            return false;
        }
        return true;
    }

    /**
     * Set current route name
     * @param string $routeName current route name
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * Translate routes and save them to the translated routes array (used in the localize route filter)
     *
     * @param string $routeName Key of the translated string
     *
     * @return string Translated string
     */
    public function transRoute($routeName)
    {
        if (!in_array($routeName, $this->translatedRoutes)) {
            $this->translatedRoutes[] = $routeName;
        }

        return $this->translator->trans($routeName);
    }

    /**
     * Returns the translation key for a given path
     *
     * @param string $path Path to get the key translated
     *
     * @return string|false Key for translation, false if not exist
     */
    public function getRouteNameFromAPath($path)
    {
        $attributes = $this->extractAttributes($path);

        $path = str_replace(url('/'), '', $path);
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }
        $path = str_replace('/' . $this->currentLocale . '/', '', $path);
        $path = trim($path, '/');

        foreach ($this->translatedRoutes as $route) {
            if ($this->substituteAttributesInRoute($attributes, $this->translator->trans($route)) === $path) {
                return $route;
            }
        }

        return false;
    }

    /**
     * Sets the base url for the site
     * @param string $url Base url for the site
     */
    public function setBaseUrl($url)
    {
        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        $this->baseUrl = $url;
    }

    /**
     * @param string $screen
     * @param HttpRequest $request
     * @param Eloquent|false $data
     * @return bool
     */
    public function saveLanguage($screen, $request, $data)
    {
        $defaultLanguage = $this->getDefaultLanguage(['lang_id']);
        if (!empty($defaultLanguage)) {
            if ($data != false && in_array(get_class($data), $this->supportedModels())) {
                if ($request->input('language')) {
                    $unique_key = null;
                    $meta = $this->languageMetaRepository->getFirstBy(
                        [
                            'reference_id'   => $data->id,
                            'reference_type' => get_class($data),
                        ]
                    );
                    if (!$meta && !$request->input('ref_from')) {
                        $unique_key = md5($data->id . $screen . time());
                    } elseif ($request->input('ref_from')) {
                        $unique_key = $this->languageMetaRepository->getFirstBy(
                            [
                                'reference_id'   => $request->input('ref_from'),
                                'reference_type' => get_class($data),
                            ]
                        )->lang_meta_origin;
                    }

                    if (!$meta) {
                        $meta = $this->languageMetaRepository->getModel();
                        $meta->reference_id = $data->id;
                        $meta->reference_type = get_class($data);
                        $meta->lang_meta_origin = $unique_key;
                    }

                    $meta->lang_meta_code = $request->input('language');
                    $this->languageMetaRepository->createOrUpdate($meta);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array $select
     * @return Language
     * @since 2.2
     */
    public function getDefaultLanguage($select = ['*'])
    {
        if ($this->defaultLanguage && $this->defaultLanguageSelect === $select) {
            return $this->defaultLanguage;
        }
        $this->defaultLanguage = $this->languageRepository->getDefaultLanguage($select);
        $this->defaultLanguageSelect = $select;

        return $this->defaultLanguage;
    }

    /**
     * @since 2.0
     */
    public function supportedModels()
    {
        return apply_filters(LANGUAGE_FILTER_MODEL_USING_MULTI_LANGUAGE,
            config('plugins.language.general.supported', []));
    }

    /**
     * @param string $screen
     * @param Eloquent|false $data
     */
    public function deleteLanguage($screen, $data)
    {
        $defaultLanguage = $this->getDefaultLanguage(['lang_id']);
        if (!empty($defaultLanguage)) {
            if (in_array(get_class($data), $this->supportedModels())) {
                $this->languageMetaRepository->deleteBy([
                    'reference_id'   => $data->id,
                    'reference_type' => get_class($data),
                ]);
                return true;
            }
        }

        return false;
    }

    /**
     * @param string | array $model
     * @return LanguageManager
     */
    public function registerModule($model)
    {
        if (!is_array($model)) {
            $model = [$model];
        }
        config([
            'plugins.language.general.supported' => array_merge(config('plugins.language.general.supported', []),
                $model),
        ]);

        return $this;
    }

    /**
     * Returns the translation key for a given path
     *
     * @return boolean Returns value of useAcceptLanguageHeader in config.
     */
    protected function useAcceptLanguageHeader()
    {
        return config('plugins.language.general.useAcceptLanguageHeader');
    }
}

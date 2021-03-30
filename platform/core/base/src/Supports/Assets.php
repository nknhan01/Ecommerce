<?php

namespace Botble\Base\Supports;

use Botble\Assets\Assets as BaseAssets;
use Botble\Assets\HtmlBuilder;
use File;
use Illuminate\Config\Repository;
use Illuminate\Support\Str;
use Throwable;

/**
 * @since 22/07/2015 11:23 PM
 */
class Assets extends BaseAssets
{
    /**
     * Assets constructor.
     *
     * @param Repository $config
     * @param HtmlBuilder $htmlBuilder
     */
    public function __construct(Repository $config, HtmlBuilder $htmlBuilder)
    {
        parent::__construct($config, $htmlBuilder);

        $this->config = $config->get('core.base.assets');

        $this->scripts = $this->config['scripts'];

        $this->styles = $this->config['styles'];
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get all admin themes
     *
     * @return array
     */
    public function getThemes(): array
    {
        $themes = [];

        if (!File::isDirectory(public_path('vendor/core/css/themes'))) {
            return $themes;
        }

        foreach (File::files(public_path('vendor/core/css/themes')) as $file) {
            $name = '/vendor/core/css/themes/' . basename($file);
            if (!Str::contains($file, '.css.map')) {
                $themes[basename($file, '.css')] = $name;
            }
        }

        return $themes;
    }

    /**
     * @return array
     */
    public function getAdminLocales(): array
    {
        $languages = [];
        $locales = scan_folder(resource_path('lang'));
        if (in_array('vendor', $locales)) {
            $locales = array_merge($locales, scan_folder(resource_path('lang/vendor')));
        }

        foreach ($locales as $locale) {
            if ($locale === 'vendor') {
                continue;
            }
            foreach (Language::getListLanguages() as $key => $language) {
                if (in_array($key, [$locale, str_replace('-', '_', $locale)]) ||
                    in_array($language[0], [$locale, str_replace('-', '_', $locale)])
                ) {
                    $languages[$locale] = [
                        'locale' => $locale,
                        'name'   => $language[2],
                        'flag'   => $language[4],
                    ];
                }
            }
        }

        return $languages;
    }

    /**
     * @param array $lastStyles
     * @return string
     * @throws Throwable
     */
    public function renderHeader($lastStyles = [])
    {
        do_action(BASE_ACTION_ENQUEUE_SCRIPTS);

        return parent::renderHeader($lastStyles);
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function renderFooter()
    {
        $bodyScripts = $this->getScripts(self::ASSETS_SCRIPT_POSITION_FOOTER);

        return view('assets::footer', compact('bodyScripts'))->render();
    }
}

<?php

namespace Botble\Language\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Language;
use Botble\Language\LanguageManager;
use Botble\Language\Repositories\Interfaces\LanguageMetaInterface;
use Botble\Language\Http\Requests\LanguageRequest;
use Botble\Language\Repositories\Interfaces\LanguageInterface;
use Botble\Setting\Supports\SettingStore;
use DB;
use Exception;
use File;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Language as LanguageFacade;
use Schema;
use Throwable;

class LanguageController extends BaseController
{
    /**
     * @var LanguageInterface
     */
    protected $languageRepository;

    /**
     * @var LanguageMetaInterface
     */
    protected $languageMetaRepository;

    /**
     * LanguageController constructor.
     * @param LanguageInterface $languageRepository
     * @param LanguageMetaInterface $languageMetaRepository
     */
    public function __construct(LanguageInterface $languageRepository, LanguageMetaInterface $languageMetaRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->languageMetaRepository = $languageMetaRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        page_title()->setTitle(trans('plugins/language::language.name'));

        Assets::addScriptsDirectly(['vendor/core/plugins/language/js/language.js']);

        $languages = Language::getListLanguages();
        $flags = Language::getListLanguageFlags();
        $activeLanguages = $this->languageRepository->all();

        return view('plugins/language::index', compact('languages', 'flags', 'activeLanguages'));
    }

    /**
     * @param LanguageRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postStore(LanguageRequest $request, BaseHttpResponse $response)
    {
        try {
            $language = $this->languageRepository->getFirstBy([
                'lang_code' => $request->input('lang_code'),
            ]);
            if ($language) {
                return $response
                    ->setError()
                    ->setMessage(__('This language is added already!'));
            }

            if ($this->languageRepository->count() == 0) {
                $request->merge(['lang_is_default' => 1]);
            }
            $language = $this->languageRepository->createOrUpdate($request->except('lang_id'));

            $defaultLocale = resource_path('lang/en');
            $locale = $language->lang_locale;
            if (File::exists($defaultLocale)) {
                File::copyDirectory($defaultLocale, resource_path('lang/' . $locale));
            }

            $this->createLocaleInPath(resource_path('lang/vendor/core'), $locale);
            $this->createLocaleInPath(resource_path('lang/vendor/packages'), $locale);
            $this->createLocaleInPath(resource_path('lang/vendor/plugins'), $locale);

            $themeLocale = Arr::first(scan_folder(theme_path(setting('theme') . '/lang')));

            if ($themeLocale) {
                File::copy(theme_path(setting('theme') . '/lang/' . $themeLocale), resource_path('lang/' . $locale . '.json'));
            }

            event(new CreatedContentEvent(LANGUAGE_MODULE_SCREEN_NAME, $request, $language));

            return $response
                ->setData(view('plugins/language::partials.language-item', ['item' => $language])->render())
                ->setMessage(trans('core/base::notices.create_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param string $path
     * @param string $locale
     * @return int|void
     */
    protected function createLocaleInPath(string $path, $locale)
    {
        $folders = File::directories($path);

        foreach ($folders as $module) {
            foreach (File::directories($module) as $item) {
                if (File::name($item) == 'en') {
                    File::copyDirectory($item, $module . '/' . $locale);
                }
            }
        }

        return count($folders);
    }

    /**
     * @param string $path
     * @return int|void
     */
    protected function removeLocaleInPath(string $path, $locale)
    {
        $folders = File::directories($path);

        foreach ($folders as $module) {
            foreach (File::directories($module) as $item) {
                if (File::name($item) == $locale) {
                    File::deleteDirectory($item);
                }
            }
        }

        return count($folders);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function update(Request $request, BaseHttpResponse $response)
    {
        try {
            $language = $this->languageRepository->getFirstBy(['lang_id' => $request->input('lang_id')]);
            if (empty($language)) {
                abort(404);
            }
            $language->fill($request->input());
            $language = $this->languageRepository->createOrUpdate($language);

            event(new UpdatedContentEvent(LANGUAGE_MODULE_SCREEN_NAME, $request, $language));

            return $response
                ->setData(view('plugins/language::partials.language-item', ['item' => $language])->render())
                ->setMessage(trans('core/base::notices.update_success_message'));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postChangeItemLanguage(Request $request, BaseHttpResponse $response)
    {
        $referenceId = $request->input('reference_id') ? $request->input('reference_id') : $request->input('lang_meta_created_from');
        $currentLanguage = $this->languageMetaRepository->getFirstBy([
            'reference_id'   => $referenceId,
            'reference_type' => $request->input('reference_type'),
        ]);
        $others = $this->languageMetaRepository->getModel();
        if ($currentLanguage) {
            $others = $others->where('lang_meta_code', '!=', $request->input('lang_meta_current_language'))
                ->where('lang_meta_origin', $currentLanguage->origin);
        }
        $others = $others->select('reference_id', 'lang_meta_code')
            ->get();
        $data = [];
        foreach ($others as $other) {
            $language = $this->languageRepository->getFirstBy(['lang_code' => $other->lang_code], [
                'lang_flag',
                'lang_name',
                'lang_code',
            ]);
            if (!empty($language) && !empty($currentLanguage) && $language->lang_code != $currentLanguage->lang_meta_code) {
                $data[$language->lang_code]['lang_flag'] = $language->lang_flag;
                $data[$language->lang_code]['lang_name'] = $language->lang_name;
                $data[$language->lang_code]['reference_id'] = $other->reference_id;
            }
        }

        $languages = $this->languageRepository->all();
        foreach ($languages as $language) {
            if (!array_key_exists($language->lang_code,
                    $data) && $language->lang_code != $request->input('lang_meta_current_language')) {
                $data[$language->lang_code]['lang_flag'] = $language->lang_flag;
                $data[$language->lang_code]['lang_name'] = $language->lang_name;
                $data[$language->lang_code]['reference_id'] = null;
            }
        }

        return $response->setData($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $language = $this->languageRepository->getFirstBy(['lang_id' => $id]);
            $this->languageRepository->delete($language);
            $deleteDefaultLanguage = false;
            if ($language->lang_is_default) {
                $default = $this->languageRepository->getFirstBy([
                    'lang_is_default' => 0,
                ]);
                if ($default) {
                    $default->lang_is_default = 1;
                    $this->languageRepository->createOrUpdate($default);
                    $deleteDefaultLanguage = $default->lang_id;
                }
            }

            $locale = $language->lang_locale;

            if ($locale !== 'en') {
                $defaultLocale = resource_path('lang/' . $locale);
                if (File::exists($defaultLocale)) {
                    File::deleteDirectory($defaultLocale);
                }

                if (File::exists(resource_path('lang/' . $locale . '.json'))) {
                    File::delete(resource_path('lang/' . $locale . '.json'));
                }

                $this->removeLocaleInPath(resource_path('lang/vendor/core'), $locale);
                $this->removeLocaleInPath(resource_path('lang/vendor/packages'), $locale);
                $this->removeLocaleInPath(resource_path('lang/vendor/plugins'), $locale);

                if (is_plugin_active('translation') && Schema::hasTable('translations')) {
                    DB::table('translations')->where('locale', $locale)->delete();
                }
            }

            event(new DeletedContentEvent(LANGUAGE_MODULE_SCREEN_NAME, $request, $language));

            return $response
                ->setData($deleteDefaultLanguage)
                ->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getSetDefault(Request $request, BaseHttpResponse $response)
    {
        $this->languageRepository->update(['lang_is_default' => 1], ['lang_is_default' => 0]);
        $language = $this->languageRepository->getFirstBy(['lang_id' => $request->input('lang_id')]);
        if ($language) {
            $language->lang_is_default = 1;
            $this->languageRepository->createOrUpdate($language);
        }

        event(new UpdatedContentEvent(LANGUAGE_MODULE_SCREEN_NAME, $request, $language));

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getLanguage(Request $request, BaseHttpResponse $response)
    {
        $language = $this->languageRepository->getFirstBy(['lang_id' => $request->input('lang_id')]);

        return $response->setData($language);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     */
    public function postEditSettings(Request $request, BaseHttpResponse $response, SettingStore $settingStore)
    {
        $settingStore
            ->set('language_hide_default', $request->input('language_hide_default', false))
            ->set('language_display', $request->input('language_display'))
            ->set('language_switcher_display', $request->input('language_switcher_display'))
            ->set('language_hide_languages', json_encode($request->input('language_hide_languages', [])))
            ->set('language_show_default_item_if_current_version_not_existed',
                $request->input('language_show_default_item_if_current_version_not_existed'))
            ->save();

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param string $code
     * @param LanguageManager $language
     * @return RedirectResponse
     * @since 2.2
     */
    public function getChangeDataLanguage($code, LanguageManager $language)
    {
        $previousUrl = strtok(app('url')->previous(), '?');

        $queryString = null;
        if ($code !== $language->getDefaultLocaleCode()) {
            $queryString = '?' . http_build_query(['ref_lang' => $code]);
        }

        return redirect()->to($previousUrl . $queryString);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getThemeTranslations(Request $request)
    {
        page_title()->setTitle(trans('plugins/language::language.theme-translations'));

        Assets::addScriptsDirectly('vendor/core/plugins/language/js/theme-translations.js')
            ->addStylesDirectly('vendor/core/plugins/language/css/theme-translations.css');

        $groups = LanguageFacade::getActiveLanguage(['lang_id', 'lang_name', 'lang_code', 'lang_locale', 'lang_flag'])->toArray();
        $groups = array_filter($groups, function ($item) {
            return $item['lang_locale'] != LanguageFacade::getDefaultLocale();
        });

        if (!$request->has('ref_lang')) {
            $group = Arr::first($groups);
        } else {
            $group = Arr::first(Arr::where($groups, function ($item) use ($request) {
                return $item['lang_locale'] == $request->input('ref_lang');
            }));
        }

        $translations = [];
        if ($group) {
            $jsonFile = resource_path('lang/' . $group['lang_locale'] . '.json');

            if (!File::exists($jsonFile)) {
                $jsonFile = theme_path(setting('theme') . '/lang/' . $group['lang_locale'] . '.json');
            }

            if (!File::exists($jsonFile)) {
                $languages = scan_folder(theme_path(setting('theme') . '/lang'));

                if (!empty($languages)) {
                    $jsonFile = theme_path(setting('theme') . '/lang/' . Arr::first($languages));
                }
            }

            if (File::exists($jsonFile)) {
                $translations = get_file_data($jsonFile, true);
            }
        }

        $defaultLanguage = LanguageFacade::getDefaultLanguage(['lang_name']);

        return view('plugins/language::theme-translations', compact('translations', 'groups', 'group', 'defaultLanguage'));
    }

    /**
     * @param Request $request
     */
    public function postThemeTranslations(Request $request, BaseHttpResponse $response)
    {
        $translations = $request->input('translations', []);

        $json = [];

        foreach ($translations as $translation) {
            $json[$translation['key']] = $translation['value'];
        }

        $jsonFile = resource_path('lang/' . $request->input('locale') . '.json');

        $json = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        save_file_data($jsonFile, $json, false);

        return $response
            ->setPreviousUrl(route('languages.theme-translations'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}

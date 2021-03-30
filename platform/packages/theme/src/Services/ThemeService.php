<?php

namespace Botble\Theme\Services;

use Botble\Base\Supports\Helper;
use Botble\PluginManagement\Services\PluginService;
use Botble\Setting\Repositories\Interfaces\SettingInterface;
use Botble\Setting\Supports\SettingStore;
use Botble\Theme\Events\ThemeRemoveEvent;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class ThemeService
{
    /**
     * @var Filesystem
     */
    public $files;

    /**
     * @var SettingStore
     */
    public $settingStore;

    /**
     * @var PluginService
     */
    public $pluginService;

    /**
     * @var WidgetInterface
     */
    public $widgetRepository;

    /**
     * @var SettingInterface
     */
    public $settingRepository;

    /**
     * ThemeService constructor.
     * @param Filesystem $files
     * @param SettingStore $settingStore
     * @param PluginService $pluginService
     * @param WidgetInterface $widgetRepository
     * @param SettingInterface $settingRepository
     */
    public function __construct(
        Filesystem $files,
        SettingStore $settingStore,
        PluginService $pluginService,
        WidgetInterface $widgetRepository,
        SettingInterface $settingRepository
    ) {
        $this->files = $files;
        $this->settingStore = $settingStore;
        $this->pluginService = $pluginService;
        $this->widgetRepository = $widgetRepository;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @param string $theme
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function activate(string $theme): array
    {
        $validate = $this->validate($theme);

        if ($validate['error']) {
            return $validate;
        }

        if ($theme == setting('theme')) {
            return [
                'error'   => true,
                'message' => 'Theme "' . $theme . '" is activated already!',
            ];
        }

        try {
            $content = get_file_data($this->getPath($theme, 'theme.json'));

            if (!empty($content)) {
                $requiredPlugins = Arr::get($content, 'required_plugins', []);
                if (!empty($requiredPlugins)) {
                    foreach ($requiredPlugins as $plugin) {
                        $this->pluginService->activate($plugin);
                    }
                }
            }
        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }

        $this->settingStore
            ->set('theme', $theme)
            ->save();

        $this->publishAssets($theme);

        Helper::clearCache();

        return [
            'error'   => false,
            'message' => __('Activate theme :name successfully!', ['name' => $theme]),
        ];
    }

    /**
     * @param string $theme
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function validate(string $theme): array
    {
        $location = theme_path($theme);

        if (!$this->files->isDirectory($location)) {
            return [
                'error'   => true,
                'message' => __('This theme is not exists.'),
            ];
        }

        if (!$this->files->exists($location . '/theme.json')) {
            return [
                'error'   => true,
                'message' => __('Missing file theme.json!'),
            ];
        }

        return [
            'error'   => false,
            'message' => __('Theme is valid!'),
        ];
    }

    /**
     * Get root writable path.
     *
     * @param string $theme
     * @param string|null $path
     * @return string
     */
    protected function getPath(string $theme, $path = null)
    {
        return rtrim(theme_path(), '/') . '/' . rtrim(ltrim(strtolower($theme), '/'), '/') . '/' . $path;
    }

    /**
     * @param string|null $theme
     * @return array
     */
    public function publishAssets(string $theme = null): array
    {
        if ($theme) {
            $themes = [$theme];
        } else {
            $themes = scan_folder(theme_path());
        }

        foreach ($themes as $theme) {
            $resourcePath = $this->getPath($theme, 'public');
            $publishPath = public_path('themes/' . $theme);

            if (!$this->files->isDirectory($publishPath)) {
                $this->files->makeDirectory($publishPath, 0755, true);
            }

            $this->files->copyDirectory($resourcePath, $publishPath);
            $this->files->copy($this->getPath($theme, 'screenshot.png'), $publishPath . '/screenshot.png');
        }

        return [
            'error'   => false,
            'message' => __('Publish assets for :themes successfully!', ['themes' => implode(', ', $themes)]),
        ];
    }

    /**
     * @param string $theme
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function remove(string $theme): array
    {
        $validate = $this->validate($theme);

        if ($validate['error']) {
            return $validate;
        }

        if ($this->settingStore->get('theme') == $theme) {
            return [
                'error'   => true,
                'message' => __('Cannot remove activated theme, please activate another theme before removing ":name"!',
                    ['name' => $theme]),
            ];
        }

        $this->removeAssets($theme);

        $this->files->deleteDirectory($this->getPath($theme), false);
        $this->widgetRepository->deleteBy(['theme' => $theme]);
        $this->settingRepository->getModel()
            ->where('key', 'like', 'theme-' . $theme . '-%')
            ->delete();

        event(new ThemeRemoveEvent($theme));

        return [
            'error'   => false,
            'message' => __('Theme ":name" has been destroyed.', ['name' => $theme]),
        ];
    }

    /**
     * @param string $theme
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function removeAssets(string $theme): array
    {
        $validate = $this->validate($theme);

        if ($validate['error']) {
            return $validate;
        }

        $this->files->deleteDirectory(public_path('themes/' . $theme));

        return [
            'error'   => false,
            'message' => __('Remove assets of a theme :name successfully!', ['name' => $theme]),
        ];
    }
}

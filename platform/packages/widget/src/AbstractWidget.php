<?php

namespace Botble\Widget;

use Botble\Widget\Repositories\Interfaces\WidgetInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Language;
use Theme;

abstract class AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'backend';

    /**
     * @var string
     */
    protected $widgetDirectory;

    /**
     * @var bool
     */
    protected $isCore = false;

    /**
     * @var WidgetInterface
     */
    protected $widgetRepository;

    /**
     * @var string
     */
    protected $theme = null;

    /**
     * AbstractWidget constructor.
     * @param array $config
     * @throws FileNotFoundException
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }

        $this->widgetRepository = app(WidgetInterface::class);

        $this->theme = setting('theme') . $this->getCurrentLocaleCode();
    }

    /**
     * @return null|string
     * @throws FileNotFoundException
     */
    protected function getCurrentLocaleCode()
    {
        $language_code = null;
        if (is_plugin_active('language')) {
            $current_locale = is_in_admin() ? Language::getCurrentAdminLocaleCode() : Language::getCurrentLocaleCode();
            $language_code = $current_locale && $current_locale != Language::getDefaultLocaleCode() ? '-' . $current_locale : null;
        }

        return $language_code;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     *
     * @throws FileNotFoundException
     */
    public function run()
    {
        Theme::uses(setting('theme'));
        $args = func_get_args();
        $data = $this->widgetRepository->getFirstBy([
            'widget_id'  => $this->getId(),
            'sidebar_id' => $args[0],
            'position'   => $args[1],
            'theme'      => $this->theme,
        ]);

        if (!empty($data)) {
            $this->config = array_merge($this->config, $data->data);
        }

        if (!$this->isCore) {
            return Theme::loadPartial($this->frontendTemplate,
                Theme::getThemeNamespace('/../widgets/' . $this->widgetDirectory . '/templates'), [
                    'config'  => $this->config,
                    'sidebar' => $args[0],
                ]);
        }

        return view($this->frontendTemplate, [
            'config'  => $this->config,
            'sidebar' => $args[0],
        ]);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return get_class($this);
    }

    /**
     * @param null $sidebarId
     * @param int $position
     * @return Factory|View|mixed
     * @throws FileNotFoundException
     */
    public function form($sidebarId = null, $position = 0)
    {
        Theme::uses(setting('theme'));
        if (!empty($sidebarId)) {
            $data = $this->widgetRepository->getFirstBy([
                'widget_id'  => $this->getId(),
                'sidebar_id' => $sidebarId,
                'position'   => $position,
                'theme'      => $this->theme,
            ]);
            if (!empty($data)) {
                $this->config = array_merge($this->config, $data->data);
            }
        }

        if (!$this->isCore) {
            return Theme::loadPartial($this->backendTemplate,
                Theme::getThemeNamespace('/../widgets/' . $this->widgetDirectory . '/templates'), [
                    'config' => $this->config,
                ]);
        }

        return view($this->backendTemplate, [
            'config' => $this->config,
        ]);
    }
}

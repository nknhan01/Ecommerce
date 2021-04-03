<?php

namespace Platform\Theme\Providers;

use Platform\Dashboard\Supports\DashboardWidgetInstance;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'addStatsWidgets'], 4, 2);

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSetting'], 39, 1);

        theme_option()
            ->setArgs(['debug' => config('app.debug')])
            ->setSection([
                'title'      => __('General'),
                'desc'       => __('General settings'),
                'id'         => 'opt-text-subsection-general',
                'subsection' => true,
                'icon'       => 'fa fa-home',
                'fields'     => [
                    [
                        'id'         => 'site_title',
                        'type'       => 'text',
                        'label'      => trans('core/setting::setting.general.site_title'),
                        'attributes' => [
                            'name'    => 'site_title',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'placeholder'  => trans('core/setting::setting.general.site_title'),
                                'data-counter' => 255,
                            ],
                        ],
                    ],
                    [
                        'id'         => 'show_site_name',
                        'section_id' => 'opt-text-subsection-general',
                        'type'       => 'select',
                        'label'      => trans('core/setting::setting.general.show_site_name'),
                        'attributes' => [
                            'name'    => 'show_site_name',
                            'list'    => [
                                '0' => 'No',
                                '1' => 'Yes',
                            ],
                            'value'   => '0',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id'         => 'seo_title',
                        'type'       => 'text',
                        'label'      => trans('core/setting::setting.general.seo_title'),
                        'attributes' => [
                            'name'    => 'seo_title',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'placeholder'  => trans('core/setting::setting.general.seo_title'),
                                'data-counter' => 120,
                            ],
                        ],
                    ],
                    [
                        'id'         => 'seo_description',
                        'type'       => 'textarea',
                        'label'      => trans('core/setting::setting.general.seo_description'),
                        'attributes' => [
                            'name'    => 'seo_description',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                                'rows'  => 4,
                            ],
                        ],
                    ],
                    [
                        'id'         => 'seo_og_image',
                        'type'       => 'mediaImage',
                        'label'      => __('SEO default Open Graph image'),
                        'attributes' => [
                            'name'    => 'seo_og_image',
                            'value'   => null,
                        ],
                    ],
                ],
            ])
            ->setSection([
                'title'      => __('Logo'),
                'desc'       => __('Logo'),
                'id'         => 'opt-text-subsection-logo',
                'subsection' => true,
                'icon'       => 'fa fa-image',
                'fields'     => [
                    [
                        'id'         => 'favicon',
                        'type'       => 'mediaImage',
                        'label'      => __('Favicon'),
                        'attributes' => [
                            'name'  => 'favicon',
                            'value' => null,
                        ],
                    ],
                    [
                        'id'         => 'logo',
                        'type'       => 'mediaImage',
                        'label'      => __('Logo'),
                        'attributes' => [
                            'name'  => 'logo',
                            'value' => null,
                        ],
                    ],
                ],
            ]);
    }

    /**
     * @param array $widgets
     * @param Collection $widgetSettings
     * @return array
     * @throws Throwable
     */
    public function addStatsWidgets($widgets, $widgetSettings)
    {
        $themes = count(scan_folder(theme_path()));

        return (new DashboardWidgetInstance)
            ->setType('stats')
            ->setPermission('theme.index')
            ->setTitle(trans('packages/theme::theme.theme'))
            ->setKey('widget_total_themes')
            ->setIcon('fa fa-paint-brush')
            ->setColor('#e7505a')
            ->setStatsTotal($themes)
            ->setRoute(route('theme.index'))
            ->init($widgets, $widgetSettings);
    }

    /**
     * @param null $data
     * @return string
     * @throws Throwable
     */
    public function addSetting($data = null)
    {
        return $data . view('packages/theme::setting')->render();
    }
}

<?php

namespace Platform\Theme\Forms;

use Platform\Base\Forms\FormAbstract;
use Platform\Base\Models\BaseModel;
use Platform\Theme\Http\Requests\CustomCssRequest;
use File;
use Theme;

class CustomCSSForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $css = null;
        $file = public_path(config('packages.theme.general.themeDir') . '/' . Theme::getThemeName() . '/css/style.integration.css');
        if (File::exists($file)) {
            $css = get_file_data($file, false);
        }

        $this
            ->setupModel(new BaseModel)
            ->setUrl(route('theme.custom-css.post'))
            ->setValidatorClass(CustomCssRequest::class)
            ->add('custom_css', 'textarea', [
                'label'      => trans('packages/theme::theme.custom_css'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => $css,
                'help_block' => [
                    'text' => __('Using Ctrl + Space to autocomplete.'),
                ],
            ]);
    }
}

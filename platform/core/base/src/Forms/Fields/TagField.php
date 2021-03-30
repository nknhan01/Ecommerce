<?php

namespace Botble\Base\Forms\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\FormField;

class TagField extends FormField
{

    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        Assets::addStylesDirectly('vendor/core/libraries/tagify/tagify.css')
            ->addScriptsDirectly([
                'vendor/core/libraries/tagify/tagify.js',
                'vendor/core/js/tags.js',
            ]);

        return 'core/base::forms.fields.tags';
    }
}

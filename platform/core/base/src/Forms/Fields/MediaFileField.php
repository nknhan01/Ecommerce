<?php

namespace Platform\Base\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class MediaFileField extends FormField
{

    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        return 'core/base::forms.fields.media-file';
    }
}

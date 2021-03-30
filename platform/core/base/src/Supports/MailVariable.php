<?php

namespace Botble\Base\Supports;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;

class MailVariable
{
    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var array
     */
    protected $variableValues = [];

    /**
     * @var string
     */
    protected $module = 'core';

    /**
     * MailVariable constructor.
     */
    public function initVariable()
    {
        $this->variables['core'] = [
            'header'           => __('Email template header'),
            'footer'           => __('Email template footer'),
            'site_title'       => __('Site title'),
            'site_url'         => __('Site URL'),
            'site_logo'        => __('Site Logo'),
            'date_time'        => __('Current date time'),
            'date_year'        => __('Current year'),
            'site_admin_email' => __('Site admin email'),
        ];
    }

    /**
     * @throws FileNotFoundException
     */
    public function initVariableValues()
    {
        $this->variableValues['core'] = [
            'header'           => get_setting_email_template_content('core', 'base', 'header'),
            'footer'           => get_setting_email_template_content('core', 'base', 'footer'),
            'site_title'       => setting('admin_title'),
            'site_url'         => url(''),
            'site_logo'        => setting('admin_logo') ? get_image_url(setting('admin_logo')) : url(config('core.base.general.logo')),
            'date_time'        => now(config('app.timezone'))->toDateTimeString(),
            'date_year'        => now(config('app.timezone'))->format('Y'),
            'site_admin_email' => setting('admin_email'),
        ];
    }

    /**
     * @param string $module
     * @return MailVariable
     */
    public function setModule(string $module): self
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param string $name
     * @param null $description
     * @param string $module
     * @return MailVariable
     */
    public function addVariable(string $name, ?string $description = null): self
    {
        $this->variables[$this->module][$name] = $description;
        return $this;
    }

    /**
     * @param array $variables
     * @param string $module
     * @return MailVariable
     */
    public function addVariables(array $variables): self
    {
        foreach ($variables as $name => $description) {
            $this->variables[$this->module][$name] = $description;
        }

        return $this;
    }

    /**
     * @param string|null $module
     * @return array
     * @throws FileNotFoundException
     */
    public function getVariables(?string $module = null): array
    {
        $this->initVariable();

        if (!$module) {
            return $this->variables;
        }

        return Arr::get($this->variables, $module, []);
    }

    /**
     * @param string $variable
     * @param string $value
     * @param string $module
     * @return MailVariable
     */
    public function setVariableValue(string $variable, string $value): self
    {
        $this->variables[$this->module][$variable] = $value;
        return $this;
    }

    /**
     * @param array $data
     * @param string $module
     * @return MailVariable
     */
    public function setVariableValues(array $data): self
    {
        foreach ($data as $name => $value) {
            $this->variableValues[$this->module][$name] = $value;
        }

        return $this;
    }

    /**
     * @param string $variable
     * @param string $module
     * @param string $default
     * @return string
     */
    public function getVariableValue(string $variable, string $module, string $default = ''): string
    {
        return (string)Arr::get($this->variableValues, $module . '.' . $variable, $default);
    }

    /**
     * @param string|null $module
     * @return array
     */
    public function getVariableValues(?string $module = null)
    {
        if ($module) {
            return Arr::get($this->variableValues, $module, []);
        }

        return $this->variableValues;
    }

    /**
     * @param string $content
     * @param array $variables
     * @return string
     * @throws FileNotFoundException
     */
    public function prepareData(string $content, $variables = []): string
    {
        $this->initVariable();
        $this->initVariableValues();

        if (!empty($content)) {
            $content = $this->replaceVariableValue(array_keys($this->variables['core']), 'core', $content);

            if ($this->module !== 'core') {
                if (empty($variables)) {
                    $variables = Arr::get($this->variables, $this->module, []);
                }
                $content = $this->replaceVariableValue(
                    array_keys($variables),
                    $this->module,
                    $content
                );
            }
        }

        return apply_filters(BASE_FILTER_EMAIL_TEMPLATE, $content);
    }

    /**
     * @param array $variables
     * @param string $module
     * @param string $content
     * @return string
     */
    protected function replaceVariableValue(array $variables, string $module, string $content): string
    {
        foreach ($variables as $variable) {
            $keys = [
                '{{ ' . $variable . ' }}',
                '{{' . $variable . '}}',
                '{{ ' . $variable . '}}',
                '{{' . $variable . ' }}',
                '<?php echo e(' . $variable . '); ?>',
            ];

            foreach ($keys as $key) {
                $content = str_replace($key, $this->getVariableValue($variable, $module), $content);
            }
        }

        return $content;
    }
}

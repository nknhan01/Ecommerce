<?php

use Platform\CustomField\Repositories\Interfaces\CustomFieldInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

if (!function_exists('get_field')) {
    /**
     * @param Eloquent|Model $data
     * @param null $key
     * @param null $default
     * @return string|null
     */
    function get_field($data, $key = null, $default = null)
    {
        if (empty($data)) {
            return $default;
        }

        $customFieldRepository = app(CustomFieldInterface::class);

        if ($key === null || !trim($key)) {
            return $customFieldRepository->getFirstBy([
                'use_for'    => get_class($data),
                'use_for_id' => $data->id,
            ]);
        }

        $field = $customFieldRepository->getFirstBy([
            'use_for'    => get_class($data),
            'use_for_id' => $data->id,
            'slug'       => $key,
        ]);

        if (!$field || !$field->resolved_value) {
            return $default;
        }

        return $field->resolved_value;
    }
}

if (!function_exists('has_field')) {
    /**
     * @param Eloquent|Model $data
     * @param null $key
     * @return bool
     */
    function has_field($data, $key = null)
    {
        return get_field($data, $key);
    }
}

if (!function_exists('get_sub_field')) {
    /**
     * @param array $parentField
     * @param string $key
     * @param null $default
     * @return mixed
     */
    function get_sub_field(array $parentField, $key, $default = null)
    {
        foreach ($parentField as $field) {
            if (Arr::get($field, 'slug') === $key) {
                return Arr::get($field, 'value', $default);
            }
        }

        return $default;
    }
}

if (!function_exists('has_sub_field')) {
    /**
     * @param array $parentField
     * @param string $key
     * @return bool
     */
    function has_sub_field(array $parentField, $key)
    {
        return get_sub_field($parentField, $key);
    }
}

<?php

use Illuminate\Database\Eloquent\Model;

if (!function_exists('add_meta_box')) {
    /**
     * @param string $id
     * @param string $title
     * @param $callback
     * @param null $screen
     * @param string $context
     * @param string $priority
     * @param null $callbackArgs
     */
    function add_meta_box(
        string $id,
        $title,
        $callback,
        $screen = null,
        $context = 'advanced',
        $priority = 'default',
        $callbackArgs = null
    ) {
        MetaBox::addMetaBox($id, $title, $callback, $screen, $context, $priority, $callbackArgs);
    }
}

if (!function_exists('get_meta_data')) {
    /**
     * @param Model $object
     * @param $key
     * @param boolean $single
     * @param array $select
     * @return mixed
     */
    function get_meta_data($object, $key, $single = false, $select = ['meta_value'])
    {
        return MetaBox::getMetaData($object, $key, $single, $select);
    }
}

if (!function_exists('get_meta')) {
    /**
     * @param Model $object
     * @param $key
     * @param array $select
     * @return mixed
     */
    function get_meta($object, $key, $select = ['meta_value'])
    {
        return MetaBox::getMeta($object, $key, $select);
    }
}

if (!function_exists('save_meta_data')) {
    /**
     * @param Model $object
     * @param $key
     * @param $value
     * @param $options
     * @return mixed
     */
    function save_meta_data($object, $key, $value, $options = null)
    {
        return MetaBox::saveMetaBoxData($object, $key, $value, $options);
    }
}

if (!function_exists('delete_meta_data')) {
    /**
     * @param Model $object
     * @param $key
     * @return mixed
     */
    function delete_meta_data($object, $key)
    {
        return MetaBox::deleteMetaData($object, $key);
    }
}

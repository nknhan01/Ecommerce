<?php

use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Gallery\Repositories\Interfaces\GalleryMetaInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

if (!function_exists('gallery_meta_data')) {
    /**
     * @param Model $object
     * @param array $select
     * @return array
     */
    function gallery_meta_data($object, array $select = ['images']): array
    {
        $meta = app(GalleryMetaInterface::class)->getFirstBy(['reference_id' => $object->id, 'reference_type' => get_class($object)], $select);
        if (!empty($meta)) {
            return $meta->images ?? [];
        }
        return [];
    }
}

if (!function_exists('get_galleries')) {
    /**
     * @param int $limit
     * @return Collection
     */
    function get_galleries(int $limit = 8): Collection
    {
        return app(GalleryInterface::class)->getFeaturedGalleries($limit);
    }
}

if (!function_exists('render_galleries')) {
    /**
     * @param int $limit
     * @return string
     */
    function render_galleries(int $limit): string
    {
        Gallery::registerAssets();
        return view('plugins/gallery::gallery', compact('limit'));
    }
}

if (!function_exists('get_list_galleries')) {
    /**
     * @param array $condition
     * @return Collection
     */
    function get_list_galleries(array $condition): Collection
    {
        return app(GalleryInterface::class)->allBy($condition);
    }
}

if (!function_exists('render_object_gallery')) {
    /**
     * @param array $galleries
     * @param string $category
     * @return string
     *
     * @throws Throwable
     */
    function render_object_gallery(array $galleries, $category = null): string
    {
        Theme::asset()->container('footer')->add('owl.carousel', 'vendor/core/plugins/gallery/libraries/owl-carousel/owl.carousel.css');
        Theme::asset()->container('footer')->add('object-gallery-css', 'vendor/core/plugins/gallery/css/object-gallery.css');
        Theme::asset()->container('footer')->add('carousel', 'vendor/core/plugins/gallery/libraries/owl-carousel/owl.carousel.js', ['jquery']);
        Theme::asset()->container('footer')->add('object-gallery-js', 'vendor/core/plugins/gallery/js/object-gallery.js', ['jquery']);
        return view('plugins/gallery::partials.object-gallery', compact('galleries', 'category'))->render();
    }
}

<?php

namespace Platform\Gallery\Services;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Gallery\Models\Gallery as GalleryModel;
use Platform\Gallery\Repositories\Interfaces\GalleryInterface;
use Platform\SeoHelper\SeoOpenGraph;
use Eloquent;
use Gallery;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use RvMedia;
use SeoHelper;
use Theme;

class GalleryService
{
    /**
     * @param Eloquent $slug
     * @return array|Eloquent
     */
    public function handleFrontRoutes($slug)
    {
        if (!$slug instanceof Eloquent) {
            return $slug;
        }

        $condition = [
            'id'     => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check() && request()->input('preview')) {
            Arr::forget($condition, 'status');
        }

        if ($slug->reference_type !== GalleryModel::class) {
            return $slug;
        }

        $gallery = app(GalleryInterface::class)->getFirstBy($condition, ['*']);

        if (!$gallery) {
            abort(404);
        }

        $gallery->slugable = $slug;

        SeoHelper::setTitle($gallery->name)
            ->setDescription($gallery->description);

        $meta = new SeoOpenGraph;
        $meta->setDescription($gallery->description);
        $meta->setUrl($gallery->url);
        $meta->setTitle($gallery->name);
        $meta->setType('article');

        if ($gallery->image) {
            $meta->setImage(RvMedia::getImageUrl($gallery->image));
        }

        Gallery::registerAssets();

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add($gallery->name, $gallery->url);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, GALLERY_MODULE_SCREEN_NAME, $gallery);

        return [
            'view'         => 'gallery',
            'default_view' => 'plugins/gallery::themes.gallery',
            'data'         => compact('gallery'),
            'slug'         => $gallery->slug,
        ];
    }
}

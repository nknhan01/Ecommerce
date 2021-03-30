<?php

use Botble\Blog\Models\Post;
use Botble\Gallery\Models\GalleryMeta;
use Illuminate\Database\Migrations\Migration;

class UpdateMediaUrlForCurrentData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (defined('POST_MODULE_SCREEN_NAME')) {
            $this->fixUrl(Post::get());
        }

        if (defined('GALLERY_MODULE_SCREEN_NAME')) {
            $this->fixUrl(\Botble\Gallery\Models\Gallery::get());
            $galleryMeta = GalleryMeta::get();

            foreach ($galleryMeta as $meta) {
                if (!$meta->images) {
                    continue;
                }
                $images = [];
                foreach ($meta->images as $image) {
                    $image['img'] = str_replace('/storage/', '', $image['img']);
                    $images[] = $image;
                }
                $meta->images = json_encode($images);
                $meta->save();
            }
        }
    }

    /**
     * @param $data
     */
    protected function fixUrl($data)
    {
        foreach ($data as $item) {
            $url = ltrim($item->image);
            $url = str_replace('storage/', '', $url);
            $url = ltrim($url);
            $item->image = $url;
            $item->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

namespace Botble\SeoHelper\Contracts\Entities;

use Botble\SeoHelper\Contracts\RenderableContract;

interface TwitterCardContract extends RenderableContract
{
    const TYPE_APP = 'app';
    const TYPE_GALLERY = 'gallery';
    const TYPE_PHOTO = 'photo';
    const TYPE_PLAYER = 'player';
    const TYPE_PRODUCT = 'product';
    const TYPE_SUMMARY = 'summary';
    const TYPE_SUMMARY_LARGE_IMAGE = 'summary_large_image';

    /**
     * Set the card type.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type);

    /**
     * Set card site.
     *
     * @param string $site
     *
     * @return self
     */
    public function setSite($site);

    /**
     * Set card title.
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * Set card description.
     *
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description);

    /**
     * Add image to the card.
     *
     * @param string $url
     *
     * @return self
     */
    public function addImage($url);

    /**
     * Add many meta to the card.
     *
     * @param array $meta
     *
     * @return self
     */
    public function addMetas(array $meta);

    /**
     * Add a meta to the card.
     *
     * @param string $name
     * @param string $content
     *
     * @return self
     */
    public function addMeta($name, $content);

    /**
     * Get all supported card types.
     *
     * @return array
     */
    public function types();

    /**
     * Reset the card.
     *
     * @return self
     */
    public function reset();
}

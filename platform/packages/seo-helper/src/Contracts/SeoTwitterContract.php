<?php

namespace Botble\SeoHelper\Contracts;

use Botble\SeoHelper\Contracts\Entities\TwitterCardContract;

interface SeoTwitterContract extends RenderableContract
{
    /**
     * Set the twitter card instance.
     *
     * @param TwitterCardContract $card
     *
     * @return self
     */
    public function setCard(TwitterCardContract $card);

    /**
     * Set the card type.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type);

    /**
     * Set the card site.
     *
     * @param string $site
     *
     * @return self
     */
    public function setSite($site);

    /**
     * Set the card title.
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * Set the card description.
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
     * Add a meta to the twitter card.
     *
     * @param string $name
     * @param string $content
     *
     * @return self
     */
    public function addMeta($name, $content);
}

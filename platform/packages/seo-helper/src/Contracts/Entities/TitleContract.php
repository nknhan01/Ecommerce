<?php

namespace Botble\SeoHelper\Contracts\Entities;

use Botble\SeoHelper\Contracts\RenderableContract;

interface TitleContract extends RenderableContract
{
    /**
     * Get title only (without site name or separator).
     *
     * @return string
     */
    public function getTitleOnly();

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return self
     */
    public function set($title);

    /**
     * Get site name.
     *
     * @return string
     */
    public function getSiteName();

    /**
     * Set site name.
     *
     * @param string $siteName
     *
     * @return self
     */
    public function setSiteName($siteName);

    /**
     * Get title separator.
     *
     * @return string
     */
    public function getSeparator();

    /**
     * Set title separator.
     *
     * @param string $separator
     *
     * @return self
     */
    public function setSeparator($separator);

    /**
     * Set title first.
     *
     * @return self
     */
    public function setFirst();

    /**
     * Set title last.
     *
     * @return self
     */
    public function setLast();

    /**
     * Check if title is first.
     *
     * @return bool
     */
    public function isTitleFirst();

    /**
     * Get title max lenght.
     *
     * @return int
     */
    public function getMax();

    /**
     * Set title max lenght.
     *
     * @param int $max
     *
     * @return self
     */
    public function setMax($max);

    /**
     * Make a Title instance.
     *
     * @param string $title
     * @param string $siteName
     * @param string $separator
     *
     * @return self
     */
    public static function make($title, $siteName = '', $separator = '-');
}

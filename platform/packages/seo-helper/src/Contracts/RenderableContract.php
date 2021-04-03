<?php

namespace Platform\SeoHelper\Contracts;

interface RenderableContract
{
    /**
     * Render the tag.
     *
     * @return string
     */
    public function render();

    /**
     * Render the tag.
     *
     * @return string
     */
    public function __toString();
}

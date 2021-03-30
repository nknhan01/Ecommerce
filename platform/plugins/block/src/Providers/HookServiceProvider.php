<?php

namespace Botble\Block\Providers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Block\Repositories\Interfaces\BlockInterface;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (function_exists('shortcode')) {
            add_shortcode('static-block', __('Static Block'), __('Add a custom static block'), [$this, 'render']);
        }
    }

    /**
     * @param \stdClass $shortcode
     * @return null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function render($shortcode)
    {
        $block = $this->app->make(BlockInterface::class)
            ->getFirstBy([
                'alias'  => $shortcode->alias,
                'status' => BaseStatusEnum::PUBLISHED,
            ]);

        if (empty($block)) {
            return null;
        }

        return $block->content;
    }
}

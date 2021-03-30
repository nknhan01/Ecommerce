<?php

namespace Botble\Slug;

use Botble\Base\Models\BaseModel;
use Illuminate\Support\Arr;

class SlugHelper
{
    /**
     * @param string|array $model
     * @return $this
     */
    public function registerModule($model): self
    {
        if (!is_array($model)) {
            $model = [$model];
        }
        config([
            'packages.slug.general.supported' => array_merge(config('packages.slug.general.supported', []), $model),
        ]);

        return $this;
    }

    /**
     * @param string $model
     * @param string|null $prefix
     * @return $this
     */
    public function setPrefix(string $model, ?string $prefix): self
    {
        $prefixes = config('packages.slug.general.prefixes', []);
        $prefixes[$model] = $prefix;

        config(['packages.slug.general.prefixes' => $prefixes]);

        return $this;
    }

    /**
     * @param string $model
     * @return string|null
     */
    public function getPrefix(string $model): ?string
    {
        return Arr::get(config('packages.slug.general.prefixes', []), $model, '');
    }

    /**
     * @return bool
     */
    public function isSupportedModel(string $model): bool
    {
        return in_array($model, $this->supportedModels());
    }

    /**
     * @return array
     */
    public function supportedModels(): array
    {
        return config('packages.slug.general.supported', []);
    }

    /**
     * @param BaseModel $model
     * @return $this
     */
    public function disablePreview($model): self
    {
        if (!is_array($model)) {
            $model = [$model];
        }
        config([
            'packages.slug.general.disable_preview' => array_merge(config('packages.slug.general.disable_preview', []),
                $model),
        ]);

        return $this;
    }

    /**
     * @param string $model
     * @return bool
     */
    public function canPreview(string $model): bool
    {
        return !in_array($model, config('packages.slug.general.disable_preview', []));
    }
}

<?php

namespace Botble\Slug\Services;

use Botble\Base\Models\BaseModel;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use Illuminate\Support\Str;
use SlugHelper;

class SlugService
{
    /**
     * @var SlugInterface
     */
    protected $slugRepository;

    /**
     * SlugService constructor.
     * @param SlugInterface $slugRepository
     */
    public function __construct(SlugInterface $slugRepository)
    {
        $this->slugRepository = $slugRepository;
    }

    /**
     * @param string $name
     * @param int $slugId
     * @return int|string
     */
    public function create($name, $slugId = 0, $model = null)
    {
        $slug = Str::slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->checkIfExistedSlug($slug, $slugId, $model)) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        return $slug;
    }

    /**
     * @param string $slug
     * @param string $slugId
     * @param BaseModel $model
     * @return bool
     */
    protected function checkIfExistedSlug($slug, $slugId, $model)
    {
        $prefix = null;
        if (!empty($model)) {
            $prefix = SlugHelper::getPrefix($model);
        }
        $count = $this->slugRepository
            ->getModel()
            ->where([
                'key'    => $slug,
                'prefix' => $prefix,
            ])
            ->where('id', '!=', $slugId)
            ->count();

        return $count > 0;
    }
}

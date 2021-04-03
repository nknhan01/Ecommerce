<?php

namespace Platform\Menu;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Models\BaseModel;
use Platform\Menu\Repositories\Eloquent\MenuRepository;
use Platform\Menu\Repositories\Interfaces\MenuInterface;
use Platform\Menu\Repositories\Interfaces\MenuLocationInterface;
use Platform\Menu\Repositories\Interfaces\MenuNodeInterface;
use Platform\Support\Services\Cache\Cache;
use Collective\Html\HtmlBuilder;
use Exception;
use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Theme;
use Throwable;

class Menu
{
    /**
     * @var MenuInterface
     */
    protected $menuRepository;

    /**
     * @var HtmlBuilder
     */
    protected $html;

    /**
     * @var MenuNodeInterface
     */
    protected $menuNodeRepository;

    /**
     * @var MenuLocationInterface
     */
    protected $menuLocationRepository;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * Menu constructor.
     * @param MenuInterface $menu
     * @param HtmlBuilder $html
     * @param MenuNodeInterface $menuNodeRepository
     * @param MenuLocationInterface $menuLocationRepository
     * @param CacheManager $cache
     * @param Repository $config
     */
    public function __construct(
        MenuInterface $menu,
        HtmlBuilder $html,
        MenuNodeInterface $menuNodeRepository,
        MenuLocationInterface $menuLocationRepository,
        CacheManager $cache,
        Repository $config
    ) {
        $this->config = $config;
        $this->menuRepository = $menu;
        $this->html = $html;
        $this->menuNodeRepository = $menuNodeRepository;
        $this->menuLocationRepository = $menuLocationRepository;
        $this->cache = new Cache($cache, MenuRepository::class);
    }

    /**
     * @param array $args
     * @return mixed|null|string
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function generateSelect(array $args = [])
    {
        $model = Arr::get($args, 'model');
        if (!$model) {
            return null;
        }

        $view = Arr::get($args, 'view');
        $theme = Arr::get($args, 'theme', true);
        $type = Arr::get($args, 'type');

        $cacheKey = md5('cache-menu-' . serialize($args));
        if (!$this->cache->has($cacheKey) || true) {
            $parentId = Arr::get($args, 'parent_id', 0);
            $active = Arr::get($args, 'active', true);
            $options = $this->html->attributes(Arr::get($args, 'options', []));

            if (!Arr::has($args, 'items')) {
                if (method_exists($model, 'children')) {
                    $items = $model->where('parent_id', $parentId)->with('children')->orderBy('name', 'asc');
                } else {
                    $items = $model->orderBy('name', 'asc');
                }
                if ($active) {
                    $items = $items->where('status', BaseStatusEnum::PUBLISHED);
                }
                $items = apply_filters(BASE_FILTER_BEFORE_GET_ADMIN_LIST_ITEM, $items, $model, $type)->get();
            } else {
                $items = Arr::get($args, 'items', []);
            }

            if (empty($items)) {
                return null;
            }

            $data = compact('items', 'model', 'options', 'type');

            $this->cache->put($cacheKey, $data);
        } else {
            $data = $this->cache->get($cacheKey);
        }

        if (!Arr::get($data, 'items') || ($data['items'] instanceof Collection && $data['items']->isEmpty())) {
            return null;
        }

        if ($theme && $view) {
            return Theme::partial($view, $data);
        }

        if ($view) {
            return view($view, $data)->render();
        }

        return view('packages/menu::partials.select', $data)->render();
    }

    /**
     * @param string $slug
     * @param bool $active
     * @return bool
     */
    public function hasMenu($slug, $active)
    {
        return $this->menuRepository->findBySlug($slug, $active);
    }

    /**
     * @param array $menuNodes
     * @param int $menuId
     * @param int $parentId
     */
    public function recursiveSaveMenu($menuNodes, $menuId, $parentId)
    {
        try {
            foreach ($menuNodes as $row) {
                $child = Arr::get($row, 'children', []);
                $hasChild = 0;
                if (!empty($child)) {
                    $hasChild = 1;
                }
                $parent = $this->saveMenuNode($row, $menuId, $parentId, $hasChild);
                if (!empty($parent)) {
                    $this->recursiveSaveMenu($child, $menuId, $parent);
                }
            }
        } catch (Exception $ex) {
            info($ex->getMessage());
        }
    }

    /**
     * @param array $menuItem
     * @param int $menuId
     * @param int $parentId
     * @param int $hasChild
     * @return int
     */
    protected function saveMenuNode($menuItem, $menuId, $parentId, $hasChild = 0)
    {
        $item = $this->menuNodeRepository->findById(Arr::get($menuItem, 'id'));

        if (!$item) {
            $item = $this->menuNodeRepository->getModel();
        }

        $item->title = Arr::get($menuItem, 'title');
        $item->css_class = Arr::get($menuItem, 'class');
        $item->position = Arr::get($menuItem, 'position');
        $item->icon_font = Arr::get($menuItem, 'iconFont');
        $item->target = Arr::get($menuItem, 'target');
        $item->menu_id = $menuId;
        $item->parent_id = $parentId;
        $item->has_child = $hasChild;

        switch (Arr::get($menuItem, 'referenceType')) {
            case 'custom-link':
            case '':
                $item->reference_id = 0;
                $item->reference_type = null;
                $item->url = Arr::get($menuItem, 'customUrl');
                break;
            default:
                $item->reference_id = (int)Arr::get($menuItem, 'referenceId');
                $item->reference_type = Arr::get($menuItem, 'referenceType');
                break;
        }

        $this->menuNodeRepository->createOrUpdate($item);

        return $item->id;
    }

    /**
     * @param string $location
     * @param string $description
     * @return $this
     */
    public function addMenuLocation(string $location, string $description): self
    {
        $locations = $this->getMenuLocations();
        $locations[$location] = $description;

        config()->set('packages.menu.general.locations', $locations);

        return $this;
    }

    /**
     * @return array
     */
    public function getMenuLocations(): array
    {
        return $this->config->get('packages.menu.general.locations', []);
    }

    /**
     * @param string $location
     * @return $this
     */
    public function removeMenuLocation(string $location): self
    {
        $locations = $this->getMenuLocations();
        Arr::forget($locations, $location);

        config()->set('packages.menu.general.locations', $locations);

        return $this;
    }

    /**
     * @param string $location
     * @param array $attributes
     * @return string
     * @throws Throwable
     */
    public function renderMenuLocation(string $location, array $attributes = []): string
    {
        $html = '';

        $locations = $this->menuLocationRepository->allBy(compact('location'));

        foreach ($locations as $location) {
            $attributes['slug'] = $location->menu->slug;
            $html .= $this->generateMenu($attributes);
        }

        return $html;
    }

    /**
     * @param array $args
     * @return mixed|null|string
     * @throws Throwable
     */
    public function generateMenu(array $args = [])
    {
        $view = Arr::get($args, 'view');
        $theme = Arr::get($args, 'theme', true);

        $menu = Arr::get($args, 'menu');

        $slug = Arr::get($args, 'slug');
        if (!$menu && !$slug) {
            return null;
        }

        $cacheKey = md5('cache-menu-' . serialize($args));
        if (!$this->cache->has($cacheKey) || $this->config->get('packages.menu.general.cache.enabled') == false) {
            $parentId = Arr::get($args, 'parent_id', 0);
            $active = Arr::get($args, 'active', true);

            if (!$menu) {
                $menu = $this->menuRepository->findBySlug($slug, $active, ['menus.id', 'menus.slug']);
            }

            if (!$menu) {
                return null;
            }

            if (!Arr::has($args, 'menu_nodes')) {
                $menuNodes = $this->menuNodeRepository->getByMenuId($menu->id, $parentId, [
                    'menu_nodes.id',
                    'menu_nodes.menu_id',
                    'menu_nodes.parent_id',
                    'menu_nodes.reference_id',
                    'menu_nodes.reference_type',
                    'menu_nodes.icon_font',
                    'menu_nodes.css_class',
                    'menu_nodes.target',
                    'menu_nodes.url',
                    'menu_nodes.title',
                    'menu_nodes.has_child',
                ]);
            } else {
                $menuNodes = Arr::get($args, 'menu_nodes', []);
            }

            $data = [
                'menu'       => $menu,
                'menu_nodes' => $menuNodes,
            ];

            $this->cache->put($cacheKey, $data);
            $this->nodes[$slug] = $data;
        } else {
            $data = $this->cache->get($cacheKey);
        }

        $data['options'] = $this->html->attributes(Arr::get($args, 'options', []));

        if ($theme && $view) {
            return Theme::partial($view, $data);
        }

        if ($view) {
            return view($view, $data)->render();
        }

        return view('packages/menu::partials.default', $data)->render();
    }

    /**
     * @param string $model
     * @param string $name
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function registerMenuOptions(string $model, string $name)
    {
        $options = Menu::generateSelect([
            'model'   => new $model,
            'type'    => $model,
            'theme'   => false,
            'options' => [
                'class' => 'list-item',
            ],
        ]);

        echo view('packages/menu::menu-options', compact('options', 'name'));
    }
}

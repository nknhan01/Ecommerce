<?php

namespace Platform\Menu\Http\Controllers;

use Platform\Base\Events\BeforeEditContentEvent;
use Platform\Base\Events\CreatedContentEvent;
use Platform\Base\Events\DeletedContentEvent;
use Platform\Base\Events\UpdatedContentEvent;
use Platform\Base\Forms\FormBuilder;
use Platform\Base\Http\Controllers\BaseController;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Platform\Menu\Forms\MenuForm;
use Platform\Menu\Http\Requests\MenuRequest;
use Platform\Menu\Repositories\Eloquent\MenuRepository;
use Platform\Menu\Repositories\Interfaces\MenuInterface;
use Platform\Menu\Repositories\Interfaces\MenuLocationInterface;
use Platform\Menu\Repositories\Interfaces\MenuNodeInterface;
use Platform\Menu\Tables\MenuTable;
use Platform\Support\Services\Cache\Cache;
use Exception;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Menu;
use stdClass;
use Throwable;

class MenuController extends BaseController
{

    /**
     * @var MenuInterface
     */
    protected $menuRepository;

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
     * MenuController constructor.
     * @param MenuInterface $menuRepository
     * @param MenuNodeInterface $menuNodeRepository
     * @param MenuLocationInterface $menuLocationRepository
     * @param CacheManager $cache
     */
    public function __construct(
        MenuInterface $menuRepository,
        MenuNodeInterface $menuNodeRepository,
        MenuLocationInterface $menuLocationRepository,
        CacheManager $cache
    ) {
        $this->menuRepository = $menuRepository;
        $this->menuNodeRepository = $menuNodeRepository;
        $this->menuLocationRepository = $menuLocationRepository;
        $this->cache = new Cache($cache, MenuRepository::class);
    }

    /**
     * @param MenuTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(MenuTable $dataTable)
    {
        page_title()->setTitle(trans('packages/menu::menu.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('packages/menu::menu.create'));

        return $formBuilder->create(MenuForm::class)->renderForm();
    }

    /**
     * @param MenuRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function store(MenuRequest $request, BaseHttpResponse $response)
    {
        $menu = $this->menuRepository->getModel();

        $menu->fill($request->input());
        $menu->slug = $this->menuRepository->createSlug($request->input('name'));
        $menu = $this->menuRepository->createOrUpdate($menu);

        $this->cache->flush();

        event(new CreatedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

        $this->saveMenuLocations($menu, $request);

        return $response
            ->setPreviousUrl(route('menus.index'))
            ->setNextUrl(route('menus.edit', $menu->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param Model $menu
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    protected function saveMenuLocations($menu, Request $request)
    {
        $locations = $request->input('locations', []);

        $this->menuLocationRepository->deleteBy([
            'menu_id' => $menu->id,
            ['location', 'NOT_IN', $locations],
        ]);

        foreach ($locations as $location) {
            $menuLocation = $this->menuLocationRepository->firstOrCreate([
                'menu_id'  => $menu->id,
                'location' => $location,
            ]);

            event(new CreatedContentEvent(MENU_LOCATION_MODULE_SCREEN_NAME, $request, $menuLocation));
        }

        return true;
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        page_title()->setTitle(trans('packages/menu::menu.edit'));

        $oldInputs = old();
        if ($oldInputs && $id == 0) {
            $oldObject = new stdClass;
            foreach ($oldInputs as $key => $row) {
                $oldObject->$key = $row;
            }
            $menu = $oldObject;
        } else {
            $menu = $this->menuRepository->findOrFail($id);
        }

        event(new BeforeEditContentEvent($request, $menu));

        return $formBuilder->create(MenuForm::class, ['model' => $menu])->renderForm();
    }

    /**
     * @param MenuRequest $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function update(MenuRequest $request, $id, BaseHttpResponse $response)
    {
        $menu = $this->menuRepository->firstOrNew(compact('id'));

        $menu->fill($request->input());
        $this->menuRepository->createOrUpdate($menu);
        event(new UpdatedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

        $this->saveMenuLocations($menu, $request);

        $deletedNodes = ltrim($request->input('deleted_nodes', ''));
        if ($deletedNodes) {
            $deletedNodes = explode(' ', ltrim($request->input('deleted_nodes', '')));
            $this->menuNodeRepository->deleteBy([['id', 'IN', $deletedNodes]]);
        }
        Menu::recursiveSaveMenu(json_decode($request->input('menu_nodes'), true), $menu->id, 0);

        $this->cache->flush();

        return $response
            ->setPreviousUrl(route('menus.index'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $menu = $this->menuRepository->findOrFail($id);
            $this->menuNodeRepository->deleteBy(['menu_id' => $menu->id]);
            $this->menuRepository->delete($menu);

            event(new DeletedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $menu = $this->menuRepository->findOrFail($id);
            $this->menuNodeRepository->deleteBy(['menu_id' => $menu->id]);
            $this->menuRepository->delete($menu);
            event(new DeletedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

<?php

namespace Botble\Base\Http\Controllers;

use Assets;
use Botble\ACL\Models\UserMeta;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Helper;
use Botble\Base\Supports\MembershipAuthorization;
use Botble\Base\Supports\SystemManagement;
use Botble\Base\Tables\InfoTable;
use Botble\Table\TableBuilder;
use Exception;
use File;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class SystemController extends Controller
{

    /**
     * @return Factory|View
     * @throws Throwable
     */
    public function getInfo(Request $request, TableBuilder $tableBuilder)
    {
        page_title()->setTitle(trans('core/base::system.info.title'));

        Assets::addScriptsDirectly('vendor/core/js/system-info.js')
            ->addStylesDirectly(['vendor/core/css/system-info.css']);

        $composerArray = SystemManagement::getComposerArray();
        $packages = SystemManagement::getPackagesAndDependencies($composerArray['require']);

        $infoTable = $tableBuilder->create(InfoTable::class);

        if ($request->expectsJson()) {
            return $infoTable->renderTable();
        }

        $systemEnv = SystemManagement::getSystemEnv();
        $serverEnv = SystemManagement::getServerEnv();

        return view('core/base::system.info', compact(
            'packages',
            'infoTable',
            'systemEnv',
            'serverEnv'
        ));
    }

    /**
     * @return Factory|View
     */
    public function getCacheManagement()
    {
        page_title()->setTitle(trans('core/base::cache.cache_management'));

        Assets::addScriptsDirectly('vendor/core/js/cache.js');

        return view('core/base::system.cache');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param Filesystem $files
     * @param Application $app
     * @return BaseHttpResponse
     */
    public function postClearCache(Request $request, BaseHttpResponse $response, Filesystem $files, Application $app)
    {
        switch ($request->input('type')) {
            case 'clear_cms_cache':
                Helper::clearCache();
                break;
            case 'refresh_compiled_views':
                foreach ($files->glob(config('view.compiled') . '/*') as $view) {
                    $files->delete($view);
                }
                break;
            case 'clear_config_cache':
                $files->delete($app->getCachedConfigPath());
                break;
            case 'clear_route_cache':
                $files->delete($app->getCachedRoutesPath());
                break;
            case 'clear_log':
                foreach (File::allFiles(storage_path('logs')) as $file) {
                    File::delete($file->getPathname());
                }
                break;
        }

        return $response->setMessage(trans('core/base::cache.commands.' . $request->input('type') . '.success_msg'));
    }

    /**
     * @param MembershipAuthorization $authorization
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function authorize(MembershipAuthorization $authorization, BaseHttpResponse $response)
    {
        $authorization->authorize();

        return $response;
    }

    /**
     * @param string $lang
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function getLanguage($lang, Request $request)
    {
        if ($lang != false && array_key_exists($lang, Assets::getAdminLocales())) {
            if (Auth::check()) {
                UserMeta::setMeta('site-locale', $lang);
                cache()->forget(md5('cache-dashboard-menu-' . $request->user()->getKey()));
            }
            session()->put('site-locale', $lang);
        }

        return redirect()->back();
    }
}

<?php

namespace Platform\Theme\Http\Controllers;

use BaseHelper;
use Platform\Page\Models\Page;
use Platform\Page\Services\PageService;
use Platform\Theme\Events\RenderingSingleEvent;
use Platform\Theme\Events\RenderingHomePageEvent;
use Platform\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Platform\Blog\Models\Post;
use Platform\Blog\Repositories\Interfaces\PostInterface;
use Platform\Slug\Repositories\Interfaces\SlugInterface;
use Illuminate\Http\Request;
use Platform\Blog\Models\Category;
use Platform\Blog\Repositories\Interfaces\CategoryInterface;
use Platform\Theme\Http\Requests\LoginRequest;
use Response;
use SeoHelper;
use SiteMapManager;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    /**
     * @return \Illuminate\Http\Response|Response
     */
    public function getIndex()
    {
        if (defined('PAGE_MODULE_SCREEN_NAME')) {
            $homepageId = BaseHelper::getHomepageId();
            if ($homepageId) {
                $slug = SlugHelper::getSlug(null, SlugHelper::getPrefix(Page::class), Page::class, $homepageId);

                if ($slug) {
                    $data = (new PageService)->handleFrontRoutes($slug);
                    return Theme::scope($data['view'], $data['data'], $data['default_view'])->render();
                }
            }
        }

        SeoHelper::setTitle(theme_option('site_title'));

        Theme::breadcrumb()->add(__('Home'), url('/'));

        event(RenderingHomePageEvent::class);
        return Theme::scope('index')->render();
    }

    /**
     * @param string $key
     * @return \Illuminate\Http\RedirectResponse|Response
     * @throws FileNotFoundException
     */
    public function getView($key = null)
    {
        if (empty($key)) {
            return $this->getIndex();
        }

        $slug = SlugHelper::getSlug($key, '');

        if (!$slug) {
            abort(404);
        }

        if (defined('PAGE_MODULE_SCREEN_NAME')) {
            if ($slug->reference_type == Page::class && BaseHelper::isHomepage($slug->reference_id)) {
                return redirect()->to('/');
            }
        }

        $result = apply_filters(BASE_FILTER_PUBLIC_SINGLE_DATA, $slug);

        if (isset($result['slug']) && $result['slug'] !== $key) {
            return redirect()->route('public.single', $result['slug']);
        }

        event(new RenderingSingleEvent($slug));

        if (!empty($result) && is_array($result)) {
            // dd($result['data']);
            return Theme::scope($result['view'], $result['data'], Arr::get($result, 'default_view'),)->render();
        }

        abort(404);
    }
    public function detailPost($slugCategory, $slugPost, CategoryInterface $categoryInterface, SlugInterface $slugRepository, PostInterface $postInterface, Request $request)
    {
        //get Slug category
        $slugCategory = $slugRepository->getFirstBy(['key' => $slugCategory, 'reference_type' => Category::class]);
        $slugPost = $slugRepository->getFirstBy(['key' => $slugPost, 'reference_type' => Post::class]);
        $data['post'] = $postInterface->getFirstBy(['id' => $slugPost->reference_id]);
        return Theme::scope('post', $data)->render();
    }
    /**
     * @return string
     */
    public function getSiteMap()
    {
        event(RenderingSiteMapEvent::class);

        // show your site map (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return SiteMapManager::render('xml');
    }

    public function loginGuest(LoginRequest $request)
    {
        $auth = auth()->attempt(['email' => $request->email, 'password' => $request->password]);
        if ($auth) {
            return redirect()->route('dashboard.index');
        }
        return back()->withErrors([
            'error' => 'Sai thông tin đăng nhập! Vui lòng thử lại'
        ]);
    }
}

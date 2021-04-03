<?php

namespace Platform\Gallery\Http\Controllers;

use Platform\Gallery\Models\Gallery as GalleryModel;
use Platform\Gallery\Repositories\Interfaces\GalleryInterface;
use Platform\Gallery\Services\GalleryService;
use Platform\Slug\Repositories\Interfaces\SlugInterface;
use Gallery;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SeoHelper;
use SlugHelper;
use Theme;

class PublicController extends Controller
{

    /**
     * @var GalleryInterface
     */
    protected $galleryRepository;

    /**
     * @var SlugInterface
     */
    protected $slugRepository;

    /**
     * PublicController constructor.
     * @param GalleryInterface $galleryRepository
     * @param SlugInterface $slugRepository
     */
    public function __construct(GalleryInterface $galleryRepository, SlugInterface $slugRepository)
    {
        $this->galleryRepository = $galleryRepository;
        $this->slugRepository = $slugRepository;
    }

    /**
     * @return \Response
     */
    public function getGalleries()
    {
        Gallery::registerAssets();
        $galleries = $this->galleryRepository->getAll();

        SeoHelper::setTitle(__('Galleries'));

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add(__('Galleries'), route('public.galleries'));

        return Theme::scope('galleries', compact('galleries'), 'plugins/gallery::themes.galleries')
            ->render();
    }

    /**
     * @param string $slug
     * @param Request $request
     * @return \Response
     */
    public function getGallery($slug, GalleryService $galleryService)
    {
        $slug = $this->slugRepository->getFirstBy([
            'key'    => $slug,
            'prefix' => SlugHelper::getPrefix(GalleryModel::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $data = $galleryService->handleFrontRoutes($slug);

        return Theme::scope($data['view'], $data['data'], $data['default_view'])
            ->render();
    }
}

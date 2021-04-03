<?php

namespace Platform\Member\Http\Controllers;

use Assets;
use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Events\BeforeEditContentEvent;
use Platform\Base\Events\CreatedContentEvent;
use Platform\Base\Events\UpdatedContentEvent;
use Platform\Base\Forms\FormBuilder;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Platform\Blog\Models\Post;
use Platform\Blog\Repositories\Interfaces\PostInterface;
use Platform\Blog\Repositories\Interfaces\TagInterface;
use Platform\Blog\Services\StoreCategoryService;
use Platform\Blog\Services\StoreTagService;
use Platform\Member\Forms\PostForm;
use Platform\Member\Http\Requests\PostRequest;
use Platform\Member\Models\Member;
use Platform\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Platform\Member\Repositories\Interfaces\MemberInterface;
use Platform\Member\Tables\PostTable;
use EmailHandler;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RvMedia;
use SeoHelper;

class PostController extends Controller
{
    /**
     * @var MemberInterface
     */
    protected $memberRepository;

    /**
     * @var PostInterface
     */
    protected $postRepository;

    /**
     * @var MemberActivityLogInterface
     */
    protected $activityLogRepository;

    /**
     * PublicController constructor.
     * @param Repository $config
     * @param MemberInterface $memberRepository
     * @param PostInterface $postRepository
     * @param MemberActivityLogInterface $memberActivityLogRepository
     */
    public function __construct(
        Repository $config,
        MemberInterface $memberRepository,
        PostInterface $postRepository,
        MemberActivityLogInterface $memberActivityLogRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->postRepository = $postRepository;
        $this->activityLogRepository = $memberActivityLogRepository;

        Assets::setConfig($config->get('plugins.member.assets', []));
    }

    /**
     * @param Request $request
     * @param PostTable $postTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View|\Response
     * @throws \Throwable
     */
    public function index(PostTable $postTable)
    {
        SeoHelper::setTitle(__('Posts'));

        return $postTable->render('plugins/member::table.base');
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @throws \Throwable
     */
    public function create(FormBuilder $formBuilder)
    {
        SeoHelper::setTitle(__('Write a post'));

        return $formBuilder->create(PostForm::class)->renderForm();
    }

    /**
     * @param PostRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function store(
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {

        if ($request->hasFile('image_input')) {
            $result = RvMedia::handleUpload($request->file('image_input'), 0, 'members');
            if ($result['error'] == false) {
                $file = $result['data'];
                $request->merge(['image' => $file->url]);
            }
        }

        /**
         * @var Post $post
         */
        $post = $this->postRepository->createOrUpdate(array_merge($request->except('status'), [
            'author_id'   => auth('member')->user()->getAuthIdentifier(),
            'author_type' => Member::class,
            'status'      => BaseStatusEnum::PENDING,
        ]));

        event(new CreatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'create_post',
            'reference_name' => $post->name,
            'reference_url'  => route('public.member.posts.edit', $post->id),
        ]);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        EmailHandler::setModule(MEMBER_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'post_name'   => $post->name,
                'post_url'    => route('posts.edit', $post->id),
                'post_author' => $post->author->getFullName(),
            ])
            ->sendUsingTemplate('new-pending-post');

        return $response
            ->setPreviousUrl(route('public.member.posts.index'))
            ->setNextUrl(route('public.member.posts.edit', $post->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     *
     * @throws \Throwable
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $post = $this->postRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('member')->user()->getAuthIdentifier(),
            'author_type' => Member::class,
        ]);

        if (!$post) {
            abort(404);
        }

        event(new BeforeEditContentEvent($request, $post));

        SeoHelper::setTitle(trans('plugins/blog::posts.edit') . ' "' . $post->name . '"');

        return $formBuilder
            ->create(PostForm::class, ['model' => $post])
            ->renderForm();
    }

    /**
     * @param int $id
     * @param PostRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function update(
        $id,
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {
        $post = $this->postRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('member')->user()->getAuthIdentifier(),
            'author_type' => Member::class,
        ]);

        if (!$post) {
            abort(404);
        }

        if ($request->hasFile('image_input')) {
            $result = RvMedia::handleUpload($request->file('image_input'), 0, 'members');
            if ($result['error'] == false) {
                $file = $result['data'];
                $request->merge(['image' => $file->url]);
            }
        }

        $post->fill($request->except('status'));

        $this->postRepository->createOrUpdate($post);

        event(new UpdatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'update_post',
            'reference_name' => $post->name,
            'reference_url'  => route('public.member.posts.edit', $post->id),
        ]);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $response
            ->setPreviousUrl(route('public.member.posts.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function delete($id, BaseHttpResponse $response)
    {
        $post = $this->postRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('member')->user()->getAuthIdentifier(),
            'author_type' => Member::class,
        ]);

        if (!$post) {
            abort(404);
        }

        $this->postRepository->delete($post);

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'delete_post',
            'reference_name' => $post->name,
        ]);

        return $response->setMessage(__('Delete post successfully!'));
    }

    /**
     * Get list tags in db
     *
     * @param TagInterface $tagRepository
     * @return array
     */
    public function getAllTags(TagInterface $tagRepository)
    {
        return $tagRepository->pluck('name');
    }
}

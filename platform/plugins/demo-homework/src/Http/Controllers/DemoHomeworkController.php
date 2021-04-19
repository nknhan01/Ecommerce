<?php

namespace Platform\DemoHomework\Http\Controllers;

use Platform\Base\Events\BeforeEditContentEvent;
use Platform\DemoHomework\Http\Requests\DemoHomeworkRequest;
use Platform\DemoHomework\Repositories\Interfaces\DemoHomeworkInterface;
use Platform\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Platform\DemoHomework\Tables\DemoHomeworkTable;
use Platform\Base\Events\CreatedContentEvent;
use Platform\Base\Events\DeletedContentEvent;
use Platform\Base\Events\UpdatedContentEvent;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Platform\DemoHomework\Forms\DemoHomeworkForm;
use Platform\Base\Forms\FormBuilder;

class DemoHomeworkController extends BaseController
{
    /**
     * @var DemoHomeworkInterface
     */
    protected $demoHomeworkRepository;

    /**
     * @param DemoHomeworkInterface $demoHomeworkRepository
     */
    public function __construct(DemoHomeworkInterface $demoHomeworkRepository)
    {
        $this->demoHomeworkRepository = $demoHomeworkRepository;
    }

    /**
     * @param DemoHomeworkTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(DemoHomeworkTable $table)
    {
        page_title()->setTitle(trans('plugins/demo-homework::demo-homework.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/demo-homework::demo-homework.create'));

        return $formBuilder->create(DemoHomeworkForm::class)->renderForm();
    }

    /**
     * @param DemoHomeworkRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(DemoHomeworkRequest $request, BaseHttpResponse $response)
    {
        $demoHomework = $this->demoHomeworkRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DEMO_HOMEWORK_MODULE_SCREEN_NAME, $request, $demoHomework));

        return $response
            ->setPreviousUrl(route('demo-homework.index'))
            ->setNextUrl(route('demo-homework.edit', $demoHomework->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $demoHomework = $this->demoHomeworkRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $demoHomework));

        page_title()->setTitle(trans('plugins/demo-homework::demo-homework.edit') . ' "' . $demoHomework->name . '"');

        return $formBuilder->create(DemoHomeworkForm::class, ['model' => $demoHomework])->renderForm();
    }

    /**
     * @param int $id
     * @param DemoHomeworkRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, DemoHomeworkRequest $request, BaseHttpResponse $response)
    {
        $demoHomework = $this->demoHomeworkRepository->findOrFail($id);

        $demoHomework->fill($request->input());

        $this->demoHomeworkRepository->createOrUpdate($demoHomework);

        event(new UpdatedContentEvent(DEMO_HOMEWORK_MODULE_SCREEN_NAME, $request, $demoHomework));

        return $response
            ->setPreviousUrl(route('demo-homework.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $demoHomework = $this->demoHomeworkRepository->findOrFail($id);

            $this->demoHomeworkRepository->delete($demoHomework);

            event(new DeletedContentEvent(DEMO_HOMEWORK_MODULE_SCREEN_NAME, $request, $demoHomework));

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
            $demoHomework = $this->demoHomeworkRepository->findOrFail($id);
            $this->demoHomeworkRepository->delete($demoHomework);
            event(new DeletedContentEvent(DEMO_HOMEWORK_MODULE_SCREEN_NAME, $request, $demoHomework));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

<?php

namespace Platform\Testimonial\Http\Controllers;

use Platform\Base\Events\BeforeEditContentEvent;
use Platform\Base\Traits\HasDeleteManyItemsTrait;
use Platform\Testimonial\Http\Requests\TestimonialRequest;
use Platform\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Platform\Base\Http\Controllers\BaseController;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Platform\Testimonial\Tables\TestimonialTable;
use Platform\Base\Events\CreatedContentEvent;
use Platform\Base\Events\DeletedContentEvent;
use Platform\Base\Events\UpdatedContentEvent;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Platform\Testimonial\Forms\TestimonialForm;
use Platform\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Throwable;

class TestimonialController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var TestimonialInterface
     */
    protected $testimonialRepository;

    /**
     * TestimonialController constructor.
     * @param TestimonialInterface $testimonialRepository
     */
    public function __construct(TestimonialInterface $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    /**
     * @param TestimonialTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(TestimonialTable $table)
    {

        page_title()->setTitle(trans('plugins/testimonial::testimonial.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/testimonial::testimonial.create'));

        return $formBuilder->create(TestimonialForm::class)->renderForm();
    }

    /**
     * @param TestimonialRequest $request
     * @return BaseHttpResponse
     */
    public function store(TestimonialRequest $request, BaseHttpResponse $response)
    {
        $testimonial = $this->testimonialRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(TESTIMONIAL_MODULE_SCREEN_NAME, $request, $testimonial));

        return $response
            ->setPreviousUrl(route('testimonial.index'))
            ->setNextUrl(route('testimonial.edit', $testimonial->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $testimonial = $this->testimonialRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $testimonial));

        page_title()->setTitle(trans('plugins/testimonial::testimonial.edit') . ' "' . $testimonial->name . '"');

        return $formBuilder->create(TestimonialForm::class, ['model' => $testimonial])->renderForm();
    }

    /**
     * @param $id
     * @param TestimonialRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, TestimonialRequest $request, BaseHttpResponse $response)
    {
        $testimonial = $this->testimonialRepository->findOrFail($id);

        $testimonial->fill($request->input());

        $this->testimonialRepository->createOrUpdate($testimonial);

        event(new UpdatedContentEvent(TESTIMONIAL_MODULE_SCREEN_NAME, $request, $testimonial));

        return $response
            ->setPreviousUrl(route('testimonial.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $testimonial = $this->testimonialRepository->findOrFail($id);

            $this->testimonialRepository->delete($testimonial);

            event(new DeletedContentEvent(TESTIMONIAL_MODULE_SCREEN_NAME, $request, $testimonial));

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
        return $this->executeDeleteItems($request, $response, $this->testimonialRepository,
            TESTIMONIAL_MODULE_SCREEN_NAME);
    }
}

<?php

namespace Platform\CustomField\Http\Controllers;

use Assets;
use Platform\Base\Forms\FormBuilder;
use Platform\Base\Http\Controllers\BaseController;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Platform\CustomField\Actions\CreateCustomFieldAction;
use Platform\CustomField\Actions\DeleteCustomFieldAction;
use Platform\CustomField\Actions\ExportCustomFieldsAction;
use Platform\CustomField\Actions\ImportCustomFieldsAction;
use Platform\CustomField\Actions\UpdateCustomFieldAction;
use Platform\CustomField\Forms\CustomFieldForm;
use Platform\CustomField\Http\Requests\CreateFieldGroupRequest;
use Platform\CustomField\Http\Requests\UpdateFieldGroupRequest;
use Platform\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Platform\CustomField\Repositories\Interfaces\FieldItemInterface;
use Platform\CustomField\Tables\CustomFieldTable;
use CustomField;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CustomFieldController extends BaseController
{

    /**
     * @var FieldGroupInterface
     */
    protected $fieldGroupRepository;

    /**
     * @var FieldItemInterface
     */
    protected $fieldItemRepository;

    /**
     * @param FieldGroupInterface $fieldGroupRepository
     * @param FieldItemInterface $fieldItemRepository
     */
    public function __construct(FieldGroupInterface $fieldGroupRepository, FieldItemInterface $fieldItemRepository)
    {
        $this->fieldGroupRepository = $fieldGroupRepository;
        $this->fieldItemRepository = $fieldItemRepository;
    }

    /**
     * @param CustomFieldTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(CustomFieldTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/custom-field::base.page_title'));

        Assets::addScriptsDirectly('vendor/core/plugins/custom-field/js/import-field-group.js')
            ->addScripts(['blockui']);

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @throws Throwable
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/custom-field::base.form.create_field_group'));

        Assets::addStylesDirectly([
            'vendor/core/plugins/custom-field/css/custom-field.css',
            'vendor/core/plugins/custom-field/css/edit-field-group.css',
        ])
            ->addScriptsDirectly('vendor/core/plugins/custom-field/js/edit-field-group.js')
            ->addScripts(['jquery-ui']);

        return $formBuilder->create(CustomFieldForm::class)->renderForm();
    }

    /**
     * @param CreateFieldGroupRequest $request
     * @param CreateCustomFieldAction $action
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(CreateFieldGroupRequest $request, CreateCustomFieldAction $action, BaseHttpResponse $response)
    {
        $result = $action->run($request->input());

        $hasError = false;
        $message = trans('core/base::notices.create_success_message');
        if ($result['error']) {
            $hasError = true;
            $message = $result['message'];
        }

        return $response
            ->setError($hasError)
            ->setPreviousUrl(route('custom-fields.index'))
            ->setNextUrl(route('custom-fields.edit', $result['data']['id']))
            ->setMessage($message);
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @return string
     * @throws Throwable
     */
    public function edit($id, FormBuilder $formBuilder)
    {

        Assets::addStylesDirectly([
            'vendor/core/plugins/custom-field/css/custom-field.css',
            'vendor/core/plugins/custom-field/css/edit-field-group.css',
        ])
            ->addScriptsDirectly('vendor/core/plugins/custom-field/js/edit-field-group.js')
            ->addScripts(['jquery-ui']);

        $object = $this->fieldGroupRepository->findOrFail($id);

        page_title()->setTitle(trans('plugins/custom-field::base.form.edit_field_group') . ' "' . $object->title . '"');

        $object->rules_template = CustomField::renderRules();

        return $formBuilder->create(CustomFieldForm::class, ['model' => $object])->renderForm();
    }

    /**
     * @param int $id
     * @param UpdateFieldGroupRequest $request
     * @param UpdateCustomFieldAction $action
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update(
        $id,
        UpdateFieldGroupRequest $request,
        UpdateCustomFieldAction $action,
        BaseHttpResponse $response
    ) {
        $result = $action->run($id, $request->input());

        $message = trans('core/base::notices.update_success_message');
        if ($result['error']) {
            $response->setError(true);
            $message = $result['message'];
        }

        return $response
            ->setPreviousUrl(route('custom-fields.index'))
            ->setMessage($message);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param DeleteCustomFieldAction $action
     * @return BaseHttpResponse
     */
    public function destroy($id, BaseHttpResponse $response, DeleteCustomFieldAction $action)
    {
        try {
            $action->run($id);
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
     * @param DeleteCustomFieldAction $action
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response, DeleteCustomFieldAction $action)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $action->run($id);
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param ExportCustomFieldsAction $action
     * @param null $id
     * @return JsonResponse
     */
    public function getExport(ExportCustomFieldsAction $action, $id = null)
    {
        $ids = [];

        if (!$id) {
            foreach ($this->fieldGroupRepository->all() as $item) {
                $ids[] = $item->id;
            }
        } else {
            $ids[] = $id;
        }

        $json = $action->run($ids)['data'];

        return response()->json($json, 200, [
            'Content-type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="export-field-group.json"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param ImportCustomFieldsAction $action
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function postImport(ImportCustomFieldsAction $action, Request $request)
    {
        $json = $request->input('json_data');

        return $action->run($json);
    }
}

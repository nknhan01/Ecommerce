<?php

namespace Botble\CustomField\Actions;

use Exception;
use Illuminate\Support\Facades\Auth;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Botble\CustomField\Repositories\Interfaces\FieldItemInterface;
use DB;
use Illuminate\Support\Arr;

class ImportCustomFieldsAction extends AbstractAction
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
     * ImportCustomFieldsAction constructor.
     * @param FieldGroupInterface $fieldGroupRepository
     * @param FieldItemInterface $fieldItemRepository
     */
    public function __construct(
        FieldGroupInterface $fieldGroupRepository,
        FieldItemInterface $fieldItemRepository
    ) {
        $this->fieldGroupRepository = $fieldGroupRepository;
        $this->fieldItemRepository = $fieldItemRepository;
    }

    /**
     * @param array $fieldGroupsData
     * @return array
     * @throws Exception
     */
    public function run(array $fieldGroupsData)
    {
        DB::beginTransaction();
        foreach ($fieldGroupsData as $fieldGroup) {
            if (!is_array($fieldGroup)) {
                continue;
            }
            $fieldGroup['created_by'] = Auth::user()->id;
            $item = $this->fieldGroupRepository->create($fieldGroup);
            if (!$item) {
                DB::rollBack();
                return $this->error();
            }
            $createItems = $this->createFieldItem(Arr::get($fieldGroup, 'items', []), $item->id);
            if (!$createItems) {
                DB::rollBack();
                return $this->error();
            }
        }
        DB::commit();
        return $this->success();
    }

    /**
     * @param array $items
     * @param int $fieldGroupId
     * @param null|int $parentId
     * @return bool
     */
    protected function createFieldItem(array $items, $fieldGroupId, $parentId = null): bool
    {
        foreach ($items as $item) {
            $item['field_group_id'] = $fieldGroupId;
            $item['parent_id'] = $parentId;
            $item['created_by'] = Auth::user()->id;
            $field = $this->fieldItemRepository->create($item);

            if (!$field) {
                return false;
            }

            $createChildren = $this->createFieldItem(Arr::get($item, 'children', []), $fieldGroupId, $field->id);

            if (!$createChildren) {
                return false;
            }
        }
        return true;
    }
}

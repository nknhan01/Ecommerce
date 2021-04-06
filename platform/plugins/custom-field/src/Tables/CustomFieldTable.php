<?php

namespace Platform\CustomField\Tables;

use BaseHelper;
use Platform\CustomField\Models\FieldGroup;
use Illuminate\Support\Facades\Auth;
use Platform\Base\Enums\BaseStatusEnum;
use Platform\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Platform\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class CustomFieldTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * @var string
     */
    protected $view = 'plugins/custom-field::list';

    /**
     * CustomFieldTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param FieldGroupInterface $fieldGroupRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        FieldGroupInterface $fieldGroupRepository
    ) {
        $this->repository = $fieldGroupRepository;
        $this->setOption('id', 'table-custom-fields');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['custom-fields.edit', 'custom-fields.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('title', function ($item) {
                if (!Auth::user()->hasPermission('custom-fields.edit')) {
                    return $item->name;
                }

                return Html::link(route('custom-fields.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return $this->getOperations('custom-fields.edit', 'custom-fields.destroy', $item,
                    Html::link(
                        route('custom-fields.export', ['id' => $item->id]),
                        Html::tag('i', '', ['class' => 'fa fa-download'])->toHtml(),
                        [
                            'class' => 'btn btn-icon btn-info btn-sm tip',
                            'title' => trans('plugins/custom-field::base.export'),
                        ],
                        null,
                        false
                    )->toHtml());
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $select = [
            'field_groups.id',
            'field_groups.title',
            'field_groups.status',
            'field_groups.order',
            'field_groups.created_at',
        ];

        $query = $model->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'field_groups.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title'      => [
                'name'  => 'field_groups.title',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'field_groups.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'field_groups.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('custom-fields.create'), 'custom-fields.create');

        $buttons['import-field-group'] = [
            'link' => '#',
            'text' => view('plugins/custom-field::_partials.import')->render(),
        ];
        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, FieldGroup::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('custom-fields.deletes'), 'custom-fields.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'field_groups.title'      => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'field_groups.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'field_groups.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}

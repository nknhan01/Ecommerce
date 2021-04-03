<?php

namespace Platform\RequestLog\Tables;

use Platform\RequestLog\Models\RequestLog;
use Illuminate\Support\Facades\Auth;
use Platform\RequestLog\Repositories\Interfaces\RequestLogInterface;
use Platform\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class RequestLogTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = false;

    /**
     * RequestLogTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param RequestLogInterface $requestLogRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        RequestLogInterface $requestLogRepository
    ) {
        $this->repository = $requestLogRepository;
        $this->setOption('id', 'table-request-histories');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasPermission('request-log.destroy')) {
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
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('url', function ($item) {
                return Html::link($item->url, $item->url, ['target' => '_blank'])->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return $this->getOperations(null, 'request-log.destroy', $item);
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
            'request_logs.id',
            'request_logs.url',
            'request_logs.status_code',
            'request_logs.count',
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
            'id'          => [
                'name'  => 'request_logs.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'url'         => [
                'name'  => 'request_logs.url',
                'title' => trans('core/base::tables.url'),
                'class' => 'text-left',
            ],
            'status_code' => [
                'name'  => 'request_logs.status_code',
                'title' => trans('plugins/request-log::request-log.status_code'),
            ],
            'count'       => [
                'name'  => 'request_logs.count',
                'title' => trans('plugins/request-log::request-log.count'),
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = [
            'empty' => [
                'link' => route('request-log.empty'),
                'text' => Html::tag('i', '', ['class' => 'fa fa-trash'])->toHtml() . ' ' . __('Delete all records'),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, RequestLog::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('request-log.deletes'), 'request-log.destroy', parent::bulkActions());
    }
}

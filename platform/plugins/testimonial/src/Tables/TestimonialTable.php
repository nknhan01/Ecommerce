<?php

namespace Platform\Testimonial\Tables;

use Platform\Testimonial\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Platform\Table\Abstracts\TableAbstract;
use Platform\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use RvMedia;
use Yajra\DataTables\DataTables;

class TestimonialTable extends TableAbstract
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
     * TestimonialTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param TestimonialInterface $testimonialRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        TestimonialInterface $testimonialRepository
    ) {
        $this->repository = $testimonialRepository;
        $this->setOption('id', 'table-plugins-testimonial');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['testimonial.edit', 'testimonial.destroy'])) {
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
            ->editColumn('image', function ($item) {
                return Html::image(RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()), $item->name, ['width' => 70]);
            })
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('testimonial.edit')) {
                    return $item->name;
                }
                return Html::link(route('testimonial.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('testimonial.edit', 'testimonial.destroy', $item);
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
            'testimonials.id',
            'testimonials.name',
            'testimonials.created_at',
            'testimonials.image',
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
                'name'  => 'testimonials.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'      => [
                'name'  => 'testimonials.image',
                'title' => trans('core/base::tables.image'),
                'width' => '100px',
            ],
            'name'       => [
                'name'  => 'testimonials.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'testimonials.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('testimonial.create'), 'testimonial.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Testimonial::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('testimonial.deletes'), 'testimonial.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'testimonials.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'testimonials.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}

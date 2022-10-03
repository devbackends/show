<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class UserHelpRequestDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'id';

    protected $sortOrder = 'desc'; // asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('user_help_requests')
                ->select('user_help_requests.*');


        $this->addFilter('id', 'id');
        $this->addFilter('email', 'email');
        $this->addFilter('status', 'status');
        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('marketplace::app.admin.user-help-requests.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('marketplace::app.admin.user-help-requests.name'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => false
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => trans('marketplace::app.admin.user-help-requests.email'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('marketplace::app.admin.user-help-requests.status'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ($row->status === 'waiting_for_response') {
                    return '<span class="badge badge-md badge-warning">' . trans("marketplace::app.admin.user-help-requests.waiting_for_response") . '</span>';
                } else {
                    return '<span class="badge badge-md badge-success">' . trans("marketplace::app.admin.user-help-requests.answered") . '</span>';
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'route' => 'admin.user-help-requests.handle',
            'icon' => 'icon eye-icon',
            'method' => 'GET'
        ]);
    }
}
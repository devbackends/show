<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class AddressDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $this->setQueryBuilder(
            DB::table('addresses')->select('id', 'address1', 'address2', 'city', 'country')
        );
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('saas::app.admin.tenant.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'address1',
            'label' => trans('saas::app.admin.tenant.address1'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'address2',
            'label' => trans('saas::app.admin.tenant.address2'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'city',
            'label' => trans('saas::app.admin.tenant.city'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'country',
            'label' => trans('saas::app.admin.tenant.country'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'method' => 'GET', //use post only for redirects only
            'route' => 'company.address.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST', //use post only for redirects only
            'route' => 'company.address.delete',
            'icon' => 'icon trash-icon'
        ]);
    }
}

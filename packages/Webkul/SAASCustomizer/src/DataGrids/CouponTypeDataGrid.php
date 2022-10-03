<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Tenant's Customers DataGrid class
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CouponTypeDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    protected $itemsPerPage = 50;

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('coupon_type')

            ->addSelect('id', 'type', 'description','image', 'status');
        $this->addFilter('type', 'type');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => 'id',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => 'Type',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);


        $this->addColumn([
            'index' => 'description',
            'label' => 'Description',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);




        $this->addColumn([
            'index' => 'status',
            'label' => 'status',
            'type' => 'boolean',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-md badge-success">Activated</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">Blocked</span>';
                }
            }
        ]);
    }

    public function prepareActions()
    {

        $this->addAction([
            'title' => 'View',
            'type' => 'View',
            'method' => 'GET', //use post only for redirects only
            'route' => 'super.coupons-type.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'title' => trans('saas::app.super-user.tenants.delete'),
            'method' => 'POST', // other than get request it fires ajax and self refreshes datagrid
            'route' => 'super.coupons-type.delete',
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'action' => route('super.coupons-type.massdelete'),
            'label' => trans('saas::app.super-user.tenants.mass-delete'),
            'index' => 'admin_name',
            'method' => 'DELETE'
        ]);
    }
}

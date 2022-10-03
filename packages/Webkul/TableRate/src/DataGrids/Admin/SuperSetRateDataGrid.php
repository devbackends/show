<?php

namespace Webkul\TableRate\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * SuperSetRate DataGrid Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
Class SuperSetRateDataGrid extends DataGrid
{
    /**
     *
     * @var integer
    */
    public $index = 'id';
    
    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('tablerate_superset_rates')
                ->leftJoin('tablerate_supersets', 'tablerate_superset_rates.tablerate_superset_id', '=', 'tablerate_supersets.id')
                ->addSelect('tablerate_superset_rates.*')
                ->addSelect('tablerate_supersets.name as superset_name');
                
        $this->addFilter('id', 'tablerate_superset_rates.id');
        $this->addFilter('price_from', 'tablerate_superset_rates.price_from');
        $this->addFilter('price_to', 'tablerate_superset_rates.price_to');
        $this->addFilter('price', 'tablerate_superset_rates.price');
        $this->addFilter('shipping_type', 'tablerate_superset_rates.shipping_type');
        $this->addFilter('superset_name', 'tablerate_supersets.name');
        $this->addFilter('created_at', 'tablerate_superset_rates.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('tablerate::app.admin.superset-rates.id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'price_from',
            'label' => trans('tablerate::app.admin.superset-rates.price-from'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'price_to',
            'label' => trans('tablerate::app.admin.superset-rates.price-to'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'shipping_type',
            'label' => trans('tablerate::app.admin.superset-rates.shipping-type'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'superset_name',
            'label' => trans('tablerate::app.admin.superset-rates.superset-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('tablerate::app.admin.superset-rates.price'),
            'type' => 'price',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET',
            'route' => 'admin.tablerate.superset_rates.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST',
            'route' => 'admin.tablerate.superset_rates.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'SuperSet Rate']),
            'icon' => 'icon trash-icon'
        ]);

        $this->enableAction = true;
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('tablerate::app.admin.superset-rates.delete'),
            'action' => route('admin.tablerate.superset_rates.massdelete'),
            'method' => 'DELETE'
        ]);

        $this->enableMassAction = true;
    }
}
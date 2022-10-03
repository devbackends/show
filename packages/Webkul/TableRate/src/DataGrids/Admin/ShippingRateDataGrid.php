<?php

namespace Webkul\TableRate\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * ShippingRate DataGrid Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
Class ShippingRateDataGrid extends DataGrid
{
    /**
     * @var integer
     *
     */
    public $index = 'id';
    
    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder  = DB::table('tablerate_shipping_rates')
                ->leftJoin('tablerate_supersets', 'tablerate_shipping_rates.tablerate_superset_id', '=', 'tablerate_supersets.id')
                ->addSelect('tablerate_shipping_rates.*')
                ->addSelect(
                    'tablerate_supersets.name as superset_name',
                    'tablerate_supersets.code as superset_code'
                );

        $this->addFilter('id', 'tablerate_shipping_rates.id');
        $this->addFilter('country','tablerate_shipping_rates.country');
        $this->addFilter('state','tablerate_shipping_rates.state');
        $this->addFilter('is_zip_range', 'tablerate_shipping_rates.is_zip_range');
        $this->addFilter('zip_from','tablerate_shipping_rates.zip_from');
        $this->addFilter('zip_to','tablerate_shipping_rates.zip_to');
        $this->addFilter('weight_from','tablerate_shipping_rates.weight_from');
        $this->addFilter('weight_to','tablerate_shipping_rates.weight_to');
        $this->addFilter('created_at', 'tablerate_shipping_rates.created_at');
        $this->addFilter('superset_name', 'tablerate_supersets.name');
        $this->addFilter('superset_code', 'tablerate_supersets.code');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('tablerate::app.admin.shipping-rates.id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'superset_name',
            'label' => trans('tablerate::app.admin.shipping-rates.superset-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'country',
            'label' => trans('tablerate::app.admin.shipping-rates.country'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'state',
            'label' => trans('tablerate::app.admin.shipping-rates.region'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'zip_from',
            'label' => trans('tablerate::app.admin.shipping-rates.zip-from'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'zip_to',
            'label' => trans('tablerate::app.admin.shipping-rates.zip-to'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'weight_from',
            'label' => trans('tablerate::app.admin.shipping-rates.weight-from'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'weight_to',
            'label' => trans('tablerate::app.admin.shipping-rates.weight-to'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('tablerate::app.admin.shipping-rates.price'),
            'type' => 'price',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'is_zip_range',
            'label' => trans('tablerate::app.admin.shipping-rates.is-range'),
            'type' => 'boolean',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure'    => true,
            'wrapper' => function($row) {
                if ( $row->is_zip_range == 1 ) {
                    return '<span class="badge badge-md badge-success">'. trans('tablerate::app.admin.shipping-rates.isrange-yes') .'</span>';
                } else {
                    return '<span class="badge badge-md badge-warning">'. trans('tablerate::app.admin.shipping-rates.isrange-no') .'</span>';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'zip_code',
            'label' => trans('tablerate::app.admin.shipping-rates.zip-code'),
            'type' => 'string',
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
            'route' => 'admin.tablerate.shipping_rates.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST',
            'route' => 'admin.tablerate.shipping_rates.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Shipping Rate']),
            'icon' => 'icon trash-icon'
        ]);

        $this->enableAction = true;
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('tablerate::app.admin.shipping-rates.delete'),
            'action' => route('admin.tablerate.shipping_rates.massdelete'),
            'method' => 'DELETE'
        ]);

        $this->enableMassAction = true;
    }
}
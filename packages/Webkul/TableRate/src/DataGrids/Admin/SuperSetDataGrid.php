<?php

namespace Webkul\TableRate\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * SuperSet DataGrid Class *
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
Class SuperSetDataGrid extends DataGrid
{
    /**
     *
     * @var integer
    */
    public $index = 'id';
    
    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder  = DB::table('tablerate_supersets')
                ->select('id', 'code', 'name', 'status');
                
        $this->addFilter('name', 'tablerate_supersets.name');
        $this->addFilter('code', 'tablerate_supersets.code');
        $this->addFilter('status', 'tablerate_supersets.status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('tablerate::app.admin.supersets.id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('tablerate::app.admin.supersets.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('tablerate::app.admin.supersets.code'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('tablerate::app.admin.supersets.status'),
            'type' => 'boolean',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure'    => true,
            'wrapper' => function($data) {
                if ( $data->status ) {
                    return '<span class="badge badge-md badge-success">'. trans('tablerate::app.admin.supersets.active') .'</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">'. trans('tablerate::app.admin.supersets.in-active') .'</span>';
                }
            }
        ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET',
            'route' => 'admin.tablerate.supersets.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST',
            'route' => 'admin.tablerate.supersets.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Shipping Method']),
            'icon' => 'icon trash-icon'
        ]);

        $this->enableAction = true;
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('tablerate::app.admin.supersets.delete'),
            'action' => route('admin.tablerate.supersets.massdelete'),
            'method' => 'DELETE'
        ]);

        $this->addMassAction([
            'type' => 'update',
            'label' => trans('tablerate::app.admin.supersets.update'),
            'action' => route('admin.tablerate.supersets.massupdate'),
            'method' => 'PUT',
            'options' => [
                trans('tablerate::app.admin.supersets.in-active') => 0,
                trans('tablerate::app.admin.supersets.active') => 1
            ]
        ]);

        $this->enableMassAction = true;
    }
}
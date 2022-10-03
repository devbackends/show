<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class CMSPageDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cms_pages')
            ->select('cms_pages.id','cms_pages.published','cms_page_translations.pb_page_id', 'cms_page_translations.page_title',DB::raw("CONCAT('page/',cms_page_translations.url_key) AS url_key"))
            ->leftJoin('cms_page_translations', function($leftJoin) {
                $leftJoin->on('cms_pages.id', '=', 'cms_page_translations.cms_page_id')
                         ->where('cms_page_translations.locale', app()->getLocale());
            });

        $this->addFilter('id', 'cms_pages.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'page_title',
            'label'      => trans('admin::app.cms.pages.page-title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'url_key',
            'label'      => trans('admin::app.datagrid.url-key'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'published',
            'label'      => trans('admin::app.cms.pages.published'),
            'type'       => 'status',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.cms.pages.settings'),
            'method' => 'GET',
            'route'  => 'admin.cms.edit',
            'icon'   => 'icon fas fa-cog',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.page_builder.edit',
            'icon'   => 'icon pencil-lg-icon',
            'custom' => true,
            'custom_type' => 'pagebuilder',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.cms.delete',
            'icon'   => 'icon trash-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.duplicate'),
            'method' => 'POST',
            'route'  => 'admin.cms.duplicate',
            'icon'   => 'icon fa fa-copy',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.cms.mass-delete'),
            'method' => 'DELETE',
        ]);
    }
}
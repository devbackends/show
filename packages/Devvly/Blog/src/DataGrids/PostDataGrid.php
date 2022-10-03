<?php

namespace Devvly\Blog\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class PostDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('posts')
            ->select('posts.id', 'posts.published', 'post_translations.title', 'post_translations.pb_page_id', 'post_translations.url_key')
            ->leftJoin('post_translations', function($leftJoin) {
                $leftJoin->on('posts.id', '=', 'post_translations.post_id')
                         ->where('post_translations.locale', app()->getLocale());
            });

        $this->addFilter('id', 'posts.id');

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
            'index'      => 'title',
            'label'      => trans('blog::app.post.title'),
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
            'title'  => trans('blog::app.general.view'),
            'method' => 'GET',
            'route'  => 'blog.front.post.view',
            'icon'   => 'icon eye-icon',
            'custom' => true,
            'custom_type' => 'view-post',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.cms.pages.settings'),
            'method' => 'GET',
            'route'  => 'blog.admin.post.edit',
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
            'route'  => 'blog.admin.post.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('blog.admin.post.mass-delete'),
            'method' => 'DELETE',
        ]);
    }
}
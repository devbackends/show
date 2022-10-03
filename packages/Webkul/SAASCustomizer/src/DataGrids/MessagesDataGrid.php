<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Tenant's Customers DataGrid class
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MessagesDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    protected $itemsPerPage = 50;

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketplace_messages')
            ->addSelect('*',DB::raw('(select CONCAT(customers.first_name, " ", customers.last_name) from customers where customers.id=marketplace_messages.from) AS sender_name'),DB::raw('(select CONCAT(customers.first_name, " ", customers.last_name) from customers where customers.id=marketplace_messages.to) AS receiver_name'))
            ->orderBy('updated_at','desc');

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
            'index' => 'sender_name',
            'label' => 'Sender',
            'type' => 'string'
        ]);
        $this->addColumn([
            'index' => 'receiver_name',
            'label' => 'Receiver',
            'type' => 'string'
        ]);

        $this->addColumn([
            'index' => 'updated_at',
            'label' => 'date',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);


    }

    public function prepareActions()
    {

        $this->addAction([
            'title' => 'View',
            'type' => 'View',
            'method' => 'GET', //use post only for redirects only
            'route' => 'super.messages.details',
            'icon' => 'icon pencil-lg-icon'
        ]);


    }


}

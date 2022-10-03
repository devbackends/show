<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Tenant's Customers DataGrid class
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReportedMessagesDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    protected $itemsPerPage = 50;

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketplace_message_reports')
            ->addSelect('marketplace_messages.id','marketplace_message_reports.reason','marketplace_message_reports.created_at','marketplace_messages.from','marketplace_messages.to',DB::raw('(select CONCAT(customers.first_name, " ", customers.last_name) from customers where customers.id=marketplace_messages.from) AS sender_name'),DB::raw('(select CONCAT(customers.first_name, " ", customers.last_name) from customers where customers.id=marketplace_messages.to) AS receiver_name'))
            ->leftJoin('marketplace_messages', 'marketplace_message_reports.message_id', '=', 'marketplace_messages.id')
            ->orderBy('created_at','desc');

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
            'index' => 'reason',
            'label' => 'Reason',
            'type' => 'string'
        ]);
        $this->addColumn([
            'index' => 'created_at',
            'label' => 'created on',
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

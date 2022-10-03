<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Transaction Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TransactionDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketplace_transactions')
                ->leftJoin('marketplace_sellers', 'marketplace_transactions.marketplace_seller_id', '=', 'marketplace_sellers.id')
                ->leftJoin('customers', 'marketplace_sellers.customer_id', '=', 'customers.id')
                ->select('marketplace_transactions.id', 'transaction_id', 'comment', 'base_total', 'marketplace_seller_id')
                ->addSelect(DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as seller_name'));

        $this->addFilter('seller_name', DB::raw('CONCAT(customers.first_name, " ", customers.last_name)'));
        $this->addFilter('id', 'marketplace_transactions.id');
        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('marketplace::app.admin.transactions.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'seller_name',
            'label' => trans('marketplace::app.admin.transactions.seller-name'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'marketplace_seller_id',
            'label' => trans('marketplace::app.admin.transactions.seller-id'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'transaction_id',
            'label' => trans('marketplace::app.admin.transactions.transaction-id'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => trans('marketplace::app.admin.transactions.comment'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_total',
            'label' => trans('marketplace::app.admin.transactions.total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);
    }
}
<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Review Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketplace_seller_reviews')
                ->leftJoin('customers', 'marketplace_seller_reviews.customer_id', '=', 'customers.id')
                ->leftJoin('marketplace_sellers', 'marketplace_seller_reviews.marketplace_seller_id', '=', 'marketplace_sellers.id')
                ->leftJoin('customers as seller_customers', 'marketplace_sellers.customer_id', '=', 'seller_customers.id')
                ->select('marketplace_seller_reviews.id', 'rating', 'marketplace_seller_reviews.status', 'comment')
                ->addSelect(DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name'))
                ->addSelect(DB::raw('CONCAT(seller_customers.first_name, " ", seller_customers.last_name) as seller_name'));

        $this->addFilter('customer_name', DB::raw('CONCAT(customers.first_name, " ", customers.last_name)'));
        $this->addFilter('seller_name', DB::raw('CONCAT(seller_customers.first_name, " ", seller_customers.last_name)'));
        $this->addFilter('id', 'marketplace_seller_reviews.id');
        $this->addFilter('status', 'marketplace_seller_reviews.status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('marketplace::app.admin.reviews.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans('marketplace::app.admin.reviews.customer-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'seller_name',
            'label' => trans('marketplace::app.admin.reviews.seller-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'rating',
            'label' => trans('marketplace::app.admin.reviews.rating'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('marketplace::app.admin.reviews.status'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'wrapper' => function($row) {
                if ($row->status  == 'approved')
                    return trans('marketplace::app.admin.reviews.approved');
                else
                    return trans('marketplace::app.admin.reviews.un-approved');
            }
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => trans('marketplace::app.admin.reviews.comment'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'update',
            'label' => trans('marketplace::app.admin.reviews.update'),
            'action' => route('admin.marketplace.reviews.massupdate'),
            'method' => 'PUT',
            'options' => [
                trans('marketplace::app.admin.reviews.approve') => 'approved',
                trans('marketplace::app.admin.reviews.unapprove') => 'unapproved'
            ]
        ]);
    }
}
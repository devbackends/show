<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Attribute\Models\Attribute;
use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class ProductDataGrid extends DataGrid
{
    protected $sortOrder = 'desc';

    protected $index = 'product_id';

    protected $itemsPerPage = 50;

    protected $locale = 'all';

    protected $channel = 'all';

    public function __construct()
    {
        parent::__construct();

        $this->locale = request()->get('locale') ?? 'all';

        $this->channel = request()->get('channel') ?? 'all';
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_flat')
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->leftJoin('product_categories', 'product_flat.product_id', '=', 'product_categories.product_id')
            ->leftJoin('attribute_families', 'products.attribute_family_id', '=', 'attribute_families.id')

            ->selectRaw("
                product_flat.product_id as product_id,
                products.sku as product_sku,
                product_flat.name as product_name,
                products.type as product_type,
                product_flat.status,
                product_flat.price,
                attribute_families.name as attribute_family,
                products.parent_id as parent_id,
                group_concat(DISTINCT product_categories.category_id ORDER BY product_categories.category_id DESC SEPARATOR ', ') as categories,
                product_flat.quantity as quantity
            ");

        if ($this->locale !== 'all') {
            $queryBuilder->where('locale', $this->locale);
        } else {
            $queryBuilder->whereNotNull('product_flat.name');
        }

        if ($this->channel !== 'all') {
            $queryBuilder->where('channel', $this->channel);
        }

        $queryBuilder->groupBy('product_flat.product_id');

        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('product_name', 'product_flat.name');
        $this->addFilter('product_sku', 'products.sku');
        $this->addFilter('status', 'product_flat.status');
        $this->addFilter('product_type', 'products.type');
        $this->addFilter('attribute_family', 'attribute_families.name');
        $this->addFilter('quantity', 'product_flat.quantity');

        $this->addFilter('categories', 'product_categories.category_id');


        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_sku',
            'label' => trans('admin::app.datagrid.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'attribute_family',
            'label' => trans('admin::app.datagrid.attribute-family'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_type',
            'label' => trans('admin::app.datagrid.type'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,

            'wrapper' => function ($value) {
                if ($value->status == 1) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('admin::app.datagrid.price'),
            'type' => 'price',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('admin::app.datagrid.qty'),
            'type' => 'quantity',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
            'wrapper' => function ($value) {
                if (is_null($value->quantity)) {
                    return 0;
                } else {
                    return $value->quantity;
                }
            },
        ]);

        $this->addColumn([
            'index' => 'parent_id',
            'label' => trans('parent_id'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'filterable' => false,
            'closure' => true,
            'hidden' => true,
            'wrapper' => function ($value) {
                // call your component and pass the value of the configurable product
                if (is_null($value->parent_id)) {
                    return 0;
                } else {
                    return $value->parent_id;
                }
            },
        ]);

        $this->addColumn([
            'index' => 'categories',
            'label' => 'Category',
            'type' => 'categories',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function ($value) {
                if (is_null($value->categories)) {
                    return 0;
                } else {
                    return $value->categories;
                }
            },
        ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'title' => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route' => 'admin.catalog.products.edit',
            'icon' => 'far fa-pencil',
            'condition' => function () {
                return true;
            },
        ]);

        $this->addAction([
            'title' => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route' => 'admin.catalog.products.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon' => 'far fa-trash-alt',
        ]);

        $this->addAction([
            'title' => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route' => 'admin.catalog.products.view',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon' => 'far fa-eye',
        ]);


    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('admin::app.datagrid.delete'),
            'action' => route('admin.catalog.products.massdelete'),
            'method' => 'DELETE',
        ]);

        $this->addMassAction([
            'type' => 'update',
            'label' => trans('admin::app.datagrid.update-status'),
            'action' => route('admin.catalog.products.massupdate'),
            'method' => 'PUT',
            'options' => [
                'Active' => 1,
                'Inactive' => 0,
            ],
        ]);
    }

    public function sortOrFilterCollection($collection, $parseInfo)
    {
        $attributesKey = 'attributes';
        if (isset($parseInfo[$attributesKey])) {
            $attrIds = [];
            $wheres = [];
            foreach ($parseInfo[$attributesKey] as $attrId => $attrValue) {
                $attrIds[] = $attrId;
                $attribute = Attribute::query()->find($attrId);
                if ($attribute->type === 'select') {
                    $where = 'integer_value';
                } elseif ($attribute->type === 'textarea') {
                    $where = 'text_value';
                } elseif ($attribute->type === 'price') {
                    $where = 'float_value';
                } elseif ($attribute->type === 'multiselect') {
                    $where = 'json_value';
                } else {
                    $where = $attribute->type . '_value';
                }
                $wheres[] = ['product_attribute_values.'.$where, 'like', '%'.$attrValue.'%'];
            }

            $collection->leftJoin('product_attribute_values', function ($join) use ($attrIds) {
                $join->on('product_flat.product_id', '=', 'product_attribute_values.product_id')
                    ->whereIn('product_attribute_values.attribute_id', $attrIds);
            })->where($wheres);
            unset($parseInfo[$attributesKey]);
        }

        $categoryKey = 'categories';
        if (isset($parseInfo[$categoryKey])) {
            $operator = array_key_first($parseInfo[$categoryKey]);
            $value = $parseInfo[$categoryKey][$operator];
            $collection->where($this->filterMap[$categoryKey], ($operator === 'with') ? '=' : '!=', $value);
            //collection->having('product_flat.categories', 'like', '%'.$value.'%');
            unset($parseInfo[$categoryKey]);
        }


        $quantityKey = 'quantity';
        if (isset($parseInfo[$quantityKey]) && isset($parseInfo[$quantityKey]['eq'])) {
            if ($parseInfo[$quantityKey]['eq'] == 'with') {
                $collection->where($this->filterMap[$quantityKey],'>',0);
           } else {
                $collection->where($this->filterMap[$quantityKey],'=',0);
            }
            unset($parseInfo[$quantityKey]);
        }


        return parent::sortOrFilterCollection($collection, $parseInfo);

    }
}
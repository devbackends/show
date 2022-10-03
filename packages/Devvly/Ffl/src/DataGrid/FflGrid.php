<?php

namespace Devvly\Ffl\DataGrid;

use Devvly\Ffl\Models\Ffl;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid as BaseGrid;

class FflGrid extends BaseGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $this->setQueryBuilder(
            DB::table('ffl')
                ->join('ffl_business_info', 'ffl.id', '=', 'ffl_business_info.ffl_id')
                ->join('ffl_license', 'ffl.id', '=', 'ffl_license.ffl_id')
                ->select('ffl.id','ffl.is_approved')
                ->addSelect(
                    'company_name', 'phone', 'email', 'license_number', 'contact_name', 'source'
                )
                ->where(function ($query) {
                    /** @var Builder $query */
                    $query->where('source', '=', Ffl::ON_BOARDING_FORM)
                        ->orWhere('source', '=', Ffl::ON_BOARDING_ADMIN);
                })
                /*->where('is_approved', '<', '1')*/
        );
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'ffl.id',
            'label'      => 'ID',
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn(
            [
                'index'      => 'source',
                'label'      => 'Source',
                'type'       => 'string',
                'searchable' => true,
                'sortable'   => true,
                'filterable' => true,
            ]
        );

        $this->addColumn([
            'index'      => 'contact_name',
            'label'      => 'Contact name',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'company_name',
            'label'      => 'Company name',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => 'Email',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => 'Phone',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
        ]);
        $this->addColumn([
            'index'      => 'is_approved',
            'label'      => 'Approved',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'wrapper' => function($row) {
                if ($row->is_approved) {
                    return 'Approved';
                } else {
                    return 'Not Approved';
                }
            }
        ]);
        $this->addColumn([
            'index'      => 'license_number',
            'label'      => 'License number',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type'   => 'Edit',
            'method' => 'GET',
            'route'  => 'ffl.review.approve',
            'icon'   => 'icon pencil-lg-icon',
        ]);
    }
}

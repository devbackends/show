<?php

namespace Webkul\TableRate\Http\Controllers\Admin;
use Webkul\TableRate\Exports\DataGridExport;
use Excel;

/**
 * ExportController Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ExportController extends Controller
{
    protected $exportableGrids = [
        'ShippingRateDataGrid'
    ];

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * function for export datagrid
     *
     * @return void
    */
    public function export()
    {
        $data = request()->all();

        $format = $data['format'];

        $gridName = explode('\\', $data['gridName']);

        $path = '\Webkul\TableRate\DataGrids\Admin'. '\\'.last($gridName);

        $proceed = false;

        foreach($this->exportableGrids as $exportableGrid)
            if (last($gridName) == $exportableGrid) {
                $proceed = true;
            }

            if (! $proceed) {
                redirect()->back();
            }

            $gridInstance = new $path;
            $exportRecords = $gridInstance->export();

            if (count($exportRecords) == 0) {
                session()->flash('warning', trans('tablerate::app.admin.export.no-records'));
                return redirect()->back();
            }

            if ($format == 'csv') {
                return Excel::download(new DataGridExport($exportRecords), 'Shipping Information'.'.csv');
            }

            if ($format == 'xls') {
                return Excel::download(new DataGridExport($exportRecords), 'Shipping Information'.'.xlsx');
            }

            session()->flash('warning', trans('tablerate::app.admin.export.illegal-format'));

            return redirect()->back();
    }
}
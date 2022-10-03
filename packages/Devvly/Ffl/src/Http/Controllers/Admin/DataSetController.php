<?php

namespace Devvly\Ffl\Http\Controllers\Admin;

use Devvly\Ffl\DTO\FflDataSetDto;
use Devvly\Ffl\Http\Controllers\Controller;
use Devvly\Ffl\Http\Requests\StoreFflImportForm;
use Devvly\Ffl\Jobs\ProcessFflDataSet;
use Illuminate\Http\Request;

class DataSetController extends Controller
{
    /**
     * @var FflDataSetDto
     */
    private $fflDataSet;

    /**
     * DataSetController constructor.
     * @param FflDataSetDto $fflDataSet
     */

    /**
     * @var array|\Illuminate\Contracts\Foundation\Application|Request|string
     */
    private $_config;

    public function __construct(FflDataSetDto $fflDataSet)
    {
        $this->_config = request('_config');
        $this->fflDataSet = $fflDataSet;
    }

    /**
     * @param StoreFflImportForm $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreFflImportForm $request)
    {   
        ini_set('max_execution_time', 0);
        $request->validated();
        $data = $request->file('data');
        $this->fflDataSet->setFileName(base64_encode(microtime() . $data->getClientOriginalName()) . '.' . $data->extension());
        $request->file('data')->storeAs(FflDataSetDto::BASE_PATH, $this->fflDataSet->getFileName(), ['disk' => 'local']);
//        ProcessFflDataSet::dispatch($this->fflDataSet)->onConnection('database')->onQueue('ffl_data_set_import');
        ProcessFflDataSet::dispatch($this->fflDataSet);
//        return new JsonResponse(['status' => 'ok', 'message' => 'data set has been started to processing']);
        return redirect()->back()->with('message', 'Success, data uploaded');
    }

    public function index()
    {
        return view($this->_config['view']);
    }
}

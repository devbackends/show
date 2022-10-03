<?php

namespace Webkul\TableRate\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Webkul\TableRate\Repositories\SuperSetRateRepository;
use Webkul\TableRate\Repositories\ShippingRateRepository;
use Webkul\TableRate\Repositories\SuperSetRepository;

/**
 * SuperSetRateController Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SuperSetRateController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SuperSetRateRepository object
     *
     * @var array
    */
    protected $superSetRateRepository;

    /**
     * ShippingRateRepository object
     *
     * @var array
    */
    protected $shippingRateRepository;

    /**
     * SuperSetRepository object
     *
     * @var array
    */
    protected $superSetRepository;

    /**
     * Create a new controller instance.
     *
     * @param Webkul\TableRate\Repositories\SuperSetRateRepository $superSetRateRepository
     * @param Webkul\TableRate\Repositories\ShippingRateRepository $shippingRateRepository
     * @param Webkul\TableRate\Repositories\SuperSetRepository $superSetRepository
     * @return void
     */
    public function __construct(
        SuperSetRateRepository $superSetRateRepository,
        ShippingRateRepository $shippingRateRepository,
        SuperSetRepository $superSetRepository
    )   {
        $this->_config = request('_config');

        $this->superSetRateRepository = $superSetRateRepository;

        $this->shippingRateRepository = $shippingRateRepository;

        $this->superSetRepository = $superSetRepository;
    }

    /**
     * Method to populate the superSetRate page.
     *
     * @return Mixed
     */
    public function index()
    {
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], compact('flag', 'pathInfo'));
    }

    /**
     * Add New  Shipping Set
     *
     * @return response
     */
    public function create()
    {
        $superSets = $this->superSetRepository->findWhere(['status' => 1]);
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], compact('superSets', 'flag', 'pathInfo'));
    }

    /**
     * Store The supersetRate
     *
     * @return response
     */
    public function store()
    {
        $this->validate(request(),[
            'price_from'            => 'required',
            'price_to'              => 'required',
            'shipping_type'         => 'required|string',
            'tablerate_superset_id' => 'required|numeric',
            'price'                 => 'required',
        ]);

        $data = request()->all();

        if ($data['shipping_type'] == 'Free') {
            $data['price'] = 0;
        }

        if ( $data['price_from'] >= $data['price_to'] ) {
            session()->flash('error', trans('tablerate::app.admin.superset-rates.price-error'));

            return redirect()->back();
        }

        $superSetRate = $this->superSetRateRepository->getMatchData($data)->first();

        if ( isset($superSetRate->id) ) {
            session()->flash('error', trans('tablerate::app.admin.superset-rates.range-exist',
            ['name' => 'SuperSet Rate']));

            return redirect()->back();
        }

        $this->superSetRateRepository->create($data);

        session()->flash('success', trans('tablerate::app.admin.superset-rates.create-success', ['name' => 'SuperSet Rate']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Edit superseRate .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $superSets = $this->superSetRepository->all();

        $superSetRate = $this->superSetRateRepository->findOrFail($id);
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], compact('superSets', 'superSetRate', 'flag', 'pathInfo'));
    }

     /**
     * Update supersetRate.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'price_from'            => 'required',
            'price_to'              => 'required',
            'shipping_type'         => 'required|string',
            'tablerate_superset_id' => 'required|string',
            'price'                 => 'required',
        ]);

        $data = request()->all();

        if ($data['shipping_type'] == 'Free') {
            $data['price'] = 0;
        }

        if ( $data['price_from'] >= $data['price_to'] ) {
            session()->flash('error', trans('tablerate::app.admin.superset-rates.price-error'));

            return redirect()->back();
        }
        $data['tablerate_supersetrate_id'] = $id;

        $superSetRate = $this->superSetRateRepository->getMatchData($data)->first();

        if ( isset($superSetRate->id) ) {
            session()->flash('error', trans('tablerate::app.admin.superset-rates.range-exist',
            ['name' => 'SuperSet Rate']));

            return redirect()->back();
        }

        $this->superSetRateRepository->update($data, $id);

        session()->flash('success', trans('tablerate::app.admin.superset-rates.update-success',
        ['name' => 'SuperSet Rate']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Delete SuperSet Rate from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->superSetRateRepository->delete($id);

            session()->flash('success', trans('tablerate::app.admin.superset-rates.delete-success',
            ['name' => 'SuperSet Rate']));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'SuperSet Rate']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * MassDelete SuperSetRate
     *
     * @return response
     */
    public function massDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    $suppressFlash = true;

                    $this->superSetRateRepository->delete($value);

                } catch (\Exception $e) {
                    report($e);

                    $suppressFlash = true;

                    continue;
                }
            }

            if ($suppressFlash) {
                session()->flash('success', trans('tablerate::app.admin.superset-rates.mass-delete-success'));
            } else {
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'SuperSet Rates']));
            }

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}
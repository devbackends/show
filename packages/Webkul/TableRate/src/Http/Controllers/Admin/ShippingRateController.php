<?php

namespace Webkul\TableRate\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Webkul\Admin\Imports\DataGridImport;
use Illuminate\Support\Facades\Validator;
use Webkul\TableRate\Repositories\ShippingRateRepository;
use Webkul\TableRate\Repositories\SuperSetRepository;
use Webkul\Core\Repositories\CountryRepository;

/**
 * ShippingRateController Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShippingRateController extends Controller
{
    /**
     * route configuration
     *
     * @var array
     */
    protected $_config;

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
     * CountryRepository object
     *
     * @var array
    */
    protected $countryRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\TableRate\Repositories\ShippingRateRepository $shippingRateRepository
     * @param  Webkul\TableRate\Repositories\SuperSetRepository $supersetRepository
     * @param  Webkul\Core\Repositories\CountryRepository $countryRepository
     * @return void
     */
    public function __construct(
        ShippingRateRepository $shippingRateRepository,
        SuperSetRepository $superSetRepository,
        CountryRepository $countryRepository
    ){
        $this->_config = request('_config');

        $this->shippingRateRepository = $shippingRateRepository;

        $this->superSetRepository = $superSetRepository;

        $this->countryRepository = $countryRepository;
    }

    /**
     * Method to populate shipping rate page.
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
     * Add ShippingRate.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $superSets = $this->superSetRepository->findWhere(['status' => 1]);
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], [
            'defaultCountry'    => config('app.default_country'),
            'superSets'         => $superSets,
            'flag'              => $flag,
            'pathInfo'          => $pathInfo
        ]);
    }

    /**
     * Store Shipping Rate
     *
     * @return response
     */
    public function store()
    {
        $this->validate(request(), [
            'tablerate_superset_id' => 'required|string',
            'country'               => 'required|string',
            'state'                 => 'string|nullable',
            'weight_from'           => 'required|min:0.0001',
            'weight_to'             => 'required|min:0.0001',
            'zip_from'              => 'required|string',
            'zip_to'                => 'required|string',
            'price'                 => 'required|min:0.0001',
            'is_zip_range'          => 'sometimes',
            'zip_code'              => 'string|nullable',
        ]);

        $data = request()->all();

        if ( $data['weight_from'] >= $data['weight_to'] ) {
            session()->flash('error', trans('tablerate::app.admin.shipping-rates.invalid-weight'));

            return redirect()->back();
        }

        $shippingRates = $this->shippingRateRepository->getMatchData($data);

        if ( count($shippingRates) ) {
            foreach ($shippingRates as $shipping_rate) {
                if (
                    ($shipping_rate->weight_from <= $data['weight_from'] && $shipping_rate->weight_to <= $data['weight_from']) ||
                    ($shipping_rate->weight_from <= $data['weight_to'] && $shipping_rate->weight_to <= $data['weight_to']) ||
                    ($shipping_rate->weight_from >= $data['weight_from'] && $shipping_rate->weight_from <= $data['weight_to']) ||
                    ($shipping_rate->weight_to >= $data['weight_from'] && $shipping_rate->weight_to <= $data['weight_to'])
                    ) {
                    session()->flash('error', trans('tablerate::app.admin.shipping-rates.weight-range-exist',
                    ['name' => 'Shipping Rate']));

                    return redirect()->back();
                }
            }
        }

        $this->shippingRateRepository->create($data);

        session()->flash('success',trans('tablerate::app.admin.shipping-rates.create-success', ['name' => 'Shipping Rate']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Edit Shipping Rate page .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $superSets = $this->superSetRepository->findWhere(['status' => 1]);

        $shippingRate = $this->shippingRateRepository->findOrFail($id);
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], [
            'defaultCountry'    => config('app.default_country'),
            'superSets'         => $superSets,
            'shippingRate'      => $shippingRate,
            'flag'              => $flag,
            'pathInfo'          => $pathInfo
        ]);
    }

    /**
     * update Shipping Rate
     *
     * @return response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'tablerate_superset_id' => 'required|string',
            'country'               => 'required|string',
            'state'                 => 'string|nullable',
            'weight_from'           => 'required|min:0.0001',
            'weight_to'             => 'required|min:0.0001',
            'zip_from'              => 'required|string',
            'zip_to'                => 'required|string',
            'price'                 => 'required|min:0.0001',
            'is_zip_range'          => 'sometimes',
            'zip_code'              => 'string|nullable',
        ]);

        $data = request()->all();

        if ( $data['weight_from'] >= $data['weight_to'] ) {
            session()->flash('error', trans('tablerate::app.admin.shipping-rates.invalid-weight'));

            return redirect()->back();
        }

        $data['shipping_rate_id']   = $id;

        $shippingRates = $this->shippingRateRepository->getMatchData($data);

        if ( count($shippingRates) ) {
            foreach ($shippingRates as $shipping_rate) {
                if (
                    ($shipping_rate->weight_from <= $data['weight_from'] && $shipping_rate->weight_to <= $data['weight_from']) ||
                    ($shipping_rate->weight_from <= $data['weight_to'] && $shipping_rate->weight_to <= $data['weight_to']) ||
                    ($shipping_rate->weight_from >= $data['weight_from'] && $shipping_rate->weight_from <= $data['weight_to']) ||
                    ($shipping_rate->weight_to >= $data['weight_from'] && $shipping_rate->weight_to <= $data['weight_to'])
                    ) {
                    session()->flash('error', trans('tablerate::app.admin.shipping-rates.weight-range-exist',
                    ['name' => 'Shipping Rate']));

                    return redirect()->back();
                }
            }
        }

        $this->shippingRateRepository->update($data, $id);

        session()->flash('success', trans('tablerate::app.admin.shipping-rates.update-success', ['name' => 'Shipping Rate']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Delete ShippingRate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->shippingRateRepository->delete($id);

            session()->flash('success', trans('tablerate::app.admin.superset-rates.delete-success',
        ['name' => 'Shipping Rate']));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Shipping Rate']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * MassDelete ShippingRate Method
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

                    $this->shippingRateRepository->delete($value);

                } catch (\Exception $e) {
                    report($e);

                    $suppressFlash = true;

                    continue;
                }
            }

            if ($suppressFlash) {
                session()->flash('success', trans('tablerate::app.admin.shipping-rates.mass-delete-success'));
            } else {
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'shipping rates']));
            }

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }

    /**
     * Method to Download The Sample File.
     *
     */
    public function sampleDownload()
    {
        $pathToFile=base_path(). "/packages/Webkul/TableRate/src/Resources/views/sampledownload/sampleupload.csv";

        return response()->download($pathToFile);
    }

    /**
     * import function for the upload CSV
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $success_records = $failedRules = [];
        $valid_extension = ['csv'];
        $file_extension[] = request()->file('file')->getClientOriginalExtension();

        if ($file_extension != $valid_extension) {
            session()->flash('error', trans('tablerate::app.admin.shipping-rates.upload-error'));
        } else {
            $excelData = (new DataGridImport)->toArray(request()->file('file'));

            foreach ($excelData as $tableRateData) {
                foreach ($tableRateData as $column => $data) {
                    $ifAlreadyExist = 0;

                    $validator = Validator::make($data, [
                        'superset_code' => 'required|string',
                        'country'       => 'required|string',
                        'state'         => 'nullable|string',
                        'zip_from'      => 'nullable',
                        'zip_to'        => 'required_with:zip_from',
                        'weight_from'   => 'required|min:0.0001',
                        'weight_to'     => 'required|min:0.0001',
                        'price'         => 'required|min:0',
                        'is_zip_range'  => 'sometimes',
                        'zip_code'      => 'nullable'
                    ]);

                    if ( $validator->fails() ) {
                        $failedRules[$column+1] = $validator->errors();

                        continue;
                    } else {
                        $super_set = $this->superSetRepository->findOneWhere(['code' => $data['superset_code']]);

                        if ( isset($super_set->id) ) {
                            $data['tablerate_superset_id']  = $super_set->id;

                            if ( $data['weight_from'] >= $data['weight_to'] ) {
                                $failedRules[$column+1] = trans('tablerate::app.admin.shipping-rates.invalid-weight');

                                continue;
                            }

                            $shippingRates = $this->shippingRateRepository->getMatchData($data);

                            if ( count($shippingRates) ) {
                                foreach ($shippingRates as $shipping_rate) {
                                    if (
                                        ($shipping_rate->weight_from <= $data['weight_from'] && $shipping_rate->weight_to <= $data['weight_from']) ||
                                        ($shipping_rate->weight_from <= $data['weight_to'] && $shipping_rate->weight_to <= $data['weight_to']) ||
                                        ($shipping_rate->weight_from >= $data['weight_from'] && $shipping_rate->weight_from <= $data['weight_to']) ||
                                        ($shipping_rate->weight_to >= $data['weight_from'] && $shipping_rate->weight_to <= $data['weight_to'])
                                        ) {
                                        $ifAlreadyExist +=1;

                                        break;
                                    }
                                }
                            }

                            if ( $ifAlreadyExist > 0 ) {
                                $failedRules[$column+1] = trans('tablerate::app.admin.shipping-rates.rate-range-exists', [
                                    'weight_from'   => $data['weight_from'],
                                    'weight_to'     => $data['weight_to'],
                                    'zip_from'      => $data['zip_from'],
                                    'zip_to'        => $data['zip_to'],
                                ]);

                                continue;
                            } else {
                                $shipping_rate = $this->shippingRateRepository->create($data);
                                if ( $shipping_rate ) {
                                    $success_records[$column+1] = trans('tablerate::app.admin.shipping-rates.rate-added-success');

                                    continue;
                                }
                            }
                        } else {
                            $failedRules[$column+1] = trans('tablerate::app.admin.supersets.no-superset-exists', [
                                'superset_code' => $data['superset_code'],
                            ]);

                            continue;
                        }
                    }
                }
            }
        }

        foreach ($failedRules as $column => $message) {
            $failedRules[$column] = $message . ' (At Row ' . $column . ')';
        }

        if (!empty($failedRules)) {
            session()->flash('error', implode(" ", $failedRules));
        }

        foreach ($success_records as $column => $message) {
            $success_records[$column] = $message . ' (At Row ' . $column . ')';
        }

        if (!empty($success_records)) {
            session()->flash('success', implode(" ", $success_records));
        }

        return redirect()->route($this->_config['redirect']);
    }
}
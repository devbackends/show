<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Illuminate\Support\Facades\Event;
use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\Super\SuperConfigRepository;
use Webkul\SAASCustomizer\Http\Requests\ConfigurationForm;
use Webkul\Core\Tree;
use Illuminate\Support\Facades\Storage;
use Webkul\SAASCustomizer\Models\Predefined\Mmc;
use Webkul\SAASCustomizer\Models\Predefined\BusinessType;
use Devvly\OnBoarding\Models\Pricing;


/**
 * Super Configuration controller
 *
 * @author    Mohamad Kabalan <mohamad@devvly.com>
 */
class PredefinedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * SuperConfigRepository object
     *
     * @var array
     */
    protected $superConfigRepository;

    /**
     *
     * @var array
     */
    protected $configTree;


    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\SAASCustomizer\Repositories\Super\SuperConfigRepository $superConfigRepository
     * @return void
     */
    public function __construct(SuperConfigRepository $superConfigRepository, Mmc $mmc, BusinessType $businessType, Pricing $pricing)
    {
        $this->middleware('super-admin');

        $this->superConfigRepository = $superConfigRepository;

        $this->_config = request('_config');
        $this->mmc = $mmc;
        $this->businessType = $businessType;
        $this->pricing = $pricing;
        $this->prepareConfigTree();

    }

    /**
     * Prepares config tree
     *
     * @return void
     */
    public function prepareConfigTree()
    {
        $tree = Tree::create();

        foreach (config('company') as $item) {
            $tree->add($item);
        }

        $tree->items = core()->sortItems($tree->items);

        $this->configTree = $tree;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        return view($this->_config['view'], ['config' => $this->configTree]);
    }

    public function addMmc()
    {

        return view($this->_config['view'], ['config' => $this->configTree]);
    }

    public function insertMmc()
    {

        $this->validate(request(), [
            'code' => 'required|max:255',
            'name' => 'required|max:255'
        ]);

        $data = request()->all();

        $mmc = new mmc();
        if ($mmc) {
            $mmc->code = $data['code'];
            $mmc->name = $data['name'];
            $mmc->status = $data['status'];

            $result = $mmc->save();

            if ($result) {
                session()->flash('success', trans('MMC Added successully'));
            }
        }

        return redirect()->back();

    }

    public function editMmc($id)
    {
        $mmc = $this->mmc->findOrFail($id);

        return view('saas::super.predefined.mmc.edit')->with('mmc', $mmc);
    }

    public function storeMmc($id)
    {

        $this->validate(request(), [
            'code' => 'required|max:255',
            'name' => 'required|max:255'
        ]);

        $data = request()->all();
        $mmc = $this->mmc->findOrFail($id);
        if ($mmc) {
            $result = $mmc->update($data);
            if ($result) {
                session()->flash('success', trans('MMC updated successully'));
            }
        }

        return redirect()->back();

    }

    public function deleteMmc($id)
    {
        $mmc = $this->mmc->findOrFail($id);


        $delete = $mmc->delete($id);

        if ($delete) {
            session()->flash('success', 'MMC deleted succesfully');

            return response()->json(['message' => true], 200);
        }


        return response()->json(['message' => false], 400);
    }

    public function showBusinessType()
    {

        return view($this->_config['view'], ['config' => $this->configTree]);
    }

    public function addBusinessType()
    {

        return view($this->_config['view'], ['config' => $this->configTree]);
    }

    public function insertBusinessType()
    {

        $this->validate(request(), [
            'business_type' => 'required|max:255'
        ]);

        $data = request()->all();

        $businessType = new BusinessType();
        if ($businessType) {
            $businessType->business_type = $data['business_type'];
            $businessType->status = $data['status'];

            $result = $businessType->save();

            if ($result) {
                session()->flash('success', trans('Business Type Added successully'));
            }
        }

        return redirect()->back();

    }

    public function editBusinessType($id)
    {

        $businessType = $this->businessType->findOrFail($id);

        return view('saas::super.predefined.business-type.edit')->with('businessType', $businessType);
    }

    public function storeBusinessType($id)
    {

        $this->validate(request(), [
            'business_type' => 'required|max:255'
        ]);

        $data = request()->all();
        $businessType = $this->businessType->findOrFail($id);
        if ($businessType) {
            $result = $businessType->update($data);
            if ($result) {
                session()->flash('success', trans('Business Type updated successully'));
            }
        }

        return redirect()->back();

    }

    public function deleteBusinessType($id)
    {
        $businessType = $this->businessType->findOrFail($id);


        $delete = $businessType->delete($id);

        if ($delete) {
            session()->flash('success', 'Business Type deleted succesfully');

            return response()->json(['message' => true], 200);
        }


        return response()->json(['message' => false], 400);
    }

    public function editPricing()
    {

        /*    $pricing = $this->Pricing->findOrFail($id);*/
        $pricing = Pricing::first();

        return view('saas::super.predefined.pricing.edit')->with('pricing', $pricing);
    }

    public function storePricing()
    {
        $pricing = Pricing::first();
        $this->validate(request(), [
            'flat_rate' => 'required|numeric|min:0|max:100',
            'express_merchant_funding' => 'required|numeric|min:0|max:100',
            'settlement' => 'required|string',
            "whalut_fee" => 'required|numeric|min:0|max:100',
            "authorization_fee" => 'required|numeric|min:0|max:100',
            "capture_settlement_fee" => 'required|numeric|min:0|max:100',
            "american_express_auth_fee" => 'required|numeric|min:0|max:100',
            "pin_based_debit_transaction_fee" => 'required|numeric|min:0|max:100',
            "ebt_transaction_fee" => 'required|numeric|min:0|max:100',
            "batch_fee" => 'required|numeric|min:0|max:100',
            "ivr" => 'required|numeric|min:0|max:100',
            "voice_authorization" => 'required|numeric|min:0|max:100',
            "monthly_statement_fee" => 'required|numeric|min:0|max:100',
            "monthly_account_fee" => 'required|numeric|min:0|max:100',
            "monthly_help_desk_fee" => 'required|numeric|min:0|max:100',
            "monthly_compass_fee" => 'required|numeric|min:0|max:100',
            "monthly_data_guardian_fee" => 'required|numeric|min:0|max:100',
            "non_complete_pci_fee" => 'required|numeric|min:0|max:100',
            "end_billing_option_fee" => 'required|numeric|min:0|max:100',
            "quest_virtual_terminal" => 'required|numeric|min:0|max:100',
            "paylink" => 'required|numeric|min:0|max:100',
            "setup_fee" => 'required|numeric|min:0|max:100',
            "update_fee" => 'required|numeric|min:0|max:100',
            "annual_regulatory_reporting_fee" => 'required|numeric|min:0|max:100',
            "chargback_fee" => 'required|numeric|min:0|max:100',
            "retrieval_fee" => 'required|numeric|min:0|max:100',
            "keyed_application_fee" => 'required|numeric|min:0|max:100',
            "inactivity_fee" => 'required|numeric|min:0|max:100',
            "discount_billed_to_merchant" => 'required|numeric|min:0|max:100',
            "helpdesk_calls_for_non_supported_terminals" => 'required|numeric|min:0|max:100',
            "voyager_capture" => 'required|numeric|min:0|max:100',
            "wright_express" => 'required|numeric|min:0|max:100',
            "monthly_wireless_access_fee" => 'required|numeric|min:0|max:100',
            "express_merchant_funding_fee" => 'required|numeric|min:0|max:100',
            "host_capture_fees" => 'required|numeric|min:0|max:100',
            "host_capture_monthly_fee" => 'required|numeric|min:0|max:100',
            "host_capture_transaction_fee" => 'required|numeric|min:0|max:100',
            "host_capture_administrative_transaction_fee" => 'required|numeric|min:0|max:100',
        ]);


        $data = request()->all();


        $result = $pricing->update($data);
        if ($result) {
            session()->flash('success', trans('Pricing updated successully'));
        }


        return redirect()->back();

    }

}
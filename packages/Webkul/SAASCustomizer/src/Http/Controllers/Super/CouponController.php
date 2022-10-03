<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Illuminate\Support\Facades\Event;
use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\Super\SuperConfigRepository;
use Webkul\SAASCustomizer\Http\Requests\ConfigurationForm;
use Webkul\Core\Tree;
use Illuminate\Support\Facades\Storage;
use Webkul\SAASCustomizer\Models\Coupon\Coupon;
use Webkul\SAASCustomizer\Models\Coupon\CouponType;


/**
 * Super Configuration controller
 *
 * @author    Mohamad Kabalan <mohamad@devvly.com>
 */
class CouponController extends Controller
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
    public function __construct(SuperConfigRepository $superConfigRepository, Coupon $coupon, CouponType $couponType)
    {
        $this->middleware('super-admin');

        $this->superConfigRepository = $superConfigRepository;

        $this->_config = request('_config');

        $this->coupon = $coupon;
        $this->CouponType = $couponType;

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

    public function addCoupon()
    {
        $couponType = $this->CouponType->all();
        return view($this->_config['view'], ['config' => $this->configTree])->with('couponType', $couponType);
    }

    public function insertCoupon()
    {

        $this->validate(request(), [

            'name' => 'required|max:255',
            'description' => 'required|string|max:255',
            'coupon_code' => 'required|string|max:10',
            'starts_from' => 'date',
            'ends_till' => 'date',
            'status' => 'required|numeric|min:0|max:1',
            'coupon_type' => 'required|string|max:50',
            'usage_per_customer' => 'required|numeric|min:1',
            'uses_per_coupon' => 'required|numeric|min:1',
            'action_type' => 'required|string|max:50',
            'discount_amount' => 'required|numeric|min:0'
        ]);

        $data = request()->all();

        $coupon = new Coupon();
        if ($coupon) {
            $coupon->name = $data['name'];
            $coupon->description = $data['description'];
            $coupon->coupon_code = $data['coupon_code'];

            $coupon->starts_from = $data['starts_from'];
            $coupon->ends_till = $data['ends_till'];
            $coupon->status = $data['status'];

            $coupon->coupon_type = $data['coupon_type'];
            $coupon->usage_per_customer = $data['usage_per_customer'];
            $coupon->uses_per_coupon = $data['uses_per_coupon'];

            $coupon->action_type = $data['action_type'];
            $coupon->discount_amount = $data['discount_amount'];

            $result = $coupon->save();

            if ($result) {
                session()->flash('success', trans('Coupon Added successully'));
            }
        }

        return redirect()->back();

    }

    public function editCoupon($id)
    {
        $coupon = $this->coupon->findOrFail($id);
        $couponType = $this->CouponType->all();

        return view('saas::super.coupons.coupon.edit')->with('coupon', $coupon)->with('couponType', $couponType);
    }

    public function storeCoupon($id)
    {

        $this->validate(request(), [

            'name' => 'required|max:255',
            'description' => 'required|string|max:255',
            'coupon_code' => 'required|string|min:4|max:6',
            'starts_from' => 'date',
            'ends_till' => 'date',
            'status' => 'required|numeric|min:0|max:1',
            'coupon_type' => 'required|string|max:50',
            'usage_per_customer' => 'required|numeric|min:1',
            'uses_per_coupon' => 'required|numeric|min:1',
            'action_type' => 'required|string|max:50',
            'discount_amount' => 'required|numeric|min:0'
        ]);

        $data = request()->all();
        $coupon = $this->coupon->findOrFail($id);
        if ($coupon) {
            $result = $coupon->update($data);
            if ($result) {
                session()->flash('success', trans('Coupon updated successully'));
            }
        }

        return redirect()->back();

    }

    public function deleteCoupon($id)
    {
        $coupon = $this->coupon->findOrFail($id);


        $delete = $coupon->delete($id);

        if ($delete) {
            session()->flash('success', 'Coupon deleted succesfully');

            return response()->json(['message' => true], 200);
        }


        return response()->json(['message' => false], 400);
    }

    public function couponMassDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                $coupon = $this->coupon->find($value);

                try {

                    $coupon->delete($value);
                } catch (\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (!$suppressFlash)
                session()->flash('success', 'Coupons Deleted succesfully');
            return redirect()->back();
        }
    }

    public function checkCouponCode()
    {
        $data = request()->all();

        $coupon = Coupon::where('coupon_code', $data['coupon_code'])->first();
        if ($coupon) {
            return json_encode(array("status" => 1));
        } else {
            return json_encode(array("status" => 0));
        }

    }

    public function showCouponTypes()
    {

        return view($this->_config['view'], ['config' => $this->configTree]);
    }

    public function addCouponType()
    {

        return view($this->_config['view'], ['config' => $this->configTree]);
    }

    public function insertCouponType()
    {
        $this->validate(request(), [

            'type' => 'required|string|max:20',
            'description' => 'required|string|max:255'
        ]);

        $data = request()->all();

        $coupontype = new CouponType();
        if ($coupontype) {
            $coupontype->type = $data['type'];
            $coupontype->description = $data['description'];
            $coupontype->status = $data['status'];

            $result = $coupontype->save();

            if ($result) {
                session()->flash('success', trans('Coupon Type Added successully'));
            }
        }

        return redirect()->back();
    }

    public function editCouponType($id)
    {
        $couponType = $this->CouponType->findOrFail($id);


        return view('saas::super.coupons.coupon_type.edit')->with('couponType', $couponType);
    }

    public function storeCouponType($id)
    {

        $this->validate(request(), [
            'type' => 'required|string|max:20',
            'description' => 'required|string|max:255'
        ]);

        $data = request()->all();
        $couponType = $this->CouponType->findOrFail($id);

        if ($couponType) {
            $result = $couponType->update($data);
            if ($result) {
                session()->flash('success', trans('Coupon Type updated successully'));
            }
        }

        return redirect()->back();

    }

    public function deleteCouponType($id)
    {
        $couponType = $this->CouponType->findOrFail($id);


        $delete = $couponType->delete($id);

        if ($delete) {
            session()->flash('success', 'Coupon deleted succesfully');

            return response()->json(['message' => true], 200);
        }


        return response()->json(['message' => false], 400);
    }

    public function couponTypeMassDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                $couponType = $this->CouponType->find($value);

                try {

                    $couponType->delete($value);
                } catch (\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (!$suppressFlash)
                session()->flash('success', 'Coupons Deleted succesfully');
            return redirect()->back();
        }
    }

}
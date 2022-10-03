<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Admin;

use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\SAASCustomizer\Http\Controllers\Controller;

class CompanyAddressController extends Controller
{
    protected $_config;

    /**
     * CompanyAddressController constructor.
     */
    public function __construct()
    {
        $this->_config = request('_config');

        $this->middleware('auth:admin');
    }

    /**
     * To load the company Address index
     *
     * @return View
     */
    public function index(): View
    {
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], compact('flag', 'pathInfo'));
    }

    /**
     * To load the company Address create
     *
     * @return View
     */
    public function create(): View
    {
        $pathInfo = request()->getPathInfo();
        $flag = 1;

        return view($this->_config['view'], [
            'defaultCountry' => config('app.default_country'),
            'flag' => $flag,
            'pathInfo' => $pathInfo
        ]);
    }

    /**
     * To store company Address
     *
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        request()->validate([
            'address1' => 'required|string|max:160',
            'address2' => 'nullable|string|max:160',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'nullable',
            'zip_code' => 'required|string',
            'phone' => 'required|string'
        ]);

        $data = request()->all();

        $address = new Address(array_except($data, ['_token']));

        if ($address->save()) {
            session()->flash('success', trans('saas::app.admin.tenant.address.create-success'));
        } else {
            session()->flash('error', trans('saas::app.admin.tenant.create-failed', [
                'attribute' => 'address'
            ]));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To load the company Address edit
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $address = Address::query()->findOrFail($id);

        $defaultCountry = config('app.default_country');
        $pathInfo = request()->getPathInfo();
        $flag = 1;

        return view($this->_config['view'])->with(compact('address', 'defaultCountry', 'flag', 'pathInfo'));
    }

    /**
     * To update the company address details
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(int $id): RedirectResponse
    {
        $data = request()->all();

        $address = Address::query()->findOrFail($id);

        if ($address->update(array_except($data, ['_token']))) {
            session()->flash('success', trans('saas::app.admin.tenant.address.update-success'));
        } else {
            session()->flash('error', trans('saas::app.admin.tenant.update-failed'));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To delete company address resource
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    protected function destroy(int $id): JsonResponse
    {
        if (Address::query()->where('id', '=', $id)->delete()) {
            session()->flash('success', trans('saas::app.admin.tenant.delete-success', ['resource' => 'Company address']));

            return response()->json(['message' => true]);
        } else {
            session()->flash('error', trans('saas::app.admin.tenant.delete-failed', ['resource' => 'Company address']));

            return response()->json(['message' => false]);
        }
    }
}
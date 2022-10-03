<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Models\CoreConfig;

class CompanyProfileController extends Controller
{
    protected $_config;

    /**
     * CompanyProfileController constructor.
     */
    public function __construct()
    {
        $this->_config = request('_config');

        $this->middleware('auth:admin');
    }

    /**
     * To load the company profile index view
     */
    public function index()
    {
        $coreConfigs = CoreConfig::query()->where('code', 'like', 'company.%')->get();
        $details = [];
        foreach ($coreConfigs as $configConfig) {
            $key = explode('.', $configConfig->code)[1];
            $details[$key] = $configConfig->value;
        }

        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], compact('details', 'flag', 'pathInfo'));
    }

    /**
     * To update the company profile details
     *
     * @return RedirectResponse Redirect
     */
    public function update(): RedirectResponse
    {
        $data = request()->validate([
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'skype' => 'string|min:6|max:32',
            'phone' => 'required|string'
        ]);

        foreach ($data as $key => $item) {
            CoreConfig::query()->where('code', '=', 'company.'.$key)->update(['value' =>$item]);
        }

        session()->flash('success', trans('saas::app.admin.tenant.update-success', ['resource' => 'Company profile']));

        return redirect()->route($this->_config['redirect']);
    }
}
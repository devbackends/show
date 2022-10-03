<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\User\Repositories\AdminRepository as Admin;
use Webkul\User\Repositories\RoleRepository as Role;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\SAASCustomizer\Helpers\StatsPurger;

use DB;
use Request;
use Validator;

/**
 * Tenant controller
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TenantController extends Controller
{
    protected $attribute;
    protected $_config;
    protected $details;
    protected $admin;
    protected $role;
    protected $productRepository;
    protected $companyStats;

    const FREE_THEMES = [
      'default',
      'velocity'
    ];

    public function __construct(
        Admin $admin,
        Role $role,
        ProductRepository $productRepository,
        StatsPurger $companyStats
    ) {
        $this->admin = $admin;

        $this->role = $role;

        $this->productRepository = $productRepository;

        $this->companyStats = $companyStats;

        $this->_config = request('_config');

        $this->middleware('auth:super-admin', ['only' => ['showCompanyStats', 'edit', 'update', 'list']]);

        if (! Company::isAllowed()) {
            throw new \Exception('not_allowed_to_visit_this_section', 400);
        }
    }

    public function list()
    {
        return view($this->_config['view']);
    }

    public function showCompanyStats($id)
    {
        $aggregates = $this->companyStats->getAggregates($id);

        return view($this->_config['view'])->with('company', [$aggregates]);
    }

    public function create()
    {
        return view($this->_config['view']);
    }

    public function edit($id)
    {
        return view($this->_config['view']);
    }

    public function update($id)
    {
        $this->validate(request(), [
            'email' => 'email|max:191|unique:companies,email,'.$id,
            'name' => 'required|string|max:191|unique:companies,name,'.$id,
            'domain' => 'required|string|max:191|unique:companies,domain,'.$id,
            'is_active' => 'required|boolean'
        ]);

        session()->flash('success', trans('saas::app.tenant.registration.company-updated'));

        return redirect()->back();
    }

    protected function changeStatus($id)
    {
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Company']));
        return response()->json(['message' => true], 200);

    }

    /**
     * Remove the specified resources from database
     *
     * @return response \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'companies']));

        return redirect()->back();

    }
}
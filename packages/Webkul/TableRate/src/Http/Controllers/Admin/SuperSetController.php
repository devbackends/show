<?php

namespace Webkul\TableRate\Http\Controllers\Admin;

use Webkul\TableRate\Repositories\SuperSetRepository;

/**
 * SuperSetController Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SuperSetController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SuperSetRepository object
     *
     * @var array
    */
    protected $superSetRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\TableRate\Repositories\SuperSetRepository $superSetRepository
     * @return void
     */
    public function __construct(SuperSetRepository $superSetRepository)
    {
        $this->_config = request('_config');

        $this->superSetRepository = $superSetRepository;
    }

    /**
     * Method to populate the superSet page.
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
     * create new  superset
     *
     * @return response
     */
    public function create()
    {
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], compact('flag', 'pathInfo'));
    }

     /**
     * Method to store the shipping Method.
     *
     * @return Mixed
     */
    public function store()
    {
        $this->validate(request(), [
            'code'  => ['required', 'unique:tablerate_supersets,code'],
            'name'  => 'required',
        ]);

        $data = request()->all();

        if ( ! isset($data['status']) ) {
            $data['status'] = 0;
        }

        $this->superSetRepository->create($data);

        session()->flash('success', trans('tablerate::app.admin.supersets.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Method to edit the shipping Method.
     *
     * @return Mixed
     */
    public function edit($id)
    {
        $superset = $this->superSetRepository->findOrFail($id);
        $flag = 1;
        $pathInfo = request()->getPathInfo();

        return view($this->_config['view'], compact('superset', 'flag', 'pathInfo'));
    }

    /**
     * Method to Update the shipping Method.
     *
     * @return Mixed
     */
    public function update($id)
    {
        $this->validate(request(), [
            'code'  => ['required', 'unique:tablerate_supersets,code,' . $id],
            'name'  => 'required',
        ]);

        $data = request()->all();

        if (! isset($data['status']) ) {
            $data['status'] = 0;
        }

        $this->superSetRepository->update($data, $id);

        session()->flash('success',trans('tablerate::app.admin.supersets.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Delete Super set from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->superSetRepository->delete($id);

            session()->flash('success', trans('tablerate::app.admin.supersets.delete-success'));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'SuperSet']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * MassDelete SuperSet
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

                    $this->superSetRepository->delete($value);

                } catch (\Exception $e) {
                    report($e);

                    $suppressFlash = true;

                    continue;
                }
            }

            if ($suppressFlash) {
                session()->flash('success', trans('tablerate::app.admin.supersets.mass-delete-success'));
            } else {
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'supersets']));
            }

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }

    /**
     * Method to Mass Update the Superset.
     *
     * @return Mixed
     */
    public function massupdate()
    {
        $suppressFlash = false;
        $data = request()->all();

        if (request()->isMethod('post') && isset($data['massaction-type']) && $data['massaction-type'] == 'update') {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    $suppressFlash = true;
                    $this->superSetRepository->update([
                        'status' => $data['update-options']
                    ], $value);

                } catch (\Exception $e) {
                    report($e);

                    $suppressFlash = true;

                    continue;
                }
            }

            if ($suppressFlash) {
                session()->flash('success', trans('tablerate::app.admin.supersets.mass-update-success'));
            } else {
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'supersets']));
            }

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}
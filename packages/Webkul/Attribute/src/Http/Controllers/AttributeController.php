<?php

namespace Webkul\Attribute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use DB;
class AttributeController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AttributeRepository object
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * AttributeRepository object
     *
     * @var AttributeOptionRepository
     */
    protected $attributeOptionRepository;

    /**
     * Create a new controller instance.
     *
     * @param AttributeRepository $attributeRepository
     * @param AttributeOptionRepository $attributeOptionRepository
     */
    public function __construct(AttributeRepository $attributeRepository, AttributeOptionRepository $attributeOptionRepository)
    {
        $this->attributeRepository = $attributeRepository;

        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code'       => ['required', 'unique:attributes,code', new \Webkul\Core\Contracts\Validations\Code],
            'admin_name' => 'required',
            'type'       => 'required',
        ]);

        $data = request()->all();

        $data['is_user_defined'] = 1;

        $attribute = $this->attributeRepository->create($data);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Attribute']));

        return redirect()->route($this->_config['redirect']);
    }
    public function createAttributeOptions(){
         $data = request()->all();
         if(isset($data['attribute_id']) and isset($data['variant_option'])){

             $attr_option = app('Webkul\Attribute\Repositories\AttributeOptionRepository')->findwhere(['admin_name' => $data['variant_option'], 'attribute_id' => $data['attribute_id']])->first();
             if($attr_option){
                 return json_encode(array('status'=>'found'));
             }
             $attribute_option_id=DB::table('attribute_options')->insertGetId(
                 [
                     'admin_name' => $data['variant_option'],
                     'sort_order' => 1,
                     'attribute_id' => $data['attribute_id'],
                 ]);

             DB::table('attribute_option_translations')->insert(
                 [
                     'locale' => 'en',
                     'label' => ucfirst($data['variant_option']),
                     'attribute_option_id' => $attribute_option_id,
                 ]);

         }
         return json_encode(array('id'=>$attribute_option_id,'option'=>$data['variant_option'],'status'=>'success'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        return view($this->_config['view'], compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {


        $this->validate(request(), [
            'code'       => ['required', 'unique:attributes,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'admin_name' => 'required',
            'type'       => 'required',
        ]);


        $attribute = $this->attributeRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Attribute']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Update option sort order.
     *
     * @param Request $request
     */
    public function updateOptionSortOrder(Request $request) {

        $data = json_decode($request->input('data'),1);

        if (json_last_error() != JSON_ERROR_NONE) echo 'error';

        foreach ($data as $item) {
            $model = $this->attributeOptionRepository->find($item['id']);
            $model->update(['sort_order' => $item['sort_order']]);
        }

        response('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        if (! $attribute->is_user_defined) {
            session()->flash('error', trans('admin::app.response.user-define-error', ['name' => 'Attribute']));
        } else {
            try {
                $this->attributeRepository->delete($id);

                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Attribute']));

                return response()->json(['message' => true], 200);
            } catch(\Exception $e) {
                session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Attribute']));
            }
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Remove the specified resources from database
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                $attribute = $this->attributeRepository->find($value);

                try {
                    if ($attribute->is_user_defined) {
                        $suppressFlash = true;

                        $this->attributeRepository->delete($value);
                    } else {
                        session()->flash('error', trans('admin::app.response.user-define-error', ['name' => 'Attribute']));
                    }
                } catch (\Exception $e) {
                    report($e);

                    $suppressFlash = true;

                    continue;
                }
            }

            if ($suppressFlash) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'attributes']));
            } else {
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'attributes']));
            }

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
    public function getAttributeOptions($attribute_id){
        $attribute=$this->attributeRepository->find($attribute_id);
        return $attribute->options;
    }
}

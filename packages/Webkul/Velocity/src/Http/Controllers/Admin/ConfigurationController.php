<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;

class ConfigurationController extends Controller
{
    /**
     * VelocityMetadataRepository object
     *
     * @var \Webkul\Velocity\Repositories\VelocityMetadataRepository
     */
    protected $velocityMetaDataRepository;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Velocity\Repositories\MetadataRepository $velocityMetaDataRepository
     * @return void
     */
    public function __construct(VelocityMetadataRepository $velocityMetadataRepository)
    {
        $this->_config = request('_config');

        $this->velocityHelper = app('Webkul\Velocity\Helpers\Helper');

        $this->velocityMetaDataRepository = $velocityMetadataRepository;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderMetaData()
    {
        $velocityMetaData = $this->velocityHelper->getVelocityMetaData();

        return view($this->_config['view'], [
            'metaData' => $velocityMetaData,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function storeMetaData($id)
    {
        // check if radio button value
        if (request()->get('slides') == "on") {
            $params = request()->all() + [
                    'slider' => 1,
                ];
        } else {
            $params = request()->all() + [
                    'slider' => 0,
                ];
        }

        $velocityMetaData = $this->velocityMetaDataRepository->findorFail($id);
        $params = request()->all() + ['slider' => 0];
        if (isset($params['laraberg'])) {
            $velocityMetaData->lb_content = $params['laraberg'];
            $velocityMetaData->save();

            unset($params['laraberg']);
        }

        if (isset($params['product_view_images'])) {
            foreach ($params['product_view_images'] as $index => $productViewImage) {
                if ($productViewImage !== "") {
                    $params['product_view_images'][$index] = $this->uploadImage($productViewImage, $index);
                }
            }

            $params['product_view_images'] = json_encode($params['product_view_images']);
        }

        $params['home_page_content'] = str_replace('=&gt;', '=>', $params['home_page_content']);

        unset($params['images']);
        unset($params['slides']);
        unset($params['image_order']);

        // update hero image and its link:
        $deleted = $this->deleteOldHeroImage($id, $velocityMetaData);
        $files = request()->allFiles();
        $hero_image = isset($files['path_hero_image']) && count($files['path_hero_image'])? $this->uploadHeroImage(array_first($files['path_hero_image'])): null;
        if ($hero_image || $deleted) {
            $params['path_hero_image'] = $hero_image;
        }
        if($hero_image || $velocityMetaData->path_hero_image){
            if (empty($params['hero_image_link'])) {
                $params['hero_image_link'] = null;
            }
        }

        $product = $this->velocityMetaDataRepository->update($params, $id);
        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Velocity Theme']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * @param array $data
     * @param int $index
     * @return mixed
     */
    public function uploadImage($data, $index)
    {
        $type = 'product_view_images';
        $request = request();

        $image = '';
        $file = $type . '.' . $index;
        $dir = "velocity/$type";

        if ($request->hasFile($file)) {
            Storage::delete($dir . $file);

            $image = $request->file($file)->store($dir);
        }

        return $image;
    }


    public function deleteOldHeroImage($id, $page){
        $data = request()->all();
        $image = isset($data['path_hero_image']) ? $data['path_hero_image']: null;
        $remove = true;
        $files = request()->allFiles();

        if(($image && count($image) && empty(array_first($image))) || isset($files['path_hero_image'])){
            $remove = false;
        }
        if($remove){
            Storage::delete($page->path_hero_image);
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     * @param int $index
     * @return mixed
     */
    public function uploadHeroImage(UploadedFile $file)
    {
        return Storage::put('hero_image', $file);
    }
}
<?php

namespace Webkul\Bulkupload\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use File;
/**
 * Product Image Reposotory
 *
 */
class ProductImageRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\ProductImage';
    }

    /**
     * @param array $data
     * @param mixed $product
     * @return mixed
     */
    public function uploadImages($data, $product)
    {
        $previousImageIds = $product->images()->pluck('id');

        if (isset($data['images'])) {
            foreach ($data['images'] as $imageId => $image) {
                $file = 'images.' . $imageId;
                $dir = 'product/' . $product->id;

                if (str_contains($imageId, 'image_')) {
                    if (request()->hasFile($file)) {
                        $this->create([
                                'path' => request()->file($file)->store($dir),
                                'product_id' => $product->id
                            ]);
                    }
                } else {
                    if (is_numeric($index = $previousImageIds->search($imageId))) {
                        $previousImageIds->forget($index);
                    }

                    if (request()->hasFile($file)) {
                        if ($imageModel = $this->find($imageId)) {
                         //   Storage::delete($imageModel->path);
                        }

                        $this->update([
                                'path' => request()->file($file)->store($dir)
                            ], $imageId);
                    }
                }
            }
        }

        foreach ($previousImageIds as $imageId) {
            if ($imageModel = $this->find($imageId)) {
               // Storage::delete($imageModel->path);

                $this->delete($imageId);
            }
        }
    }


    public function bulkuploadImages($data, $product, $imageZipName)
    {

        if (isset($data['images'])) {
            foreach($data['images'] as $key => $value) {

                if(strpos($value, "__MACOSX/._") !== false){
                    $value=str_replace("__MACOSX/._","",$value);
                }

                $files = "public/imported-products/extracted-images/admin/".$data['dataFlowProfileRecordId'].'/'. $imageZipName['dirname'].'/'.basename($value);

                if(strpos($files, "__MACOSX/._") !== false){ $files=str_replace("__MACOSX/._","",$files); }
                if(strpos($files, "__MACOSX/") !== false){ $files=str_replace("__MACOSX/","",$files); }

                $destination = "product/".$product->id.'/'.basename($value);

                Storage::put($destination, Storage::disk('local')->get($files));
             //   Storage::copy($files, $destination);


                $this->create([
                    'path' => 'product/' . $product->id .'/'. basename($value),
                    'product_id' => $product->id
                ]);
            }
        }
    }
}
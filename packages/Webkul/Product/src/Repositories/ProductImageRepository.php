<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Str;
use File;

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

    public function sortImagesOrder($data){
         if(is_array($data)){
             if(sizeof($data) > 0 ){
                 foreach ($data as $key => $image){
                     $this->update([
                         'sort_order' => $key
                     ], $image['id']);
                 }
             }
         }
         return response()->json(['code'=>200,'message'=>'success'],200);
    }

    /**
     * @param $data
     * @param $product
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function uploadImages($data, $product)
    {

        if (isset($data['images'])) {
            $counter = $this->findWhere(['product_id' => $product->id])->count();
            $count = 1;
            foreach ($data['images'] as $imageId => $image) {
                $dir = 'product/' . $product->id;
                if (Str::contains($imageId, 'image_')) {

                    // start resizing
                    $input['small_image'] = 'small_image_' . rand(10000000000, 1000000000000000000) . '.' . $image->extension();
                    $input['medium_image'] = 'medium_image_' . rand(10000000000, 1000000000000000000) . '.' . $image->extension();
                    $input['large_image'] = 'large_image_' . rand(10000000000, 1000000000000000000) . '.' . $image->extension();

                    $destinationPath = public_path('/storage/product/' . $product->id);
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    $width = 2000; // your max width
                    $height = 2000; // your max height
                    $large_image = Image::make($image);
                    $large_image->height() > $large_image->width() ? $width=null : $height=null;
                    $large_image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/' . $input['large_image']);

                    $width = 800; // your max width
                    $height = 800; // your max height
                    $medium_image = Image::make($image);
                    $medium_image->height() > $medium_image->width() ? $width=null : $height=null;
                    $medium_image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/' . $input['medium_image']);

                    $width = 200; // your max width
                    $height = 200; // your max height
                    $small_image = Image::make($image);
                    $small_image->height() > $small_image->width() ? $width=null : $height=null;
                    $small_image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/' . $input['small_image']);

                    //end resizing
                    Storage::put($dir . '/' . $input['small_image'], File::get($destinationPath . '/' . $input['small_image']));
                    Storage::put($dir . '/' . $input['medium_image'], File::get($destinationPath . '/' . $input['medium_image']));
                    Storage::put($dir . '/' . $input['large_image'], File::get($destinationPath . '/' . $input['large_image']));

                    unlink($destinationPath .'/' . $input['large_image']);
                    unlink($destinationPath .'/' . $input['medium_image']);
                    unlink($destinationPath .'/' . $input['small_image']);

                    $this->create(
                        [
                            'thumbnail' =>$dir.'/'.$input['small_image'] ,
                            'large_image'=>$dir.'/'.$input['large_image'],
                            'path' => $dir.'/'.$input['medium_image'],
                            'product_id' => $product->id,
                            'sort_order' => (int)$counter + (int)$count
                        ]
                    );
                }
                $count += 1;
            }
        }

    }


    public function removeImage($image_id)
    {
        if ($imageModel = $this->find($image_id)) {
            Storage::delete($imageModel->path);
            $this->delete($image_id);
            return response()->json(['code'=>200,'message' => 'Image Deleted Successfuly','images'=>$this->findWhere(['product_id'=>$imageModel->product_id])], 200);
        }
        return response()->json(['code'=>500,'message' => 'Image Deleted Successfuly'], 200);
    }

    //the below is used to upload image through api
    public function addProductImages($data){
        $previousImages=$this->findWhere(['product_id'=>$data['product_id']]);
        foreach ($previousImages as $previousImage){
            $this->delete($previousImage->id);
        }
        $counter=$this->findWhere(['product_id'=>$data['product_id']])->count();
        $count=1;
        $returnedImges=[];
        foreach ($data['images'] as  $image) {
            $dir = 'product/' . $data['product_id'];

                // start resizing
                $input['imagename'] = rand(10000000000,1000000000000000000).'.'.pathinfo(
                        parse_url($image, PHP_URL_PATH),
                        PATHINFO_EXTENSION
                    );;
                $destinationPath = public_path('/storage/product/'.$data['product_id']);
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $img = Image::make($image)->fit(800,800);
                $img->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);

                //end resizing
                $destination = $dir.'/'.$input['imagename'];
                Storage::put($destination, File::get($destinationPath.'/'.$input['imagename']));
                $createdImage=$this->create([
                    'path' =>  $destination,
                    'product_id' =>$data['product_id'],
                    'sort_order' => (int)$counter + (int)$count
                ]);
                array_push($returnedImges,$createdImage);

            $count+=1;
        }
        return response()->json([
            'status'  => 200 ,
            'message' => "Images Uploaded Successfully",
            'images' =>$returnedImges
        ],200);
    }
}

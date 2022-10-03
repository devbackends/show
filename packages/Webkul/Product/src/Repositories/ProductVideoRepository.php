<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;


class ProductVideoRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\ProductVideo';
    }

    /**
     * @param $data
     * @param $product
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateVideo($data, $product)
    {
        if(isset($data['video'])){
            $productVideo=$this->findWhere(['product_id' => $product->id])->first();
            if($productVideo){
                if(!empty($data['video'])){
                    $this->update([
                        'path' => $data['video']
                    ],$productVideo->id);
                }else{
                    $this->delete($productVideo->id);
                }
            }else{
                $this->create([
                    'path' => $data['video'],
                    'product_id' => $product->id
                ]);
            }
        }
    }

}

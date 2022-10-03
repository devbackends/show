<?php

namespace Webkul\Product\Helpers;

use Illuminate\Support\Facades\Storage;

class ProductImage extends AbstractProduct
{
    /**
     * Retrieve collection of gallery images
     *
     * @param \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat $product
     * @return array
     */
    public function getGalleryImages($product)
    {
        if (!$product) {
            return [];
        }

        $images = [];

        foreach ($product->images as $image) {
            if (!Storage::has($image->path)) {
                continue;
            }

            $images[] = [
                'small_image_url' => $image->thumbnail ? Storage::url($image->thumbnail) : Storage::url($image->path),
                'medium_image_url' => Storage::url($image->path),
                'large_image_url' => $image->large_image ? Storage::url($image->large_image) :  Storage::url($image->path),
                'original_image_url' => $image->large_image ? Storage::url($image->large_image) :  Storage::url($image->path),
                'sort_order' => (int)$image->sort_order
            ];
        }


        if (!$product->parent_id && !count($images)) {
            $images[] = [
                'small_image_url' => asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.png'),
                'medium_image_url' => asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.png'),
                'large_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.png'),
                'original_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.png'),
            ];
        }

        return $images;
    }

    /**
     * Get product's base image
     *
     * @param \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat $product
     * @return array
     */
    public function getProductBaseImage($product)
    {

        $images = $product ? isset($product->images) ? $product->images : null  : null;

        if ($images && $images->count()) {

            $image = [
                'small_image_url' =>  $images[0]->thumbnail ? Storage::url($images[0]->thumbnail) : Storage::url($images[0]->path),
                'medium_image_url' => Storage::url($images[0]->path),
                'large_image_url' => $images[0]->large_image ? Storage::url($images[0]->large_image) :  Storage::url($images[0]->path),
                'original_image_url' => $images[0]->large_image ? Storage::url($images[0]->large_image) :  Storage::url($images[0]->path)
            ];
        } else {
            $image = [
                'small_image_url' => asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.png'),
                'medium_image_url' => asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.png'),
                'large_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.png'),
                'original_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.png'),
            ];
        }

        return $image;
    }
}

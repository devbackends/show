<?php

namespace Webkul\Product\Type;

class Simple extends AbstractType
{
    /**
     * Skip attribute for simple product type
     *
     * @var array
     */
    protected $skipAttributes = [];

    /**
     * These blade files will be included in product edit page
     *
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.inventories',
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.channels',
        'admin::catalog.products.accordians.product-links',
    ];

    /**
     * Show quantity box
     *
     * @var bool
     */
    protected $showQuantityBox = true;

    protected $isSaleable = null;

    /**
     * Return true if this product type is saleable
     *
     * @return bool
     */
    public function isSaleable($seller=0)
    {
        if ($this->isSaleable !== null) return $this->isSaleable;
        $product=app('Webkul\Product\Repositories\ProductFlatRepository')->findwhere([['product_id', '=', $this->product->product_id]])->first();
        if($product){
            if(!$product->status){
                $this->isSaleable = false;
                return false;
            }
        }else{
            $this->isSaleable = false;
            return false;
        }

        if ($this->haveSufficientQuantity(1,$seller)) {
            $this->isSaleable = true;
            return true;
        }

        $this->isSaleable = false;
        return false;
    }

    /**
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity($qty,$vendor = 0)
    {

/*        $backorders = core()->getConfigData('catalog.inventory.stock_options.backorders');
        if(is_null($backorders)){
            $backorders=$qty <= $this->totalQuantity($vendor) ? true : false;
        }*/

        return $qty <= $this->totalQuantity() ? true : false;
    }
}
<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductGroupedProduct as ProductGroupedProductContract;

/**
 * Webkul\Product\Models\ProductGroupedProduct
 *
 * @property int $id
 * @property int $qty
 * @property int $sort_order
 * @property int $product_id
 * @property int $associated_product_id
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $associated_product
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductGroupedProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductGroupedProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductGroupedProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductGroupedProduct whereAssociatedProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductGroupedProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductGroupedProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductGroupedProduct whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductGroupedProduct whereSortOrder($value)
 * @mixin \Eloquent
 */
class ProductGroupedProduct extends Model implements ProductGroupedProductContract
{
    public $timestamps = false;
    
    protected $fillable = [
        'qty',
        'sort_order',
        'product_id',
        'associated_product_id',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the product that owns the image.
     */
    public function associated_product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
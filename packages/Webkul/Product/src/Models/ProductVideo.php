<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductVideo as ProductVideoContract;

/**
 * Webkul\Product\Models\ProductImage
 *
 * @property int $id
 * @property string|null $type
 * @property string $path
 * @property int $product_id
 * @property-read mixed $url
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductImage whereType($value)
 * @mixin \Eloquent
 */
class ProductVideo extends Model implements ProductVideoContract
{
    public $timestamps = false;

    protected $fillable = [
        'path',
        'product_id',
        'sort_order',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
    /**
     * @param string $key
     *
     * @return bool
     */
    public function isCustomAttribute($attribute)
    {
        return $this->attribute_family->custom_attributes->pluck('code')->contains($attribute);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['url'] = $this->url;

        return $array;
    }
}
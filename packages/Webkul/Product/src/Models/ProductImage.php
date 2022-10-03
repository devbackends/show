<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductImage as ProductImageContract;

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
class ProductImage extends Model implements ProductImageContract
{
    public $timestamps = false;

    protected $fillable = [
        'path',
        'thumbnail',
        'large_image',
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
     * Get image url for the product image.
     */
    public function url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the product image.
     */
    public function getUrlAttribute()
    {
        return $this->url();
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
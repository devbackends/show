<?php

namespace Webkul\Product\Models;

use Devvly\ElasticSearch\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Product\Contracts\ProductFlat as ProductFlatContract;

/**
 * Webkul\Product\Models\ProductFlat
 *
 * @property int $id
 * @property string $sku
 * @property string|null $name
 * @property string|null $description
 * @property string|null $url_key
 * @property int|null $new
 * @property int|null $featured
 * @property int|null $status
 * @property string|null $thumbnail
 * @property float|null $price
 * @property float|null $special_price
 * @property string|null $special_price_from
 * @property string|null $special_price_to
 * @property float|null $weight
 * @property string|null $created_at
 * @property string|null $locale
 * @property string|null $channel
 * @property int $product_id
 * @property string|null $updated_at
 * @property int|null $parent_id
 * @property int|null $visible_individually
 * @property float|null $min_price
 * @property float|null $max_price
 * @property string|null $short_description
 * @property string|null $meta_title
 * @property string|null $meta_keywords
 * @property int|null $is_listing_fee_charged
 * @property int|null $is_seller_new
 * @property int|null $is_seller_featured
 * @property int|null $is_seller_approved
 * @property string|null $shipping_type
 * @property int|null $quantity
 * @property int|null $ordered_quantity
 * @property int|null $marketplace_seller_id
 * @property-read mixed $attribute_family
 * @property-read mixed $images
 * @property-read mixed $reviews
 * @property-read mixed $type
 * @property-read \Webkul\Product\Models\ProductFlat|null $parent
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductFlat[] $variants
 * @property-read int|null $variants_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereMaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereSpecialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereSpecialPriceFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereSpecialPriceTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereUrlKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereVisibleIndividually($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductFlat whereWeight($value)
 * @mixin \Eloquent
 */
class ProductFlat extends Model implements ProductFlatContract
{

    use Searchable;

    protected $table = 'product_flat';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $timestamps = false;

    /**
     * Retrieve type instance
     *
     * @return AbstractType
     */
    public function getTypeInstance()
    {
        return $this->product->getTypeInstance();
    }

    /**
     * Get the product attribute family that owns the product.
     */
    public function getAttributeFamilyAttribute()
    {
        return $this->product->attribute_family;
    }

    /**
     * Get the product that owns the attribute value.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the product variants that owns the product.
     */
    public function variants()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get the product that owns the product.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get product type value from base product
     */
    public function getTypeAttribute()
    {
        return $this->product->type;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isSaleable($seller=0)
    {
        if($this->product){
            return $this->product->isSaleable($seller);
        }
        return false;
    }

    /**
     * @return integer
     */
    public function totalQuantity()
    {
        return $this->product->totalQuantity();
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity($qty,$vendor=0)
    {
        return $this->product->haveSufficientQuantity($qty);
    }

    /**
     * @return bool
     */
    public function isStockable()
    {
        return $this->product->isStockable();
    }

    /**
     * The images that belong to the product.
     */
    public function images()
    {
        return (ProductImageProxy::modelClass())
            ::where('product_images.product_id', $this->product_id)
            ->select('product_images.*');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getImagesAttribute()
    {
        return $this->images()->get();
    }

    /**
     * The reviews that belong to the product.
     */
    public function reviews()
    {
        return (ProductReviewProxy::modelClass())
            ::where('product_reviews.product_id', $this->product_id)
            ->select('product_reviews.*');
    }

    /**
     * Get all of the reviews for the attribute groups.
     */
    public function getReviewsAttribute()
    {
        return $this->reviews()->get();
    }

    /**
     * The related products that belong to the product.
     */
    public function related_products()
    {
        return $this->product->related_products();
    }

    /**
     * The up sells that belong to the product.
     */
    public function up_sells()
    {
        return $this->product->up_sells();
    }

    /**
     * The cross sells that belong to the product.
     */
    public function cross_sells()
    {
        return $this->product->cross_sells();
    }

    /**
     * The images that belong to the product.
     */
    public function downloadable_samples()
    {
        return $this->product->downloadable_samples();
    }

    /**
     * The images that belong to the product.
     */
    public function downloadable_links()
    {
        return $this->product->downloadable_links();
    }

    /**
     * Get the grouped products that owns the product.
     */
    public function grouped_products()
    {
        return $this->product->grouped_products();
    }

    /**
     * Get the bundle options that owns the product.
     */
    public function bundle_options()
    {
        return $this->product->bundle_options();
    }

    /**
     * Retrieve product attributes
     *
     * @param Group $group
     * @param bool  $skipSuperAttribute
     * @return Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        return $this->product->getEditableAttributes($groupId, $skipSuperAttribute);
    }

    public function toArray($only = false)
    {
        if ($only) {
            return $this->attributesToArray();
        } else {
            return parent::toArray();
        }
    }
    public function seller()
    {
        return $this->belongsTo(\Webkul\Marketplace\Models\SellerProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * The categories that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(CategoryProxy::modelClass(), 'product_categories','product_id','product_id');
    }

}
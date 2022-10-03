<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Attribute\Models\AttributeFamilyProxy;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Product\Contracts\Product as ProductContract;

/**
 * Webkul\Product\Models\Product
 *
 * @property int $id
 * @property string $sku
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $parent_id
 * @property int|null $attribute_family_id
 * @property-read \Webkul\SAASCustomizer\Models\Attribute\AttributeFamily|null $attribute_family
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Product\ProductAttributeValue[] $attribute_values
 * @property-read int|null $attribute_values_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductBundleOption[] $bundle_options
 * @property-read int|null $bundle_options_count
 * @property-read \Kalnoy\Nestedset\Collection|\Webkul\SAASCustomizer\Models\Category\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\Product[] $cross_sells
 * @property-read int|null $cross_sells_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductDownloadableLink[] $downloadable_links
 * @property-read int|null $downloadable_links_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductDownloadableSample[] $downloadable_samples
 * @property-read int|null $downloadable_samples_count
 * @property-read mixed $base_image_url
 * @property-read mixed $product
 * @property-read mixed $product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductGroupedProduct[] $grouped_products
 * @property-read int|null $grouped_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Product\ProductImage[] $images
 * @property-read int|null $images_count
 * @property-read \Webkul\Product\Models\Product|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\Product[] $related_products
 * @property-read int|null $related_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Product\ProductReview[] $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Attribute\Attribute[] $super_attributes
 * @property-read int|null $super_attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\Product[] $up_sells
 * @property-read int|null $up_sells_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\Product[] $variants
 * @property-read int|null $variants_count
 * @method static \Webkul\Product\Database\Eloquent\Builder|\Webkul\Product\Models\Product newModelQuery()
 * @method static \Webkul\Product\Database\Eloquent\Builder|\Webkul\Product\Models\Product newQuery()
 * @method static \Webkul\Product\Database\Eloquent\Builder|\Webkul\Product\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\Product whereAttributeFamilyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\Product whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model implements ProductContract
{
    protected $fillable = [
        'type',
        'attribute_family_id',
        'sku',
        'parent_id',
    ];

    protected $typeInstance;



    /**
     * Get the product attribute family that owns the product.
     */
    public function attribute_family()
    {
        return $this->belongsTo(AttributeFamilyProxy::modelClass());
    }

    public function attribute_values()
    {
        return $this->hasMany(ProductAttributeValueProxy::modelClass());
    }

    /**
     * Get the product variants that owns the product.
     */
    public function variants()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get the product reviews that owns the product.
     */
    public function reviews()
    {
        return $this->hasMany(ProductReviewProxy::modelClass());
    }

    /**
     * Get the product that owns the product.
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * The categories that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(CategoryProxy::modelClass(), 'product_categories');
    }


    /**
     * The super attributes that belong to the product.
     */
    public function super_attributes()
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'product_super_attributes');
    }

    /**
     * The images that belong to the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImageProxy::modelClass(), 'product_id')->orderBy('sort_order');
    }

    public function videos()
    {
        return $this->hasMany(ProductVideoProxy::modelClass(), 'product_id')->orderBy('id');
    }
    /**
     * The images that belong to the product.
     */
    public function getBaseImageUrlAttribute()
    {
        $image = $this->images()->first();

        return $image ? $image->url : null;
    }

    /**
     * The related products that belong to the product.
     */
    public function related_products()
    {
        return $this->belongsToMany(static::class, 'product_relations', 'parent_id', 'child_id')->limit(4);
    }

    /**
     * The up sells that belong to the product.
     */
    public function up_sells()
    {
        return $this->belongsToMany(static::class, 'product_up_sells', 'parent_id', 'child_id')->limit(4);
    }

    /**
     * The cross sells that belong to the product.
     */
    public function cross_sells()
    {
        return $this->belongsToMany(static::class, 'product_cross_sells', 'parent_id', 'child_id')->limit(4);
    }

    /**
     * The images that belong to the product.
     */
    public function downloadable_samples()
    {
        return $this->hasMany(ProductDownloadableSampleProxy::modelClass());
    }

    /**
     * The images that belong to the product.
     */
    public function downloadable_links()
    {
        return $this->hasMany(ProductDownloadableLinkProxy::modelClass());
    }

    /**
     * Get the grouped products that owns the product.
     */
    public function grouped_products()
    {
        return $this->hasMany(ProductGroupedProductProxy::modelClass());
    }

    /**
     * Get the bundle options that owns the product.
     */
    public function bundle_options()
    {
        return $this->hasMany(ProductBundleOptionProxy::modelClass());
    }



    /**
     * Retrieve type instance
     *
     * @param bool $productFlat
     * @return AbstractType
     */
    public function getTypeInstance($productFlat = false)
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }

        $this->typeInstance = app(config('product_types.' . $this->type . '.class'));

        $this->typeInstance->setProduct($this);

        if (is_object($productFlat)) {
            $this->typeInstance->setProductFlat($productFlat);
        }

        return $this->typeInstance;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isSaleable($seller=0)
    {
        return $this->getTypeInstance()->isSaleable($seller);
    }

    /**
     * @param null $vendor
     * @return integer
     */
    public function totalQuantity()
    {
        return $this->getTypeInstance()->totalQuantity();
    }

    /**
     * @param null $vendor
     * @return integer
     */
    public function updateProductInventory($quantity, $vendor = null)
    {
        return $this->getTypeInstance()->updateProductInventory($quantity, $vendor);
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity($qty,$vendor=0)
    {
        return $this->getTypeInstance()->haveSufficientQuantity($qty);
    }

    /**
     * @return bool
     */
    public function isStockable()
    {
        return $this->getTypeInstance()->isStockable();
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
        return $this->getTypeInstance()->getEditableAttributes($group, $skipSuperAttribute);
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (! method_exists(static::class, $key)
            && ! in_array($key, ['parent_id', 'attribute_family_id'])
            && ! isset($this->attributes[$key])
        ) {
            if (isset($this->id)) {
                $this->attributes[$key] = '';

                $attribute = core()->getSingletonInstance(\Webkul\Attribute\Repositories\AttributeRepository::class)
                                   ->getAttributeByCode($key);

                $this->attributes[$key] = $this->getCustomAttributeValue($attribute);

                return $this->getAttributeValue($key);
            }
        }

        return parent::getAttribute($key);
    }

    /**
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        $hiddenAttributes = $this->getHidden();

        if (isset($this->id)) {
            $familyAttributes = core()->getSingletonInstance(\Webkul\Attribute\Repositories\AttributeRepository::class)
                                      ->getFamilyAttributes($this->attribute_family);

            foreach ($familyAttributes as $attribute) {
                if (in_array($attribute->code, $hiddenAttributes)) {
                    continue;
                }

                $attributes[$attribute->code] = $this->getCustomAttributeValue($attribute);
            }
        }

        return $attributes;
    }

    /**
     * Get an product attribute value.
     *
     * @return mixed
     */
    public function getCustomAttributeValue($attribute)
    {
        if (! $attribute) {
            return;
        }

        $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

        $locale = request()->get('locale') ?: app()->getLocale();

        if ($attribute->value_per_channel) {
            if ($attribute->value_per_locale) {
                $attributeValue = $this->attribute_values()->where('attribute_id', $attribute->id)->first();
            } else {
                $attributeValue = $this->attribute_values()->where('attribute_id', $attribute->id)->first();
            }
        } else {
            if ($attribute->value_per_locale) {
                $attributeValue = $this->attribute_values()->where('attribute_id', $attribute->id)->first();
            } else {
                $attributeValue = $this->attribute_values()->where('attribute_id', $attribute->id)->first();
            }
        }

        return $attributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]] ?? null;
    }

    /**
     * Overrides the default Eloquent query builder
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newEloquentBuilder($query)
    {
        return new \Webkul\Product\Database\Eloquent\Builder($query);
    }

    /**
     * Return the product id attribute.
     */
    public function getProductIdAttribute()
    {
        return $this->id;
    }

    /**
     * Return the product attribute.
     */
    public function getProductAttribute()
    {
        return $this;
    }


    public function productFlat()
    {
        return $this->hasOne(ProductFlatProxy::modelClass());
    }

}

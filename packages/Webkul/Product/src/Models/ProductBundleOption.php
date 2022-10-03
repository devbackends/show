<?php

namespace Webkul\Product\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Contracts\ProductBundleOption as ProductBundleOptionContract;

/**
 * Webkul\Product\Models\ProductBundleOption
 *
 * @property int $id
 * @property string $type
 * @property int $is_required
 * @property int $sort_order
 * @property int $product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductBundleOptionProduct[] $bundle_option_products
 * @property-read int|null $bundle_option_products_count
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $product
 * @property-read \Webkul\Product\Models\ProductBundleOptionTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductBundleOptionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel translated()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOption whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOption whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOption whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel withTranslation()
 * @mixin \Eloquent
 */
class ProductBundleOption extends TranslatableModel implements ProductBundleOptionContract
{
    public $timestamps = false;

    public $translatedAttributes = ['label'];

    protected $fillable = [
        'type',
        'is_required',
        'sort_order',
        'product_id',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the bundle option products that owns the bundle option.
     */
    public function bundle_option_products()
    {
        return $this->hasMany(ProductBundleOptionProductProxy::modelClass());
    }
}
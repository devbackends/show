<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductBundleOptionTranslation as ProductBundleOptionTranslationContract;

/**
 * Webkul\Product\Models\ProductBundleOptionTranslation
 *
 * @property int $id
 * @property string $locale
 * @property string|null $label
 * @property int $product_bundle_option_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOptionTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOptionTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOptionTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOptionTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOptionTranslation whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOptionTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductBundleOptionTranslation whereProductBundleOptionId($value)
 * @mixin \Eloquent
 */
class ProductBundleOptionTranslation extends Model implements ProductBundleOptionTranslationContract
{
    public $timestamps = false;

    protected $fillable = ['label'];
}
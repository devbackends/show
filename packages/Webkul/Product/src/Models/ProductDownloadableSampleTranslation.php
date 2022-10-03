<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductDownloadableSampleTranslation as ProductDownloadableSampleTranslationContract;

/**
 * Webkul\Product\Models\ProductDownloadableSampleTranslation
 *
 * @property int $id
 * @property string $locale
 * @property string|null $title
 * @property int $product_downloadable_sample_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSampleTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSampleTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSampleTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSampleTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSampleTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSampleTranslation whereProductDownloadableSampleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSampleTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class ProductDownloadableSampleTranslation extends Model implements ProductDownloadableSampleTranslationContract
{
    public $timestamps = false;

    protected $fillable = ['title'];
}
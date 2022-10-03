<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductDownloadableLinkTranslation as ProductDownloadableLinkTranslationContract;

/**
 * Webkul\Product\Models\ProductDownloadableLinkTranslation
 *
 * @property int $id
 * @property string $locale
 * @property string|null $title
 * @property int $product_downloadable_link_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLinkTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLinkTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLinkTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLinkTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLinkTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLinkTranslation whereProductDownloadableLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLinkTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class ProductDownloadableLinkTranslation extends Model implements ProductDownloadableLinkTranslationContract
{
    public $timestamps = false;

    protected $fillable = ['title'];
}
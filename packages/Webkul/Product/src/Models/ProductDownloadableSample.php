<?php

namespace Webkul\Product\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductDownloadableSample as ProductDownloadableSampleContract;

/**
 * Webkul\Product\Models\ProductDownloadableSample
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $file
 * @property string|null $file_name
 * @property string $type
 * @property int|null $sort_order
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $file_url
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $product
 * @property-read \Webkul\Product\Models\ProductDownloadableSampleTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductDownloadableSampleTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel translated()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableSample whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel withTranslation()
 * @mixin \Eloquent
 */
class ProductDownloadableSample extends TranslatableModel implements ProductDownloadableSampleContract
{
    public $translatedAttributes = ['title'];

    protected $fillable = [
        'url',
        'file',
        'file_name',
        'type',
        'sort_order',
        'product_id',
    ];

    protected $with = ['translations'];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get image url for the file.
     */
    public function file_url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the file.
     */
    public function getFileUrlAttribute()
    {
        return $this->file_url();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();


        $translation = $this->translate(request()->get('locale') ?: app()->getLocale());

        $array['title'] = $translation ? $translation->title : '';

        $array['file_url'] = $this->file ? Storage::url($this->file) : null;

        return $array;
    }
}
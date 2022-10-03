<?php

namespace Webkul\Product\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductDownloadableLink as ProductDownloadableLinkContract;

/**
 * Webkul\Product\Models\ProductDownloadableLink
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $file
 * @property string|null $file_name
 * @property string $type
 * @property float $price
 * @property string|null $sample_url
 * @property string|null $sample_file
 * @property string|null $sample_file_name
 * @property string|null $sample_type
 * @property int $downloads
 * @property int|null $sort_order
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $file_url
 * @property-read mixed $sample_file_url
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $product
 * @property-read \Webkul\Product\Models\ProductDownloadableLinkTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Product\Models\ProductDownloadableLinkTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel translated()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereSampleFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereSampleFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereSampleType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereSampleUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductDownloadableLink whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Core\Eloquent\TranslatableModel withTranslation()
 * @mixin \Eloquent
 */
class ProductDownloadableLink extends TranslatableModel implements ProductDownloadableLinkContract
{
    public $translatedAttributes = ['title'];

    protected $fillable = [
        'title',
        'price',
        'url',
        'file',
        'file_name',
        'type',
        'sample_url',
        'sample_file',
        'sample_file_name',
        'sample_type',
        'sort_order',
        'product_id',
        'downloads',
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
     * Get image url for the sample file.
     */
    public function sample_file_url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the sample file.
     */
    public function getSampleFileUrlAttribute()
    {
        return $this->sample_file_url();
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

        $array['sample_file_url'] = $this->sample_file ? Storage::url($this->sample_file) : null;

        return $array;
    }
}
<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Product\Models\Product;
use Webkul\Product\Contracts\ProductReview as ProductReviewContract;

/**
 * Webkul\Product\Models\ProductReview
 *
 * @property int $id
 * @property string $title
 * @property int $rating
 * @property string|null $comment
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $product_id
 * @property int|null $customer_id
 * @property string $name
 * @property-read \Webkul\SAASCustomizer\Models\Customer\Customer|null $customer
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductReview whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductReview extends Model implements ProductReviewContract
{
    protected $fillable = [
        'comment',
        'title',
        'rating',
        'status',
        'product_id',
        'customer_id',
        'name',
    ];

    /**
     * Get the product attribute family that owns the product.
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
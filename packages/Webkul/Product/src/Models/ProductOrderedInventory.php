<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Product\Contracts\ProductOrderedInventory as ProductOrderedInventoryContract;

/**
 * Webkul\Product\Models\ProductOrderedInventory
 *
 * @property int $id
 * @property int $qty
 * @property int $product_id
 * @property int $channel_id
 * @property-read \Webkul\SAASCustomizer\Models\Core\Channel $channel
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductOrderedInventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductOrderedInventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductOrderedInventory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductOrderedInventory whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductOrderedInventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductOrderedInventory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Product\Models\ProductOrderedInventory whereQty($value)
 * @mixin \Eloquent
 */
class ProductOrderedInventory extends Model implements ProductOrderedInventoryContract
{
    public $timestamps = false;

    protected $fillable = [
        'qty',
        'product_id',
        'channel_id',
    ];

    /**
     * Get the channel owns the inventory.
     */
    public function channel()
    {
        return $this->belongsTo(ChannelProxy::modelClass());
    }

    /**
     * Get the product that owns the product inventory.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
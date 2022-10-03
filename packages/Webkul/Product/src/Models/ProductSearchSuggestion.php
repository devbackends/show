<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\AttributeFamilyProxy;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Product\Contracts\Product as ProductContract;

class ProductSearchSuggestion extends Model implements ProductContract
{
    protected $table = 'product_search_suggestion';

    protected $fillable = [
        'manufacturer',
        'caliber',
        'gauge',
        'baarell_length',
        'year',
        'finish',
        'capacity',
        'overall_length',
        'weight'
    ];


}
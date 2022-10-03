<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Contracts\AttributesOptionsProductsAmount as AttributesOptionsProductsAmountContract;

class AttributesOptionsProductsAmount extends Model implements AttributesOptionsProductsAmountContract
{
    public $timestamps = false;

    protected $table = 'attributes_options_products_amount';

    protected $fillable = [
        'category_id',
        'option_id',
        'products_amount',
    ];

    public function increaseByCategory($category, $options)
    {
        foreach ($options as $option) {
            $validateOption=app('Webkul\Attribute\Repositories\AttributeOptionRepository')->find($option);
            if($validateOption && $category ){
                $this->newQuery()
                    ->updateOrInsert([
                        'category_id' => $category,
                        'option_id' => $option
                    ], [
                        'products_amount' => DB::raw('products_amount+1')
                    ]);
            }
        }
    }

    public function decreaseByCategory($category, $options)
    {
        $this->newQuery()
            ->where('category_id', '=', $category)
            ->whereIn('option_id', $options)
            ->update([
                'products_amount' => DB::raw('products_amount-1')
            ]);
    }

}
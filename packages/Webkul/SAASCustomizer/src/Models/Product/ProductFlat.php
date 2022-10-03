<?php

namespace Webkul\SAASCustomizer\Models\Product;

use Webkul\Product\Models\ProductFlat as BaseModel;

;

class ProductFlat extends BaseModel
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {


        if (request()->is('categories/*')) {
            return new \Illuminate\Database\Eloquent\Builder($query);
        } else {
            if (auth()->guard('super-admin')->check() || ! isset($company->id) || ($company->username === config('app.defaultCompanyCode'))) {
                return new \Illuminate\Database\Eloquent\Builder($query);
            }
        }
    }
}

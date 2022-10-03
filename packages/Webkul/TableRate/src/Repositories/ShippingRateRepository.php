<?php

namespace Webkul\TableRate\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * ShippingRate Repository Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShippingRateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\TableRate\Contracts\ShippingRate';
    }

    public function getMatchData($data) {
        $results = app('Webkul\TableRate\Repositories\ShippingRateRepository')->scopeQuery(function($query) use ($data) {
            $qb = $query->distinct()
                ->addSelect('tablerate_shipping_rates.*')
                ->leftJoin('tablerate_supersets', 'tablerate_supersets.id', '=', 'tablerate_shipping_rates.tablerate_superset_id')
                ->where('tablerate_shipping_rates.tablerate_superset_id', $data['tablerate_superset_id']);

                if ( isset($data['zip_from']) && $data['zip_from'] && isset($data['zip_to']) && $data['zip_to'] && $data['is_zip_range'] == 1 ) {
                    $qb->where(function($query) use ($data) {
                        $query->orWhere([
                            ['tablerate_shipping_rates.zip_from', '<=', $data['zip_from']],
                            ['tablerate_shipping_rates.zip_to', '>=', $data['zip_from']],
                            ]);
                        $query->orWhere([
                            ['tablerate_shipping_rates.zip_from', '<=', $data['zip_to']],
                            ['tablerate_shipping_rates.zip_to', '>=', $data['zip_to']],
                            ]);
                        $query->orWhere([
                            ['tablerate_shipping_rates.zip_from', '>=', $data['zip_from']],
                            ['tablerate_shipping_rates.zip_from', '<=', $data['zip_to']],
                            ]);
                        $query->orWhere([
                            ['tablerate_shipping_rates.zip_to', '>=', $data['zip_from']],
                            ['tablerate_shipping_rates.zip_to', '<=', $data['zip_to']],
                            ]);
                    });
                }
                
                if ( isset($data['is_zip_range']) && $data['is_zip_range'] == 0 ) {
                    $qb->where('tablerate_shipping_rates.zip_code', $data['zip_code']);
                }
                
                if ( isset($data['country']) && $data['country'] ) {
                    $qb->where('tablerate_shipping_rates.country', $data['country']);
                }

                if ( isset($data['shipping_rate_id']) && $data['shipping_rate_id'] ) {
                    $qb->where('tablerate_shipping_rates.id', '!=', $data['shipping_rate_id']);
                }
                
            return $qb->groupBy('tablerate_shipping_rates.id');
        })->paginate(isset($data['limit']) ? $data['limit'] : 9);
        
        return $results;
    }
}
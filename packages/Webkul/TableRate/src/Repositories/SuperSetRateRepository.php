<?php

namespace Webkul\TableRate\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;

/**
 * SuperSetRate Repository Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SuperSetRateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\TableRate\Contracts\SuperSetRate';
    }

    public function getMatchData($data) {
        $results = app('Webkul\TableRate\Repositories\SupersetRateRepository')->scopeQuery(function($query) use ($data) {
            $qb = $query->distinct()
                ->addSelect('tablerate_superset_rates.*')
                ->leftJoin('tablerate_supersets', 'tablerate_supersets.id', '=', 'tablerate_superset_rates.tablerate_superset_id');

                if ( isset($data['price_from']) && $data['price_from'] && isset($data['price_to']) && $data['price_to'] ) {
                    $qb->where(function($query) use ($data) {
                        $query->where([
                            ['tablerate_superset_rates.price_from', '<=', $data['price_from']],
                            ['tablerate_superset_rates.price_to', '>=', $data['price_from']],
                            ]);
                        $query->orWhere([
                            ['tablerate_superset_rates.price_from', '<=', $data['price_to']],
                            ['tablerate_superset_rates.price_to', '>=', $data['price_to']],
                            ]);
                        $query->orWhere([
                            ['tablerate_superset_rates.price_from', '>=', $data['price_from']],
                            ['tablerate_superset_rates.price_from', '<=', $data['price_to']],
                            ]);
                        $query->orWhere([
                            ['tablerate_superset_rates.price_to', '>=', $data['price_from']],
                            ['tablerate_superset_rates.price_to', '<=', $data['price_to']],
                            ]);
                    });
                }

                $qb->where('tablerate_superset_rates.tablerate_superset_id', $data['tablerate_superset_id']);

                if ( isset($data['tablerate_supersetrate_id']) && $data['tablerate_supersetrate_id'] ) {
                    $qb->where('tablerate_superset_rates.id', '!=', $data['tablerate_supersetrate_id']);
                }

            return $qb->groupBy('tablerate_superset_rates.id');
        })->paginate(isset($data['limit']) ? $data['limit'] : 9);

        return $results;
    }
}
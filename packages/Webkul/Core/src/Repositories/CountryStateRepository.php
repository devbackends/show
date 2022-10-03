<?php

namespace Webkul\Core\Repositories;

use Illuminate\Database\Query\Builder;
use Webkul\Core\Eloquent\Repository;

class CountryStateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Contracts\CountryState';
    }

    /**
     * @param string $country
     * @param string $state
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findByCountryAndState(string $country, string $state)
    {
        return $this->makeModel()->newModelQuery()
            ->where(function($query) use ($state, $country) {
                /** @var Builder $query  */
                $query->where('code', '=', $state)
                    ->where('country_code', '=', $country);
            })->first();
    }
}

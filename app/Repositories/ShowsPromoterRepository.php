<?php

namespace App\Repositories;

use App\Models\ShowsPromoter;
use Webkul\Core\Eloquent\Repository;

class ShowsPromoterRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return ShowsPromoter::class;
    }
}

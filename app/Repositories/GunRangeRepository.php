<?php

namespace App\Repositories;

use App\Models\GunRange;
use Webkul\Core\Eloquent\Repository;

class GunRangeRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return GunRange::class;
    }
}
<?php

namespace App\Repositories;

use App\Models\Clubs;
use Webkul\Core\Eloquent\Repository;

class ClubRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Clubs::class;
    }
}
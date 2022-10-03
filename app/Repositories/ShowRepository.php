<?php

namespace App\Repositories;

use App\Models\Show;
use Webkul\Core\Eloquent\Repository;

class ShowRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Show::class;
    }

}
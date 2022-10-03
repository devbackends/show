<?php

namespace App\Repositories;

use App\Models\FirearmTraining;
use Webkul\Core\Eloquent\Repository;

class FirearmTrainingRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return FirearmTraining::class;
    }
}
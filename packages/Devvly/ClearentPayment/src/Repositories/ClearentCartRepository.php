<?php

namespace Devvly\ClearentPayment\Repositories;

use Webkul\Core\Eloquent\Repository;

class ClearentCartRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Devvly\ClearentPayment\Contracts\ClearentCart';
    }
}
<?php

namespace Webkul\TableRate\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * SuperSet Repository Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SuperSetRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\TableRate\Contracts\SuperSet';
    }
}
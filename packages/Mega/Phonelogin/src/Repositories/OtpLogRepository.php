<?php

namespace Mega\Phonelogin\Repositories;

use Webkul\Core\Eloquent\Repository;

class OtpLogRepository extends Repository
{

    public function model(){
        return 'Mega\Phonelogin\Models\OtpLog';
    }

}
<?php

namespace Devvly\ClearentPayment\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container as App;

class ClearentCardRepository extends Repository
{  

    public function __construct(App $app)
    {  
        parent::__construct($app); 
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Devvly\ClearentPayment\Models\ClearentCard';
    }

   
    public function getAllChannels()
    {
        $allChannels = core()->getAllChannels();
        $channels = [];
        foreach($allChannels as $channel) {
            $channels[$channel->id] = $channel->name;
        }

        return $channels;
    }
}

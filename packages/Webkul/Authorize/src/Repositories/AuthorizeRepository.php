<?php

namespace Webkul\Authorize\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container as App;


/**
 * Stripe Reposotory
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AuthorizeRepository extends Repository
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
        return 'Webkul\Authorize\Models\AuthorizeCard';
    }


    public function getAllChannels()
    {
        $allChannels = core()->getAllChannels();

        foreach($allChannels as $channel) {
            $channels[$channel->id] = $channel->name;
        }

        return $channels;
    }
}

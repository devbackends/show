<?php


namespace Mega\SmsNotifications\Helpers;


abstract class AbstractHelper
{


    /**
     * Retrieve information from configuration
     *
     * @param string $field
     * @param int|string|null
     *
     * @return mixed
     */
    public function getConfigData($path)
    {
        return core()->getConfigData($path);
    }

}
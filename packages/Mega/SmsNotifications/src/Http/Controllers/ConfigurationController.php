<?php

namespace Mega\SmsNotifications\src\Http\Controllers;

use \Webkul\Admin\Http\Controllers\Controller as AdminController;

class ConfigurationController extends AdminController
{

    public function general(){
        die('asdasd');
        return view($this->_config['view'], ['config' => $this->configTree]);
    }
}
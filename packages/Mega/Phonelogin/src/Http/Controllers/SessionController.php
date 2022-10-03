<?php


namespace Mega\Phonelogin\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Customer\Http\Controllers\SessionController as ParentController;
use Mega\Phonelogin\Helper\PhoneloginHelper ;

class SessionController extends ParentController
{

    protected $megaHelper;

    public function __construct(PhoneloginHelper $megaHelper)
    {
        $this->megaHelper = $megaHelper;
        return parent::__construct();
    }

    public function create(){
        if(!$this->megaHelper->isEnabled())
            return parent::create();
        $request = request();
        $email = $request->input('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //if(1){
            $phone = $email;
            $email = $this->megaHelper->findEmailByPhone($phone);

            if($email){
                $request->merge(['email' => $email]);
            }
        }
        return parent::create();
    }

}
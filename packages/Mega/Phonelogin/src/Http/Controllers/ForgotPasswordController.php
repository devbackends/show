<?php


namespace Mega\Phonelogin\Http\Controllers;

use Mega\Phonelogin\Helper\PhoneloginHelper;
use Webkul\Customer\Http\Controllers\ForgotPasswordController as ParentController;
use Illuminate\Auth\Passwords\PasswordBroker;

class ForgotPasswordController extends ParentController
{

    protected $megaHelper;

    public function __construct(
        PhoneloginHelper $phoneloginHelper
    )
    {
        $this->megaHelper = $phoneloginHelper;
        parent::__construct();
    }

    public function store(){


        if(!$this->megaHelper->isEnabled())
            return parent::store();

        $request = request();
        $email = $request->input('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $phone = $email;
            $email = $this->megaHelper->findEmailByPhone($phone);
            if($email){
                $request->merge(['email' => $email]);
            }
        }
        return parent::store();
    }

}
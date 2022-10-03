<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

/**
 * Super Admin forget password controller
 *
 * @author    Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ForgetPasswordController extends Controller
{

    use SendsPasswordResetEmails;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_config = request('_config');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'email' => 'required|email'
        ]);

        $response = $this->broker()->sendResetLink(
            request(['email'])
        );
        
        if ($response == Password::RESET_LINK_SENT) {
            session()->flash('success', trans($response));

            return back();
        }

        return back()
            ->withInput(request(['email']))
            ->withErrors(
                ['email' => trans($response)]
            );
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('superadmins');
    }
}
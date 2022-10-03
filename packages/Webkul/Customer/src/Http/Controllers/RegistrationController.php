<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Mail\RegistrationEmail;
use Webkul\Customer\Mail\VerificationEmail;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Cookie;
use Webkul\User\Repositories\AdminRepository;

class RegistrationController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CustomerRepository object
     *
     * @var \Webkul\Customer\Repositories\CustomerRepository
     */
    protected $customerRepository;

    /**
     * CustomerGroupRepository object
     *
     * @var \Webkul\Customer\Repositories\CustomerGroupRepository
     */
    protected $customerGroupRepository;
    protected $adminRepository;
    /**
     * Create a new Repository instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository $customer
     * @param  \Webkul\Customer\Repositories\CustomerGroupRepository $customerGroupRepository
     *
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository,
        CustomerGroupRepository $customerGroupRepository,
        AdminRepository $adminRepository
    )
    {
        $this->_config = request('_config');

        $this->customerRepository = $customerRepository;

        $this->customerGroupRepository = $customerGroupRepository;
        $this->adminRepository = $adminRepository;
    }

    /**
     * Opens up the user's sign up form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        if(auth('customer')->check()){
            return redirect()->route('customer.profile.index');
        }
        return view($this->_config['view']);
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {

        if(auth('customer')->check()){
            return redirect()->route('customer.profile.index');
        }
        $this->validate(request(), [
            'first_name' => 'string|required|max:35',
            'last_name' => 'string|required|max:35',
            'email' => 'email|required|unique:customers,email',
            'password' => 'confirmed|min:6|required',
        ]);

        $data = request()->input();
        if($data['first_name']==$data['last_name']){

            if(isset(request()->all()['async'])){
                return response()->json([
                    'code' => 500,
                    'message' => 'last Name Should be different than first name'
                ],200);
            }
            session()->flash('error', 'Invalid Name');
            return redirect()->back();
        }

        $endsWith = '.ru';

        if(str_ends_with($data['email'], $endsWith)){
            if(isset(request()->all()['async'])){
                return response()->json([
                    'code' => 500,
                    'message' => 'Invalid Email'
                ],200);
            }
            session()->flash('error', 'Invalid Email');
            return redirect()->back();
        }


        if($data['first_name']==$data['last_name']){
            session()->flash('error', 'First Name and Last Name Should Not Be Identical ');
            return redirect()->back();
        }
        $data['password'] = bcrypt($data['password']);
        $data['api_token'] = Str::random(80);

        if (core()->getConfigData('customer.settings.email.verification')) {
            $data['is_verified'] = 0;
        } else {
            $data['is_verified'] = 1;
        }

        $data['customer_group_id'] = $this->customerGroupRepository->findOneWhere(['code' => 'general'])->id;

        $verificationData['email'] = $data['email'];
        $verificationData['token'] = md5(uniqid(rand(), true));
        $data['token'] = $verificationData['token'];

        Event::dispatch('customer.registration.before');

        $customer = $this->customerRepository->create($data);

        Event::dispatch('customer.registration.after', $customer);

        if ($customer) {
            if (core()->getConfigData('customer.settings.email.verification')) {
                try {
                    $configKey = 'emails.general.notifications.emails.general.notifications.verification';
                    if (core()->getConfigData($configKey)) {
                        Mail::send(new VerificationEmail($verificationData));
                    }

                    session()->flash('success', trans('shop::app.customer.signup-form.success-verify'));
                } catch (\Exception $e) {
                    report($e);
                    session()->flash('info', trans('shop::app.customer.signup-form.success-verify-email-unsent'));
                }
            } else {
                try {
                    $configKey = 'emails.general.notifications.emails.general.notifications.registration';
                    if (core()->getConfigData($configKey)) {
                        Mail::send(new RegistrationEmail(request()->all()));
                    }

                    session()->flash('success', trans('shop::app.customer.signup-form.success-verify')); //customer registered successfully
                } catch (\Exception $e) {
                    report($e);
                    session()->flash('info', trans('shop::app.customer.signup-form.success-verify-email-unsent'));
                }

                session()->flash('success', trans('shop::app.customer.signup-form.success'));
            }

            // authentication attempt session will be destroyed for that we need to keep the cart session,for that purpose we save if before attempt and then put it again into session after attempt
            $cart=session()->get('cart');

            auth()->guard('customer')->attempt(request(['email', 'password']));

            if($cart){session()->put('cart',$cart);}

            if(isset(request()->all()['async'])){
                return response()->json([
                    'code' => 200,
                    'message' =>'Registered  Successfully',
                    'customer' => auth()->guard('customer')->user()
                ],200);
            }
            if (request()->get('want_to_be_seller') === '1' || request()->get('want_to_be_trainer') === '1') {
                return redirect()->route('marketplace.account.seller.create');
            } else {
                return redirect()->route('shop.home.index');
            }
        } else {
            if(isset(request()->all()['async'])){
                return response()->json([
                    'code' => 500,
                    'message' =>'Sign Up Failed'
                ],200);
            }
            session()->flash('error', trans('shop::app.customer.signup-form.failed'));

            return redirect()->back();
        }
    }


    /**
     * Method to verify account
     *
     * @param  string $token
     * @return \Illuminate\Http\Response
     */
    public function verifyAccount($token)
    {
        $customer = $this->customerRepository->findOneByField('token', $token);

        if ($customer) {
            $customer->update(['is_verified' => 1, 'token' => 'NULL']);

            session()->flash('success', trans('shop::app.customer.signup-form.verified'));
        } else {
            session()->flash('warning', trans('shop::app.customer.signup-form.verify-failed'));
        }

        return redirect()->route('customer.session.index');
    }

    /**
     * @param  string $email
     * @return \Illuminate\Http\Response
     */
    public function resendVerificationEmail($email)
    {
        $verificationData['email'] = $email;
        $verificationData['token'] = md5(uniqid(rand(), true));

        $customer = $this->customerRepository->findOneByField('email', $email);

        $this->customerRepository->update(['token' => $verificationData['token']], $customer->id);

        try {
            Mail::queue(new VerificationEmail($verificationData));

            if (Cookie::has('enable-resend')) {
                \Cookie::queue(\Cookie::forget('enable-resend'));
            }

            if (Cookie::has('email-for-resend')) {
                \Cookie::queue(\Cookie::forget('email-for-resend'));
            }
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', trans('shop::app.customer.signup-form.verification-not-sent'));

            return redirect()->back();
        }

        session()->flash('success', trans('shop::app.customer.signup-form.verification-sent'));

        return redirect()->back();
    }
}

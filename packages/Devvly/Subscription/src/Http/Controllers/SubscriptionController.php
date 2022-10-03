<?php

namespace Devvly\Subscription\Http\Controllers;

use App\Http\Controllers\Response;
use Devvly\Clearent\Client;
use Devvly\Clearent\Models\CustomerOptions;
use Devvly\Clearent\Models\Error;
use Devvly\Clearent\Resources\Resources;
use Devvly\Subscription\Services\HooksListener;
use Devvly\Subscription\Services\SubscriptionManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Devvly\Subscription\Models\PaymentMethod;

use Psr\Log\LoggerInterface;
use Webkul\SAASCustomizer\Models\Coupon\Coupon;
use Webkul\SAASCustomizer\Models\Coupon\CouponUsage;
use Devvly\OnBoarding\Models\Pricing;
class SubscriptionController extends Controller
{
    protected $_config;

    /** @var SubscriptionManager */
    protected $subscriptionManager;

    /** @var Client */
    protected $clearentClient;

    /** @var \Psr\Log\LoggerInterface: the logging service */
    protected $logger;

    /** @var \Devvly\Subscription\Services\HooksListener: internal clearent hooks listener*/
    protected $hooksListener;

    /** @var Resources */
    protected $resources;

    public function __construct(
        SubscriptionManager $subscriptionManager,
        Client $clearentClient,
        LoggerInterface $logger,
        HooksListener $hooksListener,
        Resources $resources
    )
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->clearentClient = $clearentClient;
        $this->_config = request('_config');
        $this->logger = $logger;
        $this->hooksListener = $hooksListener;
        $this->resources = $resources;
    }

    public function index(){

        /** @var \Devvly\Subscription\Models\Company $company */
        $company = Company()->getCurrent();
        $current_plan = $company->plans()->first();
        if (!$current_plan) {
            return redirect()->route('subscription.create');
        }
        $next_billing = $current_plan->pivot->start_date;
        $vars = [
            'current_plan' => $current_plan,
            'next_billing' => $next_billing
        ];
        return view('subscription::subscription.index');
    }

    public function subscribe(){
        return view('subscription::subscription.subscribe');
    }
    public function subscribtionDetail(){
        $pricing= Pricing::all();
        return view('subscription::subscription.subscription-detail',compact('pricing'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('subscription.index');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function settings(){

        $settings = $this->clearentClient->getSettings();
        return new JsonResponse($settings);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCard(Request $request){
      $request->validate(
        [
          'plan' => 'required',
          'jwt_token' => 'required',
          'card_type' => 'required',
          'last_four' => 'required',
          'coupon_code' => 'string|min:4|max:6'
        ]
      );
      $company = company()->getCurrent();
      if (!$company) {
        session()->flash('error', trans('subscription::app.subscription.no_company'));
        return back();
      }

      // apply coupon:
      $plan = Plan::findOrFail($request->input('plan'));
      $coupon_code = $request->input('coupon_code');
      if (!empty($coupon_code)) {
        $coupon_type = 'subscription';
        $Coupon = Coupon::where('coupon_code', $coupon_code)->first();
        if($Coupon){
          $checkCouponAvailibility=$this->checkCouponAvailibility($coupon_code, $coupon_type);
          if ($checkCouponAvailibility['status']) {
            if ($Coupon->action_type == 'percentage') {
              $plan->price = $plan->price * (100 - $Coupon->discount_amount) / 100;
            } elseif ($Coupon->action_type == 'amount') {
              $plan->price = $plan->price - $Coupon->discount_amount;
            }
          }
        }
      }
      $payment_method = new PaymentMethod;
      $payment_method->jwt_token = $request->input('jwt_token');
      $payment_method->card_type = $request->input('card_type');
      $payment_method->last_four = $request->input('last_four');
      $details = $company->details()->get()->first();
      $firstName = $details->first_name;
      $lastName = $details->last_name;

      // create a card token:

      // 1. create Clearent customer:
      $cOptions = new CustomerOptions($firstName, $lastName);
      $customer = $this->resources->customers()->create($cOptions);
      if ($customer instanceof Error) {
        throw new \Exception($customer->getErrorMessage());
      }
      // 2. create credit card token
      $token = $this->resources->tokens()->create($payment_method->jwt_token);
      if ($token instanceof Error) {
        throw new \Exception($token->getErrorMessage());
      }
      // 3. add that token to the created customer:
      $response = $this->resources->customers()->addTokenToCustomer(
        $token,
        $customer->getCustomerKey()
      );
      $payload = $response->getPayload();
      if ($payload instanceof Error) {
        throw new \Exception($payload->getErrorMessage());
      }
      // update payment method:
      $payment_method->card_token = $token->getTokenId();
      $payment_method->save();
      // create a disabled subscription (activate it later in the sale hook):
      $attrs = [
        'customer_key' => $customer->getCustomerKey(),
        'payment_method_id' => $payment_method->id,
        'active' => 0,
        'coupon_code'=> $coupon_code
      ];
      $company->plans()->attach($plan->id, $attrs);
      session()->flash('success', trans('subscription::app.subscription.card_stored'));
      $params = [
        'slug' => 'sales',
        'slug2' => 'paymentmethods',
        'payment_processing' => 'clearent'
      ];

      // @todo activate the subscription plan once the onb-boarding application is ready.
      return redirect()->route('admin.configuration.index', $params);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'plan' => 'required',
            'jwt_token' => 'required',
            'card_type' => 'required',
            'last_four' => 'required',
            'coupon_code' => 'string|min:4|max:6'
        ]);
        // 1. store the payment method
        /** @var \Devvly\Subscription\Models\Company $company */
        $company = Company()->getCurrent();

        $payment_method = new PaymentMethod;
        $payment_method->jwt_token = $request->input('jwt_token');
        $payment_method->card_type = $request->input('card_type');
        $payment_method->last_four = $request->input('last_four');

        $plan = Plan::findOrFail($request->input('plan'));
        if (!$company) {
            session()->flash('error', trans('subscription::app.subscription.no_company'));
            return back();
        }
        $coupon_code = $request->input('coupon_code');
        if (!empty($coupon_code)) {
            $coupon_type = 'subscription';
            $Coupon = Coupon::where('coupon_code', $coupon_code)->first();
            if($Coupon){
                $checkCouponAvailibility=$this->checkCouponAvailibility($coupon_code, $coupon_type);
                if ($checkCouponAvailibility['status']) {
                    if ($Coupon->action_type == 'percentage') {
                        $plan->price = $plan->price * (100 - $Coupon->discount_amount) / 100;
                    } elseif ($Coupon->action_type == 'amount') {
                        $plan->price = $plan->price - $Coupon->discount_amount;
                    }
                }
            }
        }
        try {
            /** @var \Devvly\Clearent\Models\PaymentPlan $payment_plan */
            $payment_plan = $this->subscriptionManager->subscribe($company, $payment_method, $plan);
            // update payment method:
            $payment_method->card_token = $payment_plan->getTokenId();
            $payment_method->save();
            // create a disabled subscription (activate it later in the sale hook):
            $attrs = [
                'start_date' => $payment_plan->getStartDate(),
                'end_date' => $payment_plan->getEndDate(),
                'plan_key' => $payment_plan->getPlanKey(),
                'customer_key' => $payment_plan->getCustomerKey(),
                'payment_method_id' => $payment_method->id,
                'active' => 0,
                'coupon_code'=>$coupon_code
            ];
            $company->plans()->attach($plan->id, $attrs);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('subscription.create');
        }
        session()->flash('success', trans('subscription::app.subscription.subscribed'));
        return redirect()->route('subscription.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hooks(Request $request)
    {
        $data = $request->all();
        // @todo check data received and call the appropriate method on hookListener

        if ($data['code'] == "200") {
            if($data['payload']['payloadType']=='transactionToken'){
                if($data['payload']['transaction']['result'] == "APPROVED"){
                    $order_id=$data['payload']['transaction']['order-id'];
                    $customer_id=$data['payload']['transaction']['customer-id'];

                    $companyPlan = CompanyPlan::where('plan_key', $order_id)->orwhere('customer_key',$customer_id)->first();
                    if ($companyPlan) {
                        if (!empty($companyPlan->coupon_code)) {
                            $Coupon = Coupon::where('coupon_code', $companyPlan->coupon_code)->first();
                            if ($Coupon) {
                                $Coupon->times_used=$Coupon->times_used + 1;
                                $Coupon->save();
                                $CouponUsage= new CouponUsage();
                                $CouponUsage->times_used=1;
                                $CouponUsage->coupon_id =$Coupon->id;
                                $CouponUsage->company_id =$companyPlan->company_id;
                                $CouponUsage->save();
                            }
                        }
                        $companyPlan->active = 1;
                        $companyPlan->save();
                    }
                }
            }
        }
        return new JsonResponse(['data' => 'hook ran']);
    }

    public function checkCouponAvailibility($coupon_code, $coupon_type)
    {
        $Company = Company()->getCurrent();
        $Coupon = Coupon::where('coupon_code', $coupon_code)->first();
        if ($Coupon) {
            if ($Coupon->status == 1) {
                if ($Coupon->times_used < $Coupon->uses_per_coupon) {
                    if ($Coupon->coupon_type == $coupon_type) {
                        $date_time = new \DateTime(date('Y-m-d H:i:s'));

                        if ($this->validateDate($Coupon->starts_from) && $this->validateDate($Coupon->ends_till)) {
                            $start_time = new \DateTime($Coupon->starts_from);
                            $ends_till = new \DateTime($Coupon->ends_till);
                            if ($date_time < $start_time) {
                                return array('status' => false, 'message' => 'You cannot use this coupon in future');
                            } elseif ($date_time > $ends_till) {
                                return array('status' => false, 'message' => 'This coupon has been expired');
                            }
                        }
                        $coupon_usage_count = count(CouponUsage::where('coupon_id', $Coupon->id)->groupBy('company_id')->get());
                        if ($coupon_usage_count < $Coupon->usage_per_customer) {
                            return array('status' => true, 'message' => '');
                        } elseif ($coupon_usage_count == $Coupon->usage_per_customer) {
                            $company_used_before = count(CouponUsage::where('company_id', $Company->id)->get());
                            if ($company_used_before > 0) {
                                return array('status' => true, 'message' => '');
                            } else {
                                return array('status' => false, 'message' => 'This coupon is allwed for a specific number of companies');
                            }
                        }
                    } else {
                        return array('status' => false, 'message' => 'Coupon used is not allowed for ' . $coupon_type);
                    }
                } else {
                    return array('status' => false, 'message' => 'Coupon has been used before');
                }
            } else {
                return array('status' => false, 'message' => 'This coupon is not active');
            }
        } else {
            return array('status' => false, 'message' => 'Coupon not found');
        }
    }

    function validateDate($date)
    {
        $d = explode(' ', $date);
        $date_array = explode('-', $d[0]);

        $year = $date_array[0];
        $month = $date_array[1];
        $day = $date_array[2];
        return checkdate($month, $day, $year);

    }
    public function checkCoupon(Request $request){
        $coupon_code=$request->request->get('coupon_code');
        return json_encode($this->checkCouponAvailibility($coupon_code, 'subscription'));
    }
}

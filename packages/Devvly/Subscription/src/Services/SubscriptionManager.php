<?php

namespace Devvly\Subscription\Services;

use Devvly\Subscription\Models\Company;
use Devvly\Subscription\Models\PaymentMethod;
use Devvly\Subscription\Models\Plan;
use Devvly\Clearent\Models\CustomerOptions;
use Devvly\Clearent\Models\Error;
use Devvly\Clearent\Resources\Resources;
use Psr\Log\LoggerInterface;


/**
 * Class SubscriptionManager
 */
class SubscriptionManager
{

  /** @var LoggerInterface: the logging service */
  protected $logger;

  /** @var Resources  */
  protected $resources;

  /**
   * SubscriptionManager constructor.
   *
   * @param  LoggerInterface  $logger
   * @param  Resources  $resources
   */
  public function __construct(LoggerInterface $logger, Resources $resources)
  {
    $this->logger = $logger;
    $this->resources = $resources;
  }

  /**
   * @param  \Devvly\Subscription\Models\Company  $company
   * @param  \Devvly\Subscription\Models\PaymentMethod  $paymentMethod
   * @param  \Devvly\Subscription\Models\Plan  $plan
   *
   * @return \Devvly\Clearent\Models\Payload
   * @throws \Exception
   */
  public function subscribe(
      Company $company,
      PaymentMethod $paymentMethod,
      Plan $plan
  ) {

    // 1. create customer:
    $details = $company->details()->get()->first();
    $cOptions = new CustomerOptions($details->first_name, $details->last_name);
    $customer = $this->resources->customers()->create($cOptions);
    if ($customer instanceof Error) {
      throw new \Exception($customer->getErrorMessage());
    }
    // 2. create credit card token
    $token = $this->resources->tokens()->create($paymentMethod->jwt_token);
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
    $customerName = $details->first_name . " " . $details->last_name;
    $planName = $customerName . "'s plan";
    $options = $this->resources->plans()->createPlanOptions(
        $customer->getCustomerKey(),
        $customerName,
        $planName,
        $token->getTokenId(),
        $plan->price,
        strtoupper($plan->frequency)
    );
    // 4. create the plan:
    $paymentPlan = $this->resources->plans()->create($options);

    if ($paymentPlan instanceof Error) {
      throw new \Exception($paymentPlan->getErrorMessage());
    }

    return $paymentPlan;
  }
}
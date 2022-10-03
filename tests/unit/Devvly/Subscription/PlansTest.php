<?php


namespace Tests\Unit\Devvly\Subscription;

use Carbon\Carbon;
use Devvly\Clearent\Client;
use Devvly\Clearent\Models\Customer;
use Devvly\Clearent\Models\CustomerOptions;
use Devvly\Clearent\Models\Error;
use Devvly\Clearent\Models\PaymentPlan;
use Devvly\Clearent\Models\PlanOptions;
use Devvly\Clearent\Models\Token;
use Devvly\Clearent\Resources\Customers;
use Devvly\Clearent\Resources\Plans;
use Devvly\Clearent\Resources\Tokens;


/**
 * @coversDefaultClass \Devvly\Clearent\Resources\Plans
 */
class PlansTest extends BaseTest
{

  public function testGet(){
    $plan = $this->createPlan();
    $service = $service = new Plans($this->app->get(Client::class));
    $response = $service->get($plan->getPlanKey());
    $this->assertInstanceOf(PaymentPlan::class, $response, 'Should be PaymentPlan');
    $this->deletePlan($plan->getPlanKey());
  }

  public function testAll(){
    $plan = $this->createPlan();
    $service = $service = new Plans($this->app->get(Client::class));
    $plans = $service->all();
    $this->assertIsArray($plans);
    $this->assertInstanceOf(PaymentPlan::class, $plans[0],'Should be plans');
    $this->deletePlan($plan->getPlanKey());
  }

  /**
   * @covers ::create
   */
  public function testCreate(){
    // test yearly plan:
    $plan = $this->createPlan();
    $this->deletePlan($plan->getPlanKey());
    // test monthly plan:
    $plan = $this->createPlan(PlanOptions::PLAN_MONTHLY);
    $this->deletePlan($plan->getPlanKey());
    // test weekly plan:
    $timezone = env('APP_TIMEZONE','America/Chicago');
    $now = Carbon::createFromDate(2024,2,28, $timezone);
    $plan = $this->createPlan(PlanOptions::PLAN_WEEKLY, $now);
    $this->assertEquals(5, $plan->getFrequencyDay(),'plan should run each Friday');
    $this->assertEquals(1, $plan->getFrequencyWeek(),'plan should run first week in the month');
    $this->assertEquals(1, $plan->getStartDate()->day,'plan should run the next day');
    $this->assertEquals(3, $plan->getStartDate()->month,'plan should run the next month');
    $this->deletePlan($plan->getPlanKey());
  }

  /**
   * @covers ::updateStatus
   */
  public function testUpdateStatus(){
    $client = $this->app->get(Client::class);
    $service = new Plans($client);
    $plan = $this->createPlan();
    $response = $service->updateStatus($plan->getPlanKey(), PlanOptions::PLAN_CANCELED);
    $this->assertInstanceOf(PaymentPlan::class, $response,'Payload should be PaymentPlan');
    $this->assertSame('CANCELED', $response->getStatus());
    $this->deletePlan($plan->getPlanKey());
  }

  /**
   * @covers ::delete
   */
  public function testDelete(){
    $plan = $this->createPlan();
    $this->deletePlan($plan->getPlanKey());
  }
  /**
   * @covers ::createPlanOptions
   */
  public function testCreatePlanOptions(){
    $methods = get_class_methods(Plans::class);
    unset($methods[array_search('createPlanOptions', $methods)]);
    $service = $this->getMockBuilder(Plans::class)
        ->disableOriginalConstructor()
        ->setMethods($methods)
        ->getMock();
    $timezone = env('APP_TIMEZONE','America/Chicago');
    $date = Carbon::createFromDate(2020,1,1, $timezone);
    $customerName = "John Doe";
    $planName = $customerName . "'s plan";
    $options = $service->createPlanOptions(
        'x',
        $customerName,
        $planName,
        'y',
        '420.22',
        PlanOptions::PLAN_WEEKLY,
        $date
    );
    // the plan should run the next day
    $dayToRun = $options->getStartDate()->day;
    $this->assertEquals(2, $dayToRun,'plan should run tomorrow');

    // test when the start date is near the end of the month, it should move to the next month
    $date = Carbon::createFromDate(2020,1,29, $timezone);
    $options = $service->createPlanOptions(
        'x',
        $customerName,
        $planName,
        'y',
        '420.22',
        PlanOptions::PLAN_WEEKLY,
        $date
    );
    $this->assertEquals(6, $options->getFrequencyDay(),'plan should run each Saturday');
    $this->assertEquals(1, $options->getFrequencyWeek(),'plan should run first week in the next month');
    $this->assertEquals(1, $options->getStartDate()->day,'plan should run first day in the next month');
    $this->assertEquals(2, $options->getStartDate()->month,'plan should run the next month');

    // it also should start the subscription in the next month if the current day is 31:
    $date = Carbon::createFromDate(2020,1,31, $timezone);
    $options = $service->createPlanOptions(
        'x',
        $customerName,
        $planName,
        'y',
        '420.22',
        PlanOptions::PLAN_WEEKLY,
        $date
    );
    $this->assertEquals(6, $options->getFrequencyDay(),'plan should run each Saturday');
    $this->assertEquals(1, $options->getFrequencyWeek(),'plan should run first week in the next month');
    $this->assertEquals(1, $options->getStartDate()->day,'plan should run first day in the next month');
    $this->assertEquals(2, $options->getStartDate()->month,'plan should run the next month');

    $date = Carbon::createFromDate(2020,2,29, $timezone);
    $options = $service->createPlanOptions(
        'x',
        $customerName,
        $planName,
        'y',
        '420.22',
        PlanOptions::PLAN_WEEKLY,
        $date
    );
    $this->assertEquals(7, $options->getFrequencyDay(),'plan should run each Sunday');
    $this->assertEquals(1, $options->getFrequencyWeek(),'plan should run first week in the month');
    $this->assertEquals(1, $options->getStartDate()->day,'plan should run first day in the next month');
    $this->assertEquals(3, $options->getStartDate()->month,'plan should run the next month');

    $date = Carbon::createFromDate(2020,2,27, $timezone);
    // plan should run each first day of each week starting from 28 of month 2
    $options = $service->createPlanOptions(
        'x',
        $customerName,
        $planName,
        'y',
        '420.22',
        PlanOptions::PLAN_WEEKLY,
        $date
    );
    $this->assertEquals(5, $options->getFrequencyDay(),'plan should run each Friday');
    $this->assertEquals(1, $options->getFrequencyWeek(),'plan should run first week in the month');
    $this->assertEquals(28, $options->getStartDate()->day,'plan should run the next day');
    $this->assertEquals(2, $options->getStartDate()->month,'plan should run the next month');
  }

  protected function deletePlan($id){
    $client = $this->app->get(Client::class);
    $service = new Plans($client);
    $customerSer = new Customers($this->app->get(Client::class));
    $response = $service->updateStatus($id,PlanOptions::PLAN_CANCELED);
    $this->assertInstanceOf(PaymentPlan::class, $response,'Payload should be PaymentPlan');
    $response = null;
    $response = $service->delete($id);
    $this->assertInstanceOf(PaymentPlan::class, $response,'Payload should be PaymentPlan');
    $res = $customerSer->delete($response->getCustomerKey());
    $this->assertInstanceOf(Customer::class, $res, 'should be customer');
    // insure that it doesn't exist on clearent side:
    $response = $service->get($id);
    $this->assertInstanceOf(Error::class, $response, "Should be Response");
    $this->assertSame('Object not found', $response->getErrorMessage());
  }

  protected function createPlan($frequency = PlanOptions::PLAN_YEARLY, $startDate = null): PaymentPlan{
    $client = $this->app->get(Client::class);
    $customerSer = $this->app->get(Customers::class);
    $tokenSer = $this->app->get(Tokens::class);
    $service = new Plans($client);
    $customerOptions = new CustomerOptions("John","Doe");
    // create customer, token and assign token to customer:
    $customer = $customerSer->create($customerOptions);
    $token = $tokenSer->create($this->jwtToken);
    $this->assertInstanceOf(Token::class, $token);
    $res = $customerSer->addTokenToCustomer($token,$customer->getCustomerKey());
    $this->assertEquals('success', $res->getStatus());
    // initialize options:
    $customerKey = $customer->getCustomerKey();
    $tokenId = $token->getTokenId();
    $customerName = $customerOptions->getFirstName() . ' '. $customerOptions->getLastName();
    $planName = $customerName . "'s plan";
    $options = $service->createPlanOptions(
        $customerKey,
        $customerName,
        $planName,
        $tokenId,
        '12.22',
        $frequency,
        $startDate
    );
    $this->assertInstanceOf(PlanOptions::class, $options);
    /** @var PaymentPlan $response */
    $response = $service->create($options);
    $this->assertInstanceOf(PaymentPlan::class, $response,'Payload should be PaymentPlan');
    return $response;
  }
}
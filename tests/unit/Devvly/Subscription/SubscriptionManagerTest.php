<?php


namespace Tests\Unit\Devvly\Subscription;

use Devvly\Subscription\Models\Company;
use Devvly\Subscription\Models\PaymentMethod;
use Devvly\Subscription\Models\Plan;
use Devvly\Clearent\Models\PaymentPlan;
use Devvly\Clearent\Resources\Plans;
use Devvly\Clearent\Resources\Resources;
use Devvly\Clearent\SubscriptionManager;
use Psr\Log\LoggerInterface;
use Tests\TestCase;
use Tests\Unit\Devvly\Subscription\Utils\UnitTestHelper;
use Webkul\SAASCustomizer\Models\CompanyDetails;

/**
 * @coversDefaultClass \Devvly\Clearent\SubscriptionManager
 */
class SubscriptionManagerTest extends TestCase
{
  use UnitTestHelper;

  /**
   * @covers ::subscribe
   */
  public function testSubscribe(){
    $logger = $this->app->get(LoggerInterface::class);
    $resources = $this->app->get(Resources::class);
    $manager = new SubscriptionManager($logger, $resources);
    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = factory(PaymentMethod::class)->create();
    /** @var Company $company */
    $company = $paymentMethod->company;
    factory(CompanyDetails::class)->create(['company_id' => $company->id]);
    $plan = factory(Plan::class)->make();
    $plan->frequency = "yearly";
    $plan->save();
    $paymentMethod->jwt_token = env('CLEARENT_API_JWT_TOKEN');
    $paymentMethod->save();
    $payment_plan = $manager->subscribe($company, $paymentMethod, $plan);
    $this->assertInstanceOf(PaymentPlan::class, $payment_plan);
    $plansSer = $this->app->get(Plans::class);
    $res = $plansSer->delete($payment_plan->getPlanKey());
    $this->assertInstanceOf(PaymentPlan::class, $res,'delete response should be plan');
  }

}
<?php


namespace Tests\unit\Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Application;
use Devvly\OnBoarding\Clearent\Resources\Applications;
use Devvly\OnBoarding\Clearent\Resources\PricingPlans;
use Devvly\OnBoarding\Clearent\Responses\Pricing\PricingPlanTemplate;
use Tests\unit\Devvly\OnBoarding\BaseTest;

/**
 * Class ApplicationsTest
 *
 * @package Tests\unit\Devvly\OnBoarding\Clearent\Resources
 * @coversDefaultClass \Devvly\OnBoarding\Clearent\Resources\PricingPlans
 */
class PricingPlansTest extends BaseTest
{

  /**
   * @covers ::getTemplates
   */
  public function testGetTemplates()
  {
    $client = $this->app->get(Client::class);
    $app = new Applications($client);
    $options = new Application();
    $options->setDbaName('Test');
    $createdApp = $app->create($options);
    $pricingPlans = new PricingPlans($client);
    $res = $pricingPlans->getTemplates($createdApp->getMerchantNumber());
    $this->assertIsArray($res);
    $this->assertInstanceOf(PricingPlanTemplate::class, $res[0]);
  }
}
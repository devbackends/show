<?php


namespace Tests\unit\Devvly\OnBoarding\Controllers;


use Devvly\OnBoarding\Clearent\Models\Application as AppModel;
use Devvly\OnBoarding\Clearent\Responses\Application;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Tests\unit\Devvly\OnBoarding\BaseTest;

/**
 * Class OnBoardingControllerTest
 *
 * @coversDefaultClass \Devvly\OnBoarding\Http\Controllers\BusinessInformationController
 */
class BusinessInformationControllerTest extends BaseTest
{

  use AppHelper;

  /**
   * @covers ::show
   */
  public function testShow()
  {
    $number = $this->testUpdate();
    $response = $this
        ->actingAs($this->user,'admin')
        ->getJson(route('onboarding.business_information.show', $number));
    $response->assertStatus(200);
    $content = json_decode($response->getContent(), true);
    $this->assertArrayHasKey('business_contacts', $content);
    $this->assertTrue($content['business_contacts'][0]['is_compass_user']);
    $this->assertTrue($content['business_contacts'][0]['is_authorized_to_purchase']);
  }

  /**
   * @covers ::update
   */
  public function testUpdate()
  {
    $app = $this->createApp();
    $this->mockClient();
    $this->updateBusinessInfo($app->getMerchantNumber());
    return $app->getMerchantNumber();
  }
}
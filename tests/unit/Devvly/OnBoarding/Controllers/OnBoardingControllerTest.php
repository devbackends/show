<?php


namespace Tests\unit\Devvly\OnBoarding\Controllers;


use Tests\unit\Devvly\OnBoarding\BaseTest;

/**
 * Class OnBoardingControllerTest
 *
 * @coversDefaultClass \Devvly\OnBoarding\Http\Controllers\OnBoardingController
 */
class OnBoardingControllerTest extends BaseTest
{

  use AppHelper;

  /**
   * @covers ::create
   */
  public function testCreate()
  {
    $response = $this->actingAs($this->user,'admin')->get(route('onboarding.create'));
    $response->assertStatus(200);
  }

  /**
   * @covers ::submitApp
   */
  public function testSubmitApp()
  {
    $app = $this->createApp();
    $number = $app->getMerchantNumber();
    $businessInfo = $this->updateBusinessInfo($number);
    $this->updateSalesProfile($number);
    $this->createBankAccount($number);
    $contactId = $businessInfo['business_contacts'][0]['business_contact_id'];
    $data = [
        'merchant_number' => $number,
        'business_contact_id' => $contactId,
    ];
    $response = $this
        ->actingAs($this->user,'admin')
        ->postJson(route('onboarding.submit'), $data);
    $content = json_decode($response->getContent(), true);
    $stop = null;
  }
}
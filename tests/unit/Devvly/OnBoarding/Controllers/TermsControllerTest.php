<?php


namespace Tests\unit\Devvly\OnBoarding\Controllers;


use Tests\unit\Devvly\OnBoarding\BaseTest;

/**
 * Class OnBoardingControllerTest
 *
 * @coversDefaultClass \Devvly\OnBoarding\Http\Controllers\TermsController
 */
class TermsControllerTest extends BaseTest
{

  use AppHelper;

  /**
   * @covers ::merchantTerms
   */
  public function testMerchantTerms()
  {
    $response = $this
        ->actingAs($this->user,'admin')
        ->getJson(route('onboarding.terms',6588000000063461));
    $content = json_decode($response->getContent(), true);
    $response->assertStatus(200);
    $stop = null;
  }

  /**
   * @covers ::updateSignatures
   */
  public function testUpdateSignatures()
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
        ->postJson(route('onboarding.terms.signatures'), $data);
    $content = json_decode($response->getContent(), true);
    $response->assertStatus(200);
  }
}
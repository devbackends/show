<?php


namespace Tests\unit\Devvly\OnBoarding\Controllers;


use Tests\unit\Devvly\OnBoarding\BaseTest;

/**
 * Class OnBoardingControllerTest
 *
 * @coversDefaultClass \Devvly\OnBoarding\Http\Controllers\DocumentsController
 */
class DocumentsControllerTest extends BaseTest
{
  use AppHelper;

  /**
   * @covers ::voidedCheck
   */
  public function testVoidedCheck()
  {
    $app = $this->createApp();
    $number = $app->getMerchantNumber();
    // update business info profile:
    $this->updateBusinessInfo($number);

    // update sales profile:
    $this->updateSalesProfile($number);
    // create bank account:
    $account = $this->createBankAccount($number);
    $file = $this->testUpload('voided_check');
    $route = route('onboarding.documents.voided_check');
    $body = [
        'merchant_number' => $number,
        'bank_account_id' => $account['bank_account_id'],
        'voided_check_path' => $file,
    ];
    $response = $this
        ->actingAs($this->user,'admin')
        ->postJson($route, $body);
    $content = json_decode($response->getContent(), true);
    $response->assertStatus(200);
    $this->assertArrayHasKey('document_id', $content);
  }
}
<?php


namespace Tests\unit\Devvly\OnBoarding\Controllers;


use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Application as AppModel;
use Devvly\OnBoarding\Clearent\Resources\Applications;
use Devvly\OnBoarding\Clearent\Responses\Application;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Tests\unit\Devvly\OnBoarding\BaseTest;

/**
 * Class OnBoardingControllerTest
 *
 * @coversDefaultClass \Devvly\OnBoarding\Http\Controllers\BankAccountController
 */
class BankAccountControllerTest extends BaseTest
{
  use AppHelper;

  /**
   * @covers ::show
   */
  public function testShow()
  {
    // todo: test this
    $this->assertTrue(true);
  }

  /**
   * @covers ::update
   */
  public function testUpdate()
  {
    $app = $this->createApp();

    // update business info profile:
    $this->updateBusinessInfo($app->getMerchantNumber());

    // update sales profile:
    $salesProfile  = $this->updateSalesProfile($app->getMerchantNumber());
    $number = $salesProfile['merchant_number'];
    // create bank account:
    $this->createBankAccount($number);
  }
}
<?php


namespace Tests\unit\Devvly\OnBoarding\Controllers;


use Devvly\OnBoarding\Clearent\Models\Application as AppModel;
use Devvly\OnBoarding\Clearent\Responses\Application;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Tests\unit\Devvly\OnBoarding\BaseTest;

/**
 * Class OnBoardingControllerTest
 *
 * @coversDefaultClass \Devvly\OnBoarding\Http\Controllers\SalesProfileController
 */
class SalesProfileControllerTest extends BaseTest
{

  use AppHelper;

  /**
   * @covers ::show
   */
  public function testShow()
  {
    // @todo: test this
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
    $content  = $this->updateSalesProfile($app->getMerchantNumber());
    return $content;
  }
}
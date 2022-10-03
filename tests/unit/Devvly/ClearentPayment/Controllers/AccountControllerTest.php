<?php


namespace Tests\unit\Devvly\ClearentPayment\Controllers;


use Tests\unit\Devvly\ClearentPayment\BaseTest;


/**
 * Class SettingsControllerTest
 *
 * @coversDefaultClass \Devvly\ClearentPayment\Http\Controllers\AccountController
 */
class AccountControllerTest extends BaseTest
{

  use CardHelper;

  /**
   * @covers ::storeCard
   */
  public function testStoreCard()
  {
    $this->createCard();
  }

}
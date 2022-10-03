<?php


namespace Tests\unit\Devvly\ClearentPayment\Controllers;


use Tests\unit\Devvly\ClearentPayment\BaseTest;

/**
 * Class PaymentControllerTest
 *
 * @package Tests\unit\Devvly\ClearentPayment\Controllers
 * @coversDefaultClass \Devvly\ClearentPayment\Http\Controllers\PaymentController
 */
class PaymentControllerTest extends BaseTest
{

  use CardHelper;
  /**
   * @covers ::createCart
   */
  public function testCreateCart()
  {
    return $this->createCart();
  }

  /**
   * @covers ::createCharge
   */
  public function testCreateCharge()
  {
    //$this->testCreateCart();
    $url = route('clearent.make.payment');
    $response = $this
      ->getJson($url);
    $response->isRedirection();
    $response->isRedirect(route('shop.checkout.success'));
  }
}
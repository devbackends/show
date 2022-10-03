<?php


namespace Tests\unit\Devvly\OnBoarding\Controllers;


use Tests\unit\Devvly\OnBoarding\BaseTest;

/**
 * Class EquipmentsControllerTest
 *
 * @package Tests\unit\Devvly\OnBoarding\Controllers
 *
 * @coversDefaultClass \Devvly\OnBoarding\Http\Controllers\EquipmentsController
 */
class EquipmentsControllerTest extends BaseTest
{


  /**
   * @covers ::products
   */
  public function testProducts()
  {
    $route = route('onboarding.equipments.products','Devvly');
    $response = $this->actingAs($this->user, 'admin')->getJson($route);
    $content = json_decode($response->getContent(),true);
    $response->assertStatus(200);
    return $content;
  }

  /**
   * @covers ::getSurvey
   */
  public function testGetSurvey()
  {
//    $prodcuts = $this->testProducts();
//    $item = $prodcuts['products']['content'][0];
    $name = "Devvly (JavaScript)";
    $route = route('onboarding.equipments.survey', $name);
    $response = $this->actingAs($this->user, 'admin')->getJson($route);
    $content = json_decode($response->getContent(),true);
    $response->assertStatus(200);
  }
}
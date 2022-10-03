<?php


namespace Tests\unit\Devvly\ClearentPayment\Controllers;


use Tests\unit\Devvly\ClearentPayment\BaseTest;


/**
 * Class SettingsControllerTest
 *
 * @coversDefaultClass \Devvly\ClearentPayment\Http\Controllers\SettingsController
 */
class SettingsControllerTest extends BaseTest
{

  /**
   * @covers ::settings
   */
  public function testSettings()
  {
    $route = route('clearent.settings.get');
    $response = $this
        ->actingAs($this->customer,'customer')
        ->getJson($route);
    $response->assertStatus(200);
    $content = json_decode($response->getContent(), true);
    $this->assertIsArray($content);
    $this->assertArrayHasKey('url', $content);
    $this->assertArrayHasKey('public_key', $content);
  }

}
<?php


namespace Tests\unit\Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Responses\Demographics\SalesProfile;
use Tests\TestCase;

/**
 * Class SalesProfileTest
 *
 * @package Tests\unit\Devvly\OnBoarding\Clearent\Responses\Demographics
 * @coversDefaultClass \Devvly\OnBoarding\Clearent\Responses\Demographics\SalesProfile
 */
class SalesProfileTest extends TestCase
{

  /**
   * @covers ::__construct
   */
  public function testConstruct()
  {
    $json_path = __DIR__ . "/../../../assets/sales_profile.json";
    $content = file_get_contents($json_path);
    $data = json_decode($content, true);
    $salesProfile = new SalesProfile($data);
    $this->assertInstanceOf(SalesProfile::class, $salesProfile);
  }
}
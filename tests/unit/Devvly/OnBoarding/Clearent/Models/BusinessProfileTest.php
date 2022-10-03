<?php


namespace Tests\unit\Devvly\OnBoarding\Clearent\Models;


use Devvly\OnBoarding\Clearent\Models\Helpers\BusinessInformation;
use Tests\TestCase;

/**
 * Class ModelTest
 *
 * @package Tests\unit\Devvly\OnBoarding\Clearent\Models
 * @coversDefaultClass  \Devvly\OnBoarding\Clearent\Models\Helpers\BusinessInformation
 */
class BusinessProfileTest extends TestCase
{

  /**
   * @covers ::__construct
   */
  public function testConstruct()
  {
    $json_path = __DIR__."/../../assets/business_information.json";
    $data = file_get_contents($json_path);
    $data = json_decode($data,true);
    $profile = new BusinessInformation($data);
    $this->assertInstanceOf(BusinessInformation::class, $profile);
  }

  /**
   * @covers ::toArray
   */
  public function testToArray()
  {
    $json_path = __DIR__."/../../assets/business_information.json";
    $data = file_get_contents($json_path);
    $data = json_decode($data,true);
    $profile = new BusinessInformation($data);
    $this->assertInstanceOf(BusinessInformation::class, $profile);
    $profileArray = $profile->toArray(true);
    $this->allElementsAreArray($profileArray);
    $this->assertArrayNotHasKey('phone_type_code_i_d', $profileArray['merchant']['phones'][0]);
    $this->assertArrayHasKey('phone_type_code_id', $profileArray['merchant']['phones'][0]);
    $this->assertArrayNotHasKey('tin_type_i_d', $profileArray['tax_payer']);
    $this->assertArrayHasKey('tin_type_id', $profileArray['tax_payer']);
    $stop = null;
  }

  protected function allElementsAreArray($data)
  {
    foreach ($data as $item) {
      if(is_array($item)){
        $this->allElementsAreArray($item);
      }
      else{
        $this->assertIsNotObject($item);
      }
    }
  }
}
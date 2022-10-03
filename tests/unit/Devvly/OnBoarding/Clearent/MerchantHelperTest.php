<?php


namespace Tests\unit\Devvly\OnBoarding\Clearent;


use Devvly\OnBoarding\Clearent\ClearentHelper;
use Devvly\OnBoarding\Clearent\Models\Application as AppModel;
use Devvly\OnBoarding\Clearent\Responses\Application;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\Demographics\SalesProfile;
use Devvly\OnBoarding\Clearent\Responses\Merchant\Merchant;
use Tests\TestCase;

/**
 * Class MerchantHelperTest
 *
 * @package Tests\unit\Devvly\OnBoarding\Clearent
 *
 * @coversDefaultClass \Devvly\OnBoarding\Clearent\ClearentHelper
 */
class MerchantHelperTest extends TestCase
{

  /**
   * @covers ::createMerchant
   */
  public function testCreateMerchant()
  {
    $json_path = __DIR__."/../assets/application_data.json";
    $data = file_get_contents($json_path);
    $data = json_decode($data,true);
    $resources = $this->app->get(Resources::class);
    $app = new AppModel();
    $res = $resources->applications()->create($app);
    $data['merchant_number'] = $res->getMerchantNumber();
    $helper = $this->app->get(ClearentHelper::class);
    $result = $helper->createMerchant($data);
    $this->assertInstanceOf(Merchant::class, $result);
  }

  /**
   * @covers ::createSalesProfile
   */
  public function testCreateSalesProfile()
  {
    $json_path = __DIR__."/../assets/application_data.json";
    $data = file_get_contents($json_path);
    $data = json_decode($data,true);
    $helper = $this->app->get(ClearentHelper::class);
    $resources = $this->app->get(Resources::class);
    $app = new AppModel();
    $app->setDbaName('test');
    $app = $resources->applications()->create($app);
    $this->assertInstanceOf(Application::class, $app);
    $result = $helper->createSalesProfile($app->getMerchantNumber(), $data);
    $this->assertInstanceOf(SalesProfile::class, $result);
    $stop = null;
  }
}
<?php


namespace Tests\unit\Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Application;
use Devvly\OnBoarding\Clearent\Resources\Applications;
use Tests\TestCase;

/**
 * Class ApplicationsTest
 *
 * @package Tests\unit\Devvly\OnBoarding\Clearent\Resources
 * @coversDefaultClass \Devvly\OnBoarding\Clearent\Resources\Applications
 */
class ApplicationsTest extends TestCase
{

  /**
   * @covers ::create
   */
  public function testCreate()
  {
    $options = new Application();
    $createResponse = [
        'merchantNumber' => '6588000000055806',
        'metadata' => [
            'exchangeId' => 'ID-ee9110e9-1d71-4ea8-8c80-029454133755',
            'timestamp' => '2020-05-04T22:31:25.9832623Z'
        ],
    ];
    $methods = get_class_methods(Client::class);
    $client = $this->getMockBuilder(Client::class)
        ->disableOriginalConstructor()
        ->setMethods($methods)
        ->getMock();
    $client->expects($this->any())->method('post')->willReturn($createResponse);
    $options->setDbaName('Test Application');
    $service = new Applications($client);
    $res = $service->create($options);
    $this->assertInstanceOf(Application::class, $res);
    $this->assertSame($createResponse['merchantNumber'],$res->getMerchantNumber());
  }
}
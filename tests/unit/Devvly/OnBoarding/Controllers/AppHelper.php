<?php


namespace Tests\unit\Devvly\OnBoarding\Controllers;


use Devvly\OnBoarding\Clearent\Models\Application as AppModel;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\Application;

trait AppHelper
{

  /**
   * @return Application
   */
  public function createApp()
  {
    $resources = $this->app->get(Resources::class);
    $app = new AppModel();
    $app = $resources->applications()->create($app);
    $this->assertInstanceOf(Application::class, $app);
    return $app;
  }
  /**
   * This updates/creates business info.
   *
   * @return mixed
   */
  public function updateBusinessInfo($number)
  {$json_path = __DIR__."/../assets/business_information.json";
    $data = file_get_contents($json_path);
    $data = json_decode($data,true);
    $data['merchant']['merchant_number'] = $number;
    $route = route('onboarding.business_information.update', $number);
    $response = $this
        ->actingAs($this->user,'admin')
        ->putJson($route, $data);
    $response->assertStatus(200);
    $content = json_decode($response->getContent(), true);
    $this->assertArrayHasKey('merchant', $content);
    $this->assertArrayHasKey('merchant', $content);
    $this->assertArrayHasKey('physical_address', $content);
    $this->assertArrayHasKey('mailing_address', $content);
    $this->assertArrayHasKey('business_contacts', $content);
    $this->assertArrayHasKey('tax_payer', $content);
    $contactId = $content['business_contacts'][0]['business_contact_id'];
    $data['business_contacts'][0]['business_contact_id'] = $contactId;
    // test actual updating of merchant:
    $response = $this
        ->actingAs($this->user,'admin')
        ->putJson($route, $data);
    $resContent = json_decode($response->getContent(), true);
    $response->assertStatus(200);
    return $content;
  }

  public function updateSalesProfile($number)
  {
    // update sales profile:
    $json_path = __DIR__."/../assets/sales_profile.json";
    $profileData = file_get_contents($json_path);
    $profileData = json_decode($profileData,true);
    $image_path = $this->testUpload();
    $profileData['firearms_license_document_path'] = $image_path;
    //$this->mockClient();
    //$types = $resources->references()->getFutureDeliveryTypes();
    $route = route('onboarding.sales_profile.update', $number);
    $response = $this->actingAs($this->user,'admin')->putJson($route, $profileData);
    $content = json_decode($response->getContent(),true);
    $response->assertStatus(200);
    $content['merchant_number'] = $number;
    return $content;
  }

  public function createBankAccount($number)
  {
    // create bank account:
    $json_path = __DIR__."/../assets/bank_account.json";
    $accountData = file_get_contents($json_path);
    $accountData = json_decode($accountData,true);
    // upload voided check
    $accountData['voided_check_document_path'] = $this->testUpload('voided_check');
    $route = route('onboarding.banking.update', $number);
    $response = $this->actingAs($this->user,'admin')->putJson($route, $accountData);
    $content = json_decode($response->getContent(),true);
    $response->assertStatus(200);
    $this->assertArrayHasKey('voided_check_document_id', $content);
    $this->assertNotEmpty($content['voided_check_document_id']);

    // update bank account:
    $accountData['bank_account_id'] = $content['bank_account_id'];
    $response = $this->actingAs($this->user,'admin')->putJson($route, $accountData);
    $content = json_decode($response->getContent(), true);
    $response->assertStatus(200);
    $accountData['merchant_number'] = $number;
    return $accountData;
  }

}
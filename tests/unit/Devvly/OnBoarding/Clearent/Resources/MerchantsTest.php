<?php


namespace Tests\unit\Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Application;
use Devvly\OnBoarding\Clearent\Models\Merchant\Merchant as MerchantModel;
use Devvly\OnBoarding\Clearent\Models\Merchant\Phone;
use Devvly\OnBoarding\Clearent\Models\Merchant\SalesInformation;
use Devvly\OnBoarding\Clearent\Models\Merchant\SeasonalSchedule;
use Devvly\OnBoarding\Clearent\Resources\Applications;
use Devvly\OnBoarding\Clearent\Resources\References;
use Devvly\OnBoarding\Clearent\Resources\Merchants;
use Devvly\OnBoarding\Clearent\Responses\Merchant\Merchant;
use Tests\TestCase;

/**
 * Class ApplicationsTest
 *
 * @package Tests\unit\Devvly\OnBoarding\Clearent\Resources
 * @coversDefaultClass \Devvly\OnBoarding\Clearent\Resources\Merchants
 */
class MerchantsTest extends TestCase
{

  /**
   * @covers ::create
   */
  public function testCreate()
  {
    $model = $this->generateModel();
    $service = new Merchants($this->app->get(Client::class));
    $res = $service->create($model);
    $this->assertInstanceOf(Merchant::class, $res);
  }

  /**
   * @return MerchantModel
   */
  protected function generateModel(){
    $client = $this->app->get(Client::class);
    $app = new Applications($client);
    $options = new Application();
    $options->setDbaName('Test');
    $createdApp = $app->create($options);
    $references = new References($client);
    $mmc = $references->getMccCodes();
    $companyTypes = $references->getCompanyTypes();
    $companyType = $companyTypes[1];
    $phoneTypes = $references->getPhoneTypes();
    $phoneType = $phoneTypes[0];
    $phone = new Phone();
    $phone->setPhoneTypeCodeID($phoneType->getPhoneTypeCodeID());
    $phone->setAreaCode("248");
    $phone->setPhoneNumber('8888888');
    $phone->setExtension("001");
    $phones = [$phone];

    $seasonalSchedule = new SeasonalSchedule();
    $seasonalSchedule->setJanuary(true);
    $seasonalSchedule->setFebruary(true);
    $seasonalSchedule->setMarch(true);
    $seasonalSchedule->setApril(true);
    $seasonalSchedule->setMay(true);
    $seasonalSchedule->setJune(true);
    $seasonalSchedule->setJuly(true);
    $seasonalSchedule->setAugust(true);
    $seasonalSchedule->setSeptember(true);
    $seasonalSchedule->setOctober(true);
    $seasonalSchedule->setNovember(true);
    $seasonalSchedule->setDecember(true);

    $salesInfo = new SalesInformation();
    $salesInfo->setBusinessID(1);
    $salesInfo->setAssignedUser(0);
    $salesInfo->setReferralPartner('test');
    $salesInfo->setCompensationType(0);

    $model = new MerchantModel();
    $model->setBusinessId($mmc[0]->getMccCode());
    $model->setDbaName('Test Application');
    $model->setMerchantNumber($createdApp->getMerchantNumber());
    $model->setEmailAddress('test@test.com');
    $model->setWebsite('test.com');
    $model->setPhones($phones);
    $model->setAcceptsPaperStatements(true);
    $model->setAcceptsPaperTaxForms(true);
    $model->setCompanyTypeId($companyType->getId());
    $model->setSeasonalSchedule($seasonalSchedule);
    return $model;
  }
}
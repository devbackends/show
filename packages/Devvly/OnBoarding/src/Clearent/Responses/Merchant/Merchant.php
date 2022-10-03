<?php


namespace Devvly\OnBoarding\Clearent\Responses\Merchant;


use Devvly\OnBoarding\Clearent\Models\Merchant\MerchantAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class Merchant extends Response
{

  use MerchantAttributes;

  /** @var Link[] */
  protected $links;

  public function __construct($data)
  {
    parent::__construct($data);
    $this->setAttributes($data);
  }

  protected function setAttributes($data)
  {
    $linksKeys = [
        'self',
        'physicalAddress',
        'mailingAddress',
        'salesProfile',
        'siteSurvey',
        'taxpayer',
        'businessContact',
        'beneficialOwnerAgreement',
        'salesInformation',
        'documents',
        'termsAndConditions',
        'signatures'
    ];
    $this->links = [];
    foreach ($linksKeys as $key) {
      $data['_links'][$key]['id'] = $key;
      $this->links[] = new Link($data['_links'][$key]);
    }
    $phonesData = $data['phones'];
    $phones = [];
    foreach ($phonesData as $phonesDatum) {
      $phones[] = new Phone($phonesDatum);
    }
    $this->phones = $phones;
    $this->seasonalSchedule = new SeasonalSchedule($data['seasonalSchedule']);
    if(isset($data['salesInformation'])){
      $this->salesInformation = new SalesInformation($data['salesInformation']);
    }
  }

  /**
   * @return \Devvly\OnBoarding\Clearent\Responses\Merchant\Link[]
   */
  public function getLinks()
  {
    return $this->links;
  }

}
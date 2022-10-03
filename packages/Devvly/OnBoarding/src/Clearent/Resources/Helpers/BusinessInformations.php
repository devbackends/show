<?php


namespace Devvly\OnBoarding\Clearent\Resources\Helpers;


use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Demographics\SalesProfile;
use Devvly\OnBoarding\Clearent\Models\Demographics\SiteSurvey;
use Devvly\OnBoarding\Clearent\Resources\ICrudResource;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Clearent\Responses\Helpers\BusinessInformation as Response;
use Devvly\OnBoarding\Clearent\Models\Helpers\BusinessInformation as Model;
use Devvly\OnBoarding\Clearent\Responses\Helpers\BusinessInformation;
use Devvly\OnBoarding\Helpers\ClearentHelper;
use Devvly\OnBoarding\Helpers\JsonHelper;
class BusinessInformations extends ICrudResource
{
  use JsonHelper;
  use ClearentHelper;

  protected $resources;
  public function __construct(Client $client, Resources $resources)
  {
    parent::__construct($client);
    $this->resources = $resources;
  }

  protected function getResponseClass()
  {
    Response::class;
  }


  /**
   * @param $id
   *
   * @return BusinessInformation
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function get($id)
  {
    // 1. get the merchant
    $merchant = $this->resources->merchants()->get($id);

    // 2. get physical address
    $physicalAddress = $this->resources->physicalAddresses()->get($id);

    // 3. get mailing address
    $mailingAddress = $this->resources->mailingAddresses()->get($id);

    // 4. get business contacts:
    $businessContacts = $this->resources->businessContacts()->get($id);

    // due to the returned contacts not the updated ones, we need to get them by id:
    $contacts = [];
    foreach ($businessContacts as $businessContact) {
      $contacts[] = $this->resources->businessContacts()->getItem($businessContact->getBusinessContactId(), $id);
    }
    // 5. get tax payer:
    $taxPayer = $this->resources->taxPayers()->get($id);
    $data = [
        'merchant' => $merchant,
        'physicalAddress' => $physicalAddress,
        'mailingAddress' => $mailingAddress,
        'businessContacts' => $contacts,
        'taxPayer' => $taxPayer,
    ];
    return new BusinessInformation($data);
  }

  public function all()
  {
    // TODO: Implement all() method.
  }

  /**
   * @param  Model $options
   *
   * @return mixed|void
   */
  public function create($options)
  {
    // TODO: Implement create() method.
  }


  /**
   * @param  string  $number The merchant number
   * @param  Model  $options The profile data
   *
   * @return BusinessInformation
   * @throws ClearentException
   */
  public function update($number, $options)
  {

    // 1. create merchant:
    $merchant = $this->resources->merchants()->createOrUpdate($options->getMerchant());

    // 2. update physical address
    $phyisical = $this->resources->physicalAddresses()->update($number, $options->getMailingAddress());

    // 3. update mailing address
    $mailing = $this->resources->mailingAddresses()->update($number, $options->getPhysicalAddress());

    // 4. create business contacts:
    $contacts = [];
    foreach ($options->getBusinessContacts() as $businessContact) {
      if ($businessContact->getBusinessContactId()) {
        $contacts[] = $this->resources->businessContacts()->update($businessContact->getBusinessContactId(), $number, $businessContact);
      }
      else {
        $contacts[] = $this->resources->businessContacts()->create($number, $businessContact);
      }
    }
    // 5. update tax payer:
    $taxPayer = $this->resources->taxPayers()->update($number, $options->getTaxPayer());

    // 6. update site survey:
    $siteSurvey = $this->updateSiteSurvey($number);
    $data = [
        'merchant' => $merchant,
        'physicalAddress' => $phyisical,
        'mailingAddress' => $mailing,
        'businessContacts' => $contacts,
        'taxPayer' => $taxPayer,
    ];
    // 7. update sales profile:
    //$salesProfile = $this->updateSalesProfile($number, $data);
    return new BusinessInformation($data);
  }

  /**
   * @param $number
   *
   * @return \Devvly\OnBoarding\Clearent\Responses\Demographics\SiteSurvey
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  protected function updateSiteSurvey($number)
  {
    $survey = new SiteSurvey();
    // 1. Other
    // 2. Brick & Mortar
    // 3. Tradeshow
    $survey->setSiteTypeID(1);
    // required if site type id is 1:
    $survey->setOtherSiteTypeDescription('e-commerce');

    $survey->setSiteSurveyConductedInPerson(true);

    // optional:
    // 1. Rep called merchant
    // 2. Merchant called rep
    // 3. Web lead
    //$survey->setMerchantAcquisitionTypeID(3);
    $survey->setValidIDVerified(true);
    $survey->setInventoryMatchesProductSold(true);
    $survey->setAgreementAccepted(true);
    $res = $this->resources->siteSurveys()->update($number, $survey);
    if ($res instanceof ClearentException) {
      throw $res;
    }
    return $res;
  }

  /**
   * @param $number
   * @param $data
   *
   * @return \Devvly\OnBoarding\Clearent\Responses\Demographics\SalesProfile
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function updateSalesProfile($number, $data){
    $profile = new SalesProfile();
    $profile->setIsECommerce(true);
    $profile->setMccCode($data['mcc']);
    $profile->setCardPresentPercentage(0);
    $profile->setCardNotPresentPercentage(100);
    // @todo: update the refund policy:
    $profile->setReturnRefundPolicy("http://test.com/policy");
    $profile->setProductsSold($data['products_sold']);
    $profile->setPreviouslyAcceptedPaymentCards($data['accept_credit_cards']);
    $profile->setPreviouslyTerminatedOrIdentifiedByRiskMonitoring($data['terminated']);
    $profile->setReasonPreviouslyTerminatedOrIdentifiedByRisk($data['termination_cause']);
    $profile->setCurrentlyOpenForBusiness($data['open_for_business']);
    $profile->setAnnualVolume($data['estimated_annual_volume']);
    $profile->setAverageTicket($data['estimated_average_ticket']);
    $profile->setHighTicket($data['highest_ticket']);
    $profile->setSellsFirearms($data['sells_firearms']);
    $profile->setSellsFirearmAccessories($data['sells_firearms_accessories']);
    if($data['sells_firearms']){
      $profile->setFireArmsLicense($data['ffl_number']);
    }
    $percentage = (int) $data['future_delivery_percentage'];
    $profile->setFutureDeliveryPercentage($percentage);
    if ($percentage > 0) {
      $delivery_type = $data['future_delivery_type'];
      $profile->setFutureDeliveryTypeID($delivery_type);
      if ($delivery_type === "3") {
        $profile->setOtherDeliveryType($data['future_delivery_timing']);
      }
    }
    //1 American Express OptBlue
    //2 Debit Network
    //3 Discover
    //4 MasterCard
    //5 Visa
    //6 EBT
    //7 American Express ESA
    //$profile->setAmexMID($data['amex_mid']);
    //$profile->setEbtNumber($data['ebt_number']);
    $brands = [2,3,4,5];
    if (isset($data['ebt_number'])) {
      $profile->setEbtNumber($data['ebt_number']);
      $brands[] = 6;
    }
    if (isset($data['amex_mid'])) {
      $profile->setAmexMID($data['amex_mid']);
      $brands[] = 7;
    }
    else {
      $brands[] = 1;
    }
    $profile->setCardBrands($brands);
    $result = $this->resources->salesProfiles()->update($number, $profile);
    if ($result instanceof ClearentException) {
      throw $result;
    }
    return $result;
  }

  public function delete($id)
  {
    // TODO: Implement delete() method.
  }

}
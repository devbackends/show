<?php


namespace Devvly\OnBoarding\Clearent;


use Devvly\OnBoarding\Clearent\Models\Demographics\Address;
use Devvly\OnBoarding\Clearent\Models\Demographics\BusinessContact;
use Devvly\OnBoarding\Clearent\Models\Demographics\Contact;
use Devvly\OnBoarding\Clearent\Models\Demographics\ContactType;
use Devvly\OnBoarding\Clearent\Models\Demographics\SalesProfile;
use Devvly\OnBoarding\Clearent\Models\Demographics\SiteSurvey;
use Devvly\OnBoarding\Clearent\Models\Merchant\Merchant as MerchantOptions;
use Devvly\OnBoarding\Clearent\Models\Merchant\Phone;
use Devvly\OnBoarding\Clearent\Models\Merchant\SeasonalSchedule;
use Devvly\OnBoarding\Clearent\Models\Demographics\TaxPayer;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Clearent\Responses\Merchant\Merchant;

class MerchantHelper
{
  /** @var Resources */
  protected $resources;

  /**
   * MerchantHelper constructor.
   *
   * @param  \Devvly\OnBoarding\Clearent\Resources\Resources  $resources
   */
  public function __construct(Resources $resources)
  {
    $this->resources = $resources;
  }

  /**
   * @param array $data
   *
   * @return Merchant|ClearentException
   */
  public function createMerchant($data)
  {
    $data = $this->transformData($data);
    // create application:
    $merchant_number = $data['merchant_number'];
    // create merchant:
    $phone = $this->generatePhone($data['business_phone']);
    $phones = [$phone];
    $merchant = new MerchantOptions();
    $merchant->setDbaName($data['dba_name']);
    $merchant->setMerchantNumber($merchant_number);
    $merchant->setEmailAddress($data['business_email']);
    $merchant->setWebsite($data['business_website']);
    $merchant->setPhones($phones);
    $merchant->setAcceptsPaperStatements($data['receive_statements_online']);
    $merchant->setAcceptsPaperTaxForms($data['receive_tax_online']);
    $merchant->setCompanyTypeId((int)$data['company_type']);
    $schedule = new SeasonalSchedule();
    if(!$data['seasonal_business']){
      $schedule->setAll();
    }
    else {
      foreach ($data['business_months'] as $businessMonth) {
        $schedule->set($businessMonth, true);
      }
    }
    $merchant->setSeasonalSchedule($schedule);
    $createdMerchant = $this->resources->merchants()->create($merchant);
    if($createdMerchant instanceof ClearentException){
      return $createdMerchant;
    }

    // update the physical and mailing addresses:
    $physicalAddress = new Address();
    $physicalAddress->setLine1($data['business_address']);
    $physicalAddress->setCity($data['business_city']);
    $physicalAddress->setStateCode($data['business_state']);
    $physicalAddress->setZip($data['business_zip']);
    // 840: USA
    $physicalAddress->setCountryCode(840);
    $mailingAddress = $physicalAddress;
    if(!$data['mailing_address_same']){
      $mailingAddress = new Address();
      $mailingAddress->setLine1($data['mailing_address']);
      $mailingAddress->setCity($data['mailing_city']);
      $mailingAddress->setStateCode($data['mailing_state']);
      $mailingAddress->setZip($data['mailing_zip']);
      $mailingAddress->setCountryCode(840);
    }
    $addressResult = $this->resources
        ->physicalAddresses()
        ->update($merchant_number, $physicalAddress);
    if($addressResult instanceof ClearentException){
      return $addressResult;
    }
    $mailingAddressResult = $this->resources
        ->mailingAddresses()
        ->update($merchant_number, $mailingAddress);
    if($mailingAddressResult instanceof ClearentException){
      return $mailingAddressResult;
    }
    $salesProfile = $this->createSalesProfile($merchant_number, $data);
    $taxPayer = $this->createTaxPayer($merchant_number, $data);
    $survey = $this->createSiteSurvey($merchant_number, $data);
    foreach ($data['contact_persons'] as $contactPerson) {
      $this->createBusinessContact($merchant_number, $contactPerson);
    }

    return $createdMerchant;
  }

  public function createSalesProfile($merchantNumber, $data){
    $profile = new SalesProfile();
    $profile->setIsECommerce(true);
    $profile->setMccCode($data['mcc']);
    $profile->setCardPresentPercentage(0);
    // @todo: update the refund policy:
    $profile->setReturnRefundPolicy("http://test.com/policy");
    $profile->setProductsSold("test");
    $profile->setPreviouslyAcceptedPaymentCards($data['accept_credit_cards']);
    $profile->setPreviouslyTerminatedOrIdentifiedByRiskMonitoring($data['terminated']);
    $profile->setReasonPreviouslyTerminatedOrIdentifiedByRisk($data['termination_cause']);
    $profile->setCurrentlyOpenForBusiness($data['open_for_business']);
    $profile->setAnnualVolume($data['estimated_annual_volume']);
    $profile->setAverageTicket($data['estimated_average_ticket']);
    $profile->setHighTicket($data['highest_ticket']);
    $profile->setSellsFirearms($data['sells_firearms']);
    $profile->setSellsFirearmAccessories($data['sells_firearms_accessories']);
    $profile->setFireArmsLicense($data['ffl_number']);
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
    $result = $this->resources->salesProfiles()->update($merchantNumber, $profile);
    return $result;
  }
  protected function createTaxPayer($merchantNumber, $data)
  {
    $taxPayer = new TaxPayer();
    $taxPayer->setTin((int)$data['federal_tax_id']);
    // options are 1 - Tin, 2 - Ssn
    $taxPayer->setTinTypeID(1);
    $taxPayer->setStateIncorporatedCode($data['legal_state']);
    if ($data['same_as_dba']) {
      $taxPayer->setBusinessLegalName($data['dba_name']);
    }
    else {
      $taxPayer->setBusinessLegalName($data['legal_name']);
    }
    $res = $this->resources->taxPayers()->update($merchantNumber, $taxPayer);
    return $res;
  }
  protected function createSiteSurvey($merchantNumber, $data)
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
    $res = $this->resources->siteSurveys()->update($merchantNumber, $survey);
    return $res;
  }
  protected function createBusinessContact($merchantNumber, $data)
  {
    $contact = new Contact();
    $contact->setFirstName($data['first_name']);
    $contact->setLastName($data['last_name']);
    $contactType = new ContactType();
    // 1. signer
    // 2. owner
    // 3. general contact
    $type = (int) $data['type'];
    $contactType->setContactTypeID($type);
    $businessContact = new BusinessContact();
    $businessContact->setEmailAddress($data['email']);
    $phone = $this->generatePhone($data['phone']);
    $businessContact->setPhoneNumbers([$phone]);
    $businessContact->setContactTypes([$contactType]);
    if($type == 1 || $type == 2){
      $contact->setSsn($data['ssn']);
      $contact->setDateOfBirth($data['date_of_birth']);
      // 840: USA
      $contact->setCountryOfCitizenshipCode($data['country']);
      $address = new Address();
      $address->setLine1($data['address_line_1']);
      $address->setLine2($data['address_line_2']);
      $address->setCity($data['city']);
      $address->setStateCode($data['state']);
      $address->setZip($data['zip']);
      $address->setCountryCode($data['country']);
      $contact->setAddress($address);
      $businessContact->setTitle($data['title']);
      $businessContact->setOwnershipAmount($data['ownership_percentage']);
    }
    $businessContact->setContact($contact);
    $res = $this->resources->businessContacts()->create($merchantNumber, $businessContact);
    $stop = null;
  }

  public function transformData($data){
    foreach ($data as $key => $value) {
      if ($value === "true" || $value === "false") {
        $data[$key] = $value === "true";
      }
    }
    return $data;
  }

  public function generatePhone(string $number)
  {
    $phone = new Phone();
    $phoneData = explode('-', $number);
    $phone->setAreaCode($phoneData[0]);
    $phone->setPhoneNumber($phoneData[1]);
    if(isset($phoneData[2])){
      $phone->setExtension($phoneData[2]);
    }
    // 5. work
    $phone->setPhoneTypeCodeID(5);
    return $phone;
  }
  public function createMerchantProfile($data)
  {
    $stop = null;
  }
}
<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class SalesProfile extends Model
{

  use SalesProfileAttributes;
  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if($underscore_keys){
      unset($data['is_e_commerce']);
      $data['is_ecommerce'] = $this->isECommerce;
      unset($data['future_delivery_type_i_d']);
      $data['future_delivery_type_id'] = $this->futureDeliveryTypeID;
      unset($data['amex_m_i_d']);
      $data['amex_mid'] = $this->amexMID;
    }
    return $data;
  }

  /**
   * @param  bool  $isECommerce
   */
  public function setIsECommerce($isECommerce): void
  {
    $this->isECommerce = $isECommerce;
  }

  /**
   * @param  string  $mccCode
   */
  public function setMccCode(string $mccCode): void
  {
    $this->mccCode = $mccCode;
  }

  /**
   * @param  int  $cardPresentPercentage
   */
  public function setCardPresentPercentage(int $cardPresentPercentage): void
  {
    $this->cardPresentPercentage = $cardPresentPercentage;
  }

  /**
   * @param  int  $percentage
   */
  public function setCardNotPresentPercentage(int $percentage): void
  {
    $this->cardNotPresentPercentage = $percentage;
  }

  /**
   * @param  string  $returnRefundPolicy
   */
  public function setReturnRefundPolicy(string $returnRefundPolicy): void
  {
    $this->returnRefundPolicy = $returnRefundPolicy;
  }

  /**
   * @param  string  $productsSold
   */
  public function setProductsSold(string $productsSold): void
  {
    $this->productsSold = $productsSold;
  }

  /**
   * @param  bool  $previouslyAcceptedPaymentCards
   */
  public function setPreviouslyAcceptedPaymentCards(
      bool $previouslyAcceptedPaymentCards
  ): void {
    $this->previouslyAcceptedPaymentCards = $previouslyAcceptedPaymentCards;
  }

  /**
   * @param  int  $previousProcessorId
   */
  public function setPreviousProcessorId($previousProcessorId): void
  {
    $this->previousProcessorId = $previousProcessorId;
  }

  /**
   * @param  bool  $previouslyTerminatedOrIdentifiedByRiskMonitoring
   */
  public function setPreviouslyTerminatedOrIdentifiedByRiskMonitoring(
      $previouslyTerminatedOrIdentifiedByRiskMonitoring
  ): void {
    $this->previouslyTerminatedOrIdentifiedByRiskMonitoring = $previouslyTerminatedOrIdentifiedByRiskMonitoring;
  }

  /**
   * @param  string  $reasonPreviouslyTerminatedOrIdentifiedByRisk
   */
  public function setReasonPreviouslyTerminatedOrIdentifiedByRisk(
      $reasonPreviouslyTerminatedOrIdentifiedByRisk
  ): void {
    $this->reasonPreviouslyTerminatedOrIdentifiedByRisk = $reasonPreviouslyTerminatedOrIdentifiedByRisk;
  }

  /**
   * @param  bool  $currentlyOpenForBusiness
   */
  public function setCurrentlyOpenForBusiness(bool $currentlyOpenForBusiness
  ): void {
    $this->currentlyOpenForBusiness = $currentlyOpenForBusiness;
  }

  /**
   * @param  int  $annualVolume
   */
  public function setAnnualVolume(int $annualVolume): void
  {
    $this->annualVolume = $annualVolume;
  }

  /**
   * @param  int  $averageTicket
   */
  public function setAverageTicket(int $averageTicket): void
  {
    $this->averageTicket = $averageTicket;
  }

  /**
   * @param  int  $highTicket
   */
  public function setHighTicket(int $highTicket): void
  {
    $this->highTicket = $highTicket;
  }

  /**
   * @param  bool  $ownsProduct
   */
  public function setOwnsProduct($ownsProduct): void
  {
    $this->ownsProduct = $ownsProduct;
  }

  /**
   * @param  bool  $ordersProduct
   */
  public function setOrdersProduct($ordersProduct): void
  {
    $this->ordersProduct = $ordersProduct;
  }

  /**
   * @param  bool  $sellsFirearms
   */
  public function setSellsFirearms(bool $sellsFirearms): void
  {
    $this->sellsFirearms = $sellsFirearms;
  }

  /**
   * @param  bool  $sellsFirearmAccessories
   */
  public function setSellsFirearmAccessories(bool $sellsFirearmAccessories
  ): void {
    $this->sellsFirearmAccessories = $sellsFirearmAccessories;
  }

  /**
   * @param  int  $futureDeliveryTypeID
   */
  public function setFutureDeliveryTypeID($futureDeliveryTypeID): void
  {
    $this->futureDeliveryTypeID = $futureDeliveryTypeID;
  }

  /**
   * @param  string  $otherDeliveryType
   */
  public function setOtherDeliveryType($otherDeliveryType): void
  {
    $this->otherDeliveryType = $otherDeliveryType;
  }

  /**
   * @param  int  $futureDeliveryPercentage
   */
  public function setFutureDeliveryPercentage($futureDeliveryPercentage
  ): void {
    $this->futureDeliveryPercentage = $futureDeliveryPercentage;
  }

  /**
   * @param  string  $fireArmsLicense
   */
  public function setFireArmsLicense($fireArmsLicense): void
  {
    $this->fireArmsLicense = $fireArmsLicense;
  }

  /**
   * @param  array  $cardBrands
   */
  public function setCardBrands(array $cardBrands): void
  {
    $this->cardBrands = $cardBrands;
  }

  /**
   * @param  string  $ebtNumber
   */
  public function setEbtNumber($ebtNumber): void
  {
    $this->ebtNumber = $ebtNumber;
  }

  /**
   * @param  string  $amexMID
   */
  public function setAmexMID($amexMID): void
  {
    $this->amexMID = $amexMID;
  }

  /**
   * @param  string  $firearmsLicenseDocumentPath
   */
  public function setFirearmsLicenseDocumentPath(string $firearmsLicenseDocumentPath): void
  {
    $this->firearmsLicenseDocumentPath = $firearmsLicenseDocumentPath;
  }
}
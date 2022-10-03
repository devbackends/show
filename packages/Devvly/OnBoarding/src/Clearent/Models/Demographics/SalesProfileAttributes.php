<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


trait SalesProfileAttributes
{
  /** @var bool */
  protected $isECommerce;

  /** @var string */
  protected $mccCode;

  /** @var int*/
  protected $cardPresentPercentage;

  /** @var int*/
  protected $cardNotPresentPercentage;

  /** @var string */
  protected $returnRefundPolicy;

  /** @var string */
  protected $productsSold;

  /** @var bool */
  protected $previouslyAcceptedPaymentCards;

  /** @var int */
  protected $previousProcessorId;

  /** @var bool */
  protected $previouslyTerminatedOrIdentifiedByRiskMonitoring;

  /** @var string */
  protected $reasonPreviouslyTerminatedOrIdentifiedByRisk;

  /** @var bool */
  protected $currentlyOpenForBusiness;

  // @todo: check if this is float:
  /** @var int */
  protected $annualVolume;

  /** @var int */
  protected $averageTicket;

  /** @var int */
  protected $highTicket;

  /** @var bool */
  protected $ownsProduct;

  /** @var bool */
  protected $ordersProduct;

  /** @var bool */
  protected $sellsFirearms;

  /** @var bool */
  protected $sellsFirearmAccessories;

  /** @var string */
  protected $fireArmsLicense;

  /** @var int */
  protected $futureDeliveryTypeID;

  /** @var string */
  protected $otherDeliveryType;

  /** @var int */
  protected $futureDeliveryPercentage;

  /** @var array */
  protected $cardBrands;

  /** @var string */
  protected $ebtNumber;

  /** @var string */
  protected $amexMID;

  /** @var int */
  protected $competitorId;

  /** @var string */
  protected $firearmsLicenseDocumentPath;

  /**
   * @return bool
   */
  public function isECommerce(): bool
  {
    return $this->isECommerce;
  }

  /**
   * @return string
   */
  public function getMccCode(): string
  {
    return $this->mccCode;
  }

  /**
   * @return int
   */
  public function getCardPresentPercentage(): int
  {
    return $this->cardPresentPercentage;
  }

  /**
   * @return int
   */
  public function getCardNotPresentPercentage(): int
  {
    return $this->cardNotPresentPercentage;
  }

  /**
   * @return string
   */
  public function getReturnRefundPolicy(): string
  {
    return $this->returnRefundPolicy;
  }

  /**
   * @return string
   */
  public function getProductsSold(): string
  {
    return $this->productsSold;
  }

  /**
   * @return bool
   */
  public function isPreviouslyAcceptedPaymentCards(): bool
  {
    return $this->previouslyAcceptedPaymentCards;
  }

  /**
   * @return int
   */
  public function getPreviousProcessorId(): int
  {
    return $this->previousProcessorId;
  }

  /**
   * @return bool
   */
  public function isPreviouslyTerminatedOrIdentifiedByRiskMonitoring(): bool
  {
    return $this->previouslyTerminatedOrIdentifiedByRiskMonitoring;
  }

  /**
   * @return string
   */
  public function getReasonPreviouslyTerminatedOrIdentifiedByRisk(): string
  {
    return $this->reasonPreviouslyTerminatedOrIdentifiedByRisk;
  }

  /**
   * @return bool
   */
  public function isCurrentlyOpenForBusiness(): bool
  {
    return $this->currentlyOpenForBusiness;
  }

  /**
   * @return int
   */
  public function getAnnualVolume(): int
  {
    return $this->annualVolume;
  }

  /**
   * @return int
   */
  public function getAverageTicket(): int
  {
    return $this->averageTicket;
  }

  /**
   * @return int
   */
  public function getHighTicket(): int
  {
    return $this->highTicket;
  }

  /**
   * @return bool
   */
  public function isOwnsProduct(): bool
  {
    return $this->ownsProduct;
  }

  /**
   * @return bool
   */
  public function isOrdersProduct(): bool
  {
    return $this->ordersProduct;
  }

  /**
   * @return bool
   */
  public function isSellsFirearms(): bool
  {
    return $this->sellsFirearms;
  }

  /**
   * @return bool
   */
  public function isSellsFirearmAccessories(): bool
  {
    return $this->sellsFirearmAccessories;
  }

  /**
   * @return int
   */
  public function getFutureDeliveryTypeID(): int
  {
    return $this->futureDeliveryTypeID;
  }

  /**
   * @return string
   */
  public function getOtherDeliveryType(): string
  {
    return $this->otherDeliveryType;
  }

  /**
   * @return int
   */
  public function getFutureDeliveryPercentage(): int
  {
    return $this->futureDeliveryPercentage;
  }

  /**
   * @return string
   */
  public function getFireArmsLicense(): string
  {
    return $this->fireArmsLicense;
  }

  /**
   * @return array
   */
  public function getCardBrands(): array
  {
    return $this->cardBrands;
  }

  /**
   * @return string
   */
  public function getEbtNumber(): string
  {
    return $this->ebtNumber;
  }

  /**
   * @return string
   */
  public function getAmexMID(): string
  {
    return $this->amexMID;
  }

  /**
   * @param  mixed  $competitorId
   */
  public function setCompetitorId($competitorId): void
  {
    $this->competitorId = $competitorId;
  }

  /**
   * @return string
   */
  public function getFirearmsLicenseDocumentPath(): string
  {
    return $this->firearmsLicenseDocumentPath;
  }
}
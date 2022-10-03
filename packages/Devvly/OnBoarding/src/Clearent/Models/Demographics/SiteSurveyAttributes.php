<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


trait SiteSurveyAttributes
{
  /** @var int */
  protected $siteTypeID;

  /** @var string */
  protected $otherSiteTypeDescription;

  /** @var bool */
  protected $siteSurveyConductedInPerson;

  /** @var string */
  protected $merchantAcquisitionTypeID;

  /** @var bool */
  protected $validIDVerified;

  /** @var bool */
  protected $inventoryMatchesProductSold;

  /** @var string */
  protected $inventoryMatchesProductSoldComments;

  /** @var bool */
  protected $agreementAccepted;

  /**
   * @return int
   */
  public function getSiteTypeID(): int
  {
    return $this->siteTypeID;
  }

  /**
   * @return string
   */
  public function getOtherSiteTypeDescription(): string
  {
    return $this->otherSiteTypeDescription;
  }

  /**
   * @return bool
   */
  public function getSiteSurveyConductedInPerson(): bool
  {
    return $this->siteSurveyConductedInPerson;
  }

  /**
   * @return string
   */
  public function getMerchantAcquisitionTypeID(): string
  {
    return $this->merchantAcquisitionTypeID;
  }

  /**
   * @return bool
   */
  public function isValidIDVerified(): bool
  {
    return $this->validIDVerified;
  }

  /**
   * @return bool
   */
  public function isInventoryMatchesProductSold(): bool
  {
    return $this->inventoryMatchesProductSold;
  }

  /**
   * @return string
   */
  public function getInventoryMatchesProductSoldComments(): string
  {
    return $this->inventoryMatchesProductSoldComments;
  }

  /**
   * @return bool
   */
  public function getAgreementAccepted(): bool
  {
    return $this->agreementAccepted;
  }
}
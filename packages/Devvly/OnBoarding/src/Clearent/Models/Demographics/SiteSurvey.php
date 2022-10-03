<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class SiteSurvey extends Model
{
  use SiteSurveyAttributes;

  /**
   * @param  int  $siteTypeID
   */
  public function setSiteTypeID(int $siteTypeID): void
  {
    $this->siteTypeID = $siteTypeID;
  }

  /**
   * @param  string  $otherSiteTypeDescription
   */
  public function setOtherSiteTypeDescription(string $otherSiteTypeDescription
  ): void {
    $this->otherSiteTypeDescription = $otherSiteTypeDescription;
  }

  /**
   * @param  bool  $siteSurveyConductedInPerson
   */
  public function setSiteSurveyConductedInPerson(
      bool $siteSurveyConductedInPerson
  ): void {
    $this->siteSurveyConductedInPerson = $siteSurveyConductedInPerson;
  }

  /**
   * @param  string  $merchantAcquisitionTypeID
   */
  public function setMerchantAcquisitionTypeID(string $merchantAcquisitionTypeID
  ): void {
    $this->merchantAcquisitionTypeID = $merchantAcquisitionTypeID;
  }

  /**
   * @param  bool  $validIDVerified
   */
  public function setValidIDVerified(bool $validIDVerified): void
  {
    $this->validIDVerified = $validIDVerified;
  }

  /**
   * @param  bool  $inventoryMatchesProductSold
   */
  public function setInventoryMatchesProductSold(
      bool $inventoryMatchesProductSold
  ): void {
    $this->inventoryMatchesProductSold = $inventoryMatchesProductSold;
  }

  /**
   * @param  string  $inventoryMatchesProductSoldComments
   */
  public function setInventoryMatchesProductSoldComments(
      string $inventoryMatchesProductSoldComments
  ): void {
    $this->inventoryMatchesProductSoldComments = $inventoryMatchesProductSoldComments;
  }

  /**
   * @param  bool  $agreementAccepted
   */
  public function setAgreementAccepted(bool $agreementAccepted): void
  {
    $this->agreementAccepted = $agreementAccepted;
  }
}
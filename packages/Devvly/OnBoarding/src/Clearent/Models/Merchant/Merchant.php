<?php


namespace Devvly\OnBoarding\Clearent\Models\Merchant;


use Devvly\OnBoarding\Clearent\Models\Model;

class Merchant extends Model
{

  use MerchantAttributes;

  /**
   * @param  int  $businessId
   */
  public function setBusinessId($businessId)
  {
    $this->businessId = $businessId;
  }

  /**
   * @param  string  $hierarchyNodeKey
   */
  public function setHierarchyNodeKey($hierarchyNodeKey)
  {
    $this->hierarchyNodeKey = $hierarchyNodeKey;
  }

  /**
   * @param  string  $dbaName
   */
  public function setDbaName($dbaName)
  {
    $this->dbaName = $dbaName;
  }

  /**
   * @param  string  $merchantNumber
   */
  public function setMerchantNumber($merchantNumber)
  {
    $this->merchantNumber = $merchantNumber;
  }

  /**
   * @param  string  $emailAddress
   */
  public function setEmailAddress($emailAddress)
  {
    $this->emailAddress = $emailAddress;
  }

  /**
   * @param  string  $website
   */
  public function setWebSite($website)
  {
    $this->webSite = $website;
  }

  /**
   * @param  Phone[]  $phones
   */
  public function setPhones($phones)
  {
    $this->phones = [];
    foreach ($phones as $phone) {
      if (!$phone instanceof Phone) {
        $phone = new Phone($phone);
      }
      $this->phones[] = $phone;
    }
  }

  /**
   * @param  bool  $acceptsPaperStatements
   */
  public function setAcceptsPaperStatements($acceptsPaperStatements)
  {
    $this->acceptsPaperStatements = $acceptsPaperStatements;
  }

  /**
   * @param  bool  $acceptsPaperTaxForms
   */
  public function setAcceptsPaperTaxForms($acceptsPaperTaxForms)
  {
    $this->acceptsPaperTaxForms = $acceptsPaperTaxForms;
  }

  /**
   * @param  int  $companyTypeId
   */
  public function setCompanyTypeId($companyTypeId)
  {
    $this->companyTypeId = $companyTypeId;
  }

  /**
   * @param  SeasonalSchedule  $seasonalSchedule
   */
  public function setSeasonalSchedule($seasonalSchedule)
  {
    if (!$seasonalSchedule instanceof SeasonalSchedule) {
      $seasonalSchedule = new SeasonalSchedule($seasonalSchedule);
    }
    $this->seasonalSchedule = $seasonalSchedule;
  }

  /**
   * @param  SalesInformation $salesInformation
   */
  public function setSalesInformation($salesInformation)
  {
    if (!$salesInformation instanceof SalesInformation) {
      $salesInformation = new SalesInformation($salesInformation);
    }
    $this->salesInformation = $salesInformation;
  }

}
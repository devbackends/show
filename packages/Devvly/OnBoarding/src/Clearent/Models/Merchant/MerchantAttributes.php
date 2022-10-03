<?php


namespace Devvly\OnBoarding\Clearent\Models\Merchant;


trait MerchantAttributes
{
  /** @var int */
  protected $businessId;

  /** @var string */
  protected $hierarchyNodeKey;

  /** @var string */
  protected $dbaName;

  /** @var string */
  protected $merchantNumber;

  /** @var string */
  protected $emailAddress;

  /** @var string */
  protected $webSite;

  /** @var Phone[] */
  protected $phones;

  /** @var bool */
  protected $acceptsPaperStatements;

  /** @var bool */
  protected $acceptsPaperTaxForms;

  /** @var int */
  protected $companyTypeId;

  /** @var SeasonalSchedule */
  protected $seasonalSchedule;

  /** @var SalesInformation */
  protected $salesInformation;

  /**
   * @return int
   */
  public function getBusinessId()
  {
    return $this->businessId;
  }

  /**
   * @return string
   */
  public function getHierarchyNodeKey()
  {
    return $this->hierarchyNodeKey;
  }

  /**
   * @return string
   */
  public function getDbaName()
  {
    return $this->dbaName;
  }

  /**
   * @return string
   */
  public function getMerchantNumber()
  {
    return $this->merchantNumber;
  }

  /**
   * @return string
   */
  public function getEmailAddress()
  {
    return $this->emailAddress;
  }

  /**
   * @return string
   */
  public function getWebSite()
  {
    return $this->webSite;
  }

  /**
   * @return Phone[]
   */
  public function getPhones()
  {
    return $this->phones;
  }

  /**
   * @return bool
   */
  public function isAcceptsPaperStatements()
  {
    return $this->acceptsPaperStatements;
  }

  /**
   * @return bool
   */
  public function isAcceptsPaperTaxForms()
  {
    return $this->acceptsPaperTaxForms;
  }

  /**
   * @return int
   */
  public function getCompanyTypeId()
  {
    return $this->companyTypeId;
  }

  /**
   * @return SeasonalSchedule
   */
  public function getSeasonalSchedule()
  {
    return $this->seasonalSchedule;
  }

  /**
   * @return SalesInformation
   */
  public function getSalesInformation()
  {
    return $this->salesInformation;
  }

}
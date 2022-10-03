<?php


namespace Devvly\OnBoarding\Clearent\Models\Merchant;


trait SalesInformationAttributes
{
  /** @var int */
  protected $businessID;

  /** @var int */
  protected $assignedUser;

  /** @var string */
  protected $referralPartner;

  /** @var int */
  protected $compensationType;

  /**
   * @return int
   */
  public function getBusinessID()
  {
    return $this->businessID;
  }

  /**
   * @return int
   */
  public function getAssignedUser()
  {
    return $this->assignedUser;
  }

  /**
   * @return string
   */
  public function getReferralPartner()
  {
    return $this->referralPartner;
  }

  /**
   * @return int
   */
  public function getCompensationType()
  {
    return $this->compensationType;
  }
}
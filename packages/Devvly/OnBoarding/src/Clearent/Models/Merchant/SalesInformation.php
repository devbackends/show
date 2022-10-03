<?php


namespace Devvly\OnBoarding\Clearent\Models\Merchant;


use Devvly\OnBoarding\Clearent\Models\Model;

class SalesInformation extends Model
{
  use SalesInformationAttributes;

  /**
   * @param  int  $businessID
   */
  public function setBusinessID($businessID)
  {
    $this->businessID = $businessID;
  }

  /**
   * @param  int  $assignedUser
   */
  public function setAssignedUser($assignedUser)
  {
    $this->assignedUser = $assignedUser;
  }

  /**
   * @param  string  $referralPartner
   */
  public function setReferralPartner($referralPartner)
  {
    $this->referralPartner = $referralPartner;
  }

  /**
   * @param  int  $compensationType
   */
  public function setCompensationType($compensationType)
  {
    $this->compensationType = $compensationType;
  }

}
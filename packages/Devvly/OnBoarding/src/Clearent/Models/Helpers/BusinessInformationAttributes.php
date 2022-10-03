<?php


namespace Devvly\OnBoarding\Clearent\Models\Helpers;

use Devvly\OnBoarding\Clearent\Models\Merchant\Merchant;
use Devvly\OnBoarding\Clearent\Models\Demographics\Address;
use Devvly\OnBoarding\Clearent\Models\Demographics\BusinessContact;
use Devvly\OnBoarding\Clearent\Models\Demographics\TaxPayer;

/**
 * Trait BusinessProfileAttributes
 *
 * @package Devvly\OnBoarding\Clearent\Models\Helpers
 */
trait BusinessInformationAttributes
{
  /** @var Merchant */
  protected $merchant;

  /** @var Address */
  protected $physicalAddress;

  /** @var Address */
  protected $mailingAddress;

  /** @var BusinessContact[] */
  protected $businessContacts;

  /** @var TaxPayer */
  protected $taxPayer;

  /**
   * @return Merchant
   */
  public function getMerchant()
  {
    return $this->merchant;
  }

  /**
   * @return Address
   */
  public function getPhysicalAddress()
  {
    return $this->physicalAddress;
  }

  /**
   * @return Address
   */
  public function getMailingAddress()
  {
    return $this->mailingAddress;
  }

  /**
   * @return BusinessContact[]
   */
  public function getBusinessContacts()
  {
    return $this->businessContacts;
  }

  /**
   * @return TaxPayer
   */
  public function getTaxPayer()
  {
    return $this->taxPayer;
  }
}
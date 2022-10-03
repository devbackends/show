<?php


namespace Devvly\OnBoarding\Clearent\Models\Helpers;


use Devvly\OnBoarding\Clearent\Models\Demographics\Address;
use Devvly\OnBoarding\Clearent\Models\Demographics\BusinessContact;
use Devvly\OnBoarding\Clearent\Models\Demographics\TaxPayer;
use Devvly\OnBoarding\Clearent\Models\Merchant\Merchant;
use Devvly\OnBoarding\Clearent\Models\Model;
use Devvly\OnBoarding\Helpers\JsonHelper;

/**
 * Class BusinessProfile
 *
 * @package Devvly\OnBoarding\Clearent\Models\Helpers
 */
class BusinessInformation extends Model
{
  use BusinessInformationAttributes;
  use JsonHelper;

  /**
   * @param  Merchant  $merchant
   */
  public function setMerchant($merchant)
  {
    if(!$merchant instanceof Merchant) {
      $merchant = new Merchant($merchant);
    }
    $this->merchant = $merchant;
  }

  /**
   * @param  Address  $physicalAddress
   */
  public function setPhysicalAddress($physicalAddress)
  {
    if (!$physicalAddress instanceof Address) {
      $physicalAddress = new Address($physicalAddress);

    }
    $this->physicalAddress = $physicalAddress;
  }

  /**
   * @param  Address  $mailingAddress
   */
  public function setMailingAddress($mailingAddress)
  {
    if (!$mailingAddress instanceof Address) {
      $mailingAddress = new Address($mailingAddress);
    }
    $this->mailingAddress = $mailingAddress;
  }

  /**
   * @param  BusinessContact[]  $businessContacts
   */
  public function setBusinessContacts($businessContacts)
  {
    $this->businessContacts = array();
    foreach ($businessContacts as $businessContact) {
      if (!$businessContact instanceof BusinessContact) {
        $businessContact = new BusinessContact($businessContact);
      }
      $this->businessContacts[] = $businessContact;
    }
  }

  /**
   * @param  TaxPayer  $taxPayer
   */
  public function setTaxPayer($taxPayer)
  {
    if (!$taxPayer instanceof TaxPayer) {
      $taxPayer = new TaxPayer($taxPayer);
    }
    $this->taxPayer = $taxPayer;
  }



}
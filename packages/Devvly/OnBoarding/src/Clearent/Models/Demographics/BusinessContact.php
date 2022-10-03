<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Merchant\Phone;
use Devvly\OnBoarding\Clearent\Models\Model;

class BusinessContact extends Model
{
  use BusinessContactAttributes;

  /**
   * @param  int  $businessContactId
   */
  public function setBusinessContactId(int $businessContactId): void
  {
    $this->businessContactId = $businessContactId;
  }

  /**
   * @param  Contact  $contact
   */
  public function setContact($contact): void
  {
    if (!$contact instanceof Contact) {
      $contact = new Contact($contact);
    }
    $this->contact = $contact;
  }

  /**
   * @param  int  $ownershipAmount
   */
  public function setOwnershipAmount(int $ownershipAmount): void
  {
    $this->ownershipAmount = $ownershipAmount;
  }

  /**
   * @param  string  $emailAddress
   */
  public function setEmailAddress(string $emailAddress): void
  {
    $this->emailAddress = $emailAddress;
  }

  /**
   * @param  string  $title
   */
  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  /**
   * @param  Phone[]  $phoneNumbers
   */
  public function setPhoneNumbers(array $phoneNumbers): void
  {
    $this->phoneNumbers = array();
    foreach ($phoneNumbers as $phoneNumber) {
      if (!$phoneNumber instanceof Phone) {
        $phoneNumber = new Phone($phoneNumber);
      }
      $this->phoneNumbers[] = $phoneNumber;
    }
  }

  /**
   * @param  ContactType[]  $contactTypes
   */
  public function setContactTypes(array $contactTypes): void
  {
    $this->contactTypes = array();
    foreach ($contactTypes as $contactType) {
      if (!$contactType instanceof ContactType) {
        $contactType = new ContactType($contactType);
      }
      $this->contactTypes[] = $contactType;
    }
  }

  /**
   * @param  bool  $isCompassUser
   */
  public function setIsCompassUser(bool $isCompassUser): void
  {
    $this->isCompassUser = $isCompassUser;
  }

  /**
   * @param  bool  $isAuthorizedToPurchase
   */
  public function setIsAuthorizedToPurchase(bool $isAuthorizedToPurchase): void
  {
    $this->isAuthorizedToPurchase = $isAuthorizedToPurchase;
  }
}
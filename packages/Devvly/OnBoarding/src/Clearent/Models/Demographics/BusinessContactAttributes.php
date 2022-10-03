<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;

use Devvly\OnBoarding\Clearent\Models\Merchant\Phone;

trait BusinessContactAttributes
{
  /** @var int */
  protected $businessContactId;

  /** @var Contact */
  protected $contact;

  /** @var int */
  protected $ownershipAmount;

  /** @var string */
  protected $emailAddress;

  /** @var string */
  protected $title;

  /** @var Phone[] */
  protected $phoneNumbers;

  /** @var ContactType[] */
  protected $contactTypes;

  /** @var bool */
  protected $isCompassUser;

  /** @var bool */
  protected $isAuthorizedToPurchase;

  /**
   * @return int
   */
  public function getBusinessContactId(): ?int
  {
    return $this->businessContactId;
  }

  /**
   * @return Contact
   */
  public function getContact(): Contact
  {
    return $this->contact;
  }

  /**
   * @return int
   */
  public function getOwnershipAmount(): int
  {
    return $this->ownershipAmount;
  }

  /**
   * @return string
   */
  public function getEmailAddress(): string
  {
    return $this->emailAddress;
  }

  /**
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @return Phone[]
   */
  public function getPhoneNumbers(): array
  {
    return $this->phoneNumbers;
  }

  /**
   * @return ContactType[]
   */
  public function getContactTypes(): array
  {
    return $this->contactTypes;
  }

  /**
   * @return bool
   */
  public function isCompassUser(): bool
  {
    return $this->isCompassUser;
  }

  /**
   * @return bool
   */
  public function isAuthorizedToPurchase(): bool
  {
    return $this->isAuthorizedToPurchase;
  }

}
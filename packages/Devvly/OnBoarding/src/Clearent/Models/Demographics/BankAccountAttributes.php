<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


trait BankAccountAttributes
{
  /** @var int */
  protected $bankAccountID;

  /** @var string */
  protected $bankName;

  /** @var string */
  protected $nameOnAccount;

  /** @var string */
  protected $accountHolderFirstName;

  protected $accountHolderLastName;

  /** @var int */
  protected $bankAccountTypeID;

  /** @var int */
  protected $bankAccountNameTypeID;

  /** @var string */
  protected $aba;

  /** @var string */
  protected $accountNumber;

  /** @var string */
  protected $voidedCheckDocumentID;

  /** @var boolean */
  protected $hasFees;

  /** @var boolean */
  protected $hasFunds;

  /** @var boolean */
  protected $hasChargebacks;

  /** @var boolean */
  protected $isNameSameAsLegalOrDBAName;

  /** @var string */
  protected $licenseIdDocumentPath;

  /** @var string */
  protected $voidedCheckDocumentPath;

  /**
   * @return int
   */
  public function getBankAccountID()
  {
    return $this->bankAccountID;
  }

  /**
   * @return string
   */
  public function getBankName(): string
  {
    return $this->bankName;
  }

  /**
   * @return string
   */
  public function getNameOnAccount()
  {
    return $this->nameOnAccount;
  }

  /**
   * @return string
   */
  public function getAccountHolderFirstName()
  {
    return $this->accountHolderFirstName;
  }

  /**
   * @return mixed
   */
  public function getAccountHolderLastName()
  {
    return $this->accountHolderLastName;
  }

  /**
   * @return int
   */
  public function getBankAccountTypeID(): int
  {
    return $this->bankAccountTypeID;
  }

  /**
   * @return int
   */
  public function getBankAccountNameTypeID(): int
  {
    return $this->bankAccountNameTypeID;
  }

  /**
   * @return string
   */
  public function getAba(): string
  {
    return $this->aba;
  }

  /**
   * @return string
   */
  public function getAccountNumber(): string
  {
    return $this->accountNumber;
  }

  /**
   * @return string
   */
  public function getVoidedCheckDocumentID()
  {
    return $this->voidedCheckDocumentID;
  }

  /**
   * @return bool
   */
  public function isHasFees(): bool
  {
    return $this->hasFees;
  }

  /**
   * @return bool
   */
  public function isHasFunds(): bool
  {
    return $this->hasFunds;
  }

  /**
   * @return bool
   */
  public function isHasChargebacks(): bool
  {
    return $this->hasChargebacks;
  }

  /**
   * @return bool
   */
  public function isNameSameAsLegalOrDBAName()
  {
    return $this->isNameSameAsLegalOrDBAName;
  }

  /**
   * @return string
   */
  public function getLicenseIdDocumentPath(): string
  {
    return $this->licenseIdDocumentPath;
  }

  /**
   * @return string
   */
  public function getVoidedCheckDocumentPath(): string
  {
    return $this->voidedCheckDocumentPath;
  }
}
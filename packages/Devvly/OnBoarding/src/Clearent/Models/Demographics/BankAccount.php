<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class BankAccount extends Model
{
  use BankAccountAttributes;

  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if($underscore_keys){
      if(isset($data['bank_account_i_d'])){
        unset($data['bank_account_i_d']);
        $data['bank_account_id'] = $this->bankAccountID;
      }
      if(isset($data['bank_account_type_i_d'])){
        unset($data['bank_account_type_i_d']);
        $data['bank_account_type_id'] = $this->bankAccountTypeID;
      }
      if(isset($data['bank_account_name_type_i_d'])){
        unset($data['bank_account_name_type_i_d']);
        $data['bank_account_name_type_id'] = $this->bankAccountNameTypeID;
      }
      if(isset($data['voided_check_document_i_d'])){
        unset($data['voided_check_document_i_d']);
        $data['voided_check_document_id'] = $this->voidedCheckDocumentID;
      }
      if(isset($data['is_name_same_as_legal_or_d_b_a_name'])){
        unset($data['is_name_same_as_legal_or_d_b_a_name']);
        $data['is_name_same_as_legal_or_dba_name'] = $this->isNameSameAsLegalOrDBAName;
      }
    }
    return $data;
  }

  /**
   * @param  int  $bankAccountID
   */
  public function setBankAccountID($bankAccountID): void
  {
    $this->bankAccountID = $bankAccountID;
  }

  /**
   * @param  string  $bankName
   */
  public function setBankName(string $bankName): void
  {
    $this->bankName = $bankName;
  }

  /**
   * @param  string  $nameOnAccount
   */
  public function setNameOnAccount($nameOnAccount): void
  {
    $this->nameOnAccount = $nameOnAccount;
  }

  /**
   * @param  string  $accountHolderFirstName
   */
  public function setAccountHolderFirstName($accountHolderFirstName
  ): void {
    $this->accountHolderFirstName = $accountHolderFirstName;
  }

  /**
   * @param  mixed  $accountHolderLastName
   */
  public function setAccountHolderLastName($accountHolderLastName): void
  {
    $this->accountHolderLastName = $accountHolderLastName;
  }

  /**
   * @param  int  $bankAccountTypeID
   */
  public function setBankAccountTypeID(int $bankAccountTypeID): void
  {
    $this->bankAccountTypeID = $bankAccountTypeID;
  }

  /**
   * @param  int  $bankAccountNameTypeID
   */
  public function setBankAccountNameTypeID(int $bankAccountNameTypeID): void
  {
    $this->bankAccountNameTypeID = $bankAccountNameTypeID;
  }

  /**
   * @param  string  $aba
   */
  public function setAba(string $aba): void
  {
    $this->aba = $aba;
  }

  /**
   * @param  string  $accountNumber
   */
  public function setAccountNumber(string $accountNumber): void
  {
    $this->accountNumber = $accountNumber;
  }

  /**
   * @param  string  $voidedCheckDocumentID
   */
  public function setVoidedCheckDocumentID($voidedCheckDocumentID): void
  {
    $this->voidedCheckDocumentID = $voidedCheckDocumentID;
  }

  /**
   * @param  bool  $hasFees
   */
  public function setHasFees(bool $hasFees): void
  {
    $this->hasFees = $hasFees;
  }

  /**
   * @param  bool  $hasFunds
   */
  public function setHasFunds(bool $hasFunds): void
  {
    $this->hasFunds = $hasFunds;
  }

  /**
   * @param  bool  $hasChargebacks
   */
  public function setHasChargebacks(bool $hasChargebacks): void
  {
    $this->hasChargebacks = $hasChargebacks;
  }

  /**
   * @param  bool  $isNameSameAsLegalOrDBAName
   */
  public function setIsNameSameAsLegalOrDBAName($isNameSameAsLegalOrDBAName): void
  {
    $this->isNameSameAsLegalOrDBAName = $isNameSameAsLegalOrDBAName;
  }

  /**
   * @param  string  $licenseIdDocumentPath
   */
  public function setLicenseIdDocumentPath(string $licenseIdDocumentPath): void
  {
    $this->licenseIdDocumentPath = $licenseIdDocumentPath;
  }

  /**
   * @param  string  $voidedCheckDocumentPath
   */
  public function setVoidedCheckDocumentPath(string $voidedCheckDocumentPath): void
  {
    $this->voidedCheckDocumentPath = $voidedCheckDocumentPath;
  }
}
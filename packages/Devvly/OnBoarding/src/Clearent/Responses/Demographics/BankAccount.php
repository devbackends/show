<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Demographics\BankAccountAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class BankAccount extends Response
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
}
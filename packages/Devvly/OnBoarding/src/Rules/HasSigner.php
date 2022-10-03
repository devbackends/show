<?php


namespace Devvly\OnBoarding\Rules;


use Illuminate\Contracts\Validation\ImplicitRule;

class HasSigner implements ImplicitRule
{
  public function __construct()
  {
  }

  public function passes($attribute, $value)
  {
    if(!is_array($value)){
      return false;
    }
    $has_signer = false;
    foreach ($value as $business_contact) {
      if(!isset($business_contact['contact_types'])){
        return false;
      }
      foreach ($business_contact['contact_types'] as $contactType) {
        if(isset($contactType['contact_type_id'])){
          if($contactType['contact_type_id'] === 1){
            $has_signer = true;
          }
        }
      }
    }
    return $has_signer;
  }

  public function message()
  {
    return "The Business Contacts should at least have one signer.";
  }

}
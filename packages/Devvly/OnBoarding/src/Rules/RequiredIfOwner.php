<?php


namespace Devvly\OnBoarding\Rules;


use Illuminate\Contracts\Validation\ImplicitRule;

class RequiredIfOwner implements ImplicitRule
{
  protected $businessContacts;
  public function __construct($businessContacts)
  {
    $this->businessContacts = $businessContacts;
  }

  public function passes($attribute, $value)
  {
    $chunks = explode('.', $attribute);
    $key = null;
    foreach ($chunks as $chunk) {
      if (is_numeric($chunk)) {
        $key = (int) $chunk;
      }
    }
    if (is_null($key)) {
      return false;
    }
    $is_owner = false;
    foreach ($this->businessContacts[$key]['contact_types'] as $contactType) {
      if (isset($contactType['contact_type_id'])) {
        $type = (int) $contactType['contact_type_id'];
        if ($type == 1 || $type == 2) {
          $is_owner = true;
        }
      }
      else {
        return false;
      }
    }
    if ($is_owner) {
      if (!empty($value)) {
        return true;
      }
    }
    return false;
  }

  public function message()
  {
    return "The :attribute is required when the contact is owner or signer.";
  }

}
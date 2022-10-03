<?php


namespace Devvly\OnBoarding\Rules;


use Illuminate\Contracts\Validation\Rule;

class IsPhone implements Rule
{

  public function passes($attribute, $value)
  {
    $matches = [];
    preg_match('/[0-9]{3}-([0-9]{7}-[0-9]{3}|[0-9]{7})/i', $value, $matches);
    return count($matches);
  }

  public function message()
  {
    return "Phone number is not valid.";
  }

}
<?php


namespace Devvly\OnBoarding\Rules;


use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class BirthDate implements Rule
{

  public function passes($attribute, $value)
  {
    $current_date = Carbon::now(env('APP_TIMEZONE'));
    $date = Carbon::parse($value);
    $diff = $date->diffInYears($current_date);
    return $diff > 17;
  }

  public function message()
  {
    return "Date of birth is not valid.";
  }

}
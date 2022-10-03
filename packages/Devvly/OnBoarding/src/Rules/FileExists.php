<?php


namespace Devvly\OnBoarding\Rules;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class FileExists implements Rule
{

  public function passes($attribute, $value)
  {
    return Storage::disk('local')->exists($value);
  }

  public function message()
  {
    return "File doesn't exist.";
  }

}
<?php


namespace Devvly\OnBoarding\Helpers;


trait JsonHelper
{
  public function transformData($data){
    foreach ($data as $key => $value) {
      if ($value === "true" || $value === "false") {
        $data[$key] = $value === "true";
      }
    }
    return $data;
  }
}
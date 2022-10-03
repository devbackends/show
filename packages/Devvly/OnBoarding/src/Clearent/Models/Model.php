<?php


namespace Devvly\OnBoarding\Clearent\Models;


use Devvly\OnBoarding\Clearent\Responses\Metadata;
use Devvly\OnBoarding\Helpers\JsonHelper;

abstract class Model
{
  use JsonHelper;
  public function __construct($data = null)
  {
    if ($data && is_array($data)) {
      $this->fromArray($data);
    }
    else if($data && is_string($data)) {
      $this->fromString($data);
    }
  }
  public function fromString($data) {}

  /**
   * @param array $data
   */
  public function fromArray($data)
  {
    $data = $this->transformData($data);
    foreach ($data as $key => $value) {
      if(is_null($value)){
        continue;
      }
      $newKey = $this->camelize($key);
      $method = 'set'. lcfirst($newKey);
      if(method_exists($this, $method)){
        $this->{$method}($value);
      }else if(property_exists($this, $key)){
        $this->{$key} = $value;
      }
    }
  }

  /**
   * @param  bool  $underscore_keys
   *
   * @return array
   */
  public function toArray($underscore_keys = false)
  {
    $keys = get_object_vars($this);
    $data = [];
    foreach ($keys as $key => $val) {
      if (!is_null($val)) {
        if ($val instanceof Model || $val instanceof Metadata) {
          $val = $val->toArray($underscore_keys);
        }
        elseif (is_array($val)) {
          $values = [];
          foreach ($val as $i => $subVal) {
            if ($underscore_keys && !is_numeric($i)) {
              $i = $this->underscore($i);
            }
            if ($subVal instanceof Model || $val instanceof Metadata) {
              $values[$i] = $subVal->toArray($underscore_keys);
            }
            else {
              $values[$i] = $subVal;
            }
          }
          $val = $values;
        }
        if ($underscore_keys && !is_numeric($key)) {
          $key = $this->underscore($key);
        }
        $data[$key] = $val;
      }
    }
    return $data;
  }

  protected function camelize($input, $separator = '_')
  {
    return lcfirst(str_replace($separator, '', ucwords($input, $separator)));
  }

  protected function underscore($input)
  {
    if (!is_string($input)) {
      return $input;
    }
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
  }
}
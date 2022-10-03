<?php


namespace Devvly\OnBoarding\Clearent\Responses;


use Devvly\OnBoarding\Clearent\Models\ClientError;
class ClearentException extends \Exception
{

  /** @var Error[] */
  protected $errors;

  /** @var Metadata */
  protected $metadata;

  /**
   * ErrorResponse constructor.
   *
   * @param $data
   * @param $code
   */
  public function __construct($data, $code)
  {
    $this->errors = [];
    if(is_array($data) && isset($data['metadata']) && is_array($data['metadata'])){
      $this->metadata = new Metadata($data);
    }
    $this->createErrors($data, $code);
    parent::__construct($this->errors[0]->getMessage(), $code);
  }

  protected function createErrors($data, $code){
    if(is_string($data)){
      $this->errors[] = new ClientError($data, $code);
    }
    if(is_array($data) && isset($data['errors'])){
      foreach ($data['errors'] as $error) {
        $this->errors[] = new Error($error);
      }
    }
  }

  public function toArray()
  {
    $keys = get_object_vars($this);
    $data = [];
    foreach ($keys as $key => $val) {
      if(!is_null($val)){
        if($val instanceof Response || $val instanceof Metadata){
          $data[$key] = $val->toArray();
        }else if(is_array($val) && $val[0] instanceof Response){
          $values = [];
          foreach ($val as $item) {
            $values[] = $item->toArray();
          }
          $data[$key] = $values;
        }else{
          $data[$key] = $val;
        }
      }
    }
    return $data;
  }

  /**
   * @return Error[]
   */
  public function getErrors()
  {
    return $this->errors;
  }

}
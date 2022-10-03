<?php


namespace Devvly\OnBoarding\Clearent\Responses;



use Devvly\OnBoarding\Clearent\Models\Model;

class Response extends Model
{

  /** @var Metadata */
  protected $metadata;

  public function __construct($data)
  {
    parent::__construct($data);
    if(is_array($data) && isset($data['metadata'])){
      $this->metadata = new Metadata($data);
    }
  }

  /**
   * @return Metadata|null
   */
  public function getMetadata()
  {
    return $this->metadata;
  }

  public function __get($name)
  {
    $vars = get_object_vars($this);
    $vars = array_keys($vars);
    if(in_array($name,$vars)){
      return $this->{$name};
    }
    return null;
  }
}
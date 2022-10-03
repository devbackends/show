<?php


namespace Devvly\OnBoarding\Clearent\Models;


class Application extends Model
{
  /** @var string */
  protected $dbaName;

  /**
   * @return string
   */
  public function getDbaName()
  {
    return $this->dbaName;
  }

  /**
   * @param  string  $dbaName
   */
  public function setDbaName($dbaName)
  {
    $this->dbaName = $dbaName;
  }


}
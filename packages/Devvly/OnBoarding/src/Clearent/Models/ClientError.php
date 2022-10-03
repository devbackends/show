<?php


namespace Devvly\OnBoarding\Clearent\Models;


class ClientError
{
  /** @var string */
  protected $message;

  /** @var int */
  protected $code;

  /**
   * ClientError constructor.
   *
   * @param $message
   * @param $code
   */
  public function __construct($message, $code)
  {
    $this->message = $message;
    $this->code = $code;
  }

  /**
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @return int
   */
  public function getCode()
  {
    return $this->code;
  }


}
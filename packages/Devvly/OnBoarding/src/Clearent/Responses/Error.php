<?php


namespace Devvly\OnBoarding\Clearent\Responses;


class Error extends Response
{
  /** @var string */
  protected $message;

  /** @var int|null */
  protected $resultCode;

  /** @var string */
  protected $timestamp;

  /**
 * Error constructor.
 *
 * @param array $data
 */
  public function __construct($data)
  {
    parent::__construct($data);
    $this->message = $data['errorMessage'];
    $this->resultCode = $data['resultCode'];
    if(isset($data['timestamp'])){
      $this->timestamp = $data['timestamp'];
    }
  }

  /**
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @return int|null
   */
  public function getResultCode()
  {
    return $this->resultCode;
  }

  /**
   * @return string
   */
  public function getTimestamp()
  {
    return $this->timestamp;
  }
}
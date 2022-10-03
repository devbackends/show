<?php


namespace Devvly\Clearent\Models;


class Error extends Payload
{

  /** @var string */
  protected $errorMessage;

  public function __construct(string $payloadType, $content)
  {
    parent::__construct($payloadType, $content);
    if (is_array($content)) {
      if (isset($content['error-message'])) {
        $this->errorMessage = $content['error-message'];
      }
      if (isset($content['display-message'])) {
          $this->errorMessage = $content['display-message'];
      }
    }
  }

  /**
   * @return string
   */
  public function getErrorMessage(): string
  {
    return $this->errorMessage;
  }


}
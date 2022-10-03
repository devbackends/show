<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


use Devvly\OnBoarding\Clearent\Responses\Response;

class SignatureSourceType extends Response
{
  /** @var int */
  protected $signatureSourceTypeId;

  /** @var string */
  protected $signatureSourceTypeName;

  /** @var string */
  protected $signatureSourceTypeDescription;

  public function __construct($data)
  {
    parent::__construct($data);
  }

  /**
   * @return int
   */
  public function getSignatureSourceTypeId()
  {
    return $this->signatureSourceTypeId;
  }

  /**
   * @return string
   */
  public function getSignatureSourceTypeName()
  {
    return $this->signatureSourceTypeName;
  }

  /**
   * @return string
   */
  public function getSignatureSourceTypeDescription()
  {
    return $this->signatureSourceTypeDescription;
  }
}
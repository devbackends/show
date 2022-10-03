<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


class SignatureSection
{
  /** @var int */
  protected $signatureSectionTypeId;

  /** @var string */
  protected $signatureSectionTypeName;

  /** @var string */
  protected $signatureSectionTypeDescription;

  public function __construct($data)
  {
    $this->signatureSectionTypeId = $data['signatureSectionTypeId'];
    $this->signatureSectionTypeName = $data['signatureSectionTypeName'];
    $this->signatureSectionTypeDescription = $data['signatureSectionTypeDescription'];
  }

  /**
   * @return int
   */
  public function getSignatureSectionTypeId()
  {
    return $this->signatureSectionTypeId;
  }

  /**
   * @return string
   */
  public function getSignatureSectionTypeName()
  {
    return $this->signatureSectionTypeName;
  }

  /**
   * @return string
   */
  public function getSignatureSectionTypeDescription()
  {
    return $this->signatureSectionTypeDescription;
  }

}
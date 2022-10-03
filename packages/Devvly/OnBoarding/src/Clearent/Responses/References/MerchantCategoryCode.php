<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


use Devvly\OnBoarding\Clearent\Responses\Response;

class MerchantCategoryCode extends Response
{
  /** @var int */
  protected $mccCode;

  /** @var string */
  protected $mccGroupCode;

  /** @var string */
  protected $mccDescription;

  /** @var int */
  protected $mccMostUsedRank;

  /** @var bool */
  protected $mccIsSupported;


  public function __construct($data)
  {
    $this->mccCode = $data['mccCode'];
    $this->mccGroupCode = $data['mccGroupCode'];
    $this->mccDescription = $data['mccDescription'];
    $this->mccMostUsedRank = $data['mccMostUsedRank'];
    $this->mccIsSupported = $data['mccIsSupported'];
  }

  /**
   * @return int
   */
  public function getMccCode()
  {
    return $this->mccCode;
  }

  /**
   * @return string
   */
  public function getMccGroupCode()
  {
    return $this->mccGroupCode;
  }

  /**
   * @return string
   */
  public function getMccDescription()
  {
    return $this->mccDescription;
  }

  /**
   * @return int
   */
  public function getMccMostUsedRank()
  {
    return $this->mccMostUsedRank;
  }

  /**
   * @return bool
   */
  public function isMccIsSupported()
  {
    return $this->mccIsSupported;
  }
}
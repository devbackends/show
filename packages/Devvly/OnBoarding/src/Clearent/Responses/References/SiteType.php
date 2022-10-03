<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


class SiteType
{
  /** @var int */
  protected $siteTypeID;

  /** @var string */
  protected $siteTypeDescription;


  public function __construct($data)
  {
    $this->siteTypeID = $data['siteTypeID'];
    $this->siteTypeDescription = $data['siteTypeDescription'];
  }

  /**
   * @return int
   */
  public function getSiteTypeID()
  {
    return $this->siteTypeID;
  }

  /**
   * @return string
   */
  public function getSiteTypeDescription()
  {
    return $this->siteTypeDescription;
  }
}
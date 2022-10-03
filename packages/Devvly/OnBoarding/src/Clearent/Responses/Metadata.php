<?php


namespace Devvly\OnBoarding\Clearent\Responses;


class Metadata
{

  /** @var string */
  protected $exchangeId;

  /** @var string */
  protected $timestamp;


  /**
   * Metadata constructor.
   *
   * @param array $data
   */
  public function __construct($data)
  {
    $meta = $data['metadata'];
    $this->exchangeId = $meta['exchangeId'];
    if (isset($meta['timeStamp'])) {
      $this->timestamp = $meta['timeStamp'];
    }
    else if (isset($meta['timestamp'])) {
      $this->timestamp = $meta['timestamp'];
    }
  }

  public function toArray($underscore_keys = false)
  {
    $idKey = "exchangeId";
    if ($underscore_keys) {
      $idKey = "exchange_id";
    }
    $data = [];
    $data[$idKey] = $this->exchangeId;
    $data['timestamp'] = $this->timestamp;
    return $data;
  }


  /**
   * @return string
   */
  public function getExchangeId()
  {
    return $this->exchangeId;
  }

  /**
   * @return string
   */
  public function getTimestamp()
  {
    return $this->timestamp;
  }


}
<?php


namespace Devvly\Clearent\Models;


class Token extends Payload
{

  /** @var string */
  protected $tokenId;

  /** @var int|null */
  protected $timesUsed;

  /** @var string|null */
  protected $status;

  /** @var string */
  protected $created;

  /** @var string|null */
  protected $updated;

  /** @var string|null */
  protected $expDate;

  /** @var string */
  protected $lastFour;

  /** @var string */
  protected $cardType;

  /** @var string|null */
  protected $trackToData;

  /** @var string|null */
  protected $trackFormat;

  public function __construct(string $payloadType, $content, $links = null)
  {
    parent::__construct($payloadType, $content, $links);

    $this->tokenId = (int)$content['token-id'];
    if (isset($content['exp-date'])) {
      $this->expDate = $content['exp-date'];
    }
    if (isset($content['last-four-digits'])) {
      $this->lastFour = $content['last-four-digits'];
    }
    if (isset($content['card-type'])) {
      $this->cardType = $content['card-type'];
    }
    if (isset($content['status'])) {
      $this->status = $content['status'];
    }
    if (isset($content['times-used'])) {
      $this->timesUsed = (int)$content['times-used'];
    }
    if (isset($content['created'])) {
      $this->created = $content['created'];

    }
    if (isset($content['updated'])) {
      $this->created = $content['updated'];

    }
    if (isset($content['track2-data']) && !empty($content['track2-data'])) {
      $this->trackToData = $content['track2-data'];
    }

    if (isset($content['track-format']) && !empty($content['track-format'])) {
      $this->trackFormat = $content['track-format'];
    }

  }

  /**
   * @return string
   */
  public function getTokenId(): string
  {
    return $this->tokenId;
  }

  /**
   * @return int
   */
  public function getTimesUsed(): int
  {
    return $this->timesUsed;
  }

  /**
   * @return string|null
   */
  public function getStatus(): ?string
  {
    return $this->status;
  }

  /**
   * @return string|null
   */
  public function getCreated(): ?string
  {
    return $this->created;
  }

  /**
   * @return string|null
   */
  public function getUpdated(): ?string
  {
    return $this->updated;
  }

  /**
   * @return string
   */
  public function getExpDate(): string
  {
    return $this->expDate;
  }

  /**
   * @return string
   */
  public function getLastFour(): string
  {
    return $this->lastFour;
  }

  /**
   * @return string
   */
  public function getCardType(): string
  {
    return $this->cardType;
  }

  /**
   * @return string|null
   */
  public function getTrackToData(): ?string
  {
    return $this->trackToData;
  }

  /**
   * @return string|null
   */
  public function getTrackFormat(): ?string
  {
    return $this->trackFormat;
  }

}
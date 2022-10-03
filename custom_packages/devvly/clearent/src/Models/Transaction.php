<?php


namespace Devvly\Clearent\Models;


class Transaction extends Payload
{

  /** @var string */
  protected $id;

  /** @var string */
  protected $amount;

  /** @var string */
  protected $type;

  /** @var string */
  protected $result;

  /** @var string */
  protected $card;

  /** @var string|null */
  protected $csc;

  /** @var string */
  protected $authorizationCode;

  /** @var string|null */
  protected $tokenDescription;

  /** @var string */
  protected $batchStringId;

  /** @var string */
  protected $displayMessage;

  /** @var string */
  protected $expDate;

  /** @var string */
  protected $softwareType;

  /** @var string|null */
  protected $cardType;

  /** @var string */
  protected $lastFour;

  /** @var string */
  protected $merchantId;

  /** @var string */
  protected $terminalId;


  public function __construct(string $payloadType, $content, $links)
  {
    parent::__construct($payloadType, $content, $links);
    $this->id = $content['id'];
    $this->amount = $content['amount'];
    $this->type = $content['type'];
    $this->result = $content['result'];
    $this->card = $content['card'];
    if(isset($content['csc'])) {
      $this->csc = $content['csc'];
    }
    $this->authorizationCode = $content['authorization-code'];
    if(isset($content['token-description'])){
        $this->tokenDescription = $content['token-description'];
    }
    $this->batchStringId = $content['batch-string-id'];
    $this->displayMessage = $content['display-message'];
    $this->expDate = $content['exp-date'];
    $this->softwareType = $content['software-type'];
    if(isset($content['card-type'])){
        $this->cardType = $content['card-type'];
    }
    $this->lastFour = $content['last-four'];
    $this->merchantId = $content['merchant-id'];
    $this->terminalId = $content['terminal-id'];
  }

  /**
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getAmount(): string
  {
    return $this->amount;
  }

  /**
   * @return string
   */
  public function getType(): string
  {
    return $this->type;
  }

  /**
   * @return string
   */
  public function getResult(): string
  {
    return $this->result;
  }

  /**
   * @return string
   */
  public function getCard(): string
  {
    return $this->card;
  }

  /**
   * @return string|null
   */
  public function getCsc()
  {
    return $this->csc;
  }

  /**
   * @return string
   */
  public function getAuthorizationCode(): string
  {
    return $this->authorizationCode;
  }

  /**
   * @return string|null
   */
  public function getTokenDescription()
  {
    return $this->tokenDescription;
  }

  /**
   * @return string
   */
  public function getBatchStringId(): string
  {
    return $this->batchStringId;
  }

  /**
   * @return string
   */
  public function getDisplayMessage(): string
  {
    return $this->displayMessage;
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
  public function getSoftwareType(): string
  {
    return $this->softwareType;
  }

  /**
   * @return string|null
   */
  public function getCardType()
  {
    return $this->cardType;
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
  public function getMerchantId(): string
  {
    return $this->merchantId;
  }

  /**
   * @return string
   */
  public function getTerminalId(): string
  {
    return $this->terminalId;
  }
}
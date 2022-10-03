<?php


namespace Devvly\Clearent\Models;


class SaleOptions
{

  /** @var string */
  protected $saleType;

  /** @var string */
  protected $amount;

  /** @var string */
  protected $card;

  protected $jwtToken;


  /** @var string */
  protected $expDate;

  /** @var string */
  protected $tokenDescription;

  /** @var string */
  protected $cardType;

  /** @var string */
  protected $zipCode;

  /** @var bool */
  protected $createCardToken;

  /**
   * @return string
   */
  public function getSaleType(): string
  {
    return $this->saleType;
  }

  /**
   * @param  string  $saleType
   */
  public function setSaleType(string $saleType): void
  {
    $this->saleType = $saleType;
  }

  /**
   * @return string
   */
  public function getAmount(): string
  {
    return $this->amount;
  }

  /**
   * @param  string  $amount
   */
  public function setAmount(string $amount): void
  {
    $this->amount = $amount;
  }


  /**
   * @return string|null
   */
  public function getCard()
  {
    return $this->card;
  }

  /**
   * @param  string  $card
   */
  public function setCard(string $card): void
  {
    $this->card = $card;
  }

  /**
   * @return string
   */
  public function getExpDate(): string
  {
    return $this->expDate;
  }

  /**
   * @param  string  $expDate
   */
  public function setExpDate(string $expDate): void
  {
    $this->expDate = $expDate;
  }

  /**
   * @return string
   */
  public function getTokenDescription(): string
  {
    return $this->tokenDescription;
  }

  /**
   * @param  string  $tokenDescription
   */
  public function setTokenDescription(string $tokenDescription): void
  {
    $this->tokenDescription = $tokenDescription;
  }

  /**
   * @return string
   */
  public function getCardType(): string
  {
    return $this->cardType;
  }

  /**
   * @param  string  $cardType
   */
  public function setCardType(string $cardType): void
  {
    $this->cardType = $cardType;
  }

  /**
   * @return mixed|null
   */
  public function getJwtToken()
  {
    return $this->jwtToken;
  }

  /**
   * @param  mixed  $jwtToken
   */
  public function setJwtToken($jwtToken): void
  {
    $this->jwtToken = $jwtToken;
  }

    /**
     * @return mixed|null
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param  mixed  $zipCode
     */
    public function setZipCode($zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return bool
     */
    public function createCardToken()
    {
        return $this->createCardToken;
    }
    /**
     * @param bool $createCardToken
     */
    public function setCreateCardToken($createCardToken): void
    {
        $this->createCardToken = $createCardToken;
    }

}
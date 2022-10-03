<?php


namespace Devvly\Clearent\Models;


use Carbon\Carbon;

trait PaymentPlanProperties
{

  /** @var string */
  protected $customerKey;

  /** @var string */
  protected $customerName;

  /** @var string */
  protected $planKey;

  /** @var string */
  protected $planName;

  /** @var \Carbon\Carbon */
  protected $startDate;

  /** @var \Carbon\Carbon */
  protected $endDate;

  /** @var string */
  protected $status;

  /** @var Carbon */
  protected $statusDate;

  /** @var string */
  protected $frequency;

  /** @var string */
  protected $paymentAmount;

  /** @var string */
  protected $tokenId;

  /** @var int */
  protected $frequencyDay;

  /** @var int */
  protected $frequencyWeek;

  /** @var int */
  protected $frequencyMonth;

  /**
   * @return string
   */
  public function getCustomerKey(): string
  {
    return $this->customerKey;
  }

  /**
   * @param  string  $customerKey
   */
  public function setCustomerKey(string $customerKey): void
  {
    $this->customerKey = $customerKey;
  }

  /**
   * @param  string  $planKey
   */
  public function setPlanKey(string $planKey): void
  {
    $this->planKey = $planKey;
  }

  /**
   * @return string
   */
  public function getPlanKey(): ?string
  {
    return $this->planKey;
  }


  /**
   * @return string
   */
  public function getCustomerName(): ?string
  {
    return $this->customerName;
  }

  /**
   * @param  string  $customerName
   */
  public function setCustomerName(string $customerName): void
  {
    $this->customerName = $customerName;
  }

  /**
   * @return string
   */
  public function getPlanName(): ?string
  {
    return $this->planName;
  }

  /**
   * @param  string  $planName
   */
  public function setPlanName(string $planName): void
  {
    $this->planName = $planName;
  }

  /**
   * @return Carbon
   */
  public function getStartDate(): Carbon
  {
    return $this->startDate;
  }

  /**
   * @param  string|Carbon  $startDate
   */
  public function setStartDate($startDate): void
  {
    $this->startDate = $this->createDate($startDate);
  }

  /**
   * @return Carbon
   */
  public function getEndDate(): Carbon
  {
    return $this->endDate;
  }

  /**
   * @param  string|Carbon  $endDate
   */
  public function setEndDate($endDate): void
  {
    $this->endDate = $this->createDate($endDate);
  }

  /**
   * @return string
   */
  public function getStatus(): ?string
  {
    return $this->status;
  }

  /**
   * @param  string  $status
   */
  public function setStatus(string $status): void
  {
    $this->status = $status;
  }

  /**
   * @return \Carbon\Carbon
   */
  public function getStatusDate(): ?Carbon
  {
    return $this->statusDate;
  }

  /**
   * @param  string|Carbon  $statusDate
   */
  public function setStatusDate($statusDate): void
  {
    $this->statusDate = $this->createDate($statusDate);
  }

  /**
   * @return string
   */
  public function getFrequency(): string
  {
    return $this->frequency;
  }

  /**
   * @param  string  $frequency
   */
  public function setFrequency(string $frequency): void
  {
    $this->frequency = $frequency;
  }

  /**
   * @return string
   */
  public function getPaymentAmount(): string
  {
    return $this->paymentAmount;
  }

  /**
   * @param  string  $paymentAmount
   */
  public function setPaymentAmount(string $paymentAmount): void
  {
    $this->paymentAmount = $paymentAmount;
  }

  /**
   * @return string
   */
  public function getTokenId(): string
  {
    return $this->tokenId;
  }

  /**
   * @param  string  $tokenId
   */
  public function setTokenId(string $tokenId): void
  {
    $this->tokenId = $tokenId;
  }

  /**
   * @return int
   */
  public function getFrequencyDay(): int
  {
    return $this->frequencyDay;
  }

  /**
   * The day the plan will run, numeric representation of the day
   * for weekly plans it's 1-7
   * for monthly and yearly plans, it's the number of the day in month
   *
   * @param  int  $frequencyDay
   */
  public function setFrequencyDay(int $frequencyDay): void
  {
    $this->frequencyDay = $frequencyDay;
  }

  /**
   * @return int
   */
  public function getFrequencyWeek(): ?int
  {
    return $this->frequencyWeek;
  }

  /**
   * The week the plan will run, for weekly plans only
   * how many weeks until the next billing.
   * eg: for each 3 weeks, then set the value 3.
   *
   * @param  int  $frequencyWeek
   */
  public function setFrequencyWeek(int $frequencyWeek): void
  {
    $this->frequencyWeek = $frequencyWeek;
  }

  /**
   * @return int
   */
  public function getFrequencyMonth(): ?int
  {
    return $this->frequencyMonth;
  }

  /**
   * @param  int  $frequencyMonth
   */
  public function setFrequencyMonth(int $frequencyMonth): void
  {
    $this->frequencyMonth = $frequencyMonth;
  }

  protected function createDate($date)
  {
    if($date instanceof Carbon){
      return $date;
    }
    $timezone = env('APP_TIMEZONE', 'America/Chicago');
    $instance = Carbon::parse($date, $timezone);
    return $instance;
  }
}
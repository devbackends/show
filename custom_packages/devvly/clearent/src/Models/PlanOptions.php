<?php


namespace Devvly\Clearent\Models;



class PlanOptions
{
  use PaymentPlanProperties;

  /** @var string For a weekly plan */
  const PLAN_WEEKLY = "WEEKLY";

  /** @var string For a monthly plan */
  const PLAN_MONTHLY = "MONTHLY";

  /** @var string For a yearly plan */
  const PLAN_YEARLY = "YEARLY";

  /** @var string For an active plan */
  const PLAN_ACTIVE = "ACTIVE";

  /** @var string For a suspended plan */
  const PLAN_SUSPENDED = "SUSPENDED";

  /** @var string For a canceled plan */
  const PLAN_CANCELED = "CANCELED";

  /**
   * gets all properties as array
   *
   * @return array
   */
  public function toArray()
  {
    $properties = [
        'customer-key' => $this->getCustomerKey(),
        'customer-name' => $this->getCustomerName(),
        'plan-key' => $this->getPlanKey(),
        'plan-name' => $this->getPlanName(),
        'start-date' => $this->getStartDate()->format('Y-m-d'),
        'end-date' => $this->getEndDate()->format('Y-m-d'),
        'status' => $this->getStatus(),
        'status-date' => $this->getStatusDate()? $this->getStatusDate()->format('Y-m-d'): null,
        'frequency' => $this->getFrequency(),
        'payment-amount' => $this->getPaymentAmount(),
        'token-id' => $this->getTokenId(),
        'frequency-day' => $this->getFrequencyDay(),
        'frequency-week' => $this->getFrequencyWeek(),
        'frequency-month' => $this->getFrequencyMonth(),
    ];


    return $properties;
  }
}
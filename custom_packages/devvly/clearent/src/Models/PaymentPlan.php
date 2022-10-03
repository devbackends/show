<?php


namespace Devvly\Clearent\Models;



class PaymentPlan extends Payload
{

  use PaymentPlanProperties;

  /**
   * @inheritDoc
   */
  public function __construct(string $payloadType, $content, $links = null)
  {
    parent::__construct($payloadType, $content, $links);

    $this->setPlanKey($content['plan-key']);
    $this->setStatus($content['status']);
    $this->setCustomerKey($content['customer-key']);
    $this->setCustomerName($content['customer-name']);
    $this->setTokenId($content['token-id']);
    $this->setPaymentAmount($content['payment-amount']);
    $this->setStartDate($content['start-date']);
    $this->setEndDate($content['end-date']);
    $this->setFrequency($content['frequency']);
    $this->setFrequencyDay((int)$content['frequency-day']);
    if (isset($content['plan-name'])) {
      $this->setPlanName($content['plan-name']);
    }
    if (isset($content['customer-name'])) {
      $this->setCustomerName($content['customer-name']);
    }
    if (isset($content['status-date'])) {
      $this->setStatusDate($content['status-date']);
    }
    if (isset($content['frequency-week'])) {
      $this->setFrequencyWeek((int) $content['frequency-week']);
    }
    if (isset($content['frequency-month'])) {
      $this->setFrequencyMonth((int) $content['frequency-month']);
    }
  }


}
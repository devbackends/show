<?php


namespace Devvly\Clearent\Resources;


use Carbon\Carbon;
use Devvly\Clearent\Models\PaymentPlan;
use Devvly\Clearent\Models\PlanOptions;
use Devvly\Clearent\Models\Error;

class Plans extends IResource
{
  const path = "/payment-plans";


  /**
   * Gets a plan info.
   *
   * @param  string  $key
   *
   * @return PaymentPlan|Error
   */
  public function get(string $key)
  {
    $url = self::path . '/' . $key;
    $result = $this->client->get($url);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Gets all plans.
   *
   * @return PaymentPlan[]|Error
   */
  public function all()
  {
    $result = $this->client->get(self::path);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * @param  PlanOptions  $options
   *
   * @return PaymentPlan|Error
   */
  public function create($options)
  {
    $body = $options->toArray();
    unset($body['plan-key']);
    $body = array_filter($body, function ($item){
      return isset($item) && !empty($item);
    });
    if ($options->getFrequency() === PlanOptions::PLAN_WEEKLY) {
      unset($body['frequency-month']);
    }
    else {
      unset($body['frequency-week']);
    }
    $result = $this->client->post(self::path, $body);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * @param  string  $id
   * @param  mixed  $options
   *
   * @return PaymentPlan[]|Error
   */
  public function update(string $id, $options)
  {
    $body = $options->toArray();
    unset($body['plan-key']);
    $body = array_filter($body, function ($item){
      return isset($item) && !empty($item);
    });
    if ($options->getFrequency() === PlanOptions::PLAN_WEEKLY) {
      unset($body['frequency-month']);
    }
    else {
      unset($body['frequency-week']);
    }
    $url = self::path . '/' . $id;
    $result = $this->client->put($url, $body);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * @param  string  $id
   *
   * @return PaymentPlan|Error
   */
  public function delete(string $id)
  {
    $url = self::path . "/" . $id;
    $result = $this->client->delete($url);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * @param  string  $id
   * @param string $status
   * @return PaymentPlan|Error
   */
  public function updateStatus(string $id, string $status)
  {
    $url = self::path . "/" . $id;
    $body = [
        'status' => $status,
    ];
    $result = $this->client->put($url, $body);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Creates a plan options.
   *
   * @param  string  $customerKey
   * @param  string  $customerName
   * @param  string  $planName
   * @param  string  $tokenId
   * @param  float   $amount
   * @param  string  $frequency
   * @param  Carbon  $startDate
   *
   * @return PlanOptions
   */
  public function createPlanOptions(
      $customerKey,
      $customerName,
      $planName,
      $tokenId,
      $amount,
      $frequency = PlanOptions::PLAN_MONTHLY,
      $startDate = null
  ) {
    if ($startDate) {
      $startDate = $startDate->clone();
    } else {
      $startDate = $this->client->getCurrentDate();
    }
    // check if we need to move the subscription start date to the next month.
    // we should always start next month if the next days is more than 28 (clearent won't accept 29 as a value)
    $nearTheEnd = ($startDate->day + 1) > 28;
    // assure that when we add the next day, it won't move to the next month.
    $endOfTheMonth = ($startDate->day + 1) <= $startDate->daysInMonth;
    if ($nearTheEnd && $endOfTheMonth) {
      $startDate->addMonth();
      $startDate->setDay(1);
    } else {
      $startDate->addDay();
    }
    $endDate = $startDate->clone();
    $endDate->addYears(10);

    $options = new PlanOptions();
    // set the frequencies:
    if ($frequency === PlanOptions::PLAN_WEEKLY) {
      // dayOfWeekIso: 1-7, if 1, fire the plan each monday
      $options->setFrequencyDay($startDate->dayOfWeekIso);
      // fire each week
      $options->setFrequencyWeek(1);
    } else {
      $options->setFrequencyDay($startDate->day);
      $options->setFrequencyMonth($startDate->month);
    }

    $options->setCustomerKey($customerKey);
    $options->setCustomerName($customerName);
    $options->setPlanName($planName);
    $options->setTokenId($tokenId);
    $options->setFrequency($frequency);
    $options->setStartDate($startDate);
    $options->setEndDate($endDate);
    $options->setPaymentAmount($amount);

    return $options;
  }


}
<?php


namespace Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Responses\Pricing\PricingPlanTemplate;
use Devvly\OnBoarding\Clearent\Models\Pricing\PricingPlan;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Clearent\Responses\Pricing\PricingPlan as Response;

class PricingPlans extends IResource
{
  const path = "/pricing/v2/PricingPlan";

  /**
   * @param $number
   * @param  \Devvly\OnBoarding\Clearent\Models\Pricing\PricingPlan  $plan
   *
   * @return Response
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function createPlan($number, PricingPlan $plan)
  {
    $url = self::path . "/" . $number;
    $body = $plan->toArray();
    $headers = [
        'MerchantID' => $number,
    ];
    $result = $this->client->post($url, $body, $headers);
    $plan = new Response($result);
    return $plan;
  }

  /**
   * @param $merchantNumber
   *
   * @return PricingPlanTemplate[]
   * @throws ClearentException
   */
  public function getTemplates($merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber . '/templates';
    $headers = [
        'MerchantID' => $merchantNumber,
    ];
    $templates = $this->client->get($url, $headers);
    $plans = [];
    foreach ($templates['content'] as $data) {
      $plans[] = new PricingPlanTemplate($data);
    }
    return $plans;
  }

  /**
   * @param $number
   *
   * @return array
   * @throws ClearentException
   */
  public function payInMonth($number)
  {
    $url = '/pricing/v2/Reference/PayInMonth';
    $headers = [
        'MerchantID' => $number,
    ];
    $res = $this->client->get($url, $headers);
    return $res;
  }

  /**
   * @param $number
   *
   * @return array
   * @throws ClearentException
   */
  public function displayTypeCodes($number)
  {
    $url = '/pricing/v2/Reference/DisplayTypeCode';
    $headers = [
        'MerchantID' => $number,
    ];
    $res = $this->client->get($url, $headers);
    return $res;
  }
}
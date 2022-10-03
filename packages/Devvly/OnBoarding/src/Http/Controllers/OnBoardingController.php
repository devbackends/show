<?php

namespace Devvly\OnBoarding\Http\Controllers;

use Carbon\Carbon;
use Devvly\OnBoarding\Clearent\Models\Application as ApplicationOptions;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Clearent\Responses\References\MerchantCategoryCode;
use Devvly\OnBoarding\Models\Pricing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Response;
use Illuminate\Support\Facades\Cache;
use DB;

class OnBoardingController extends Controller
{
  protected $_config;

  /** @var Resources */
  protected $resources;

  public function __construct(Resources $resources)
  {
    $this->_config = request('_config');
    $this->resources = $resources;
  }

  public function generalData(Request $request)
  {
    $date = Carbon::now();
    $date->addMonth();
    /** @var MerchantCategoryCode[] $mcc */
    $mcc = Cache::get('onboarding.mcc');
    if (!$mcc) {
      $mcc = $this->resources->references()->getMccCodes(false);
      if(!$mcc instanceof ClearentException){
        Cache::put('onboarding.mcc', $mcc, $date);
      }
    }
    $businessTypes = Cache::get('onboarding.businessTypes');
    if (!$businessTypes) {
      $businessTypes = $this->resources->references()->getCompanyTypes(false);
      if(!$businessTypes instanceof ClearentException){
        Cache::put('onboarding.businessTypes', $businessTypes, $date);
      }
    }
    $states = Cache::get('onboarding.states');
    if(!$states){
      $states = $this->resources->references()->getStateOptions(false);
      if(!$states instanceof ClearentException){
        Cache::put('onboarding.states', $states, $date);
      }
    }

    $future_delivery_types = Cache::get('onboarding.future_delivery_types');
    if(!$future_delivery_types){
      $future_delivery_types = $this->resources->references()->getFutureDeliveryTypes(false);
      if(!$future_delivery_types instanceof ClearentException){
        Cache::put('onboarding.future_delivery_types', $future_delivery_types, $date);
      }
    }
    $site_types = Cache::get('onboarding.site_types');
    if(!$site_types){
      $site_types = $this->resources->references()->getSiteTypes(false);
      if(!$site_types instanceof ClearentException){
        Cache::put('onboarding.site_types', $site_types, $date);
      }
    }

    $contact_types = Cache::get('onboarding.contact_types');
    if(!$contact_types){
      $contact_types = $this->resources->references()->getContactTypes(false);
      if(!$contact_types instanceof ClearentException){
        Cache::put('onboarding.contact_types', $contact_types, $date);
      }
    }

    $countries = Cache::get('onboarding.countries');
    if(!$countries){
      $countries = $this->resources->references()->getCountryOptions(false);
      if(!$countries instanceof ClearentException){
        Cache::put('onboarding.countries', $countries, $date);
      }
    }

    $data = [];
    $data['mcc'] = $mcc;
    $data['business_types'] = $businessTypes;
    $data['states'] = $states;
    $data['future_delivery_types'] = $future_delivery_types;
    $data['pricing'] = Pricing::all();
    $data['site_types'] = $site_types;
    $data['contact_types'] = $contact_types;
    $data['countries'] = $countries;
    return new JsonResponse($data);
  }

  public function createApp(Request $request)
  {
    $application = new ApplicationOptions();
    $result = $this->resources->applications()->create($application);
    return new JsonResponse(['result' => ['merchant_number' => $result->getMerchantNumber()]]);
  }
  /**
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function submitApp(Request $request)
  {
    $request->validate([
        'merchant_number' => 'required|numeric',
    ]);
    $number = $request->get('merchant_number');

    // 1. create pricing plan:
    try {
      $this->resources->pricing()->createPlan($number);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    // 2. submit the application:
    try {
      $this->resources->applications()->submit($number);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    return new JsonResponse(['result' => 'success']);
  }


}
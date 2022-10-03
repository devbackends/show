<?php


namespace Devvly\OnBoarding\Http\Controllers;

use Devvly\OnBoarding\Clearent\Models\Demographics\SalesProfile;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Helpers\JsonHelper;
use Devvly\OnBoarding\Rules\FileExists;
use Devvly\OnBoarding\Rules\OptBlueOrESA;
use Devvly\OnBoarding\Rules\RequiredIfAmex;
use Devvly\OnBoarding\Rules\RequiredIfEBT;
use Devvly\OnBoarding\Rules\RequiredIfHasPercentage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SalesProfileController extends Controller
{
  use JsonHelper;

  protected $resources;

  public function __construct(Resources $resources)
  {
    $this->resources = $resources;
  }

  public function index($id, Request $request)
  {
    // @todo: implement this
  }

  public function show($id, Request $request)
  {
    //
  }

  /**
   * @param $merchantNumber
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function update($merchantNumber, Request $request)
  {
    $request->validate([
        'is_ecommerce' => 'boolean',
        'mcc_code' => 'required|numeric',
        'card_present_percentage' => 'required|integer|between:0,100',
        'return_refund_policy' => 'required|string|max:255',
        'products_sold' => 'required|string|max:1000',
        'previously_accepted_payment_cards' => 'required|boolean',
        'previous_processor_id' => 'integer',
        'previously_terminated_or_identified_by_risk_monitoring' => 'boolean',
        'reason_previously_terminated_or_identified_by_risk' => 'string|max:255',
        'currently_open_for_business' => 'required|boolean',
        'annual_volume' => 'required|integer',
        'average_ticket' => 'required|integer',
        'high_ticket' => 'required|integer',
        'owns_product' => 'boolean',
        'orders_product' => 'boolean',
        'sells_firearms' => 'required|boolean',
        'sells_firearm_accessories' => 'required|boolean',
        'future_delivery_percentage' => 'required|integer|between:0,100',
        'future_delivery_type_id' => [
            'integer',
            'in:1,2,3',
            new RequiredIfHasPercentage($request->get('future_delivery_percentage'))
        ],
        'other_delivery_type' => 'string|max:255|required_if:future_delivery_type_id,3',
        'fire_arms_license' => 'string|max:255',
        'firearms_license_document_path' => ['string','max:255','required_if:sells_firearms,true', new FileExists],
        'card_brands' => ['required','array','min:1', new OptBlueOrESA],
        'card_brands.*' => 'required|integer|in:1,2,3,4,5,6,7',
        'ebt_number' => ['numeric', 'digits_between:10,20', new RequiredIfEBT($request->get('card_brands'))],
        'amex_mid' => ['numeric', 'digits_between:5,10', new RequiredIfAmex($request->get('card_brands'))],
    ]);
    $data = $request->all();
    $profile = new SalesProfile($data);
    if($data['sells_firearms']){
      $filePath = base_path() . '/storage/app/' . $data['firearms_license_document_path'];
      $profile->setFirearmsLicenseDocumentPath($filePath);
    }
    $result = null;
    try {
      $result = $this->resources->salesProfiles()->update($merchantNumber, $profile);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    Cache::forget('onboarding.sales_profile' . $merchantNumber);
    return new JsonResponse($result->toArray(true));
  }
}
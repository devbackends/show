<?php


namespace Devvly\OnBoarding\Http\Controllers;


use Carbon\Carbon;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TermsController extends Controller
{

  /** @var Resources */
  protected $resources;

  public function __construct(Resources $resources)
  {
    $this->resources = $resources;
  }

  public function getTypes(){
    try {
      /** @var  $types */
      $types = $this->resources->termsAndConditions()->getTypes();
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    return new JsonResponse($types->toArray());
  }
  public function merchantTerms($id, Request $request)
  {
    $terms = Cache::get('onboarding.terms');
    if($terms){
      return new JsonResponse($terms);
    }
    try {
      $result = $this->resources->termsAndConditions()->getMerchantTerms($id);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    $date = Carbon::now();
    $date->addMonth();
    Cache::put('onboarding.terms', $result->toArray(true), $date);
    return new JsonResponse($result->toArray());
  }

  public function updateSignatures(Request $request)
  {
    $request->validate([
        'business_contact_id' => 'required|int',
        'merchant_number' => 'required|numeric',
    ]);
    $number = $request->get('merchant_number');
    $contactId = $request->get('business_contact_id');
    $ipAddress = request()->ip();
    try {
      $this->resources->signatures()->submitSignatures($number, $contactId, $ipAddress);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    return new JsonResponse(['result' => 'success']);
  }

}
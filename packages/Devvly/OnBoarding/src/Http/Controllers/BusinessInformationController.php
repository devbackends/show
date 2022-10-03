<?php


namespace Devvly\OnBoarding\Http\Controllers;

use Carbon\Carbon;
use Devvly\OnBoarding\Clearent\Models\Helpers\BusinessInformation;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Helpers\JsonHelper;
use Devvly\OnBoarding\Rules\BirthDate;
use Devvly\OnBoarding\Rules\HasSigner;
use Devvly\OnBoarding\Rules\IsMonth;
use Devvly\OnBoarding\Rules\IsPhone;
use Devvly\OnBoarding\Rules\RequiredIfOwner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BusinessInformationController extends Controller
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
    $profile = Cache::get('onboarding.business_information_' . $id);
    if ($profile) {
      return new JsonResponse($profile);
    }
    try {
      $profile = $this->resources->businessInformations()->get($id);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    $profile = $profile->toArray(true);
    $date = Carbon::now();
    $date->addMonth();
    Cache::put('onboarding.business_information_' . $id, $profile, $date);
    return new JsonResponse($profile);
  }

  /**
   * @param $merchantNumber
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function update($merchantNumber, Request $request)
  {
    // validate merchant:
    $request->validate([
        'merchant' => 'required',
        'merchant.merchant_number' => 'required|numeric',
        'merchant.dba_name' => 'required|string|max:255',
        'merchant.phones' => 'required|array|min:1',
        'merchant.phones.*.phone_type_code_id' => 'required|integer',
        'merchant.phones.*.area_code' => 'required|digits:3',
        'merchant.phones.*.phone_number' => 'required|digits:7',
        'merchant.phones.*.extension' => 'numeric|digits_between:1,5',
        'merchant.email_address' => 'required|email|max:255',
        'merchant.web_site' => 'required|string|max:255',
        'merchant.accepts_paper_statements' => 'required|boolean',
        'merchant.accepts_paper_tax_forms' => 'required|boolean',
        'merchant.company_type_id' => 'required|numeric',
        'merchant.seasonal_schedule' => 'required|array|min:1',
        'merchant.seasonal_schedule.*' => ['required','string', new IsMonth],
    ]);
    // validate physical address:
    $request->validate([
        'physical_address' => 'required|array',
        'physical_address.line1' => 'required|string|max:255',
        'physical_address.line2' => 'string|max:255',
        'physical_address.line3' => 'string|max:255',
        'physical_address.city' => 'required|string|max:255',
        'physical_address.country_code' => 'required|numeric',
        'physical_address.state_code' => 'required|string|max:5',
        'physical_address.zip' => 'required|numeric',
    ]);
    // validate mailing address:
    $request->validate([
        'mailing_address' => 'required|array',
        'mailing_address.line1' => 'required|string|max:255',
        'mailing_address.line2' => 'string|max:255',
        'mailing_address.line3' => 'string|max:255',
        'mailing_address.city' => 'required|string|max:255',
        'mailing_address.country_code' => 'required|numeric',
        'mailing_address.state_code' => 'required|string|max:5',
        'mailing_address.zip' => 'required|numeric',
    ]);

    // validate business contacts:
    $request->validate([
        'business_contacts' => ['required','array','min:1', new HasSigner],
        'business_contacts.*.business_contact_id' => 'integer',
        'business_contacts.*.is_compass_user' => 'required|boolean',
        'business_contacts.*.is_authorized_to_purchase' => 'required|boolean',
        'business_contacts.*.email_address' => 'required|email',
        'business_contacts.*.ownership_amount' => 'required|integer|between:1,100',
        'business_contacts.*.phone_numbers' => 'required|array|min:1',
        'business_contacts.*.phone_numbers.*.phone_type_code_id' => 'required|integer',
        'business_contacts.*.phone_numbers.*.area_code' => 'required|digits:3',
        'business_contacts.*.phone_numbers.*.phone_number' => 'required|digits:7',
        'business_contacts.*.phone_numbers.*.extension' => 'numeric|digits_between:1,5',
        'business_contacts.*.contact_types' => 'required|array|min:2',
        'business_contacts.*.contact_types.*.contact_type_id' => 'required|integer|between:1,3',
        'business_contacts.*.contact' => 'required',
        'business_contacts.*.contact.first_name' => 'required|string|max:255',
        'business_contacts.*.contact.last_name' => 'required|string|max:255',
        'business_contacts.*.title' => [
            'string',
            'max:255',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.ssn' => [
            'numeric',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.date_of_birth' => [
            'date',
            new BirthDate,
            new RequiredIfOwner($request->get('business_contacts'))

        ],
        'business_contacts.*.contact.country_of_citizenship_code' => [
            'numeric',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.address' => [
            'array',
            new RequiredIfOwner($request->get('business_contacts'))
        ],

        'business_contacts.*.contact.address.line1' => [
            'string',
            'max:255',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.address.line2' => [
            'string',
            'max:255',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.address.city' => [
            'string',
            'max:255',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.address.state_code' => [
            'string',
            'max:5',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.address.zip' => [
            'numeric',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.address.country_code' => [
            'numeric',
            new RequiredIfOwner($request->get('business_contacts'))
        ],
        'business_contacts.*.contact.address.line3' => 'string|max:255',
    ]);

    // validate tax payer:
    $request->validate([
        'tax_payer' => 'required|array',
        'tax_payer.tin' => 'required|numeric',
        'tax_payer.tin_type_id' => 'required|numeric',
        'tax_payer.state_incorporated_code' => 'required|string|max:5',
        'tax_payer.business_legal_name' => 'required|string|max:255'
    ]);
    $data = $request->all();
    $profile = new BusinessInformation($data);
    $result = null;
    try {
      $result = $this->resources->businessInformations()->update($merchantNumber, $profile);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    $profile = $result->toArray(true);
    $date = Carbon::now();
    $date->addMonth();
    Cache::put('onboarding.business_information_' . $merchantNumber, $profile, $date);
    return new JsonResponse($profile);
  }
}
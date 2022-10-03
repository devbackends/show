<?php


namespace Devvly\OnBoarding\Http\Controllers;

use Carbon\Carbon;
use Devvly\OnBoarding\Clearent\Models\Demographics\BankAccount;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Rules\FileExists;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BankAccountController extends Controller
{

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
    $banking = Cache::get('onboarding.banking_' . $id);
    if ($banking) {
      return new JsonResponse($banking);
    }
    $result = null;
    try {
      $result = $this->resources->bankAccounts()->all($id);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    if(count($result)){
      $result = $result[0]->toArray(true);
      $date = Carbon::now();
      $date->addMonth();
      Cache::put('onboarding.banking_' . $id, $result, $date);
      return new JsonResponse($result);
    }
    return new JsonResponse([]);
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
        'bank_name' => 'required|string',
        'name_on_account' => 'string|max:255',
        'account_holder_first_name' => 'string|max:255|nullable',
        'account_holder_last_name' => 'string|max:255|nullable',
        'bank_account_type_id' => 'required|integer',
        'bank_account_name_type_id' => 'required|integer',
        'aba' => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
        'voided_check_document_path' => ['required','string','max:255', new FileExists],
        'has_fees' => 'required|boolean',
        'has_funds' => 'required|boolean',
        'has_chargebacks' => 'required|boolean',
        'is_name_same_as_legal_or_dba_name' => 'boolean',
    ]);
    $data = $request->all();
    $account = new BankAccount($data);
    $voidedCheckPath = base_path() . '/storage/app/' . $data['voided_check_document_path'];
    $account->setVoidedCheckDocumentPath($voidedCheckPath);
    $result = null;
    try {
      $result = $this->resources->bankAccounts()->create($merchantNumber, $account);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    Cache::forget('onboarding.banking_' . $merchantNumber);
    return new JsonResponse($result->toArray(true));
  }
}
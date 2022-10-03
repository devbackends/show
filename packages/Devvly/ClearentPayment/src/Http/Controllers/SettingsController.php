<?php


namespace Devvly\ClearentPayment\Http\Controllers;


use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{

  public function settings()
  {
    $public_key = core()->getConfigData('sales.paymentmethods.clearent.public_key');
    $settings = [
        'url' => env('CLEARENT_API_URL'),
        'public_key' => $public_key,
    ];
    return new JsonResponse($settings);
  }
}
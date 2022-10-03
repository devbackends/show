<?php


namespace Devvly\OnBoarding\Http\Controllers;

use Devvly\OnBoarding\Clearent\Models\Demographics\VoidedCheck;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Rules\FileExists;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{

  protected $resources;

  public function __construct(Resources $resources)
  {
    $this->resources = $resources;
  }

  /**
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\JsonResponse
   * @throws \Illuminate\Validation\ValidationException
   */
  public function upload(Request $request)
  {
    $this->validate($request, [
        'firearms_license' => 'mimetypes:application/pdf,image/png,image/jpeg',
        'voided_check' => 'mimetypes:application/pdf,image/png,image/jpeg',
        'license_id' => 'mimetypes:application/pdf,image/png,image/jpeg',
    ]);
    /** @var \Illuminate\Http\UploadedFile[] $files */
    $files = $request->allFiles();
    $result = [];
    foreach ($files as $key => $file) {
      $random = substr(md5(mt_rand()), 0, 16);
      $name = $random .  '.' . $file->getClientOriginalExtension();
      $path = $file->storeAs('private', $name,['disk' => 'local', 'visibility' => 'private']);
      $result[$key] = $path;
    }
    return new JsonResponse(['result' => $result]);
  }

  public function voidedCheck(Request $request)
  {
    $request->validate([
        'merchant_number' => 'required|numeric',
        'bank_account_id' => 'required|integer',
        'voided_check_path' => ['required','string', new FileExists]
    ]);
    $file = base_path() . '/storage/app/' . $request->get('voided_check_path');
    $voidedCheckOptions = new VoidedCheck($request->all());
    $fileName = 'voided_check_' . $request->get('merchant_number') . '.png';
    $voidedCheckOptions->setFileName($fileName);
    $voidedCheckOptions->setFilePath($file);
    $voidedCheck = null;
    try {
      $voidedCheck = $this->resources->documents()->uploadVoidedCheck($voidedCheckOptions);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    return new JsonResponse($voidedCheck->toArray(true));
  }
}
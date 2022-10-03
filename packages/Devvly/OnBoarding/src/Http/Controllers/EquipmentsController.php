<?php


namespace Devvly\OnBoarding\Http\Controllers;


use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Illuminate\Http\JsonResponse;

class EquipmentsController extends Controller
{
  protected $resources;

  public function __construct(Resources $resources)
  {
    $this->resources = $resources;
  }

  public function products($term = null)
  {
    try {
      $res = $this->resources->equipments()->listProducts($term);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    return new JsonResponse($res->toArray());
  }

  public function getSurvey($productName)
  {
    try {
      $res = $this->resources->equipments()->getSurvey($productName);
    } catch (ClearentException $e) {
      return new JsonResponse($e->toArray(), $e->getCode());
    }
    return new JsonResponse($res);
  }
}
<?php

namespace Devvly\Ffl\Http\Controllers\Api;

use Devvly\Ffl\Http\Controllers\Controller;
use Devvly\Ffl\Repositories\FflRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FflController extends Controller
{
    const LIMIT = 100;

    private $fflRepository;

    public function __construct(FflRepository $fflRepository)
    {
        $this->fflRepository = $fflRepository;
    }

    public function findClosest(Request $request)
    {
        return new JsonResponse([
            'ffls' => $this->fflRepository->findClosestByCoordinates(
                $request->input('coordinates.lat'), $request->input('coordinates.lng'), self::LIMIT, $request->input('offset'), 0
            ),
        ]);
    }

    public function findZip(int $zip, float $lat, float $lng, int $offset, string $state)
    {
        if(is_numeric($zip)){
            return new JsonResponse([
                'ffls' => $this->fflRepository->findByZipCodeInHundredMilesDistance(
                    $lat, $lng, $zip, self::LIMIT, $offset,$state
                ),
            ]);
        }
    }

    public function findById(int $id)
    {
        $ffl = $this->fflRepository->makeModel()->with('businessInfo')->find($id);
        if (!$ffl) {
            abort(404);
        }
        return new JsonResponse($ffl->toArray());
    }
}

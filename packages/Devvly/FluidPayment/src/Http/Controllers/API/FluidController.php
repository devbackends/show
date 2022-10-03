<?php

namespace Devvly\FluidPayment\Http\Controllers\API;

use Devvly\FluidPayment\Http\Controllers\Controller;
use Devvly\FluidPayment\Models\FluidCustomer;
use Illuminate\Http\JsonResponse;

class FluidController extends Controller
{
    /**
     * @param int $sellerId
     * @return JsonResponse
     */
    public function getTokenizerinfo(int $sellerId): JsonResponse
    {
        $creds = FluidCustomer::query()->where('seller_id', $sellerId)->first();

        return response()->json([
            'status' => true,
            'data' => [
                'public_key' => $creds->public_key ?? '',
                'url' => config('services.2acommerce.gateway_url'),
            ],
        ]);
    }
}
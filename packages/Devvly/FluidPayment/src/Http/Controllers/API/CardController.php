<?php

namespace Devvly\FluidPayment\Http\Controllers\API;

use Devvly\FluidPayment\Http\Controllers\Controller;
use Devvly\FluidPayment\Models\FluidCard;
use Exception;
use Illuminate\Http\JsonResponse;

class CardController extends Controller
{

    /**
     * @param int $customerId
     * @param int $sellerId
     * @return JsonResponse
     */
    public function get(int $customerId, int $sellerId): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => [
                'cards' => FluidCard::query()
                    ->where('customer_id', '=', $customerId)
                    ->where('seller_id', '=', $sellerId)
                    ->get(),
            ],
        ]);
    }

    /**
     * @param int $cardId
     * @return JsonResponse
     */
    public function update(int $cardId): JsonResponse
    {
        $data = request()->validate([
            'nickname' => 'required'
        ]);

        $card = FluidCard::query()->find($cardId);
        $status = false;
        if ($card) {
            $card->nickname = $data['nickname'];
            $status = $card->save();
        }

        return response()->json([
            'status' => $status
        ]);
    }

    /**
     * @param int $cardId
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(int $cardId): JsonResponse
    {
        $result = FluidCard::query()->find($cardId)->delete();

        return response()->json([
            'status' => (bool)$result,
        ]);
    }

}
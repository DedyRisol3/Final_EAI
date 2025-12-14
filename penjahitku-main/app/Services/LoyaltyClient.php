<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LoyaltyClient
{
    public function redeemPoints($userId, $points, $orderId = null)
    {
        $response = Http::post(env('LOYALTY_SERVICE_URL') . '/api/redeem', [
            'user_id' => $userId,
            'points_used' => $points,
            'order_id' => $orderId,
        ]);

        return $response->json();
    }

    public function getPointBalance($userId)
    {
        $response = Http::get(env('LOYALTY_SERVICE_URL') . '/api/points/user/' . $userId);
        return $response->json();
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LoyaltyApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('LOYALTY_SERVICE_URL'), '/');
    }

    public function grantPoints($userId, $orderId)
    {
        $response = Http::post($this->baseUrl . '/api/points/grant', [
            'user_id' => $userId,
            'order_id' => $orderId
        ]);

        return $response->json();
    }
}

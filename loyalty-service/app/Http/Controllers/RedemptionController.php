<?php

namespace App\Http\Controllers;

use App\Models\PointBalance;
use App\Models\PointTransaction;
use App\Models\Redemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RedemptionController extends Controller
{
    public function redeem(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|integer',
            'points_used' => 'required|integer|min:1',
            'order_id'    => 'nullable|string',
        ]);

        $userId = $request->user_id;
        $pointsUsed = $request->points_used;

        $balance = PointBalance::firstOrCreate(
            ['user_id' => $userId],
            ['total_points' => 0]
        );

        if ($balance->total_points < $pointsUsed) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak mencukupi.'
            ], 422);
        }

        $rate = config('loyalty.point_to_currency');
        $discount = $pointsUsed * $rate;

        DB::transaction(function () use ($balance, $userId, $pointsUsed, $discount, $request) {
            // Update saldo poin
            $balance->decrement('total_points', $pointsUsed);

            PointTransaction::create([
                'user_id'    => $userId,
                'amount'     => -$pointsUsed,
                'type'       => 'redeem',
                'description' => 'Redeem poin untuk diskon'
            ]);

            Redemption::create([
                'user_id'        => $userId,
                'points_used'    => $pointsUsed,
                'discount_value' => $discount,
                'order_id'       => $request->order_id,
            ]);
        });

        return [
            'success'          => true,
            'discount_value'   => $discount,
            'remaining_points' => $balance->fresh()->total_points,
        ];
    }
}

<?php

namespace App\Services;

use App\Models\PointBalance;
use App\Models\PointHistory;
use Illuminate\Support\Facades\DB;

class PointsProcessor
{
    /**
     * GRANT POINTS — pemberian poin ketika order berhasil
     */
    public function grant($request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'order_id' => 'required|integer'
        ]);

        $userId = $request->user_id;
        $orderId = $request->order_id;

        // Anti duplikasi berdasarkan order_id
        if (PointHistory::where('order_id', $orderId)->exists()) {
            return response()->json([
                'status' => 'duplicate',
                'message' => 'Poin untuk order ini sudah diberikan sebelumnya.'
            ]);
        }

        $pointsEarned = 5; // default rule (bisa diubah)

        DB::transaction(function () use ($userId, $orderId, $pointsEarned) {
            // Simpan history earn
            PointHistory::create([
                'user_id'    => $userId,
                'type'       => 'earn',
                'points'     => $pointsEarned,
                'order_id'   => $orderId,
                'description' => 'Earn from order ID ' . $orderId
            ]);

            // Update saldo poin user
            $balance = PointBalance::firstOrCreate(
                ['user_id' => $userId],
                ['total_points' => 0]
            );

            $balance->increment('total_points', $pointsEarned);
        });

        return response()->json([
            'status' => 'success',
            'points_earned' => $pointsEarned,
            'message' => 'Poin berhasil ditambahkan.'
        ]);
    }

    /**
     * REDEEM POINTS — menukarkan poin
     */
    public function redeem($request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'redeem_type' => 'required|string',
            'points' => 'required|integer|min:1'
        ]);

        $userId = $request->user_id;
        $redeemType = $request->redeem_type;
        $pointsToRedeem = $request->points;

        $balance = PointBalance::where('user_id', $userId)->first();

        // Jika user belum punya saldo
        if (!$balance) {
            return response()->json([
                'status' => 'error',
                'message' => 'User belum memiliki poin.'
            ]);
        }

        // Jika poin tidak cukup
        if ($balance->total_points < $pointsToRedeem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Poin tidak mencukupi untuk ditukarkan.'
            ]);
        }

        DB::transaction(function () use ($userId, $redeemType, $pointsToRedeem, $balance) {
            // Kurangi saldo poin
            $balance->decrement('total_points', $pointsToRedeem);

            // Simpan history redeem
            PointHistory::create([
                'user_id'     => $userId,
                'type'        => 'redeem',
                'points'      => $pointsToRedeem,
                'redeem_type' => $redeemType,
                'description' => 'Redeem via ' . $redeemType
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Redeem berhasil.'
        ]);
    }

    /**
     * GET USER POINT BALANCE
     */
    public function getUserPoints($userId)
    {
        $balance = PointBalance::where('user_id', $userId)->first();

        return response()->json([
            'user_id' => $userId,
            'total_points' => $balance ? $balance->total_points : 0
        ]);
    }
}

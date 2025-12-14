<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use App\Models\Redemption;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index($userId)
    {
        return response()->json([
            'transactions' => PointTransaction::where('user_id', $userId)->get(),
            'redemptions' => Redemption::where('user_id', $userId)->get(),
        ]);
    }
}

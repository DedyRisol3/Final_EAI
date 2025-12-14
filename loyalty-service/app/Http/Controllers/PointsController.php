<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PointsProcessor;

class PointsController extends Controller
{
    protected $processor;

    public function __construct(PointsProcessor $processor)
    {
        $this->processor = $processor;
    }

    // Pemberian poin setelah order sukses
    public function grantPoints(Request $request)
    {
        return $this->processor->grant($request);
    }

    // Redeem poin
    public function redeemPoints(Request $request)
    {
        return $this->processor->redeem($request);
    }

    // Get saldo poin user
    public function getUserPoints($userId)
    {
        return $this->processor->getUserPoints($userId);
    }
}

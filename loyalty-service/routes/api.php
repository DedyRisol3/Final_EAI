<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\RedemptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/points/grant', [PointsController::class, 'grantPoints']);
// Route::post('/points/redeem', [PointsController::class, 'redeemPoints']);
Route::get('/points/user/{id}', [PointsController::class, 'getUserPoints']);

Route::post('/redeem', [RedemptionController::class, 'redeem']);

Route::get('/history/{userId}', [HistoryController::class, 'index']);

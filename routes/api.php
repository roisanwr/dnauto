<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentCallbackController;

Route::post('/midtrans-callback', [PaymentCallbackController::class, 'receive']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

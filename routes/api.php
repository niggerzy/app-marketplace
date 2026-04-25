<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\AuthController;

Route::post('/auth/register', [AuthController::class, 'apiRegister']);
Route::post('/auth/login', [AuthController::class, 'apiLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'apiLogout']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    Route::get('/orders/{order}/messages', [ChatController::class, 'index']);
    Route::post('/orders/{order}/messages', [ChatController::class, 'store']);

    Route::get('/admin/orders', [OrderController::class, 'listAll']);
});

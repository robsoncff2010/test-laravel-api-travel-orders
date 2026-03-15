<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
// use App\Http\Controllers\Api\V1\TravelOrderController;

Route::prefix('v1')->group(function () {
    // Rotas públicas

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // // Rotas protegidas
    Route::middleware('jwt.auth')->group(function () {
    //     Route::post('/logout', [AuthController::class, 'logout']);
    //     Route::get('/me', [AuthController::class, 'me']);

    //     // Pedidos de viagem
    //     Route::post('/orders', [TravelOrderController::class, 'store']);
    //     Route::get('/orders', [TravelOrderController::class, 'index']);
    //     Route::get('/orders/{id}', [TravelOrderController::class, 'show']);
    //     Route::put('/orders/{id}/status', [TravelOrderController::class, 'updateStatus']);
    });
});
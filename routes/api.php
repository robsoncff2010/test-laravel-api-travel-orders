<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\TravelOrder\TravelOrderController;

Route::prefix('v1')->group(function () {
    // Rotas públicas
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:register');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login');

    // Rotas protegidas por autenticação JWT
    Route::middleware('jwt.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])
            ->middleware('throttle:logout');

        Route::get('/me', [AuthController::class, 'me'])
            ->middleware('throttle:me');

        // Pedidos de viagem
        Route::post('/travel-orders', [TravelOrderController::class, 'store'])
            ->middleware('throttle:travel-orders');

        Route::put('/travel-orders/{id}', [TravelOrderController::class, 'update'])
            ->middleware('throttle:travel-orders');

        Route::patch('/travel-orders/{id}/status', [TravelOrderController::class, 'updateStatus'])
            ->middleware('throttle:travel-orders');

        Route::get('/travel-orders/{id}', [TravelOrderController::class, 'show'])
            ->middleware('throttle:travel-orders');

        Route::get('/travel-orders', [TravelOrderController::class, 'index'])
            ->middleware('throttle:travel-orders');
    });
});
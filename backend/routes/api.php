<?php

use App\Http\Controllers\HealthController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

Route::prefix('v1')->group(function () {
    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::get('/clientes/{id}', [ClienteController::class, 'show']);
});

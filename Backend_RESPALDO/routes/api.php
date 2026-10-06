<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PersonaController;

Route::prefix('v1')->group(function () {
    
    // Núcleo de Identidad: Personas
    Route::post('/personas', [PersonaController::class, 'store']);
    
});
/*
|--------------------------------------------------------------------------
| API Routes - SIGA
|--------------------------------------------------------------------------
*/

// Rutas Públicas de Autenticación
Route::post('/auth/login', [AuthController::class, 'login']);

// Rutas Protegidas por Sanctum y Contexto de Plantel
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetCampusContext::class])->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
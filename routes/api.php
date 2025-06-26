<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\API\AuthController;

// ✅ Rrugët për autentikim jashtë prefix 'reports'
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// ✅ Rrugët për raportet
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('reports')->group(function () {
        Route::post('/', [ReportController::class, 'store']);     // krijo raport
        Route::get('/', [ReportController::class, 'index']);      // lista e raporteve
        Route::get('/{id}', [ReportController::class, 'show']);   // shfaq një raport
    });
});

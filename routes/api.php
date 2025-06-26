<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\API\AuthController;


Route::prefix('reports')->group(function () {
    Route::post('/', [ReportController::class, 'store']); // për të krijuar raport
    Route::get('/', [ReportController::class, 'index']);  // lista e të gjitha raporteve
    Route::get('/reports/{id}', [ReportController::class, 'show']);// një raport me ID
    
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

});


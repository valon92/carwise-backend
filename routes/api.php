<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return response()->json(['message' => 'API endpoint works']);
});

// Rutat për autentikim
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Rutat e raportit (akses publik)
Route::get('/reports', [ReportController::class, 'index']);
Route::get('/reports/{id}', [ReportController::class, 'show']);

Route::post('/register', [RegisteredUserController::class, 'store']);


// Rutat e mbrojtura për krijimin e raportit
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reports', [ReportController::class, 'store']);
});

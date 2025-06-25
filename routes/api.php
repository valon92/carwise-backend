<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::prefix('reports')->group(function () {
    Route::post('/', [ReportController::class, 'store']); // për të krijuar raport
    Route::get('/', [ReportController::class, 'index']);  // lista e të gjitha raporteve
    Route::get('/{id}', [ReportController::class, 'show']); // një raport me ID
});


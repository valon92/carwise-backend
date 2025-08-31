<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => true,
        'canRegister' => true,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// AI Routes
Route::middleware('auth')->prefix('ai')->name('ai.')->group(function () {
    Route::get('/chat', [AiController::class, 'chat'])->name('chat');
    Route::post('/chat/message', [AiController::class, 'processMessage'])->name('process-message');
    Route::get('/chat/history', [AiController::class, 'getChatHistory'])->name('chat-history');
    Route::post('/chat/feedback', [AiController::class, 'provideFeedback'])->name('feedback');
    Route::get('/insights', [AiController::class, 'getInsights'])->name('insights');
    Route::get('/analytics', [AiController::class, 'analytics'])->name('analytics');
    Route::post('/reports/recommendations', [AiController::class, 'getReportRecommendations'])->name('report-recommendations');
    Route::post('/vehicles/recommendations', [AiController::class, 'getVehicleRecommendations'])->name('vehicle-recommendations');
    Route::post('/reports/analyze', [AiController::class, 'analyzeReport'])->name('analyze-report');
});

// Reports Routes
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->name('store');
    Route::get('/{report}', [ReportController::class, 'show'])->name('show');
    Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('edit');
    Route::put('/{report}', [ReportController::class, 'update'])->name('update');
    Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
    Route::post('/{report}/assign', [ReportController::class, 'assign'])->name('assign');
    Route::post('/{report}/complete', [ReportController::class, 'complete'])->name('complete');
    Route::post('/{report}/urgent', [ReportController::class, 'markUrgent'])->name('urgent');
});

// Vehicles Routes
Route::middleware('auth')->prefix('vehicles')->name('vehicles.')->group(function () {
    Route::get('/', [VehicleController::class, 'index'])->name('index');
    Route::get('/create', [VehicleController::class, 'create'])->name('create');
    Route::post('/', [VehicleController::class, 'store'])->name('store');
    Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('show');
    Route::get('/{vehicle}/edit', [VehicleController::class, 'edit'])->name('edit');
    Route::put('/{vehicle}', [VehicleController::class, 'update'])->name('update');
    Route::delete('/{vehicle}', [VehicleController::class, 'destroy'])->name('destroy');
    Route::post('/{vehicle}/primary', [VehicleController::class, 'markPrimary'])->name('primary');
    Route::post('/{vehicle}/service', [VehicleController::class, 'addServiceRecord'])->name('service');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes - Include them here to ensure they have web middleware
Route::middleware('guest')->group(function () {
    Route::get('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', \App\Http\Controllers\Auth\EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', \App\Http\Controllers\Auth\VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::put('password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

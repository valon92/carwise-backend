<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    return response()->json(['message' => 'Logged out']);
});

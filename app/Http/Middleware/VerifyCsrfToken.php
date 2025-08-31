<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'login',
        'register',
        'logout',
        'password/*',
        'verify-email/*',
        'confirm-password',
        'forgot-password',
        'reset-password/*',
        'email/verification-notification',
        'sanctum/csrf-cookie',
        'api/*',
        'auth/*',
    ];
}

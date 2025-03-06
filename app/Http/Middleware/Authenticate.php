<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Ne pas rediriger vers login pour les routes d'inscription
        if ($request->is('register/*') || $request->is('register')) {
            return null;
        }

        if (!$request->expectsJson()) {
            return route('login');
        }
        return null;
    }
}

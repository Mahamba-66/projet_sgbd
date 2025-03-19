<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifie si l'utilisateur a le rôle admin ou superadmin (selon la structure de l'application)
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Accès non autorisé. Vous devez être administrateur.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifier si l'utilisateur est un admin
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin') {
            Auth::logout(); // Déconnexion de l'utilisateur
            
            return redirect()->route('login')
                ->with('error', 'Accès non autorisé. Vous devez être administrateur pour accéder à cette page.');
        }

        return $next($request);
    }
}

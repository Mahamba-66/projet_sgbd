<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CandidateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour accéder à votre tableau de bord.');
        }

        if (!auth()->user()->isCandidate()) {
            return redirect()->route('home')
                ->with('error', 'Accès non autorisé. Cette section est réservée aux candidats.');
        }

        return $next($request);
    }
}

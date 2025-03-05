<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoterMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = Auth::user();
        if ($user->role !== 'voter') {
            return redirect('/')->with('error', 'Accès non autorisé. Cette page est réservée aux électeurs.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoterMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'voter') {
            return redirect()->route('login')
                ->with('error', 'Accès réservé aux électeurs.');
        }

        return $next($request);
    }
}

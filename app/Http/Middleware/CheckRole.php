<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;
        
        // Convertir la chaîne de rôles en tableau
        $allowedRoles = is_array($roles) ? $roles : explode(',', $roles[0]);
        
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Accès non autorisé.');
    }
}

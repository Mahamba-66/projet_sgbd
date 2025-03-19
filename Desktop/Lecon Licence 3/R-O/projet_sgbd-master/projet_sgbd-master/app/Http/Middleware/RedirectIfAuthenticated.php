<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                Log::info('RedirectIfAuthenticated: User is authenticated', ['email' => $user->email, 'role' => $user->role]);
                
                switch ($user->role) {
                    case 'candidate':
                        return redirect()->route('candidate.dashboard');
                    case 'voter':
                        return redirect()->route('voter.dashboard');
                    case 'admin':
                    case 'super_admin':
                    case 'supervisor':
                        return redirect()->route('admin.dashboard');
                    default:
                        return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}

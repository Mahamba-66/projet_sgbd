<?php
// Ajouté le 03/19/2025 04:27:33
// feat: Middleware de vérification statut

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isActive()) {
            Auth::logout();
            
            return redirect()->route('login')->withErrors([
                'status' => match($user->status) {
                    'pending' => 'Votre compte est en attente de validation.',
                    'rejected' => 'Votre compte a été rejeté.',
                    'blocked' => 'Votre compte a été bloqué.',
                    default => 'Votre compte n\'est pas actif.'
                }
            ]);
        }

        if (!$user->hasVerifiedEmail() && !$request->routeIs('verification.*')) {
            return redirect()->route('verification.notice');
        }

        if ($user->isCandidate() && !$request->routeIs('candidate.*')) {
            return redirect()->route('candidate.dashboard');
        }

        if ($user->isVoter() && !$request->routeIs('voter.*')) {
            return redirect()->route('voter.dashboard');
        }

        if ($user->isAdmin() && !$request->routeIs('admin.*')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}

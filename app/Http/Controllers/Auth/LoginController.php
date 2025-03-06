<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\ActivityLog;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function authenticated(Request $request, $user)
    {
        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->log('login');

        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role === 'candidate') {
            return redirect()->route('candidate.dashboard');
        }
        
        if ($user->role === 'voter') {
            return redirect()->route('voter.dashboard');
        }

        // Si le rôle n'est pas reconnu, déconnecter l'utilisateur
        Auth::logout();
        return redirect()->route('login')
            ->with('error', 'Votre compte n\'a pas les autorisations nécessaires.');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->route('login')
            ->with('error', 'Les identifiants fournis sont incorrects.')
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}

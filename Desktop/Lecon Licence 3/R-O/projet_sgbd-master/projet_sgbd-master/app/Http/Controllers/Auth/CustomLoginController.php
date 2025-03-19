<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        Log::info('Tentative de connexion', ['email' => $request->email]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            Log::info('Connexion réussie', [
                'email' => $user->email,
                'role' => $user->role,
                'id' => $user->id
            ]);

            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->log('login');

            switch ($user->role) {
                case 'candidate':
                    return redirect()->intended(route('candidate.dashboard'));
                case 'voter':
                    return redirect()->intended(route('voter.dashboard'));
                case 'admin':
                case 'super_admin':
                case 'supervisor':
                    return redirect()->intended(route('admin.dashboard'));
                default:
                    return redirect()->intended('/');
            }
        }

        Log::warning('Échec de connexion', ['email' => $request->email]);

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        Log::info('Déconnexion', ['email' => $user ? $user->email : 'unknown']);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}

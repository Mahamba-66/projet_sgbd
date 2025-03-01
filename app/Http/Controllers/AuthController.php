<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:parrainage_users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:admin,candidate,voter',
            'phone_number' => 'required|string'
        ]);

        if ($request->user_type === 'voter') {
            $validator = Validator::make($request->all(), [
                'voter_card_number' => 'required|string|unique:parrainage_users',
                'national_id_number' => 'required|string|unique:parrainage_users',
                'polling_station' => 'required|string'
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'voter_card_number' => $request->voter_card_number,
            'national_id_number' => $request->national_id_number,
            'polling_station' => $request->polling_station,
            'phone_number' => $request->phone_number
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Rediriger selon le type d'utilisateur
            switch (Auth::user()->user_type) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'candidate':
                    return redirect()->route('candidate.dashboard');
                case 'voter':
                    return redirect()->route('voter.dashboard');
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}

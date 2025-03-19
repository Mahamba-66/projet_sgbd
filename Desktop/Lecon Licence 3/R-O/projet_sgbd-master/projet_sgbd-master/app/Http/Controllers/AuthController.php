<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nin' => 'required|string|size:13|unique:users',
            'voter_card_number' => 'required|string|unique:users',
            'phone' => 'required|string',
            'region_id' => 'required|exists:regions,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'voter',
            'nin' => $request->nin,
            'voter_card_number' => $request->voter_card_number,
            'phone' => $request->phone,
            'region_id' => $request->region_id,
            'status' => 'pending'
        ]);

        Auth::login($user);

        return redirect('/voter/dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->status === 'blocked') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte est bloqué.'
                ]);
            }

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            
            return redirect('/voter/dashboard');
        }

        return back()->withErrors([
            'email' => 'Les informations de connexion sont incorrectes.'
        ]);
    }
}

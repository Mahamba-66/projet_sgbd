<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:voter,candidate',
            'region_id' => 'required|exists:regions,id',
            'nin' => 'required|string|unique:users',
            'voter_card_number' => 'required|string|unique:users',
            'phone' => 'required|string|unique:users',
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string'
        ]);

        if ($request->role === 'candidate') {
            $request->validate([
                'party_name' => 'required|string',
                'party_position' => 'required|string'
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'region_id' => $request->region_id,
            'nin' => $request->nin,
            'voter_card_number' => $request->voter_card_number,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'party_name' => $request->party_name,
            'party_position' => $request->party_position,
            'status' => $request->role === 'candidate' ? 'pending' : 'active'
        ]);

        $verificationCode = $user->generateVerificationCode();
        Mail::to($user)->send(new VerificationCodeMail($verificationCode));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if (!$user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte est ' . $user->status
                ]);
            }

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->intended($this->getRedirectPath($user));
        }

        return back()->withErrors([
            'email' => 'Ces identifiants ne correspondent pas à nos enregistrements.'
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string'
        ]);

        $user = Auth::user();

        if ($user->verification_code !== $request->verification_code) {
            return back()->withErrors([
                'verification_code' => 'Code de vérification invalide.'
            ]);
        }

        $user->markEmailAsVerified();

        return redirect()->route($this->getRedirectPath($user));
    }

    public function resendVerificationCode()
    {
        $user = Auth::user();
        $verificationCode = $user->generateVerificationCode();
        Mail::to($user)->send(new VerificationCodeMail($verificationCode));

        return back()->with('status', 'Code de vérification renvoyé.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function getRedirectPath(User $user): string
    {
        if ($user->isAdmin()) {
            return 'admin.dashboard';
        } elseif ($user->isCandidate()) {
            return 'candidate.dashboard';
        } else {
            return 'voter.dashboard';
        }
    }
}

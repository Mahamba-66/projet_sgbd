<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string'
        ]);

        $user = User::where('verification_code', $request->verification_code)
                    ->where('email_verified_at', null)
                    ->first();

        if (!$user) {
            return back()->with('error', 'Code de vérification invalide.');
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        if ($user->role === 'candidate') {
            return redirect()->route('candidate.dashboard')
                            ->with('success', 'Email vérifié avec succès !');
        } else {
            return redirect()->route('voter.dashboard')
                            ->with('success', 'Email vérifié avec succès !');
        }
    }

    public function resend(Request $request)
    {
        $user = auth()->user();
        
        if ($user->email_verified_at) {
            if ($user->role === 'candidate') {
                return redirect()->route('candidate.dashboard');
            } else {
                return redirect()->route('voter.dashboard');
            }
        }

        $verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->verification_code = $verification_code;
        $user->save();

        Mail::to($user->email)->send(new VerificationEmail($user));

        return back()->with('success', 'Un nouveau code de vérification a été envoyé à votre adresse email.');
    }
}

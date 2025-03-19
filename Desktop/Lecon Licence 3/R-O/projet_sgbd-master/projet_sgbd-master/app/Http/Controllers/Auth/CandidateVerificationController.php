<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CandidateVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche le formulaire de vérification
     */
    public function show()
    {
        return view('auth.verify');
    }

    /**
     * Vérifie le code de vérification
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if ($user->verification_code !== $request->verification_code) {
            return back()->with('error', 'Le code de vérification est incorrect.');
        }

        // Met à jour le statut de l'utilisateur
        $user->update([
            'status' => 'active',
            'email_verified_at' => now(),
            'verification_code' => null // Efface le code après utilisation
        ]);

        if ($user->role === 'candidate') {
            return redirect()->route('candidate.dashboard')
                ->with('success', 'Votre compte a été vérifié avec succès. Vous pouvez maintenant commencer à collecter des parrainages.');
        }

        return redirect()->route('home')
            ->with('success', 'Votre compte a été vérifié avec succès.');
    }

    /**
     * Renvoie le code de vérification
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        // Génère un nouveau code
        $verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update(['verification_code' => $verification_code]);

        // Envoie le nouveau code par email
        Mail::to($user->email)->send(new VerificationEmail($user));

        return back()->with('success', 'Un nouveau code de vérification a été envoyé à votre adresse email.');
    }
}

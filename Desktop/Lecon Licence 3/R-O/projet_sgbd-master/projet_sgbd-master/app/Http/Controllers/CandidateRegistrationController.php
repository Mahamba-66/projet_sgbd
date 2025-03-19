<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CandidateRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.candidate-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nin' => 'required|string|size:13|unique:users',
            'voter_card_number' => 'required|string|unique:users',
            'phone' => 'required|string',
            'region_id' => 'required|exists:regions,id',
            'photo' => 'nullable|image|max:2048',
            'program' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Gérer les fichiers
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('candidates/photos', 'public');
        }

        if ($request->hasFile('program')) {
            $validated['program'] = $request->file('program')->store('candidates/programs', 'public');
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'candidate',
            'nin' => $validated['nin'],
            'voter_card_number' => $validated['voter_card_number'],
            'phone' => $validated['phone'],
            'region_id' => $validated['region_id'],
            'photo' => $validated['photo'] ?? null,
            'program' => $validated['program'] ?? null,
            'status' => 'pending'
        ]);

        auth()->login($user);

        return redirect()->route('candidate.dashboard')
            ->with('success', 'Votre inscription a été enregistrée avec succès. Elle est en attente de validation.');
    }
}

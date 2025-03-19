<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,voter,candidate'],
            'phone' => ['required', 'string'],
        ];

        // Ajouter des règles supplémentaires pour les électeurs et les candidats
        if ($request->role !== 'admin') {
            $rules = array_merge($rules, [
                'nin' => ['required', 'string', 'size:13', 'unique:'.User::class],
                'voter_card_number' => ['required', 'string', 'unique:'.User::class],
                'region_id' => ['required', 'exists:regions,id'],
            ]);
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'status' => 'pending'
        ];

        // Ajouter des champs supplémentaires pour les électeurs et les candidats
        if ($request->role !== 'admin') {
            $userData = array_merge($userData, [
                'nin' => $request->nin,
                'voter_card_number' => $request->voter_card_number,
                'region_id' => $request->region_id,
            ]);
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        // Redirection selon le rôle
        switch ($request->role) {
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('message', 'Compte administrateur créé avec succès.');
            case 'candidate':
                return redirect()->route('candidate.pending')
                    ->with('message', 'Votre compte candidat a été créé. Il doit être validé par l\'administration.');
            default:
                return redirect()->route('voter.dashboard')
                    ->with('message', 'Votre compte électeur a été créé avec succès.');
        }
    }
}

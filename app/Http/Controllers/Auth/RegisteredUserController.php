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
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,voter,candidate'],
            'phone' => ['required', 'string'],
        ];

        if ($request->role !== 'admin') {
            $rules = array_merge($rules, [
                'nin' => ['required', 'string', 'size:13', 'unique:' . User::class],
                'voter_card_number' => ['required', 'string', 'unique:' . User::class],
                'region_id' => ['required', 'exists:regions,id'],
            ]);
        }

        $request->validate($rules);

        $userData = $request->only('name', 'email', 'password', 'role', 'phone');
        $userData['password'] = Hash::make($request->password);
        $userData['status'] = 'pending';

        if ($request->role !== 'admin') {
            $userData = array_merge($userData, $request->only('nin', 'voter_card_number', 'region_id'));
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        $route = match ($request->role) {
            'admin' => 'admin.dashboard',
            'candidate' => 'candidate.pending',
            default => 'voter.dashboard',
        };

        $message = match ($request->role) {
            'admin' => 'Compte administrateur créé avec succès.',
            'candidate' => 'Votre compte candidat a été créé. Il doit être validé par l\'administration.',
            default => 'Votre compte électeur a été créé avec succès.',
        };

        return redirect()->route($route)->with('message', $message);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:voter,candidate,admin'],
            'voter_card_number' => ['required_if:role,voter,candidate', 'nullable', 'string', 'unique:users'],
            'nin' => ['required_if:role,voter,candidate', 'nullable', 'string', 'unique:users'],
            'region' => ['required_if:role,voter,candidate', 'nullable', 'string'],
            'department' => ['required_if:role,voter,candidate', 'nullable', 'string'],
            'commune' => ['required_if:role,voter,candidate', 'nullable', 'string'],
            'polling_station' => ['required_if:role,voter,candidate', 'nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'voter_card_number' => $request->voter_card_number,
            'nin' => $request->nin,
            'region' => $request->region,
            'department' => $request->department,
            'commune' => $request->commune,
            'polling_station' => $request->polling_station,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirection selon le rôle
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'candidate':
                return redirect()->route('candidate.dashboard');
            default:
                return redirect()->route('voter.dashboard');
        }
    }
}

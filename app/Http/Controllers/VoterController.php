<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class VoterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['register', 'store']);
        $this->middleware('voter')->except(['register', 'store']);
    }

    public function register()
    {
        $regions = Region::all();
        return view('voter.register', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nin' => 'required|string|unique:users|min:13|max:13',
            'voter_card_number' => 'required|string|unique:users',
            'phone' => 'required|string',
            'region_id' => 'required|exists:regions,id'
        ]);

        $voter = User::create([
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

        Auth::login($voter);

        return redirect()->route('voter.dashboard')->with('success', 'Inscription réussie !');
    }

    public function dashboard()
    {
        $user = Auth::user();
        if ($user->status === 'blocked') {
            return redirect()->route('login')->with('error', 'Votre compte est bloqué.');
        }

        $sponsorship = Sponsorship::where('voter_id', $user->id)->first();
        
        return view('voter.dashboard', [
            'user' => $user,
            'sponsorship' => $sponsorship
        ]);
    }

    public function profile()
    {
        return view('voter.profile', [
            'user' => Auth::user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:9'
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'phone']));

        return redirect()->route('voter.profile')->with('success', 'Profil mis à jour avec succès');
    }

    public function sponsorship()
    {
        $user = Auth::user();
        if ($user->status !== 'active') {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Votre compte doit être validé pour parrainer.');
        }

        $candidates = User::where('role', 'candidate')
            ->where('status', 'active')
            ->get();

        return view('voter.sponsorship', [
            'user' => $user,
            'candidates' => $candidates
        ]);
    }

    public function submitSponsorship(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:users,id'
        ]);

        $user = Auth::user();
        if ($user->status !== 'active') {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Votre compte doit être validé pour parrainer.');
        }

        // Vérifier si l'électeur a déjà parrainé
        if (Sponsorship::where('voter_id', $user->id)->exists()) {
            return redirect()->route('voter.sponsorship')
                ->with('error', 'Vous avez déjà parrainé un candidat.');
        }

        // Vérifier si le candidat est actif
        $candidate = User::findOrFail($request->candidate_id);
        if ($candidate->status !== 'active' || $candidate->role !== 'candidate') {
            return redirect()->route('voter.sponsorship')
                ->with('error', 'Ce candidat ne peut pas être parrainé.');
        }

        Sponsorship::create([
            'voter_id' => $user->id,
            'candidate_id' => $request->candidate_id,
            'status' => 'pending'
        ]);

        return redirect()->route('voter.dashboard')
            ->with('success', 'Votre parrainage a été enregistré avec succès.');
    }
}

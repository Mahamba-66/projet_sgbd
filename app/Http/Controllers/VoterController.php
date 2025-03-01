<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoterController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $sponsorships = Sponsorship::where('voter_nin', $user->nin)->get();
        $candidates = User::where('role', 'candidate')->get();
        
        return view('voter.dashboard', [
            'user' => $user,
            'sponsorships' => $sponsorships,
            'candidates' => $candidates
        ]);
    }

    public function sponsor(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:users,id'
        ]);

        $user = Auth::user();
        
        // Vérifier si l'électeur n'a pas déjà parrainé ce candidat
        $existingSponsorship = Sponsorship::where('voter_nin', $user->nin)
            ->where('candidate_id', $request->candidate_id)
            ->first();

        if ($existingSponsorship) {
            return redirect()->back()->with('error', 'Vous avez déjà parrainé ce candidat.');
        }

        // Créer le parrainage
        Sponsorship::create([
            'voter_nin' => $user->nin,
            'candidate_id' => $request->candidate_id,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Parrainage effectué avec succès.');
    }

    public function cancelSponsorship($id)
    {
        $sponsorship = Sponsorship::where('id', $id)
            ->where('voter_nin', Auth::user()->nin)
            ->where('status', 'pending')
            ->firstOrFail();

        $sponsorship->delete();

        return redirect()->back()->with('success', 'Parrainage annulé avec succès.');
    }

    public function profile()
    {
        return view('voter.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'polling_station' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $photoPath;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }
}

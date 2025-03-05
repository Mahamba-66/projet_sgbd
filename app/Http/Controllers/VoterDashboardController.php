<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoterDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Le middleware voter est déjà appliqué dans les routes
    }

    public function index()
    {
        $user = Auth::user();
        $candidates = User::where('role', 'candidate')
            ->where('status', 'validated')
            ->select('id', 'name', 'party_name', 'party_position', 'status', 'validated_at')
            ->get();

        return view('voter.dashboard', compact('user', 'candidates'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('voter.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        return redirect()->route('voter.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    public function candidates()
    {
        $candidates = User::where('role', 'candidate')
            ->where('status', 'validated')
            ->select('id', 'name', 'party_name', 'party_position', 'status', 'validated_at')
            ->get();

        return view('voter.candidates.index', compact('candidates'));
    }
}

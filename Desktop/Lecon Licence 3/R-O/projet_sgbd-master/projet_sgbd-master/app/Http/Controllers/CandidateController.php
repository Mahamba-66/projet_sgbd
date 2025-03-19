<?php
// Ajouté le 03/19/2025 04:27:30
// feat: Système de validation des candidats

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use App\Services\SponsorshipService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    protected $sponsorshipService;

    public function __construct(SponsorshipService $sponsorshipService)
    {
        $this->sponsorshipService = $sponsorshipService;
        $this->middleware('candidate');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $stats = $this->sponsorshipService->getCandidateStats($user);
        
        return view('candidate.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentSponsorships' => $user->receivedSponsorships()
                                       ->with(['voter', 'region'])
                                       ->latest()
                                       ->take(5)
                                       ->get()
        ]);
    }

    public function profile()
    {
        return view('candidate.profile', [
            'user' => auth()->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20'],
            'nin' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'biography' => ['required', 'string', 'max:1000'],
            'party_name' => ['required', 'string', 'max:255'],
            'program_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'photo' => ['nullable', 'image', 'max:2048']
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // Gestion du fichier programme
        if ($request->hasFile('program_file')) {
            if ($user->program_file) {
                Storage::delete($user->program_file);
            }
            $validated['program_file'] = $request->file('program_file')->store('programs');
        }

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos');
        }

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function sponsorships(Request $request)
    {
        $query = auth()->user()->receivedSponsorships()
                             ->with(['voter', 'region']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('region')) {
            $query->where('region_id', $request->region);
        }

        $sponsorships = $query->latest()->paginate(20);

        return view('candidate.sponsorships', [
            'sponsorships' => $sponsorships,
            'stats' => $this->sponsorshipService->getCandidateStats(auth()->user()),
            'regions' => Region::all()
        ]);
    }

    public function stats()
    {
        $user = auth()->user();
        $stats = $this->sponsorshipService->getCandidateStats($user);
        
        return view('candidate.stats', [
            'user' => $user,
            'stats' => $stats,
            'regions' => Region::withCount(['sponsorships' => function($query) use ($user) {
                $query->where('candidate_id', $user->id)
                      ->where('status', 'validated');
            }])->get()
        ]);
    }

    public function downloadProgram(User $candidate)
    {
        if (!$candidate->isCandidate() || !$candidate->program_file) {
            abort(404);
        }

        return Storage::download($candidate->program_file);
    }
}

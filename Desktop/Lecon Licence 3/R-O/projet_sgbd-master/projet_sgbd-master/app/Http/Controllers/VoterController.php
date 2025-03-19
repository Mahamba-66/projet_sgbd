<?php
// Ajouté le 03/19/2025 04:27:30
// feat: Implémentation de la gestion des électeurs

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use App\Services\SponsorshipService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class VoterController extends Controller
{
    protected $sponsorshipService;

    public function __construct(SponsorshipService $sponsorshipService)
    {
        $this->sponsorshipService = $sponsorshipService;
        $this->middleware('voter');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $sponsorships = $user->sponsorships()
                            ->with(['candidate', 'region'])
                            ->latest()
                            ->get();

        return view('voter.dashboard', [
            'user' => $user,
            'sponsorships' => $sponsorships,
            'canSponsor' => $user->canSponsor(),
            'region' => $user->region,
            'regionStats' => $this->sponsorshipService->getRegionalStats($user->region)
        ]);
    }

    public function profile()
    {
        return view('voter.profile', [
            'user' => auth()->user(),
            'regions' => Region::all()
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
            'voter_card_number' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'region_id' => ['required', 'exists:regions,id']
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function candidates()
    {
        $candidates = User::where('role', User::ROLE_CANDIDATE)
                         ->where('status', 'active')
                         ->withCount(['receivedSponsorships' => function($query) {
                             $query->where('status', 'validated');
                         }])
                         ->paginate(12);

        return view('voter.candidates', [
            'candidates' => $candidates,
            'canSponsor' => auth()->user()->canSponsor()
        ]);
    }

    public function showCandidate(User $candidate)
    {
        if (!$candidate->isCandidate()) {
            abort(404);
        }

        return view('voter.candidates.show', [
            'candidate' => $candidate,
            'stats' => $this->sponsorshipService->getCandidateStats($candidate),
            'canSponsor' => auth()->user()->canSponsor(),
            'hasSponsored' => auth()->user()->hasSponsored($candidate)
        ]);
    }
}

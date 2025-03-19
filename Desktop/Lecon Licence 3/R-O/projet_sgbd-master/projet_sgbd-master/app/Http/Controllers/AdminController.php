<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use App\Services\SponsorshipService;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $sponsorshipService;

    public function __construct(SponsorshipService $sponsorshipService)
    {
        $this->sponsorshipService = $sponsorshipService;
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = $this->sponsorshipService->getGlobalStats();
        
        return view('admin.dashboard', [
            'stats' => $stats,
            'pendingCandidates' => User::where('role', User::ROLE_CANDIDATE)
                                     ->where('status', 'pending')
                                     ->count(),
            'totalVoters' => User::where('role', User::ROLE_VOTER)->count(),
            'totalRegions' => Region::count()
        ]);
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('region')) {
            $query->where('region_id', $request->region);
        }

        $users = $query->with('region')
                      ->latest()
                      ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'regions' => Region::all()
        ]);
    }

    public function sponsorships(Request $request)
    {
        $query = Sponsorship::with(['voter', 'candidate', 'region']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('region')) {
            $query->where('region_id', $request->region);
        }

        if ($request->has('candidate')) {
            $query->where('candidate_id', $request->candidate);
        }

        $sponsorships = $query->latest()->paginate(20);

        return view('admin.sponsorships.index', [
            'sponsorships' => $sponsorships,
            'regions' => Region::all(),
            'candidates' => User::where('role', User::ROLE_CANDIDATE)->get()
        ]);
    }

    public function stats()
    {
        return view('admin.stats', [
            'globalStats' => $this->sponsorshipService->getGlobalStats(),
            'regions' => Region::withCount(['sponsorships' => function($query) {
                $query->where('status', 'validated');
            }])->get()
        ]);
    }

    public function validateCandidate(User $user)
    {
        if (!$user->isCandidate() || !$user->isPending()) {
            return back()->with('error', 'Action non autorisée.');
        }

        DB::transaction(function () use ($user) {
            $user->status = 'active';
            $user->save();
            
            // Envoyer email de confirmation
            $user->notify(new CandidateValidated());
        });

        return back()->with('success', 'Candidat validé avec succès.');
    }

    public function rejectCandidate(Request $request, User $user)
    {
        if (!$user->isCandidate() || !$user->isPending()) {
            return back()->with('error', 'Action non autorisée.');
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($user, $request) {
            $user->status = 'rejected';
            $user->rejection_reason = $request->reason;
            $user->save();
            
            // Envoyer email de rejet
            $user->notify(new CandidateRejected($request->reason));
        });

        return back()->with('success', 'Candidat rejeté avec succès.');
    }
}

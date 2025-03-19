<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use App\Services\SponsorshipService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SponsorshipRequest;

class SponsorshipController extends Controller
{
    protected $sponsorshipService;

    public function __construct(SponsorshipService $sponsorshipService)
    {
        $this->sponsorshipService = $sponsorshipService;
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isCandidate()) {
            $sponsorships = $user->receivedSponsorships()
                ->with(['voter', 'region'])
                ->latest()
                ->paginate(20);
        } else {
            $sponsorships = $user->sponsorships()
                ->with(['candidate', 'region'])
                ->latest()
                ->paginate(20);
        }

        return view('sponsorships.index', compact('sponsorships'));
    }

    public function create()
    {
        $this->authorize('create', Sponsorship::class);

        $candidates = User::where('role', User::ROLE_CANDIDATE)
            ->where('status', 'active')
            ->get();

        return view('sponsorships.create', compact('candidates'));
    }

    public function store(SponsorshipRequest $request)
    {
        $this->authorize('create', Sponsorship::class);

        try {
            $sponsorship = $this->sponsorshipService->createSponsorship(
                Auth::user(),
                User::findOrFail($request->candidate_id)
            );

            return redirect()->route('sponsorships.index')
                ->with('success', 'Parrainage enregistré avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Sponsorship $sponsorship)
    {
        $this->authorize('view', $sponsorship);
        return view('sponsorships.show', compact('sponsorship'));
    }

    public function validate(Sponsorship $sponsorship)
    {
        $this->authorize('validate', $sponsorship);

        try {
            $this->sponsorshipService->validateSponsorship($sponsorship);
            return back()->with('success', 'Parrainage validé avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reject(Request $request, Sponsorship $sponsorship)
    {
        $this->authorize('reject', $sponsorship);

        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        try {
            $this->sponsorshipService->rejectSponsorship($sponsorship, $request->rejection_reason);
            return back()->with('success', 'Parrainage rejeté.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function stats()
    {
        $this->authorize('viewStats', Sponsorship::class);

        $stats = $this->sponsorshipService->getGlobalStats();
        return view('sponsorships.stats', compact('stats'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function statistics()
    {
        $totalVoters = User::where('role', 'voter')->count();
        $totalCandidates = User::where('role', 'candidate')->count();
        $totalSponsorships = Sponsorship::count();
        $pendingSponsorships = Sponsorship::where('status', 'pending')->count();
        $approvedSponsorships = Sponsorship::where('status', 'approved')->count();
        $rejectedSponsorships = Sponsorship::where('status', 'rejected')->count();

        $stats = [
            'total_voters' => $totalVoters,
            'total_candidates' => $totalCandidates,
            'total_sponsorships' => $totalSponsorships,
            'pending_sponsorships' => $pendingSponsorships,
            'approved_sponsorships' => $approvedSponsorships,
            'rejected_sponsorships' => $rejectedSponsorships
        ];

        if (request()->wantsJson()) {
            return response()->json($stats);
        }

        return view('admin.statistics', compact('stats'));
    }

    public function pendingSponsorships()
    {
        $sponsorships = Sponsorship::with(['voter', 'candidate', 'region'])
            ->where('status', 'pending')
            ->paginate(20);

        if (request()->wantsJson()) {
            return response()->json($sponsorships);
        }

        return view('admin.pending-sponsorships', compact('sponsorships'));
    }

    public function voters()
    {
        $voters = User::where('role', 'voter')
            ->with('region')
            ->latest()
            ->paginate(15);

        return view('admin.voters.index', compact('voters'));
    }

    public function showVoter($id)
    {
        $voter = User::where('role', 'voter')
            ->with(['region', 'sponsorship.candidate'])
            ->findOrFail($id);

        return view('admin.voters.show', compact('voter'));
    }

    public function blockVoter(Request $request, $id)
    {
        $voter = User::where('role', 'voter')->findOrFail($id);
        
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $voter->update([
            'status' => 'blocked',
            'blocked_reason' => $request->reason,
            'blocked_at' => now()
        ]);

        // Si l'électeur a un parrainage en cours, on l'annule
        if ($voter->sponsorship) {
            $voter->sponsorship->update(['status' => 'cancelled']);
        }

        return redirect()->route('admin.voters.show', $voter)
            ->with('success', 'L\'électeur a été bloqué avec succès.');
    }

    public function validateVoter($id)
    {
        $voter = User::where('role', 'voter')->findOrFail($id);
        
        $voter->update([
            'status' => 'active',
            'validated_at' => now()
        ]);

        return redirect()->route('admin.voters.show', $voter)
            ->with('success', 'L\'électeur a été validé avec succès.');
    }

    public function searchVoters(Request $request)
    {
        $query = $request->get('q');
        
        $voters = User::where('role', 'voter')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('nin', 'like', "%{$query}%")
                    ->orWhere('voter_card_number', 'like', "%{$query}%");
            })
            ->with('region')
            ->paginate(15);

        return view('admin.voters.index', compact('voters', 'query'));
    }

    public function exportVoters()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=voters.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $voters = User::where('role', 'voter')
            ->with('region')
            ->get();

        $callback = function() use ($voters) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Email',
                'NIN',
                'Carte d\'électeur',
                'Téléphone',
                'Région',
                'Statut',
                'Date d\'inscription'
            ]);

            // Données
            foreach ($voters as $voter) {
                fputcsv($file, [
                    $voter->id,
                    $voter->name,
                    $voter->email,
                    $voter->nin,
                    $voter->voter_card_number,
                    $voter->phone,
                    $voter->region->name,
                    $voter->status,
                    $voter->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $stats = [
            'total_voters' => User::where('role', 'voter')->count(),
            'total_candidates' => User::where('role', 'candidate')->count(),
            'total_sponsorships' => Sponsorship::count(),
            'pending_sponsorships' => Sponsorship::where('status', 'pending')->count(),
            'approved_sponsorships' => Sponsorship::where('status', 'approved')->count(),
            'rejected_sponsorships' => Sponsorship::where('status', 'rejected')->count(),
        ];

        return request()->wantsJson() 
            ? response()->json($stats) 
            : view('admin.statistics', compact('stats'));
    }

    public function pendingSponsorships()
    {
        $sponsorships = Sponsorship::with(['voter', 'candidate', 'region'])
            ->where('status', 'pending')
            ->paginate(20);

        return request()->wantsJson() 
            ? response()->json($sponsorships) 
            : view('admin.pending-sponsorships', compact('sponsorships'));
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

    public function changeVoterStatus(Request $request, $id, $status)
    {
        $voter = User::where('role', 'voter')->findOrFail($id);
        $reason = $request->input('reason', null);

        $voter->update([
            'status' => $status,
            $status === 'blocked' ? 'blocked_reason' : 'validated_at' => $reason ?: now()
        ]);

        if ($status === 'blocked' && $voter->sponsorship) {
            $voter->sponsorship->update(['status' => 'cancelled']);
        }

        $message = $status === 'blocked' 
            ? 'L\'électeur a été bloqué avec succès.' 
            : 'L\'électeur a été validé avec succès.';

        return redirect()->route('admin.voters.show', $voter)
            ->with('success', $message);
    }

    public function searchVoters(Request $request)
    {
        $query = $request->get('q');
        
        $voters = User::where('role', 'voter')
            ->where(fn($q) => $q->where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('nin', 'like', "%{$query}%")
                ->orWhere('voter_card_number', 'like', "%{$query}%"))
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

        $voters = User::where('role', 'voter')->with('region')->get();

        $callback = function() use ($voters) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Nom', 'Email', 'NIN', 'Carte d\'électeur', 'Téléphone', 'Région', 'Statut', 'Date d\'inscription']);
            foreach ($voters as $voter) {
                fputcsv($file, [
                    $voter->id, $voter->name, $voter->email, $voter->nin, $voter->voter_card_number, 
                    $voter->phone, $voter->region->name, $voter->status, $voter->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

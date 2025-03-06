<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $stats = [
            'total_voters' => User::where('role', 'voter')->count(),
            'total_candidates' => User::where('role', 'candidate')->count(),
            'total_sponsorships' => Sponsorship::count(),
            'pending_sponsorships' => Sponsorship::where('status', 'pending')->count(),
            'approved_sponsorships' => Sponsorship::where('status', 'approved')->count(),
            'rejected_sponsorships' => Sponsorship::where('status', 'rejected')->count(),
        ];

        return request()->wantsJson() ? response()->json($stats) : view('admin.statistics', compact('stats'));
    }

    public function sponsorships($status = null)
    {
        $query = Sponsorship::with(['voter', 'candidate', 'region']);
        if ($status) {
            $query->where('status', $status);
        }
        $sponsorships = $query->paginate(20);

        return request()->wantsJson() ? response()->json($sponsorships) : view('admin.sponsorships', compact('sponsorships'));
    }

    public function voters(Request $request)
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
            ->latest()
            ->paginate(15);

        return view('admin.voters.index', compact('voters', 'query'));
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

        return redirect()->route('admin.voters.show', $voter)->with('success', "L'électeur a été {$status} avec succès.");
    }

    public function exportVoters()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=voters.csv',
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

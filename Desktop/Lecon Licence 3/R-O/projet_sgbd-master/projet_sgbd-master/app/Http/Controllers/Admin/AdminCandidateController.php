<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCandidateController extends Controller
{
    public function index()
    {
        $candidates = User::where('role', 'candidate')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.candidates.index', compact('candidates'));
    }

    public function show(User $candidate)
    {
        if ($candidate->role !== 'candidate') {
            abort(404);
        }

        $sponsorships = $candidate->sponsorships()
            ->with('voter')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.candidates.show', compact('candidate', 'sponsorships'));
    }

    public function validateCandidate(User $candidate)
    {
        if ($candidate->role !== 'candidate') {
            abort(404);
        }

        $candidate->update([
            'status' => 'validated',
            'validated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Le candidat a été validé avec succès.');
    }

    public function reject(Request $request, User $candidate)
    {
        if ($candidate->role !== 'candidate') {
            abort(404);
        }

        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $candidate->update([
            'status' => 'rejected',
            'blocked_reason' => $request->reason,
            'blocked_at' => now()
        ]);

        return redirect()->back()->with('success', 'Le candidat a été rejeté.');
    }

    /**
     * Télécharge le rapport détaillé d'un candidat
     *
     * @param User $candidate
     * @return \Illuminate\Http\Response
     */
    public function downloadReport(User $candidate)
    {
        if ($candidate->role !== 'candidate') {
            abort(404);
        }

        $sponsorships = $candidate->sponsorships()
            ->with('voter')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'candidate' => $candidate,
            'sponsorships' => $sponsorships,
            'total_sponsorships' => $sponsorships->count(),
            'validated_sponsorships' => $sponsorships->where('status', 'validated')->count(),
            'pending_sponsorships' => $sponsorships->where('status', 'pending')->count(),
            'rejected_sponsorships' => $sponsorships->where('status', 'rejected')->count(),
            'generated_at' => now()
        ];

        $pdf = \PDF::loadView('admin.reports.candidate', $data);
        
        return $pdf->download("rapport_candidat_{$candidate->id}.pdf");
    }
}

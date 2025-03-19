<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use PDF;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = User::where('role', 'candidate')
            ->withCount([
                'sponsorships as total_sponsorships',
                'sponsorships as validated_sponsorships' => function($query) {
                    $query->where('status', 'validated');
                },
                'sponsorships as pending_sponsorships' => function($query) {
                    $query->where('status', 'pending');
                },
                'sponsorships as rejected_sponsorships' => function($query) {
                    $query->where('status', 'rejected');
                }
            ])
            ->get()
            ->map(function($candidate) {
                $candidate->progress = $candidate->validated_sponsorships > 0 
                    ? min(round(($candidate->validated_sponsorships / 44231) * 100, 2), 100)
                    : 0;
                return $candidate;
            });

        return view('admin.candidates.index', compact('candidates'));
    }

    public function voterIndex()
    {
        $candidates = User::where('role', 'candidate')
            ->where('status', 'validated')
            ->select('id', 'name', 'party_name', 'party_position', 'status')
            ->get();
            
        return view('voter.candidates.index', compact('candidates'));
    }

    public function show($id)
    {
        $candidate = User::where('role', 'candidate')
            ->with(['sponsorships' => function($query) {
                $query->where('status', 'validated');
            }])
            ->findOrFail($id);

        return view('admin.candidates.show', compact('candidate'));
    }

    public function verify($id)
    {
        $candidate = User::findOrFail($id);
        return view('admin.candidates.verify', compact('candidate'));
    }

    public function validateCandidate($id)
    {
        $candidate = User::findOrFail($id);
        $candidate->status = 'validated';
        $candidate->validated_at = now();
        $candidate->save();

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Le candidat a été validé avec succès.');
    }

    public function rollback($id)
    {
        $candidate = User::findOrFail($id);
        $candidate->status = 'pending';
        $candidate->validated_at = null;
        $candidate->save();

        return redirect()->route('admin.candidates.index')
            ->with('success', 'La validation du candidat a été annulée.');
    }

    public function report($id)
    {
        $candidate = User::where('role', 'candidate')
            ->with(['sponsorships' => function($query) {
                $query->where('status', 'validated')
                    ->with('voter');
            }])
            ->findOrFail($id);

        $pdf = PDF::loadView('admin.candidates.report', compact('candidate'));
        return $pdf->download('rapport-parrainages-' . $candidate->name . '.pdf');
    }

    public function voterShow($id)
    {
        $candidate = User::where('role', 'candidate')
            ->select('id', 'name', 'party_name', 'party_position', 'status', 'validated_at')
            ->findOrFail($id);

        return view('voter.candidates.show', compact('candidate'));
    }
}

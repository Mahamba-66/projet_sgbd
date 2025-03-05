<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = User::where('role', 'candidate')->get();
        return view('admin.candidates.index', compact('candidates'));
    }

    public function voterIndex()
    {
        $candidates = User::where('role', 'candidate')
                        ->where('status', 'validated')
                        ->get();
        return view('voter.candidates.index', compact('candidates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'party_name' => 'required|string|max:255',
        ]);

        $validated['role'] = 'candidate';
        $validated['status'] = 'pending';

        $candidate = User::create($validated);

        if ($request->wantsJson()) {
            return response()->json($candidate, 201);
        }

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidat créé avec succès');
    }

    public function validateCandidate(User $candidate)
    {
        if ($candidate->role !== 'candidate') {
            return back()->with('error', 'Cet utilisateur n\'est pas un candidat');
        }

        $candidate->update([
            'status' => 'validated',
            'validation_date' => now()
        ]);

        if (request()->wantsJson()) {
            return response()->json($candidate);
        }

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidat validé avec succès');
    }

    public function reject(Request $request, User $candidate)
    {
        if ($candidate->role !== 'candidate') {
            return back()->with('error', 'Cet utilisateur n\'est pas un candidat');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $candidate->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

        if ($request->wantsJson()) {
            return response()->json($candidate);
        }

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidat rejeté avec succès');
    }
}

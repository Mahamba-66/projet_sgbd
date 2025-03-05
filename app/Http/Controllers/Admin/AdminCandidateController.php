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
            'rejected_at' => now(),
            'rejection_reason' => $request->reason
        ]);

        return redirect()->back()->with('success', 'Le candidat a été rejeté.');
    }

    public function create()
    {
        return view('admin.candidates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nin' => 'required|string|unique:users',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'nin' => $validated['nin'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'role' => 'candidate',
            'status' => 'pending'
        ]);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Le candidat a été créé avec succès.');
    }
}

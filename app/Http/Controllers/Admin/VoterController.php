<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EligibleVoter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function index()
    {
        $voters = User::where('role', 'voter')
                     ->paginate(10);
        
        return view('admin.voters.index', compact('voters'));
    }

    public function create()
    {
        return view('admin.voters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'voter_card_number' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $validated['role'] = 'voter';
        $validated['status'] = 'active';
        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.voters.index')
            ->with('success', 'Électeur créé avec succès.');
    }

    public function edit(User $voter)
    {
        return view('admin.voters.edit', compact('voter'));
    }

    public function update(Request $request, User $voter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $voter->id,
            'voter_card_number' => 'required|string|max:255|unique:users,voter_card_number,' . $voter->id,
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $voter->update($validated);

        return redirect()->route('admin.voters.index')
            ->with('success', 'Électeur mis à jour avec succès.');
    }

    public function destroy(User $voter)
    {
        $voter->delete();

        return redirect()->route('admin.voters.index')
            ->with('success', 'Électeur supprimé avec succès.');
    }

    public function show($id)
    {
        $voter = User::findOrFail($id);
        return view('admin.voters.show', compact('voter'));
    }

    public function details($id)
    {
        $voter = User::where('role', 'voter')
            ->withCount(['sponsorships'])
            ->findOrFail($id);

        return view('admin.voters.details', compact('voter'));
    }

    public function verify($id)
    {
        $voter = User::where('role', 'voter')->findOrFail($id);
        $voter->status = 'verified';
        $voter->save();

        return redirect()->route('admin.voters.index')
            ->with('success', 'Électeur vérifié avec succès');
    }

    public function validateVoter($id)
    {
        $voter = User::where('role', 'voter')->findOrFail($id);
        $voter->status = 'validated';
        $voter->save();

        return redirect()->route('admin.voters.index')
            ->with('success', 'Électeur validé avec succès');
    }

    public function eligibleList()
    {
        $eligibleVoters = EligibleVoter::orderBy('created_at', 'desc')->get();
        return view('admin.voters.eligible-list', compact('eligibleVoters'));
    }
}

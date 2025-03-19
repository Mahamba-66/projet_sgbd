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
            ->withCount(['sponsorships'])
            ->paginate(15);

        return view('admin.voters.index', compact('voters'));
    }

    public function show($id)
    {
        $voter = User::where('role', 'voter')
            ->withCount(['sponsorships'])
            ->findOrFail($id);

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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Models\User;
use Illuminate\Http\Request;

class SponsorshipController extends Controller
{
    public function index()
    {
        $sponsorships = Sponsorship::with(['voter', 'candidate'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Sponsorship::count(),
            'validated' => Sponsorship::where('status', 'validated')->count(),
            'pending' => Sponsorship::where('status', 'pending')->count(),
            'rejected' => Sponsorship::where('status', 'rejected')->count(),
        ];

        return view('admin.sponsorships.index', compact('sponsorships', 'stats'));
    }

    public function show($id)
    {
        $sponsorship = Sponsorship::with(['voter', 'candidate'])
            ->findOrFail($id);

        return view('admin.sponsorships.show', compact('sponsorship'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:validated,rejected,pending'
        ]);

        $sponsorship = Sponsorship::findOrFail($id);
        $sponsorship->status = $request->status;
        $sponsorship->save();

        return redirect()->back()->with('success', 'Statut du parrainage mis à jour avec succès.');
    }
}

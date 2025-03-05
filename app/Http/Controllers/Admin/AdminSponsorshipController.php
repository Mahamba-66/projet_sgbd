<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Models\Region;
use Illuminate\Http\Request;

class AdminSponsorshipController extends Controller
{
    public function index()
    {
        $sponsorships = Sponsorship::with(['candidate', 'voter', 'voter.region'])
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

    public function validate(Sponsorship $sponsorship)
    {
        $sponsorship->status = 'validated';
        $sponsorship->save();

        return response()->json(['message' => 'Parrainage validé avec succès']);
    }

    public function reject(Request $request, Sponsorship $sponsorship)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $sponsorship->status = 'rejected';
        $sponsorship->rejection_reason = $request->reason;
        $sponsorship->save();

        return response()->json(['message' => 'Parrainage rejeté avec succès']);
    }
}

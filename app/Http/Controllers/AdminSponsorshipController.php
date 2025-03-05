<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSponsorshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function pending()
    {
        $sponsorships = Sponsorship::where('status', 'pending')
            ->with(['voter.region', 'candidate'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.sponsorships.pending', [
            'user' => Auth::user(),
            'sponsorships' => $sponsorships
        ]);
    }

    public function validate(Sponsorship $sponsorship)
    {
        $sponsorship->update(['status' => 'validated']);
        return back()->with('success', 'Parrainage validé avec succès.');
    }

    public function reject(Sponsorship $sponsorship)
    {
        $sponsorship->update(['status' => 'rejected']);
        return back()->with('success', 'Parrainage rejeté avec succès.');
    }
}

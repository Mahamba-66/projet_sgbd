<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sponsorship;
use App\Models\SponsorshipPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSponsorshipController extends Controller
{
    public function index()
    {
        // Récupérer la période active
        $activePeriod = SponsorshipPeriod::where('is_active', true)->first();

        $sponsorships = DB::table('sponsorships')
            ->join('users as candidates', 'sponsorships.candidate_id', '=', 'candidates.id')
            ->join('users as voters', 'sponsorships.voter_id', '=', 'voters.id')
            ->join('regions', 'voters.region_id', '=', 'regions.id')
            ->select(
                'sponsorships.*',
                'candidates.name as candidate_name',
                'voters.name as voter_name',
                'regions.name as region_name'
            )
            ->orderBy('sponsorships.created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => DB::table('sponsorships')->count(),
            'validated' => DB::table('sponsorships')->where('status', 'validated')->count(),
            'pending' => DB::table('sponsorships')->where('status', 'pending')->count(),
            'rejected' => DB::table('sponsorships')->where('status', 'rejected')->count()
        ];

        return view('admin.sponsorships.index', compact('activePeriod', 'sponsorships', 'stats'));
    }

    public function validateSponsorship($id)
    {
        // Vérifier si une période de parrainage est active
        $activePeriod = SponsorshipPeriod::where('is_active', true)->first();
        if (!$activePeriod) {
            return redirect()->route('admin.sponsorships.index')
                ->with('error', 'Impossible de valider : aucune période de parrainage n\'est active.');
        }

        $sponsorship = DB::table('sponsorships')->where('id', $id)->first();

        if (!$sponsorship) {
            return redirect()->back()->with('error', 'Parrainage introuvable.');
        }

        DB::table('sponsorships')
            ->where('id', $id)
            ->update([
                'status' => 'validated',
                'validated_at' => now(),
                'validated_by' => auth()->id()
            ]);

        return redirect()->back()->with('success', 'Le parrainage a été validé avec succès.');
    }

    public function rejectSponsorship($id)
    {
        $sponsorship = DB::table('sponsorships')->where('id', $id)->first();

        if (!$sponsorship) {
            return redirect()->back()->with('error', 'Parrainage introuvable.');
        }

        DB::table('sponsorships')
            ->where('id', $id)
            ->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id()
            ]);

        return redirect()->back()->with('success', 'Le parrainage a été rejeté.');
    }

    public function show($id)
    {
        $sponsorship = DB::table('sponsorships')
            ->join('users as candidates', 'sponsorships.candidate_id', '=', 'candidates.id')
            ->join('users as voters', 'sponsorships.voter_id', '=', 'voters.id')
            ->select(
                'sponsorships.*',
                'candidates.name as candidate_name',
                'candidates.party_name',
                'voters.name as voter_name',
                'voters.region_id',
                'voters.email as voter_email'
            )
            ->where('sponsorships.id', $id)
            ->first();

        if (!$sponsorship) {
            return redirect()->route('admin.sponsorships.index')
                ->with('error', 'Parrainage introuvable.');
        }

        return view('admin.sponsorships.show', compact('sponsorship'));
    }
}

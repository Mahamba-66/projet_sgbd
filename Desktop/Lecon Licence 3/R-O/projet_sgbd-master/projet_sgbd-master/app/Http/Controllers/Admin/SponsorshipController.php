<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Models\SponsorshipPeriod;
use App\Models\User;
use Illuminate\Http\Request;

class SponsorshipController extends Controller
{
    /**
     * Affiche la liste des parrainages
     */
    public function index()
    {
        // Récupérer la période active
        $activePeriod = SponsorshipPeriod::where('is_active', true)->first();

        // Statistiques des parrainages
        $stats = [
            'total' => Sponsorship::count(),
            'validated' => Sponsorship::where('status', 'validated')->count(),
            'pending' => Sponsorship::where('status', 'pending')->count(),
            'rejected' => Sponsorship::where('status', 'rejected')->count(),
        ];

        // Liste des parrainages avec pagination
        $sponsorships = Sponsorship::with(['candidate', 'voter', 'region'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.sponsorships.index', compact('activePeriod', 'stats', 'sponsorships'));
    }

    /**
     * Affiche les détails d'un parrainage
     */
    public function show(Sponsorship $sponsorship)
    {
        return view('admin.sponsorships.show', compact('sponsorship'));
    }

    /**
     * Valide un parrainage
     */
    public function validate(Sponsorship $sponsorship)
    {
        // Vérifier si une période de parrainage est active
        $activePeriod = SponsorshipPeriod::where('is_active', true)->first();
        if (!$activePeriod) {
            return redirect()->route('admin.sponsorships.index')
                ->with('error', 'Impossible de valider : aucune période de parrainage n\'est active.');
        }

        $sponsorship->update(['status' => 'validated']);
        
        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Parrainage validé avec succès.');
    }

    /**
     * Rejette un parrainage
     */
    public function reject(Sponsorship $sponsorship)
    {
        $sponsorship->update(['status' => 'rejected']);
        
        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Parrainage rejeté.');
    }
}

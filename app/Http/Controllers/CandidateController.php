<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ElectoralPeriod;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $currentPeriod = ElectoralPeriod::orderBy('created_at', 'desc')->first();
        
        // Récupérer les statistiques des parrainages
        $totalSponsorships = Sponsorship::where('candidate_id', $user->id)->count();
        
        // Parrainages par statut
        $validSponsorships = Sponsorship::where('candidate_id', $user->id)
            ->where('status', 'valid')
            ->count();
            
        $invalidSponsorships = Sponsorship::where('candidate_id', $user->id)
            ->where('status', 'invalid')
            ->count();
            
        $pendingSponsorships = Sponsorship::where('candidate_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        // Calculer le pourcentage de parrainages valides
        $validPercentage = $totalSponsorships > 0 ? ($validSponsorships / $totalSponsorships) * 100 : 0;
        
        // Compter les régions couvertes
        $regionsCovered = Sponsorship::where('candidate_id', $user->id)
            ->where('status', 'valid')
            ->distinct('region')
            ->count('region');

        // Vérifier si le dossier est complet
        $fileStatus = $this->checkFileStatus($user);

        $stats = [
            'total_sponsorships' => $totalSponsorships,
            'valid_sponsorships' => $validSponsorships,
            'invalid_sponsorships' => $invalidSponsorships,
            'pending_sponsorships' => $pendingSponsorships,
            'valid_percentage' => $validPercentage,
            'regions_covered' => $regionsCovered,
            'min_required' => $currentPeriod ? $currentPeriod->min_sponsorships : 0,
            'max_allowed' => $currentPeriod ? $currentPeriod->max_sponsorships : 0,
            'file_status' => $fileStatus
        ];

        return view('candidate.dashboard', compact('stats', 'currentPeriod'));
    }

    private function checkFileStatus($user)
    {
        $requiredFields = ['name', 'email', 'phone', 'party_name'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                $missingFields[] = $field;
            }
        }

        return [
            'complete' => empty($missingFields),
            'missing_fields' => $missingFields
        ];
    }

    public function profile()
    {
        $user = Auth::user();
        return view('candidate.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'party_name' => 'required|string|max:255',
            'biography' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048'
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->party_name = $validated['party_name'];
        $user->biography = $validated['biography'];

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('avatars', 'public');
            $user->photo = $path;
        }

        $user->save();

        return redirect()->route('candidate.profile')->with('success', 'Profil mis à jour avec succès.');
    }

    public function sponsorships()
    {
        $user = Auth::user();
        $sponsorships = Sponsorship::where('candidate_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('candidate.sponsorships', compact('sponsorships'));
    }

    public function statistics()
    {
        $user = auth()->user();
        $stats = [
            'total_sponsorships' => 0,
            'valid_sponsorships' => 0,
            'invalid_sponsorships' => 0,
            'pending_sponsorships' => 0,
            'min_required' => 44000,
            'by_region' => collect([]),
            'by_age_group' => collect([]),
            'by_gender' => collect([]),
            'daily_trend' => collect([])
        ];

        // Simulation des données pour le développement
        $stats['by_region'] = collect([
            (object)['region' => 'Dakar', 'total' => 15000],
            (object)['region' => 'Thiès', 'total' => 8000],
            (object)['region' => 'Saint-Louis', 'total' => 6000],
            (object)['region' => 'Ziguinchor', 'total' => 4000],
        ]);

        // Données par groupe d'âge
        $stats['by_age_group'] = collect([
            (object)['age_group' => '18-25 ans', 'total' => 5000],
            (object)['age_group' => '26-35 ans', 'total' => 12000],
            (object)['age_group' => '36-45 ans', 'total' => 8000],
            (object)['age_group' => '46-60 ans', 'total' => 6000],
            (object)['age_group' => '60+ ans', 'total' => 2000],
        ]);

        // Données par genre
        $stats['by_gender'] = collect([
            (object)['gender' => 'M', 'total' => 18000],
            (object)['gender' => 'F', 'total' => 15000],
        ]);

        $stats['daily_trend'] = collect([
            (object)['date' => '2024-02-23', 'total' => 120],
            (object)['date' => '2024-02-24', 'total' => 150],
            (object)['date' => '2024-02-25', 'total' => 180],
            (object)['date' => '2024-02-26', 'total' => 200],
            (object)['date' => '2024-02-27', 'total' => 220],
            (object)['date' => '2024-02-28', 'total' => 250],
            (object)['date' => '2024-02-29', 'total' => 280],
        ]);

        // Calcul des totaux
        $stats['total_sponsorships'] = $stats['by_region']->sum('total');
        $stats['valid_sponsorships'] = 28000;
        $stats['invalid_sponsorships'] = 3000;
        $stats['pending_sponsorships'] = 2000;

        return view('candidate.statistics', compact('stats'));
    }
}

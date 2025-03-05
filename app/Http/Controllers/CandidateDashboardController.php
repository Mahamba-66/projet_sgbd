<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Sponsorship;
use Barryvdh\DomPDF\Facade as PDF;

class CandidateDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\CandidateMiddleware::class);
    }

    public function index()
    {
        $user = auth()->user();
        $sponsorshipsCount = Sponsorship::where('candidate_id', $user->id)->count();
        
        return view('candidate.dashboard', compact('user', 'sponsorshipsCount'));
    }

    public function sponsorships()
    {
        $user = auth()->user();
        $sponsorships = Sponsorship::where('candidate_id', $user->id)
            ->with('voter')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('candidate.sponsorships', compact('user', 'sponsorships'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('candidate.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => [
                'required',
                'string',
                'regex:/^7[0-9]{8}$/',
                'unique:users,phone,' . $user->id
            ],
            'party_name' => 'required|string|max:255',
            'party_position' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'phone.required' => 'Le numéro de téléphone est requis.',
            'phone.regex' => 'Le numéro doit commencer par 7 et contenir 9 chiffres.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'party_name.required' => 'Le nom du parti est requis.',
            'party_position.required' => 'La position dans le parti est requise.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'L\'image doit être au format jpeg, png ou jpg.',
            'photo.max' => 'L\'image ne doit pas dépasser 2Mo.'
        ]);

        try {
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($user->photo) {
                    Storage::delete($user->photo);
                }
                
                // Enregistrer la nouvelle photo
                $path = $request->file('photo')->store('public/photos');
                $validated['photo'] = str_replace('public/', '', $path);
            }

            $user->update($validated);

            return redirect()->route('candidate.profile')
                ->with('success', 'Profil mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du profil.'])
                ->withInput();
        }
    }

    public function downloadReport()
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();
        
        // Calculer les statistiques par région
        $regionStats = Sponsorship::where('candidate_id', $user->id)
            ->select('region_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('region_id')
            ->with('region')
            ->get()
            ->mapWithKeys(function ($stat) {
                return [$stat->region->name => $stat->total];
            });

        // Récupérer le nombre total de parrainages validés
        $sponsorshipsCount = Sponsorship::where('candidate_id', $user->id)
            ->where('status', 'validated')
            ->count();
        
        // Calculer le pourcentage de progression
        $progress = ($sponsorshipsCount / 44231) * 100;
        $progress = min($progress, 100);
        
        // Récupérer les 50 derniers parrainages avec leurs relations
        $recentSponsorships = Sponsorship::where('candidate_id', $user->id)
            ->with(['voter', 'region'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        // Statistiques globales
        $stats = [
            'total_validated' => $sponsorshipsCount,
            'total_pending' => Sponsorship::where('candidate_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'total_rejected' => Sponsorship::where('candidate_id', $user->id)
                ->where('status', 'rejected')
                ->count(),
            'regions' => $regionStats,
        ];

        $html = view('candidate.report', [
            'user' => $user,
            'sponsorshipsCount' => $sponsorshipsCount,
            'progress' => $progress,
            'sponsorships' => $recentSponsorships,
            'stats' => $stats,
            'generatedAt' => now()
        ])->render();

        $filename = 'rapport-parrainages-' . $user->name . '-' . now()->format('d-m-Y') . '.html';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}

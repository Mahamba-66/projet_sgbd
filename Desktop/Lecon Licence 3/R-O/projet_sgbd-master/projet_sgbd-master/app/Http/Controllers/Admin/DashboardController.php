<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sponsorship;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_candidates' => User::where('role', 'candidate')->count(),
            'total_voters' => User::where('role', 'voter')->count(),
            'total_sponsorships' => Sponsorship::count(),
            'validated_sponsorships' => Sponsorship::where('status', 'validated')->count(),
        ];

        // Récupérer les candidats avec leurs statistiques
        $candidates = User::where('role', 'candidate')
            ->withCount([
                'sponsorships as total_sponsorships',
                'sponsorships as validated_sponsorships' => function($query) {
                    $query->where('status', 'validated');
                },
                'sponsorships as pending_sponsorships' => function($query) {
                    $query->where('status', 'pending');
                },
                'sponsorships as rejected_sponsorships' => function($query) {
                    $query->where('status', 'rejected');
                }
            ])
            ->get()
            ->map(function($candidate) {
                $candidate->progress = $candidate->validated_sponsorships > 0 
                    ? min(round(($candidate->validated_sponsorships / 44231) * 100, 2), 100)
                    : 0;
                return $candidate;
            });

        return view('admin.dashboard', compact('stats', 'candidates'));
    }
}

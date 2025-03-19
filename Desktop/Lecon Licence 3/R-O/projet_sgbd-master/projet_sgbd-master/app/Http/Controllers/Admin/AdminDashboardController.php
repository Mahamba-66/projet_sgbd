<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SponsorshipPeriod;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Récupérer la période de parrainage active
        $sponsorshipPeriod = SponsorshipPeriod::where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->first();

        $stats = [
            'total_candidates' => User::where('role', 'candidate')->count(),
            'total_voters' => User::where('role', 'voter')->count(),
            'total_sponsorships' => DB::table('sponsorships')->count(),
            'validated_sponsorships' => DB::table('sponsorships')
                                        ->where('status', 'validated')
                                        ->count(),
            'pending_validations' => User::where('role', 'candidate')
                                       ->where('status', 'pending')
                                       ->count()
        ];

        // Récupérer tous les candidats avec leurs statistiques de parrainage
        $candidates = User::where('role', 'candidate')
            ->select('users.*')
            ->selectRaw('
                (SELECT COUNT(*) FROM sponsorships WHERE candidate_id = users.id) as total_sponsorships,
                (SELECT COUNT(*) FROM sponsorships WHERE candidate_id = users.id AND status = "validated") as validated_sponsorships,
                (SELECT COUNT(*) FROM sponsorships WHERE candidate_id = users.id AND status = "pending") as pending_sponsorships,
                (SELECT COUNT(*) FROM sponsorships WHERE candidate_id = users.id AND status = "rejected") as rejected_sponsorships
            ')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($candidate) {
                $candidate->progress = $candidate->total_sponsorships > 0 
                    ? round(($candidate->validated_sponsorships / $candidate->total_sponsorships) * 100)
                    : 0;
                return $candidate;
            });

        return view('admin.dashboard', compact('stats', 'candidates', 'sponsorshipPeriod'));
    }
}

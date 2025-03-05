<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sponsorship;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('App\Http\Middleware\AdminMiddleware');
    }

    public function index()
    {
        // Statistiques par région
        $regional_stats = DB::table('sponsorships')
            ->join('users as voters', 'sponsorships.voter_id', '=', 'voters.id')
            ->join('regions', 'voters.region_id', '=', 'regions.id')
            ->select(
                'regions.name as region',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN sponsorships.status = "validated" THEN 1 ELSE 0 END) as validated')
            )
            ->groupBy('regions.id', 'regions.name')
            ->get();

        // Progrès des candidats
        $candidate_progress = User::where('role', 'candidate')
            ->withCount(['sponsorships', 'sponsorships as validated_sponsorships_count' => function($query) {
                $query->where('status', 'validated');
            }])
            ->get()
            ->map(function($candidate) {
                $candidate->progress = min(($candidate->validated_sponsorships_count / 44231) * 100, 100);
                return $candidate;
            });

        // Tendance des parrainages sur 30 jours
        $sponsorship_trend = DB::table('sponsorships')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $reports = [
            'regional_stats' => $regional_stats,
            'candidate_progress' => $candidate_progress,
            'sponsorship_trend' => $sponsorship_trend
        ];

        return view('admin.reports.index', compact('reports'));
    }

    public function downloadCandidateReport($id)
    {
        $candidate = User::findOrFail($id);
        $sponsorshipsCount = Sponsorship::where('candidate_id', $candidate->id)->count();
        $progress = ($sponsorshipsCount / 44231) * 100;
        $progress = min($progress, 100);
        
        $sponsorships = Sponsorship::where('candidate_id', $candidate->id)
            ->with('voter')
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('admin.reports.candidate_report', [
            'candidate' => $candidate,
            'sponsorshipsCount' => $sponsorshipsCount,
            'progress' => $progress,
            'sponsorships' => $sponsorships,
            'generatedAt' => now()
        ])->render();

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="rapport-candidat-' . $candidate->name . '-' . now()->format('d-m-Y') . '.html"');
    }
}

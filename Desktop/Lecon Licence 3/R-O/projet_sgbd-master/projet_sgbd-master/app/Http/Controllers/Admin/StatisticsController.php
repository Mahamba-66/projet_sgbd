<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('App\Http\Middleware\AdminMiddleware');
    }

    public function index()
    {
        // Statistiques globales
        $totalCandidates = User::where('role', 'candidate')->count();
        $totalVoters = User::where('role', 'voter')->count();
        $totalSponsorships = Sponsorship::count();
        $validatedSponsorships = Sponsorship::where('status', 'validated')->count();

        // Statistiques par région
        $sponsorshipsByRegion = DB::table('sponsorships')
            ->join('regions', 'sponsorships.region_id', '=', 'regions.id')
            ->select('regions.name', DB::raw('count(*) as total'))
            ->groupBy('regions.id', 'regions.name')
            ->get();

        // Top 5 des candidats avec le plus de parrainages validés
        $topCandidates = User::where('role', 'candidate')
            ->withCount(['sponsorships as validated_count' => function($query) {
                $query->where('status', 'validated');
            }])
            ->orderBy('validated_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.statistics', compact(
            'totalCandidates',
            'totalVoters',
            'totalSponsorships',
            'validatedSponsorships',
            'sponsorshipsByRegion',
            'topCandidates'
        ));
    }
}

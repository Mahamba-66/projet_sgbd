<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $stats = [
            'total_voters' => User::where('role', 'voter')->count(),
            'total_candidates' => User::where('role', 'candidate')->count(),
            'total_sponsorships' => Sponsorship::count(),
            'pending_sponsorships' => Sponsorship::where('status', 'pending')->count(),
            'validated_sponsorships' => Sponsorship::where('status', 'validated')->count(),
            'rejected_sponsorships' => Sponsorship::where('status', 'rejected')->count(),
            'sponsorships_by_region' => DB::table('sponsorships')
                ->join('users as voters', 'sponsorships.voter_id', '=', 'voters.id')
                ->join('regions', 'voters.region_id', '=', 'regions.id')
                ->select('regions.name', DB::raw('count(*) as total'))
                ->groupBy('regions.id', 'regions.name')
                ->get()
        ];

        return view('admin.statistics', [
            'user' => Auth::user(),
            'stats' => $stats
        ]);
    }
}

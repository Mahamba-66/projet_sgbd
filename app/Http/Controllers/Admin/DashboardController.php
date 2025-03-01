<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sponsorship;
use App\Models\SponsorshipPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_voters' => User::where('role', 'voter')->count(),
            'total_candidates' => User::where('role', 'candidate')->count(),
            'total_users' => User::count(),
            'total_sponsorships' => 0, // À implémenter quand le modèle Sponsorship sera créé
            'current_period' => null, // À implémenter quand le modèle SponsorshipPeriod sera créé
            'is_active_period' => false
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

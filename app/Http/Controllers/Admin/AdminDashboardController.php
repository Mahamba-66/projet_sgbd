<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sponsorship;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        try {
            // Récupérer les statistiques simples
            $stats = [
                'total_voters' => User::where('role', 'voter')->count(),
                'total_candidates' => User::where('role', 'candidate')->count(),
                'total_sponsorships' => Sponsorship::count(),
                'validated_sponsorships' => Sponsorship::where('status', 'validated')->count(),
                'pending_sponsorships' => Sponsorship::where('status', 'pending')->count(),
                'rejected_sponsorships' => Sponsorship::where('status', 'rejected')->count()
            ];
            
            return view('admin.dashboard', compact('stats'));
        } catch (\Exception $e) {
            \Log::error('Error in AdminDashboardController: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du chargement du tableau de bord');
        }
    }
}

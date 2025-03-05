<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Sponsorship;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('App\Http\Middleware\AdminMiddleware');
    }

    public function index()
    {
        $data = [
            'voterCount' => User::where('role', 'voter')->count(),
            'candidateCount' => Candidate::count(),
            'sponsorshipCount' => Sponsorship::count(),
            'validatedSponsorshipCount' => Sponsorship::where('status', 'validated')->count(),
            'pendingSponsorshipCount' => Sponsorship::where('status', 'pending')->count(),
            'rejectedSponsorshipCount' => Sponsorship::where('status', 'rejected')->count(),
        ];

        return view('admin.statistics.index', $data);
    }
}

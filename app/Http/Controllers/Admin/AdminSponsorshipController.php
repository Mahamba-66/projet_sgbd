<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSponsorshipController extends Controller
{
    public function index(Request $request)
    {
        $query = Sponsorship::with(['voter', 'candidate']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $sponsorships = $query->paginate(10);
        
        // Calculer les statistiques en une seule requête
        $statsQuery = DB::table('sponsorships')
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "validated" THEN 1 ELSE 0 END) as validated'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->first();

        $stats = [
            'total' => $statsQuery->total,
            'pending' => $statsQuery->pending,
            'validated' => $statsQuery->validated,
            'rejected' => $statsQuery->rejected,
        ];

        return view('admin.sponsorships.index', compact('sponsorships', 'stats'));
    }

    public function show($id)
    {
        $sponsorship = Sponsorship::with(['voter', 'candidate'])->findOrFail($id);
        return view('admin.sponsorships.show', compact('sponsorship'));
    }

    public function validateSponsorship($id)
    {
        $sponsorship = Sponsorship::findOrFail($id);
        $sponsorship->status = 'validated';
        $sponsorship->validation_date = now();
        $sponsorship->save();

        return redirect()->back()->with('success', 'Parrainage validé avec succès');
    }

    public function reject(Request $request, $id)
    {
        $sponsorship = Sponsorship::findOrFail($id);
        $sponsorship->status = 'rejected';
        $sponsorship->rejection_reason = $request->input('reason');
        $sponsorship->save();

        return redirect()->back()->with('success', 'Parrainage rejeté avec succès');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SponsorshipPeriod;
use Illuminate\Http\Request;

class SponsorshipPeriodController extends Controller
{
    public function index()
    {
        $periods = SponsorshipPeriod::orderBy('start_date', 'desc')->paginate(10);
        return view('admin.sponsorship_periods.index', compact('periods'));
    }

    public function create()
    {
        return view('admin.sponsorship_periods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'min_sponsorships' => 'required|integer|min:1',
            'max_sponsorships' => 'required|integer|min:min_sponsorships'
        ]);

        SponsorshipPeriod::create($validated);

        return redirect()->route('admin.sponsorship-periods.index')
            ->with('success', 'Période de parrainage créée avec succès.');
    }

    public function edit(SponsorshipPeriod $sponsorshipPeriod)
    {
        return view('admin.sponsorship_periods.edit', compact('sponsorshipPeriod'));
    }

    public function update(Request $request, SponsorshipPeriod $sponsorshipPeriod)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'min_sponsorships' => 'required|integer|min:1',
            'max_sponsorships' => 'required|integer|min:min_sponsorships'
        ]);

        $sponsorshipPeriod->update($validated);

        return redirect()->route('admin.sponsorship-periods.index')
            ->with('success', 'Période de parrainage mise à jour avec succès.');
    }

    public function destroy(SponsorshipPeriod $sponsorshipPeriod)
    {
        $sponsorshipPeriod->delete();
        return redirect()->route('admin.sponsorship-periods.index')
            ->with('success', 'Période de parrainage supprimée avec succès.');
    }
}

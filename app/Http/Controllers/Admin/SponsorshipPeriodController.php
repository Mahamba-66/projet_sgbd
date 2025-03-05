<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SponsorshipPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SponsorshipPeriodController extends Controller
{
    public function index()
    {
        $periods = SponsorshipPeriod::orderBy('created_at', 'desc')->get();
        return view('admin.sponsorship_periods.index', compact('periods'));
    }

    public function create()
    {
        return view('admin.sponsorship_periods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'min_sponsorships' => 'required|integer|min:1',
            'max_sponsorships' => 'required|integer|min:1|gt:min_sponsorships',
        ], [
            'start_date.after_or_equal' => 'La date de début doit être aujourd\'hui ou une date future.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'max_sponsorships.gt' => 'Le nombre maximum de parrainages doit être supérieur au nombre minimum.',
        ]);

        try {
            $validated['status'] = 'inactive';
            SponsorshipPeriod::create($validated);

            return redirect()
                ->route('admin.sponsorship-periods.index')
                ->with('success', 'Période de parrainage créée avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la période. Veuillez réessayer.');
        }
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
            'max_sponsorships' => 'required|integer|min:1|gt:min_sponsorships',
        ], [
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'max_sponsorships.gt' => 'Le nombre maximum de parrainages doit être supérieur au nombre minimum.',
        ]);

        try {
            $sponsorshipPeriod->update($validated);

            return redirect()
                ->route('admin.sponsorship-periods.index')
                ->with('success', 'Période de parrainage mise à jour avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la période. Veuillez réessayer.');
        }
    }

    public function destroy(SponsorshipPeriod $sponsorshipPeriod)
    {
        try {
            $sponsorshipPeriod->delete();
            return redirect()
                ->route('admin.sponsorship-periods.index')
                ->with('success', 'Période de parrainage supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la période.');
        }
    }

    public function toggleStatus(SponsorshipPeriod $sponsorshipPeriod)
    {
        try {
            if ($sponsorshipPeriod->status === 'inactive') {
                // Vérifier si la période n'est pas déjà terminée
                if (Carbon::parse($sponsorshipPeriod->end_date)->isPast()) {
                    return back()->with('error', 'Impossible d\'activer une période déjà terminée.');
                }
                
                // Désactiver toutes les autres périodes
                SponsorshipPeriod::where('status', 'active')->update(['status' => 'inactive']);
            }
            
            $sponsorshipPeriod->status = $sponsorshipPeriod->status === 'active' ? 'inactive' : 'active';
            $sponsorshipPeriod->save();

            return redirect()
                ->route('admin.sponsorship-periods.index')
                ->with('success', 'Statut de la période mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut.');
        }
    }
}

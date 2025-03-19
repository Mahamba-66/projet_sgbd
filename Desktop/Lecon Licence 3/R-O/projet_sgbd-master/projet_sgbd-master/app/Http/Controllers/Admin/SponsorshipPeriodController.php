<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SponsorshipPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SponsorshipPeriodController extends Controller
{
    /**
     * Affiche la liste des périodes de parrainage
     */
    public function index()
    {
        $periods = SponsorshipPeriod::orderBy('start_date', 'desc')->get();
        return view('admin.sponsorship-periods.index', compact('periods'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('admin.sponsorship-periods.create');
    }

    /**
     * Enregistre une nouvelle période
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.sponsorship-periods.create')
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            if ($request->boolean('is_active')) {
                SponsorshipPeriod::deactivateAll();
            }

            SponsorshipPeriod::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active')
            ]);
        });

        return redirect()
            ->route('admin.sponsorship-periods.index')
            ->with('success', 'Période de parrainage créée avec succès');
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(SponsorshipPeriod $period)
    {
        return view('admin.sponsorship-periods.edit', compact('period'));
    }

    /**
     * Met à jour une période existante
     */
    public function update(Request $request, SponsorshipPeriod $period)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.sponsorship-periods.edit', $period)
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request, $period) {
            if ($request->boolean('is_active')) {
                SponsorshipPeriod::deactivateAll();
            }

            $period->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active')
            ]);
        });

        return redirect()
            ->route('admin.sponsorship-periods.index')
            ->with('success', 'Période de parrainage mise à jour avec succès');
    }

    /**
     * Supprime une période
     */
    public function destroy(SponsorshipPeriod $period)
    {
        $period->delete();
        return redirect()
            ->route('admin.sponsorship-periods.index')
            ->with('success', 'Période de parrainage supprimée avec succès');
    }
}

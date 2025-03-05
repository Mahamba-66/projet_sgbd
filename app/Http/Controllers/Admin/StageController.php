<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function index()
    {
        $stages = Stage::orderBy('start_date', 'desc')->get();
        return view('admin.stages.index', compact('stages'));
    }

    public function create()
    {
        return view('admin.stages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string'
        ]);

        $stage = Stage::create($validated);

        return redirect()->route('admin.stages.index')
            ->with('success', 'Période de stage créée avec succès');
    }

    public function edit(Stage $stage)
    {
        return view('admin.stages.edit', compact('stage'));
    }

    public function update(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string'
        ]);

        $stage->update($validated);

        return redirect()->route('admin.stages.index')
            ->with('success', 'Période de stage mise à jour avec succès');
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();
        return redirect()->route('admin.stages.index')
            ->with('success', 'Période de stage supprimée avec succès');
    }
}

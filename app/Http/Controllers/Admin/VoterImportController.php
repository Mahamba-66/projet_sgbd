<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EligibleVoter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VotersImport;

class VoterImportController extends Controller
{
    public function show()
    {
        return view('admin.voters.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('file');
            
            // Vérifier que le fichier existe
            if (!$file) {
                return back()->with('error', 'Veuillez sélectionner un fichier Excel.');
            }

            // Importer les électeurs
            Excel::import(new VotersImport, $file);

            return redirect()->route('admin.voters.eligible')
                ->with('success', 'La liste des électeurs a été importée avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'importation. Vérifiez le format du fichier.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportedVoterCard;
use App\Services\VoterFileValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VoterImportController extends Controller
{
    protected $validationService;

    public function __construct(VoterFileValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    public function showImportForm()
    {
        $importedCards = ImportedVoterCard::orderBy('created_at', 'desc')->paginate(10);
        $importHistory = DB::table('voter_import_history')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.voters.import', compact('importHistory', 'importedCards'));
    }

    public function import(Request $request)
    {
        try {
            if (!$request->hasFile('voter_file')) {
                return redirect()
                    ->route('admin.voters.import')
                    ->with('error', 'Aucun fichier n\'a été téléchargé.');
            }

            $file = $request->file('voter_file');
            
            if (!$file->isValid()) {
                return redirect()
                    ->route('admin.voters.import')
                    ->with('error', 'Le fichier est invalide : ' . $file->getErrorMessage());
            }

            $path = $file->store('private/temp');
            $originalName = $file->getClientOriginalName();
            $fullPath = Storage::path($path);

            Log::info('Début de l\'importation', [
                'file' => $originalName,
                'path' => $fullPath,
                'size' => $file->getSize()
            ]);

            // Valider le fichier
            $validationResult = $this->validationService->validateFile($fullPath);

            $errors = $validationResult->getErrors();
            if (!empty($errors)) {
                Log::warning('Erreurs de validation', ['errors' => $errors]);
                Storage::delete($path);
                return redirect()
                    ->route('admin.voters.import')
                    ->with('error', implode("\n", $errors));
            }

            $validCards = $validationResult->getValidCards();
            Log::info('Cartes valides trouvées', ['count' => count($validCards)]);

            // Importer les numéros de carte valides
            DB::transaction(function () use ($validCards, $originalName) {
                foreach ($validCards as $card) {
                    Log::info('Import de la carte', ['numero' => $card['voter_card_number']]);
                    ImportedVoterCard::create([
                        'voter_card_number' => $card['voter_card_number']
                    ]);
                }

                // Enregistrer l'historique
                DB::table('voter_import_history')->insert([
                    'file_name' => $originalName,
                    'total_records' => count($validCards),
                    'valid_records' => count($validCards),
                    'invalid_records' => 0,
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });

            Log::info('Importation terminée avec succès');
            return redirect()
                ->route('admin.voters.import')
                ->with('success', count($validCards) . ' numéros de carte d\'électeur ont été importés avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'importation des électeurs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->route('admin.voters.import')
                ->with('error', 'Une erreur est survenue lors de l\'importation : ' . $e->getMessage());
        } finally {
            if (isset($path)) {
                Storage::delete($path);
            }
        }
    }

    public function showValidationErrors()
    {
        $errors = session('errors', []);
        
        // Formater les erreurs pour la vue
        $formattedErrors = [];
        foreach ($errors as $index => $error) {
            $formattedErrors[] = [
                'line' => $index + 2, // +2 car la première ligne est l'en-tête
                'column' => 'N/A',
                'value' => 'N/A',
                'message' => $error
            ];
        }
        
        return view('admin.voters.validation-errors', ['errors' => $formattedErrors]);
    }
}

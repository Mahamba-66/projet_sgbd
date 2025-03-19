<?php

namespace App\Http\Controllers;

use App\Services\VoterFileValidationService;
use App\Models\UploadAttempt;
use App\Models\TempVoter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoterImportController extends Controller
{
    protected $validationService;

    public function __construct(VoterFileValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    public function showImportForm()
    {
        return view('admin.voters.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'checksum' => 'required|string|size:64'
        ]);

        $file = $request->file('file');
        $attempt = UploadAttempt::create([
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'checksum' => $request->checksum,
            'status' => 'pending'
        ]);

        if (!$this->validationService->controlerFichierElecteurs($file->path(), $request->checksum, $attempt)) {
            return back()->with('error', 'Le fichier n\'a pas passé les contrôles de validation');
        }

        // Traitement du CSV
        $handle = fopen($file->path(), 'r');
        $header = fgetcsv($handle);
        
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $voter = array_combine($header, $row);
                
                if ($this->validationService->controlerElecteurs($voter, $attempt)) {
                    TempVoter::create([
                        'cin' => $voter['cin'],
                        'numero_electeur' => $voter['numero_electeur'],
                        'nom' => $voter['nom'],
                        'prenom' => $voter['prenom'],
                        'date_naissance' => $voter['date_naissance'],
                        'lieu_naissance' => $voter['lieu_naissance'],
                        'sexe' => $voter['sexe'],
                        'bureau_vote' => $voter['bureau_vote'],
                        'upload_attempt_id' => $attempt->id
                    ]);
                }
            }
            
            $attempt->update(['status' => 'validated']);
            DB::commit();
            
            return back()->with('success', 'Fichier importé avec succès. Veuillez valider l\'importation.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $attempt->update([
                'status' => 'rejected',
                'error_message' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de l\'importation');
        } finally {
            fclose($handle);
        }
    }

    public function validerImportation(UploadAttempt $attempt)
    {
        if ($attempt->status !== 'validated') {
            return back()->with('error', 'Cette tentative d\'importation n\'est pas validée');
        }

        DB::beginTransaction();
        try {
            // Transfert vers la table permanente
            DB::table('eligible_voters')->insertUsing(
                ['cin', 'numero_electeur', 'nom', 'prenom', 'date_naissance', 'lieu_naissance', 'sexe', 'bureau_vote'],
                DB::table('temp_voters')
                    ->where('upload_attempt_id', $attempt->id)
                    ->select('cin', 'numero_electeur', 'nom', 'prenom', 'date_naissance', 'lieu_naissance', 'sexe', 'bureau_vote')
            );

            // Nettoyage de la table temporaire
            TempVoter::where('upload_attempt_id', $attempt->id)->delete();

            // Mise à jour du statut global
            DB::table('system_settings')->where('key', 'EtatUploadElecteurs')->update(['value' => true]);

            DB::commit();
            return back()->with('success', 'Importation validée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la validation : ' . $e->getMessage());
        }
    }
}

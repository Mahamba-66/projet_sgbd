<?php

namespace App\Services;

use App\Models\FileUpload;
use App\Models\ImportedVoterCard;
use App\Models\VoterValidationError;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\ValidationResult;

class VoterFileValidationService
{
    public function validateFile(string $filePath): ValidationResult
    {
        Log::info('Début de la validation du fichier', ['path' => $filePath]);

        $attempt = FileUpload::create([
            'file_path' => $filePath,
            'status' => 'pending'
        ]);

        $errors = [];
        $validCards = [];

        // Vérifier le format du fichier
        if (!$this->controlerFichierElecteurs($filePath, hash_file('sha256', $filePath), $attempt)) {
            Log::warning('Erreur de contrôle du fichier', ['error' => $attempt->error_message]);
            return new ValidationResult([], [$attempt->error_message]);
        }

        // Lire et valider chaque ligne
        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);
        
        Log::info('En-têtes trouvés', ['headers' => $headers]);
        
        // Vérifier que le fichier contient uniquement la colonne numero_electeur
        if (!in_array('numero_electeur', $headers)) {
            fclose($handle);
            $error = 'Le fichier doit contenir une colonne "numero_electeur"';
            Log::warning($error);
            return new ValidationResult([], [$error]);
        }

        $numeroElecteurIndex = array_search('numero_electeur', $headers);
        $lineNumber = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $lineNumber++;
            
            if (!isset($data[$numeroElecteurIndex])) {
                Log::warning('Ligne invalide', ['line' => $lineNumber, 'data' => $data]);
                $errors[] = "Ligne $lineNumber : Format invalide";
                continue;
            }

            $cardNumber = trim($data[$numeroElecteurIndex]);
            
            if (empty($cardNumber)) {
                Log::warning('Numéro de carte vide', ['line' => $lineNumber]);
                $errors[] = "Ligne $lineNumber : Le numéro de carte d'électeur est vide";
                continue;
            }

            // Valider le format du numéro de carte
            if (!preg_match('/^[A-Z0-9]{10}$/', $cardNumber)) {
                Log::warning('Format de numéro invalide', ['line' => $lineNumber, 'numero' => $cardNumber]);
                $errors[] = "Ligne $lineNumber : Le numéro de carte d'électeur doit contenir exactement 10 caractères alphanumériques majuscules";
                continue;
            }

            // Vérifier si le numéro n'existe pas déjà
            if (ImportedVoterCard::where('voter_card_number', $cardNumber)->exists()) {
                Log::warning('Numéro déjà existant', ['line' => $lineNumber, 'numero' => $cardNumber]);
                $errors[] = "Ligne $lineNumber : Le numéro de carte d'électeur $cardNumber existe déjà";
                continue;
            }

            Log::info('Numéro de carte valide trouvé', ['line' => $lineNumber, 'numero' => $cardNumber]);
            $validCards[] = ['voter_card_number' => $cardNumber];
        }

        fclose($handle);

        Log::info('Validation terminée', [
            'valid_count' => count($validCards),
            'error_count' => count($errors)
        ]);

        return new ValidationResult($validCards, $errors);
    }

    public function controlerFichierElecteurs(string $filePath, string $checksum, FileUpload $attempt): bool
    {
        if (!file_exists($filePath)) {
            $attempt->error_message = 'Le fichier n\'existe pas';
            return false;
        }

        if (filesize($filePath) === 0) {
            $attempt->error_message = 'Le fichier est vide';
            return false;
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $attempt->error_message = 'Impossible d\'ouvrir le fichier';
            return false;
        }

        $headers = fgetcsv($handle);
        fclose($handle);

        if (!$headers) {
            $attempt->error_message = 'Le fichier n\'a pas d\'en-tête';
            return false;
        }

        if (!in_array('numero_electeur', $headers)) {
            $attempt->error_message = 'Le fichier doit contenir une colonne "numero_electeur"';
            return false;
        }

        return true;
    }
}

class ValidationResult
{
    private $validCards;
    private $errors;

    public function __construct(array $validCards, array $errors)
    {
        $this->validCards = $validCards;
        $this->errors = $errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function getValidCards(): array
    {
        return $this->validCards;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getTotalCount(): int
    {
        return count($this->validCards) + count($this->errors);
    }
}

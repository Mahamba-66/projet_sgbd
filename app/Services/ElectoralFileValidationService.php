<?php

namespace App\Services;

use App\Models\ElectoralFile;
use League\Csv\Reader;

class ElectoralFileValidationService
{
    private $errors = [];
    private $duplicates = [];

    public function validate(ElectoralFile $electoralFile, string $filePath): bool
    {
        try {
            // Convertir le fichier en UTF-8 si nécessaire
            $content = file_get_contents($filePath);
            if (!mb_check_encoding($content, 'UTF-8')) {
                $content = mb_convert_encoding($content, 'UTF-8');
                file_put_contents($filePath, $content);
            }

            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(',');

            // Vérifier les en-têtes requis
            $header = $csv->getHeader();
            $requiredHeaders = [
                'numero_carte_electeur',
                'numero_identification',
                'nom_complet',
                'bureau_vote',
                'telephone'
            ];

            $missingHeaders = array_diff($requiredHeaders, $header);
            if (!empty($missingHeaders)) {
                $this->errors[] = [
                    'message' => 'Colonnes manquantes : ' . implode(', ', $missingHeaders),
                    'line' => 1
                ];
                return false;
            }

            $records = $csv->getRecords();
            $totalRecords = iterator_count($records);
            $electoralFile->total_records = $totalRecords;
            $electoralFile->save();

            // Reset pointer
            $records = $csv->getRecords();

            $processedVoterCards = [];
            $processedNationalIds = [];

            foreach ($records as $offset => $record) {
                $electoralFile->processed_records = $offset + 1;
                $electoralFile->save();

                // Vérifier les champs requis
                if (!$this->validateRequiredFields($record, $offset)) {
                    continue;
                }

                // Vérifier les doublons
                if (in_array($record['numero_carte_electeur'], $processedVoterCards)) {
                    $this->duplicates[] = [
                        'type' => 'voter_card',
                        'value' => $record['numero_carte_electeur'],
                        'line' => $offset + 2
                    ];
                    continue;
                }

                if (in_array($record['numero_identification'], $processedNationalIds)) {
                    $this->duplicates[] = [
                        'type' => 'national_id',
                        'value' => $record['numero_identification'],
                        'line' => $offset + 2
                    ];
                    continue;
                }

                $processedVoterCards[] = $record['numero_carte_electeur'];
                $processedNationalIds[] = $record['numero_identification'];
            }

            $electoralFile->valid_records = count($processedVoterCards);
            $electoralFile->invalid_records = $totalRecords - count($processedVoterCards);
            $electoralFile->status = empty($this->errors) && empty($this->duplicates) ? 'completed' : 'failed';
            $electoralFile->error_log = json_encode([
                'errors' => $this->errors,
                'duplicates' => $this->duplicates
            ]);
            $electoralFile->save();

            return empty($this->errors) && empty($this->duplicates);
        } catch (\Exception $e) {
            $this->errors[] = [
                'message' => $e->getMessage(),
                'line' => 0
            ];
            return false;
        }
    }

    private function validateRequiredFields(array $record, int $offset): bool
    {
        $isValid = true;

        // Valider le numéro de carte d'électeur (format: SN + 7 chiffres)
        if (empty($record['numero_carte_electeur']) || !preg_match('/^SN\d{7}$/', $record['numero_carte_electeur'])) {
            $this->errors[] = [
                'field' => 'numero_carte_electeur',
                'message' => "Le numéro de carte d'électeur est invalide (format attendu : SN + 7 chiffres)",
                'line' => $offset + 2
            ];
            $isValid = false;
        }

        // Valider le numéro d'identification (10 chiffres)
        if (empty($record['numero_identification']) || !preg_match('/^\d{10}$/', $record['numero_identification'])) {
            $this->errors[] = [
                'field' => 'numero_identification',
                'message' => "Le numéro d'identification est invalide (10 chiffres requis)",
                'line' => $offset + 2
            ];
            $isValid = false;
        }

        // Valider le nom complet (non vide)
        if (empty($record['nom_complet'])) {
            $this->errors[] = [
                'field' => 'nom_complet',
                'message' => "Le nom complet est requis",
                'line' => $offset + 2
            ];
            $isValid = false;
        }

        // Valider le bureau de vote (non vide)
        if (empty($record['bureau_vote'])) {
            $this->errors[] = [
                'field' => 'bureau_vote',
                'message' => "Le bureau de vote est requis",
                'line' => $offset + 2
            ];
            $isValid = false;
        }

        // Valider le numéro de téléphone (format: 77XXXXXXX)
        if (!empty($record['telephone']) && !preg_match('/^(77|78|76|70)\d{7}$/', $record['telephone'])) {
            $this->errors[] = [
                'field' => 'telephone',
                'message' => "Le numéro de téléphone est invalide (format attendu : 77/78/76/70 + 7 chiffres)",
                'line' => $offset + 2
            ];
            $isValid = false;
        }

        return $isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getDuplicates(): array
    {
        return $this->duplicates;
    }
}

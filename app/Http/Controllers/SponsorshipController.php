<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
use Illuminate\Http\Request;
use League\Csv\Writer;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class SponsorshipController extends Controller
{
    private $requiredColumns = [
        'nin' => ['NIN'],
        'prenom' => ['PRENOM'],
        'nom' => ['NOM'],
        'date_naissance' => ['DATE_NAISSANCE'],
        'lieu_naissance' => ['LIEU_NAISSANCE'],
        'sexe' => ['SEXE'],
        'region' => ['REGION'],
        'departement' => ['DEPARTEMENT'],
        'commune' => ['COMMUNE'],
        'bureau_vote' => ['BUREAU_VOTE'],
        'numero_electeur' => ['NUMERO_ELECTEUR']
    ];

    public function index()
    {
        $sponsorships = Sponsorship::with(['voter', 'candidate', 'period'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.sponsorships.index', compact('sponsorships'));
    }

    public function showUploadForm()
    {
        return view('sponsorship.upload');
    }

    public function downloadTemplate()
    {
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        
        // En-têtes
        $headers = [
            'NIN',
            'PRENOM',
            'NOM',
            'DATE_NAISSANCE',
            'LIEU_NAISSANCE',
            'SEXE',
            'REGION',
            'DEPARTEMENT',
            'COMMUNE',
            'BUREAU_VOTE',
            'NUMERO_ELECTEUR'
        ];
        $csv->insertOne($headers);
        
        // Ligne d'exemple
        $csv->insertOne([
            '1234567890',     // NIN
            'Moussa',         // PRENOM
            'Diop',           // NOM
            '1990-01-01',     // DATE_NAISSANCE
            'Dakar',          // LIEU_NAISSANCE
            'M',              // SEXE
            'Dakar',          // REGION
            'Dakar',          // DEPARTEMENT
            'Plateau',        // COMMUNE
            'École 1',        // BUREAU_VOTE
            'EL123456'        // NUMERO_ELECTEUR
        ]);

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="modele_parrainage.csv"',
            'Content-Transfer-Encoding' => 'binary',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $tempPath = $file->getPathname();
            
            // Lire le contenu du fichier
            $content = file_get_contents($tempPath);
            $lines = explode("\n", $content);
            
            // Traiter la première ligne (en-têtes)
            $headerLine = trim($lines[0]);
            $headers = explode(',', $headerLine);
            
            // Nettoyer les en-têtes
            $headers = array_map(function($header) {
                return strtoupper(trim(str_replace([' ', '-', '_', '.'], '', $header)));
            }, $headers);
            
            // Créer le mapping des colonnes
            $columnMap = [];
            foreach ($this->requiredColumns as $key => $aliases) {
                foreach ($headers as $index => $header) {
                    if (strpos($header, $aliases[0]) !== false) {
                        $columnMap[$key] = $index;
                        break;
                    }
                }
            }
            
            $importCount = 0;
            $errors = [];

            // Traiter chaque ligne de données
            for ($i = 1; $i < count($lines); $i++) {
                $line = trim($lines[$i]);
                if (empty($line)) continue;
                
                try {
                    // Diviser la ligne en valeurs
                    $values = explode(',', $line);
                    
                    // Extraire le NIN (première valeur)
                    $nin = trim($values[0]);
                    if (empty($nin)) continue;
                    
                    // Créer le parrainage
                    Sponsorship::create([
                        'voter_nin' => $nin,
                        'candidate_id' => auth()->id(),
                        'status' => 'pending'
                    ]);
                    
                    $importCount++;
                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($i + 1) . " : " . $e->getMessage();
                }
            }

            if ($importCount === 0) {
                throw new \Exception("Aucun parrainage valide n'a été trouvé dans le fichier.");
            }

            $message = $importCount . ' parrainages ont été importés avec succès.';
            if (!empty($errors)) {
                $message .= "\nAvertissements :\n" . implode("\n", $errors);
            }

            return redirect()->route('sponsorship.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }

    public function validateSponsorship(Request $request, Sponsorship $sponsorship)
    {
        $sponsorship->update([
            'status' => 'validated',
            'validated_at' => now()
        ]);

        return back()->with('success', 'Parrainage validé avec succès.');
    }

    public function rejectSponsorship(Request $request, Sponsorship $sponsorship)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $sponsorship->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return back()->with('success', 'Parrainage rejeté.');
    }
}

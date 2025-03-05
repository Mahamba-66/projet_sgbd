<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VoterImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.voters.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            DB::beginTransaction();
            
            $file = $request->file('file');
            
            // Lire le contenu du fichier
            $content = file_get_contents($file->getRealPath());
            
            // Convertir en UTF-8 si nécessaire
            if (!mb_check_encoding($content, 'UTF-8')) {
                $content = mb_convert_encoding($content, 'UTF-8', 'auto');
            }
            
            // Diviser en lignes
            $lines = explode("\n", $content);
            
            // Retirer les caractères BOM si présents
            $lines[0] = str_replace("\xEF\xBB\xBF", '', $lines[0]);
            
            // Nettoyer les lignes
            $lines = array_map('trim', $lines);
            $lines = array_filter($lines);
            
            // Obtenir les en-têtes
            $headers = str_getcsv(array_shift($lines));
            $headers = array_map('trim', $headers);
            
            // Vérifier les colonnes requises
            $requiredColumns = ['Prenom', 'Nom', 'Numero de Carte'];
            $headerMap = array_flip($headers);
            
            foreach ($requiredColumns as $column) {
                if (!isset($headerMap[$column])) {
                    return back()->with('error', "La colonne '$column' est manquante dans le fichier CSV.");
                }
            }

            // Vérifier d'abord si les numéros de carte sont uniques
            $cardNumbers = [];
            foreach ($lines as $line) {
                $data = str_getcsv($line);
                if (count($data) !== count($headers)) {
                    continue;
                }
                $rowData = array_combine($headers, $data);
                $cardNumber = $rowData['Numero de Carte'];
                
                // Vérifier si le numéro de carte existe déjà
                if (in_array($cardNumber, $cardNumbers)) {
                    throw new \Exception("Le numéro de carte '$cardNumber' apparaît plusieurs fois dans le fichier.");
                }
                if (User::where('voter_card_number', $cardNumber)->exists()) {
                    throw new \Exception("Le numéro de carte '$cardNumber' existe déjà dans la base de données.");
                }
                $cardNumbers[] = $cardNumber;
            }

            $importCount = 0;
            foreach ($lines as $line) {
                $data = str_getcsv($line);
                if (count($data) !== count($headers)) {
                    continue;
                }
                
                $rowData = array_combine($headers, $data);
                
                // Créer un email unique basé sur le prénom, le nom et un nombre aléatoire
                $baseEmail = Str::slug($rowData['Prenom'] . '.' . $rowData['Nom']);
                $random = rand(1000, 9999);
                $email = $baseEmail . '.' . $random . '@example.com';
                
                // S'assurer que l'email est unique
                while (User::where('email', $email)->exists()) {
                    $random = rand(1000, 9999);
                    $email = $baseEmail . '.' . $random . '@example.com';
                }
                
                // Désactiver temporairement la journalisation des activités
                $user = new User();
                $user->unsetEventDispatcher();
                
                // Créer l'utilisateur
                $user->fill([
                    'name' => $rowData['Prenom'] . ' ' . $rowData['Nom'],
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role' => 'voter',
                    'voter_card_number' => $rowData['Numero de Carte'],
                    'status' => 'active'
                ]);
                
                $user->save();
                
                $importCount++;
            }

            DB::commit();

            return redirect()->route('admin.voters.index')
                ->with('success', $importCount . ' électeurs ont été importés avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }
}

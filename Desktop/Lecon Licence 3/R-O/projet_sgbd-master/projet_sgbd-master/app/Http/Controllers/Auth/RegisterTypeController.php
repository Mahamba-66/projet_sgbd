<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\User;
use App\Models\EligibleVoter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;

class RegisterTypeController extends Controller
{
    public function showTypeSelection()
    {
        return view('auth.register_type');
    }

    public function showVoterForm()
    {
        $regions = Region::all();
        return view('auth.register_voter', compact('regions'));
    }

    public function showCandidateForm()
    {
        return view('auth.register_candidate');
    }

    public function registerVoter(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'string', 'confirmed', Password::min(8)],
                'voter_card_number' => 'required|string|size:10|regex:/^[A-Z0-9]+$/',
                'region_id' => 'required|exists:regions,id'
            ]);

            // Vérifier si la table existe
            if (!Schema::hasTable('imported_voter_cards')) {
                Log::error('Table imported_voter_cards n\'existe pas');
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Erreur système : Table de cartes d\'électeur non trouvée']);
            }

            // Vérifier si le numéro de carte existe et n'est pas déjà utilisé
            $voterCard = DB::table('imported_voter_cards')
                ->where('voter_card_number', $request->voter_card_number)
                ->where('is_used', false)
                ->first();

            if (!$voterCard) {
                Log::info('Tentative d\'activation avec carte invalide : ' . $request->voter_card_number);
                return back()
                    ->withInput()
                    ->withErrors(['voter_card_number' => 'Ce numéro de carte d\'électeur n\'est pas valide ou a déjà été utilisé.']);
            }

            DB::beginTransaction();

            try {
                // Créer l'utilisateur
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'voter',
                    'status' => 'active',
                    'region_id' => $request->region_id
                ]);

                // Marquer la carte comme utilisée
                DB::table('imported_voter_cards')
                    ->where('voter_card_number', $request->voter_card_number)
                    ->update([
                        'is_used' => true,
                        'user_id' => $user->id,
                        'used_at' => now()
                    ]);

                DB::commit();

                // Connecter l'utilisateur
                Auth::login($user);

                return redirect()->route('voter.dashboard')
                    ->with('success', 'Votre compte a été activé avec succès.');

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Erreur lors de la création du compte électeur : ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'activation du compte électeur : ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    public function registerCandidate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
            'nin' => 'required|string|size:13|regex:/^[0-9]+$/',
            'phone' => 'required|string|size:9|regex:/^[0-9]+$/',
            'birth_date' => 'required|date|before:-35 years',
            'party_name' => 'required|string|max:255',
            'party_position' => 'required|string|max:255',
            'region_id' => 'required|exists:regions,id'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'candidate',
                'status' => 'pending'
            ]);

            // Créer le profil du candidat
            $user->candidateProfile()->create([
                'nin' => $request->nin,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'party_name' => $request->party_name,
                'party_position' => $request->party_position,
                'region_id' => $request->region_id
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Votre demande d\'inscription en tant que candidat a été enregistrée. Elle sera examinée par nos services.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'inscription du candidat : ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de votre inscription.']);
        }
    }
}
